<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Events\JobStatusChanged;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing workshop jobs.
 * Implements business logic following Single Responsibility Principle.
 */
class JobService
{
    /**
     * Get paginated jobs with optional filters.
     */
    public function getPaginatedJobs(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = WorkshopJob::with(['customer', 'assignedUser']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->ofStatus($filters['status']);
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
        return DB::transaction(function () use ($data) {
            $job = WorkshopJob::create($data);

            // Create initial status history if user is authenticated
            if (auth()->check()) {
                $job->statusHistories()->create([
                    'user_id' => auth()->id(),
                    'from_status' => null,
                    'to_status' => $job->status,
                    'changed_at' => now(),
                ]);
            }

            return $job->load(['customer', 'assignedUser']);
        });
    }

    /**
     * Update an existing job.
     */
    public function updateJob(WorkshopJob $job, array $data): WorkshopJob
    {
        return DB::transaction(function () use ($job, $data) {
            // Check if status is changing
            $oldStatus = $job->status;
            $newStatus = $data['status'] ?? null;

            // Update job
            $job->update($data);

            // If status changed, record it
            if ($newStatus && $oldStatus !== $newStatus) {
                $this->recordStatusChange($job, $oldStatus, JobStatus::from($newStatus));
            }

            return $job->fresh(['customer', 'assignedUser']);
        });
    }

    /**
     * Delete a job.
     */
    public function deleteJob(WorkshopJob $job): bool
    {
        return $job->delete();
    }

    /**
     * Change job status with validation.
     */
    public function changeStatus(
        WorkshopJob $job,
        JobStatus $newStatus,
        ?string $notes = null
    ): WorkshopJob {
        // Validate transition
        if (!$job->canTransitionTo($newStatus)) {
            throw new \InvalidArgumentException(
                "Cannot transition from {$job->status->label()} to {$newStatus->label()}"
            );
        }

        return DB::transaction(function () use ($job, $newStatus, $notes) {
            $oldStatus = $job->status;

            // Update job status
            $job->update(['status' => $newStatus]);

            // Update timestamps based on status
            match($newStatus) {
                JobStatus::IN_PROGRESS => $job->update(['started_at' => $job->started_at ?? now()]),
                JobStatus::COMPLETED => $job->update(['completed_at' => now()]),
                JobStatus::INVOICED => $job->update(['invoiced_at' => now()]),
                default => null,
            };

            // Record status change
            $this->recordStatusChange($job, $oldStatus, $newStatus, $notes);

            // Dispatch event
            event(new JobStatusChanged($job, $oldStatus, $newStatus));

            return $job->fresh();
        });
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
     * Record a status change in history.
     */
    protected function recordStatusChange(
        WorkshopJob $job,
        JobStatus $fromStatus,
        JobStatus $toStatus,
        ?string $notes = null
    ): void {
        $job->statusHistories()->create([
            'user_id' => auth()->id(),
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'notes' => $notes,
            'changed_at' => now(),
        ]);
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
