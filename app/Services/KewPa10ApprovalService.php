<?php

namespace App\Services;

use App\Enums\JobMode;
use App\Enums\JobStatus;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Helpers\LoggingHelpers;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Service for handling KEW.PA-10 approval workflows.
 *
 * Business Rules:
 * - Only supervisors can approve/reject KEW.PA-10 inspections
 * - Approval transitions job to IN_PROGRESS status
 * - Rejection requires a reason and transitions to PENDING_INSPECTION
 * - All approval actions are logged with user details
 */
class KewPa10ApprovalService
{
    public function __construct(
        protected KewPa10ValidationService $validationService,
        protected JobStatusService $statusService
    ) {}

    /**
     * Approve a KEW.PA-10 inspection.
     *
     * @param WorkshopJob $job
     * @param User $approver
     * @return WorkshopJob
     * @throws InvalidArgumentException
     */
    public function approve(WorkshopJob $job, User $approver): WorkshopJob
    {
        $this->ensureCanApprove($job, $approver);

        // Validate inspection is complete
        $this->validationService->ensureInspectionComplete($job);

        LoggingHelpers::logJobOperation('Approval Attempt', $job, [
            'approver_id' => $approver->id,
            'approver_name' => $approver->name,
        ]);

        try {
            // Update job with approval details
            $job->update([
                'kew_approval_status' => 'approved',
                'kew_approved_by_id' => $approver->id,
                'kew_approved_at' => now(),
                'kew_rejection_reason' => null, // Clear any previous rejection reason
            ]);

            // Transition to approved status
            $this->statusService->transitionStatus(
                $job,
                JobStatus::INSPECTION_APPROVED,
                "Approved by {$approver->name}"
            );

            LoggingHelpers::logJobOperation('Approved', $job, [
                'approver' => $approver->name,
            ]);

            Log::channel('workshop-jobs')->info('KEW.PA-10 Job Approved', [
                'job_id' => $job->id,
                'job_number' => $job->job_number,
                'approver_id' => $approver->id,
                'approver_name' => $approver->name,
            ]);

            return $job->fresh();
        } catch (\Exception $e) {
            Log::channel('workshop-jobs')->error('KEW.PA-10 Approval Failed', [
                'job_id' => $job->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Reject a KEW.PA-10 inspection.
     *
     * @param WorkshopJob $job
     * @param User $rejector
     * @param string $reason
     * @return WorkshopJob
     * @throws InvalidArgumentException
     */
    public function reject(WorkshopJob $job, User $rejector, string $reason): WorkshopJob
    {
        $this->ensureCanApprove($job, $rejector);

        if (empty(trim($reason))) {
            throw new InvalidArgumentException('Rejection reason is required');
        }

        LoggingHelpers::logJobOperation('Rejection Attempt', $job, [
            'rejector_id' => $rejector->id,
            'rejector_name' => $rejector->name,
            'reason' => $reason,
        ]);

        try {
            // Update job with rejection details
            $job->update([
                'kew_approval_status' => 'rejected',
                'kew_approved_by_id' => $rejector->id,
                'kew_approved_at' => now(),
                'kew_rejection_reason' => $reason,
            ]);

            // Transition back to pending inspection for re-inspection
            $this->statusService->transitionStatus(
                $job,
                JobStatus::INSPECTION_REJECTED,
                "Rejected by {$rejector->name}: {$reason}"
            );

            LoggingHelpers::logJobOperation('Rejected', $job, [
                'rejector' => $rejector->name,
                'reason' => $reason,
            ]);

            Log::channel('workshop-jobs')->warning('KEW.PA-10 Job Rejected', [
                'job_id' => $job->id,
                'job_number' => $job->job_number,
                'rejector_id' => $rejector->id,
                'rejector_name' => $rejector->name,
                'reason' => $reason,
            ]);

            return $job->fresh();
        } catch (\Exception $e) {
            Log::channel('workshop-jobs')->error('KEW.PA-10 Rejection Failed', [
                'job_id' => $job->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Ensure the user can approve/reject this job.
     *
     * @param WorkshopJob $job
     * @param User $user
     * @return void
     * @throws InvalidArgumentException
     */
    protected function ensureCanApprove(WorkshopJob $job, User $user): void
    {
        // Check if job is KEW.PA-10
        if ($job->job_mode !== JobMode::KEW_PA_10) {
            throw new InvalidArgumentException(
                "Job #{$job->job_number} is not a KEW.PA-10 job"
            );
        }

        // Check if job is in correct status for approval
        $validStatuses = [
            JobStatus::INSPECTION_IN_PROGRESS,
            JobStatus::INSPECTION_REJECTED, // Allow re-approval after rejection
        ];

        if (!in_array($job->status, $validStatuses, true)) {
            throw new InvalidArgumentException(
                "Job #{$job->job_number} cannot be approved in status: {$job->status->label()}"
            );
        }

        // Check if user is a supervisor
        if (!$this->isSupervisor($user)) {
            throw new InvalidArgumentException(
                "User {$user->name} does not have supervisor permissions"
            );
        }
    }

    /**
     * Check if the user has supervisor role.
     *
     * @param User $user
     * @return bool
     */
    protected function isSupervisor(User $user): bool
    {
        // Check role using Spatie Permission package (using Malay role names)
        return $user->hasRole(['penyelia', 'pentadbiran', 'pelulus']);
    }

    /**
     * Get pending approval jobs for a supervisor.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingApprovals()
    {
        return WorkshopJob::where('job_mode', JobMode::KEW_PA_10)
            ->whereIn('status', [
                JobStatus::INSPECTION_IN_PROGRESS,
                JobStatus::INSPECTION_REJECTED
            ])
            ->with(['customer', 'assignedUser'])
            ->orderBy('kew_inspection_date', 'asc')
            ->get();
    }

    /**
     * Get approval statistics.
     *
     * @param array $filters
     * @return array
     */
    public function getApprovalStatistics(array $filters = []): array
    {
        $query = WorkshopJob::where('job_mode', JobMode::KEW_PA_10);

        if (!empty($filters['date_from'])) {
            $query->where('kew_approved_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('kew_approved_at', '<=', $filters['date_to']);
        }

        return [
            'total_approved' => (clone $query)->where('kew_approval_status', 'approved')->count(),
            'total_rejected' => (clone $query)->where('kew_approval_status', 'rejected')->count(),
            'pending_approval' => $this->getPendingApprovals()->count(),
            'average_approval_time' => $this->calculateAverageApprovalTime($query),
        ];
    }

    /**
     * Calculate average time from inspection to approval.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return float Average in hours
     */
    protected function calculateAverageApprovalTime($query): float
    {
        $jobs = (clone $query)
            ->whereNotNull('kew_inspection_date')
            ->whereNotNull('kew_approved_at')
            ->get(['kew_inspection_date', 'kew_approved_at']);

        if ($jobs->isEmpty()) {
            return 0;
        }

        $totalHours = $jobs->sum(function ($job) {
            return $job->kew_inspection_date->diffInHours($job->kew_approved_at);
        });

        return round($totalHours / $jobs->count(), 2);
    }
}
