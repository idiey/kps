<?php

namespace App\Services;

use App\Enums\JobMode;
use App\Enums\JobStatus;
use App\Events\JobStatusChanged;
use App\Helpers\LoggingHelpers;
use App\Models\WorkshopJob;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class JobStatusService
{
    /**
     * Transition a job to a new status with validation based on job mode.
     */
    public function transitionStatus(WorkshopJob $job, JobStatus $newStatus, ?string $notes = null): void
    {
        // validate based on job mode
        if ($job->job_mode === JobMode::KEW_PA_10) {
            $this->validateKewTransition($job, $newStatus);
        } else {
            $this->validateNormalTransition($job, $newStatus);
        }

        $oldStatus = $job->status;

        // If status is same, do nothing
        if ($oldStatus === $newStatus) {
            return;
        }

        // Update status
        $job->update(['status' => $newStatus]);
        
        // Handle side effects (timestamps, etc)
        $this->handleStatusSideEffects($job, $newStatus);
        
        // Record history
        $job->statusHistories()->create([
            'user_id' => auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'notes' => $notes,
            'changed_at' => now(),
        ]);

        // Dispatch event
        event(new JobStatusChanged($job, $oldStatus, $newStatus));

        LoggingHelpers::logJobOperation('Status Changed', $job, [
            'from' => $oldStatus->value,
            'to' => $newStatus->value,
            'mode' => $job->job_mode?->value,
            'notes' => $notes
        ]);
    }

    /**
     * Validate transitions specifically for KEW.PA-10 jobs.
     */
    protected function validateKewTransition(WorkshopJob $job, JobStatus $newStatus): void
    {
        $current = $job->status;

        // KEW.PA-10 Specific Flow:
        // PENDING_INSPECTION -> INSPECTION_IN_PROGRESS
        // INSPECTION_IN_PROGRESS -> INSPECTION_APPROVED | INSPECTION_REJECTED
        // INSPECTION_APPROVED -> IN_PROGRESS (Repair) | AWAITING_PARTS
        // IN_PROGRESS -> COMPLETED | AWAITING_PARTS
        // COMPLETED -> PENDING_KEW_PA_10_RETURN
        // PENDING_KEW_PA_10_RETURN -> KEW_PA_10_RETURNED
        // KEW_PA_10_RETURNED -> INVOICED
        
        // Define allowed transitions for KEW.PA-10
        $allowed = match($current) {
            JobStatus::NEW => [JobStatus::PENDING_INSPECTION, JobStatus::CANCELLED],
            JobStatus::PENDING_INSPECTION => [JobStatus::INSPECTION_IN_PROGRESS, JobStatus::CANCELLED],
            JobStatus::INSPECTION_IN_PROGRESS => [JobStatus::INSPECTION_APPROVED, JobStatus::INSPECTION_REJECTED],
            JobStatus::INSPECTION_APPROVED => [JobStatus::IN_PROGRESS, JobStatus::AWAITING_PARTS],
            JobStatus::INSPECTION_REJECTED => [JobStatus::PENDING_INSPECTION, JobStatus::CANCELLED], // Re-inspect or cancel
            JobStatus::AWAITING_PARTS => [JobStatus::IN_PROGRESS],
            JobStatus::IN_PROGRESS => [JobStatus::COMPLETED, JobStatus::AWAITING_PARTS],
            JobStatus::COMPLETED => [JobStatus::PENDING_KEW_PA_10_RETURN, JobStatus::INVOICED], // Can go direct to invoiced? Usually return first.
            JobStatus::PENDING_KEW_PA_10_RETURN => [JobStatus::KEW_PA_10_RETURNED],
            JobStatus::KEW_PA_10_RETURNED => [JobStatus::INVOICED],
            JobStatus::INVOICED => [],
            JobStatus::CANCELLED => [],
            default => []
        };

        if (!in_array($newStatus, $allowed, true)) {
             // Allow super admin override or specific roles if needed, for now strict.
             throw new InvalidArgumentException(
                "Invalid status transition for KEW.PA-10 Job: {$current->label()} -> {$newStatus->label()}"
             );
        }
    }

    /**
     * Validate transitions for NORMAL jobs.
     */
    protected function validateNormalTransition(WorkshopJob $job, JobStatus $newStatus): void
    {
        // Use the standard allowed transitions from Enum, or define custom subset
        if (!$job->status->canTransitionTo($newStatus)) {
             throw new InvalidArgumentException(
                "Invalid status transition for Normal Job: {$job->status->label()} -> {$newStatus->label()}"
             );
        }
    }

    /**
     * Handle side effects like updating timestamps.
     */
    protected function handleStatusSideEffects(WorkshopJob $job, JobStatus $newStatus): void
    {
        $updateData = [];
        
        switch ($newStatus) {
            case JobStatus::IN_PROGRESS:
            case JobStatus::INSPECTION_IN_PROGRESS: 
                if (!$job->started_at) {
                    $updateData['started_at'] = now();
                }
                break;
            case JobStatus::COMPLETED:
                $updateData['completed_at'] = now();
                break;
            case JobStatus::INVOICED:
                $updateData['invoiced_at'] = now();
                break;
        }

        if (!empty($updateData)) {
            $job->update($updateData);
        }
    }
}
