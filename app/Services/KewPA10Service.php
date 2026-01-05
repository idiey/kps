<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Models\KewPA10;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing KEW.PA-10 forms.
 * Handles form registration, verification, job creation, and return process.
 */
class KewPA10Service
{
    /**
     * Register a new KEW.PA-10 form.
     */
    public function registerKewPA10(array $data): KewPA10
    {
        return DB::transaction(function () use ($data) {
            // Validate form number uniqueness
            if (KewPA10::where('kew_pa_10_number', $data['kew_pa_10_number'])->exists()) {
                throw new \InvalidArgumentException('KEW.PA-10 number already exists in the system');
            }

            // Create KEW.PA-10 record
            $kewPA10 = KewPA10::create([
                'kew_pa_10_number' => $data['kew_pa_10_number'],
                'government_department_id' => $data['government_department_id'],
                'asset_id' => $data['asset_id'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? 'medium',
                'budget_allocation_reference' => $data['budget_allocation_reference'] ?? null,
                'kew_pa_10_document_path' => $data['kew_pa_10_document_path'] ?? null,
                'received_date' => $data['received_date'] ?? now(),
                'received_by' => auth()->id(),
            ]);

            return $kewPA10->load(['governmentDepartment', 'asset']);
        });
    }

    /**
     * Verify KEW.PA-10 form completeness and signatures.
     */
    public function verifyForm(KewPA10 $kewPA10, array $verificationData): KewPA10
    {
        $formCompletenessVerified = filter_var(
            $verificationData['form_completeness_verified'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );
        $signaturesVerified = filter_var(
            $verificationData['signatures_verified'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );

        $kewPA10->update([
            'form_completeness_verified' => $formCompletenessVerified,
            'signatures_verified' => $signaturesVerified,
            'verification_notes' => $verificationData['verification_notes'] ?? null,
        ]);

        return $kewPA10->fresh(['governmentDepartment', 'asset']);
    }

    /**
     * Create a workshop job from a verified KEW.PA-10 form.
     */
    public function createJobFromKewPA10(KewPA10 $kewPA10, array $jobData): WorkshopJob
    {
        if (empty($kewPA10->kew_pa_10_number) || empty($kewPA10->description)) {
            throw new \InvalidArgumentException('KEW.PA-10 form must have a number and description before creating a job');
        }

        // Check if job already exists for this KEW.PA-10
        if ($kewPA10->workshopJob) {
            throw new \InvalidArgumentException('A job already exists for this KEW.PA-10 form');
        }

        return DB::transaction(function () use ($kewPA10, $jobData) {
            $inspectionRequired = array_key_exists('inspection_required', $jobData)
                ? filter_var($jobData['inspection_required'], FILTER_VALIDATE_BOOLEAN)
                : true;

            // Generate job reference number (format: WS-YYYY-NNNN)
            $jobReference = $this->generateJobReference();

            // Create workshop job
            $job = WorkshopJob::create([
                'job_reference' => $jobReference,
                'kew_pa_10_id' => $kewPA10->id,
                'government_department_id' => $kewPA10->government_department_id,
                'asset_id' => $kewPA10->asset_id,
                'customer_id' => $jobData['customer_id'] ?? null,
                'description' => $kewPA10->description,
                'priority' => $kewPA10->priority,
                'status' => $inspectionRequired ? JobStatus::PENDING_INSPECTION : JobStatus::IN_PROGRESS,
                'inspection_required' => $inspectionRequired,
                'estimated_hours' => $jobData['estimated_hours'] ?? null,
                'estimated_cost' => $jobData['estimated_cost'] ?? null,
                'due_date' => $jobData['due_date'] ?? null,
                'estimated_completion_date' => $jobData['estimated_completion_date'] ?? null,
                'assigned_to' => $jobData['assigned_to'] ?? null,
            ]);

            // Create initial status history
            $job->statusHistories()->create([
                'user_id' => auth()->id(),
                'old_status' => null,
                'new_status' => $job->status,
                'notes' => "Job created from KEW.PA-10 {$kewPA10->kew_pa_10_number}",
                'changed_at' => now(),
            ]);

            return $job->load(['kewPA10', 'governmentDepartment', 'asset', 'customer']);
        });
    }

    /**
     * Get pending verifications.
     */
    public function getPendingVerifications(): Collection
    {
        return KewPA10::with(['governmentDepartment', 'asset'])
            ->where(function ($query) {
                $query->where('form_completeness_verified', false)
                      ->orWhere('signatures_verified', false);
            })
            ->whereDoesntHave('workshopJob')
            ->orderBy('received_date', 'asc')
            ->get();
    }

    /**
     * Get KEW.PA-10 forms for a specific department.
     */
    public function getKewPA10sForDepartment(int $departmentId): Collection
    {
        return KewPA10::with(['governmentDepartment', 'asset', 'workshopJob'])
            ->where('government_department_id', $departmentId)
            ->orderBy('received_date', 'desc')
            ->get();
    }

    /**
     * Get return package data for a completed job.
     */
    public function getReturnPackage(WorkshopJob $job): array
    {
        if (!$job->kewPA10) {
            throw new \InvalidArgumentException('Job does not have an associated KEW.PA-10 form');
        }

        if ($job->status !== JobStatus::COMPLETED) {
            throw new \InvalidArgumentException('Job must be completed before generating return package');
        }

        return [
            'kew_pa_10' => $job->kewPA10,
            'job' => $job->load(['customer', 'assignedUser']),
            'completion_report' => $job->completionReport,
            'inspection_report' => $job->inspectionReport,
            'photos' => $job->photos()
                ->with('user')
                ->orderBy('photo_stage')
                ->orderBy('taken_at')
                ->get(),
            'parts_used' => $job->completionReport->parts_used ?? [],
            'total_cost' => $job->actual_cost,
            'time_spent' => $job->actual_hours,
            'status_history' => $job->statusHistories()
                ->with('user')
                ->orderBy('changed_at', 'asc')
                ->get(),
        ];
    }

    /**
     * Prepare return package for KEW.PA-10.
     */
    public function prepareReturn(WorkshopJob $job): array
    {
        // Get return package
        $package = $this->getReturnPackage($job);

        // Validate return requirements
        $missing = [];

        if (!$package['completion_report']) {
            $missing[] = 'Completion report is required';
        }

        if (!$package['completion_report']->isSigned()) {
            $missing[] = 'Completion report must be signed';
        }

        if ($package['photos']->isEmpty()) {
            $missing[] = 'At least one photo is required';
        }

        if (empty($package['parts_used'])) {
            $missing[] = 'Parts used list is required';
        }

        if (!empty($missing)) {
            throw new \InvalidArgumentException(
                'Cannot prepare return package: ' . implode(', ', $missing)
            );
        }

        return $package;
    }

    /**
     * Mark KEW.PA-10 as returned to government department.
     */
    public function markAsReturned(WorkshopJob $job, array $returnData): KewPA10
    {
        // Validate return package
        $this->prepareReturn($job);

        return DB::transaction(function () use ($job, $returnData) {
            // Update job status
            $job->update([
                'status' => JobStatus::KEW_PA_10_RETURNED,
                'kew_pa_10_returned_at' => now(),
            ]);

            // Create status history
            $job->statusHistories()->create([
                'user_id' => auth()->id(),
                'old_status' => JobStatus::PENDING_KEW_PA_10_RETURN,
                'new_status' => JobStatus::KEW_PA_10_RETURNED,
                'notes' => $returnData['notes'] ?? 'KEW.PA-10 form and documentation returned to government department',
                'changed_at' => now(),
            ]);

            return $job->kewPA10->fresh(['workshopJob', 'governmentDepartment', 'asset']);
        });
    }

    /**
     * Generate unique job reference number (format: WS-YYYY-NNNN).
     */
    protected function generateJobReference(): string
    {
        $year = now()->format('Y');
        $prefix = "WS-{$year}-";

        // Get the last job number for this year
        $lastJob = WorkshopJob::where('job_reference', 'like', "{$prefix}%")
            ->orderBy('job_reference', 'desc')
            ->first();

        if ($lastJob) {
            // Extract number and increment
            $lastNumber = (int) substr($lastJob->job_reference, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: WS-2025-0001
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
