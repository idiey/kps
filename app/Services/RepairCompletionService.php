<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Enums\PhotoStage;
use App\Models\RepairCompletionReport;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing repair completion reports.
 * Handles parts tracking, technician sign-off, and submission for review.
 */
class RepairCompletionService
{
    public function __construct(
        protected PhotoStorageService $photoService
    ) {}

    /**
     * Create a completion report for a job.
     */
    public function createCompletionReport(WorkshopJob $job, int $technicianId, array $data): RepairCompletionReport
    {
        return DB::transaction(function () use ($job, $technicianId, $data) {
            // Validate job is in repair
            if (!in_array($job->status, [JobStatus::REPAIR_IN_PROGRESS, JobStatus::IN_PROGRESS])) {
                throw new \InvalidArgumentException('Job must be in progress to create completion report');
            }

            // Create completion report
            $report = RepairCompletionReport::create([
                'workshop_job_id' => $job->id,
                'technician_id' => $technicianId,
                'work_completed' => $data['work_completed'] ?? false,
                'time_spent_hours' => $data['time_spent_hours'],
                'work_description' => $data['work_description'],
                'issues_encountered' => $data['issues_encountered'] ?? null,
                'recommendations' => $data['recommendations'] ?? null,
                'quality_rating' => $data['quality_rating'] ?? null,
            ]);

            // Add parts if provided
            if (!empty($data['parts_used'])) {
                $this->updateParts($report, $data['parts_used']);
            }

            return $report->fresh(['workshopJob', 'technician']);
        });
    }

    /**
     * Update an existing completion report.
     */
    public function updateCompletionReport(RepairCompletionReport $report, array $data): RepairCompletionReport
    {
        // Cannot update if already signed
        if ($report->isSigned()) {
            throw new \InvalidArgumentException('Cannot update a signed completion report');
        }

        DB::transaction(function () use ($report, $data) {
            $report->update([
                'work_completed' => $data['work_completed'] ?? $report->work_completed,
                'time_spent_hours' => $data['time_spent_hours'] ?? $report->time_spent_hours,
                'work_description' => $data['work_description'] ?? $report->work_description,
                'issues_encountered' => $data['issues_encountered'] ?? $report->issues_encountered,
                'recommendations' => $data['recommendations'] ?? $report->recommendations,
                'quality_rating' => $data['quality_rating'] ?? $report->quality_rating,
            ]);

            // Update parts if provided
            if (isset($data['parts_used'])) {
                $this->updateParts($report, $data['parts_used']);
            }
        });

        return $report->fresh(['workshopJob', 'technician']);
    }

    /**
     * Add a single part to the report.
     */
    public function addPart(RepairCompletionReport $report, string $name, int $quantity, float $cost): void
    {
        // Use model method for incremental tracking
        $report->addPart($name, $quantity, $cost);
    }

    /**
     * Update the parts list (replaces existing).
     */
    public function updateParts(RepairCompletionReport $report, array $parts): void
    {
        // Validate parts array structure
        foreach ($parts as $part) {
            if (!isset($part['name']) || !isset($part['quantity']) || !isset($part['cost'])) {
                throw new \InvalidArgumentException('Each part must have name, quantity, and cost');
            }
        }

        // Calculate total cost
        $totalCost = collect($parts)->sum(fn($part) => $part['cost'] * $part['quantity']);

        // Update report
        $report->update([
            'parts_used' => $parts,
            'total_cost' => $totalCost,
        ]);
    }

    /**
     * Calculate total cost from parts.
     */
    public function calculateTotalCost(RepairCompletionReport $report): float
    {
        $parts = $report->parts_used ?? [];

        return collect($parts)->sum(fn($part) => ($part['cost'] ?? 0) * ($part['quantity'] ?? 0));
    }

    /**
     * Sign the completion report.
     */
    public function signReport(RepairCompletionReport $report, string $signature): void
    {
        if (empty($signature)) {
            throw new \InvalidArgumentException('Digital signature is required');
        }

        $report->update([
            'technician_signature' => $signature,
            'technician_signed_at' => now(),
        ]);
    }

    /**
     * Submit completion report for supervisor review.
     */
    public function submitForReview(RepairCompletionReport $report): WorkshopJob
    {
        // Validate completion requirements
        $validationErrors = $this->validateCompletion($report);

        if (!empty($validationErrors)) {
            throw new \InvalidArgumentException(
                'Completion report is incomplete: ' . implode(', ', $validationErrors)
            );
        }

        return DB::transaction(function () use ($report) {
            $job = $report->workshopJob;

            // Update job hours
            $job->update([
                'status' => JobStatus::PENDING_REVIEW,
                'actual_hours' => $report->time_spent_hours,
                'actual_cost' => $report->total_cost,
            ]);

            // Create status history
            $job->statusHistories()->create([
                'user_id' => auth()->id(),
                'old_status' => JobStatus::REPAIR_IN_PROGRESS,
                'new_status' => JobStatus::PENDING_REVIEW,
                'notes' => 'Repair completed - submitted for supervisor review',
                'changed_at' => now(),
            ]);

            return $job->fresh(['completionReport', 'customer', 'assignedUser']);
        });
    }

    /**
     * Validate completion report before submission.
     * Returns array of validation errors.
     */
    public function validateCompletion(RepairCompletionReport $report): array
    {
        $errors = [];

        // Check required fields
        if (empty($report->work_description)) {
            $errors[] = 'Work description is required';
        }

        if ($report->time_spent_hours <= 0) {
            $errors[] = 'Time spent must be greater than 0';
        }

        if (empty($report->quality_rating) || $report->quality_rating < 1 || $report->quality_rating > 5) {
            $errors[] = 'Quality rating must be between 1 and 5';
        }

        // Check parts list
        if (empty($report->parts_used) || count($report->parts_used) === 0) {
            $errors[] = 'At least one part must be documented';
        }

        // Check signature
        if (!$report->isSigned()) {
            $errors[] = 'Technician signature is required';
        }

        // Check photo requirements
        $photoErrors = $this->photoService->validatePhotoRequirements($report->workshopJob);
        if (!empty($photoErrors)) {
            $errors = array_merge($errors, $photoErrors);
        }

        return $errors;
    }

    /**
     * Check if a job can be marked as complete.
     */
    public function canComplete(WorkshopJob $job): bool
    {
        // Must have completion report
        if (!$job->completionReport) {
            return false;
        }

        // Validation must pass
        $errors = $this->validateCompletion($job->completionReport);

        return empty($errors);
    }

    /**
     * Get completion report for a job.
     */
    public function getCompletionReport(WorkshopJob $job): ?RepairCompletionReport
    {
        return $job->completionReport;
    }

    /**
     * Get all completion reports pending supervisor review.
     */
    public function getPendingReviews(): Collection
    {
        return RepairCompletionReport::with(['workshopJob', 'technician'])
            ->whereHas('workshopJob', function ($query) {
                $query->where('status', JobStatus::PENDING_REVIEW);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
