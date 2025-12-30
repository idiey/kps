<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Enums\PhotoStage;
use App\Models\InspectionReport;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing inspection workflow.
 * Handles inspection assignment, report creation, and approval process.
 */
class InspectionService
{
    public function __construct(
        protected PhotoStorageService $photoService
    ) {}

    /**
     * Assign an inspection to an inspector.
     */
    public function assignInspection(WorkshopJob $job, int $inspectorId, ?string $notes = null): InspectionReport
    {
        return DB::transaction(function () use ($job, $inspectorId, $notes) {
            // Create inspection report
            $inspection = InspectionReport::create([
                'workshop_job_id' => $job->id,
                'inspector_id' => $inspectorId,
                'approval_status' => 'pending',
            ]);

            // Update job status and inspection flag
            $job->update([
                'status' => JobStatus::INSPECTION_IN_PROGRESS,
                'inspection_required' => true,
            ]);

            // Create status history
            $job->statusHistories()->create([
                'user_id' => auth()->id(),
                'old_status' => JobStatus::PENDING_INSPECTION,
                'new_status' => JobStatus::INSPECTION_IN_PROGRESS,
                'notes' => $notes ?? "Inspection assigned to inspector #{$inspectorId}",
                'changed_at' => now(),
            ]);

            return $inspection->load(['workshopJob', 'inspector']);
        });
    }

    /**
     * Create or update inspection findings.
     */
    public function createReport(InspectionReport $inspection, array $findings): InspectionReport
    {
        $inspection->update($findings);

        return $inspection->fresh(['workshopJob', 'inspector', 'photos']);
    }

    /**
     * Update existing inspection report.
     */
    public function updateReport(InspectionReport $inspection, array $findings): InspectionReport
    {
        $inspection->update($findings);

        return $inspection->fresh(['workshopJob', 'inspector', 'photos']);
    }

    /**
     * Approve inspection and proceed to repair.
     */
    public function approve(InspectionReport $inspection, ?string $notes = null): void
    {
        // Validate approval requirements
        if (!$this->validateApproval($inspection)) {
            throw new \InvalidArgumentException('Inspection cannot be approved - missing required information or photos');
        }

        DB::transaction(function () use ($inspection, $notes) {
            // Approve the inspection
            $inspection->approve($notes);

            // Update job
            $job = $inspection->workshopJob;
            $job->update([
                'status' => JobStatus::INSPECTION_APPROVED,
                'inspection_approved' => true,
            ]);

            // Create status history
            $job->statusHistories()->create([
                'user_id' => auth()->id(),
                'old_status' => JobStatus::INSPECTION_IN_PROGRESS,
                'new_status' => JobStatus::INSPECTION_APPROVED,
                'notes' => $notes ?? 'Inspection approved - ready for repair',
                'changed_at' => now(),
            ]);

            // Mark inspection as completed
            $inspection->update([
                'inspection_completed_at' => now(),
            ]);
        });
    }

    /**
     * Approve inspection with additional conditions.
     */
    public function approveWithConditions(InspectionReport $inspection, string $conditions): void
    {
        // Validate approval requirements
        if (!$this->validateApproval($inspection)) {
            throw new \InvalidArgumentException('Inspection cannot be approved - missing required information or photos');
        }

        DB::transaction(function () use ($inspection, $conditions) {
            // Approve with conditions
            $inspection->approve($conditions);

            // Update job to awaiting parts (conditions usually mean additional parts needed)
            $job = $inspection->workshopJob;
            $job->update([
                'status' => JobStatus::AWAITING_PARTS,
                'inspection_approved' => true,
            ]);

            // Create status history
            $job->statusHistories()->create([
                'user_id' => auth()->id(),
                'old_status' => JobStatus::INSPECTION_IN_PROGRESS,
                'new_status' => JobStatus::AWAITING_PARTS,
                'notes' => "Inspection approved with conditions: {$conditions}",
                'changed_at' => now(),
            ]);

            // Mark inspection as completed
            $inspection->update([
                'inspection_completed_at' => now(),
            ]);
        });
    }

    /**
     * Reject inspection with reason.
     */
    public function reject(InspectionReport $inspection, string $reason): void
    {
        if (empty($reason)) {
            throw new \InvalidArgumentException('Rejection reason is required');
        }

        DB::transaction(function () use ($inspection, $reason) {
            // Reject the inspection
            $inspection->reject($reason);

            // Update job back to NEW status for re-evaluation
            $job = $inspection->workshopJob;
            $job->update([
                'status' => JobStatus::INSPECTION_REJECTED,
                'inspection_approved' => false,
            ]);

            // Create status history
            $job->statusHistories()->create([
                'user_id' => auth()->id(),
                'old_status' => JobStatus::INSPECTION_IN_PROGRESS,
                'new_status' => JobStatus::INSPECTION_REJECTED,
                'notes' => "Inspection rejected: {$reason}",
                'changed_at' => now(),
            ]);

            // Mark inspection as completed (even though rejected)
            $inspection->update([
                'inspection_completed_at' => now(),
            ]);
        });
    }

    /**
     * Validate inspection approval requirements.
     */
    public function validateApproval(InspectionReport $inspection): bool
    {
        // Check required fields
        if (empty($inspection->asset_condition_current) ||
            empty($inspection->visual_damage_assessment) ||
            empty($inspection->recommended_repairs)) {
            return false;
        }

        // Check digital signature
        if (empty($inspection->digital_signature)) {
            return false;
        }

        // Check photo requirements
        if (!$this->hasRequiredPhotos($inspection)) {
            return false;
        }

        return true;
    }

    /**
     * Check if inspection has required photos.
     */
    public function hasRequiredPhotos(InspectionReport $inspection): bool
    {
        $job = $inspection->workshopJob;

        // Check INITIAL photos (minimum 3)
        if (!$this->photoService->hasMinimumPhotos($job, PhotoStage::INITIAL, 3)) {
            return false;
        }

        // Check DIAGNOSTIC photos (minimum 3)
        if (!$this->photoService->hasMinimumPhotos($job, PhotoStage::DIAGNOSTIC, 3)) {
            return false;
        }

        return true;
    }

    /**
     * Get pending inspections.
     */
    public function getPendingInspections(): Collection
    {
        return InspectionReport::with(['workshopJob', 'inspector'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get inspections for a specific inspector.
     */
    public function getInspectionsForInspector(int $inspectorId): Collection
    {
        return InspectionReport::with(['workshopJob', 'inspector'])
            ->where('inspector_id', $inspectorId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
