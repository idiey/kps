<?php

namespace App\Services;

use App\Enums\JobMode;
use App\Enums\JobStatus;
use App\Helpers\LoggingHelpers;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service class for managing workshop jobs.
 * Implements business logic following Single Responsibility Principle.
 */
class JobService
{
    public function __construct(
        protected JobStatusService $jobStatusService,
        protected KewPa10ValidationService $kewValidationService
    ) {}

    /**
     * Get paginated jobs with optional filters.
     */
    public function getPaginatedJobs(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = WorkshopJob::with(['customer', 'assignedUser']);

        if (auth()->check()) {
            // Simplified role check logic can be added here if needed
            // Previously relied on dynamic workflow allowed_roles
        }

        // Apply filters
        if (!empty($filters['status'])) {
            $query->ofStatus($filters['status']);
        }
        
        if (!empty($filters['job_mode'])) {
            $query->where('job_mode', $filters['job_mode']);
        }

        if (!empty($filters['priority'])) {
            $query->ofPriority($filters['priority']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->assignedTo($filters['assigned_to']);
        }

        if (!empty($filters['customer_id'])) {
            $query->forCustomer($filters['customer_id']);
        }

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['overdue']) && $filters['overdue']) {
            $query->overdue();
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Create a new job.
     */
    public function createJob(array $data): WorkshopJob
    {
        Log::channel('workshop-jobs')->info('Job Creation Attempt', [
            'user_id' => auth()->id(),
            'data' => $data,
        ]);

        try {
            return DB::transaction(function () use ($data) {
                // Ensure job_mode is set
                $data['job_mode'] ??= JobMode::NORMAL->value;
                
                // For Normal jobs, status is typically NEW
                if (empty($data['status'])) {
                    $data['status'] = JobStatus::NEW->value;
                }

                $job = WorkshopJob::create($data);

                // Create initial status history if user is authenticated
                if (auth()->check()) {
                    $job->statusHistories()->create([
                        'user_id' => auth()->id(),
                        'from_status' => null,
                        'to_status' => $job->status,
                        'changed_at' => now(),
                        'notes' => 'Job created',
                    ]);
                }

                LoggingHelpers::logJobOperation('Created', $job, [
                    'job_mode' => $job->job_mode?->value,
                    'status' => $job->status->value,
                ]);

                return $job->load(['customer', 'assignedUser']);
            });
        } catch (\Exception $e) {
            Log::channel('workshop-jobs')->error('Job Creation Failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing job.
     */
    public function updateJob(WorkshopJob $job, array $data): WorkshopJob
    {
        LoggingHelpers::logJobOperation('Update Attempt', $job, [
            'changes' => array_keys($data),
        ]);

        try {
            return DB::transaction(function () use ($job, $data) {
                // Check if status is changing
                $newStatus = isset($data['status']) ? JobStatus::tryFrom($data['status']) : null;
                
                // If checking KEW specs
                if ($job->job_mode === JobMode::KEW_PA_10 && !empty($data['kew_findings'])) {
                   // Optional validations here
                }

                // Update job
                $job->update($data);

                // If status passed in data, use service to transition
                if ($newStatus && $newStatus !== $job->status) {
                    $this->jobStatusService->transitionStatus($job, $newStatus);
                }

                LoggingHelpers::logJobOperation('Updated', $job, [
                    'fields_updated' => array_keys($data),
                ]);

                return $job->fresh(['customer', 'assignedUser']);
            });
        } catch (\Exception $e) {
            Log::channel('workshop-jobs')->error('Job Update Failed', [
                'job_id' => $job->id,
                'job_number' => $job->job_number,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a job.
     */
    public function deleteJob(WorkshopJob $job): bool
    {
        LoggingHelpers::logJobOperation('Delete Attempt', $job);

        $result = $job->delete();

        if ($result) {
            LoggingHelpers::logJobOperation('Deleted (Soft Delete)', $job);
        } else {
            Log::channel('workshop-jobs')->warning('Job Delete Failed', [
                'job_id' => $job->id,
                'job_number' => $job->job_number,
            ]);
        }

        return $result;
    }

    /**
     * Change job status with validation.
     */
    public function changeStatus(
        WorkshopJob $job,
        JobStatus $newStatus,
        ?string $notes = null
    ): WorkshopJob {
        try {
            return DB::transaction(function () use ($job, $newStatus, $notes) {
                // Validate KEW specific gates
                if ($job->job_mode === JobMode::KEW_PA_10) {
                    if ($newStatus === JobStatus::INSPECTION_APPROVED || $newStatus === JobStatus::INSPECTION_REJECTED) {
                        $this->kewValidationService->ensureInspectionComplete($job);
                    }
                }

                // Delegate transition logic to JobStatusService
                $this->jobStatusService->transitionStatus($job, $newStatus, $notes);

                return $job->fresh();
            });
        } catch (\Exception $e) {
            Log::channel('workshop-jobs')->error('Status Change Failed', [
                'job_id' => $job->id,
                'job_number' => $job->job_number,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get jobs assigned to a specific user.
     */
    public function getJobsForTechnician(int $userId, ?string $status = null): Collection
    {
        $query = WorkshopJob::assignedTo($userId)
            ->with(['customer', 'assignedUser']);

        if ($status) {
            $query->ofStatus($status);
        }

        return $query->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->get();
    }

    /**
     * Get jobs for a customer.
     */
    public function getJobsForCustomer(int $customerId): Collection
    {
        return WorkshopJob::forCustomer($customerId)
            ->with(['assignedUser'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get overdue jobs.
     */
    public function getOverdueJobs(): Collection
    {
        return WorkshopJob::overdue()
            ->with(['customer', 'assignedUser'])
            ->get();
    }

    /**
     * Get job statistics.
     */
    public function getStatistics(array $filters = []): array
    {
        $query = WorkshopJob::query();

        // Apply filters
        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return [
            'total' => $query->count(),
            'by_status' => $query->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_priority' => $query->select('priority', DB::raw('count(*) as count'))
                ->groupBy('priority')
                ->pluck('count', 'priority')
                ->toArray(),
            'overdue' => WorkshopJob::overdue()->count(),
        ];
    }
}
