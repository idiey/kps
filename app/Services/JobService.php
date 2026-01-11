<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Events\JobStatusChanged;
use App\Helpers\LoggingHelpers;
use App\Models\WorkshopJob;
use App\Services\Template\TemplateRenderService;
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
        protected ?TemplateRenderService $templateRenderService = null
    ) {
        // Allow null for backward compatibility, resolve from container if needed
        $this->templateRenderService ??= app(TemplateRenderService::class);
    }
    /**
     * Get paginated jobs with optional filters.
     */
    public function getPaginatedJobs(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = WorkshopJob::with(['customer', 'assignedUser', 'workflow']);

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
        Log::channel('workshop-jobs')->info('Job Creation Attempt', [
            'user_id' => auth()->id(),
            'data' => $data,
        ]);

        try {
            return DB::transaction(function () use ($data) {
                // Set initial workflow status if workflow is selected
                if (!empty($data['workflow_id']) && empty($data['current_workflow_status_id'])) {
                    $initialStatus = \App\Models\Workflow\WorkflowStatus::where('workflow_id', $data['workflow_id'])
                        ->where('is_initial', true)
                        ->first();
                    
                    if ($initialStatus) {
                        $data['current_workflow_status_id'] = $initialStatus->id;
                    }
                }

                // Auto-assign default template from workflow if not provided
                if (!empty($data['workflow_id']) && empty($data['template_id'])) {
                    $workflow = \App\Models\Workflow\Workflow::find($data['workflow_id']);
                    $defaultTemplate = $workflow?->templates()->wherePivot('is_default', true)->first();
                    if ($defaultTemplate) {
                        $data['template_id'] = $defaultTemplate->id;
                    }
                }

                $job = WorkshopJob::create($data);

                // Save dynamic field data if provided and job has a template
                if (!empty($data['field_data']) && $job->template) {
                    $this->templateRenderService->saveFormData($job, $data['field_data']);
                    Log::channel('workshop-jobs')->debug('Job Field Data Saved', [
                        'job_id' => $job->id,
                        'job_number' => $job->job_number,
                        'field_count' => count($data['field_data']),
                    ]);
                }

                // Create initial status history if user is authenticated
                if (auth()->check()) {
                    $job->statusHistories()->create([
                        'user_id' => auth()->id(),
                        'from_status' => null,
                        'to_status' => $job->status,
                        'changed_at' => now(),
                    ]);
                }

                LoggingHelpers::logJobOperation('Created', $job, [
                    'workflow_id' => $job->workflow_id,
                    'template_id' => $job->template_id,
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
                $oldStatus = $job->status;
                $newStatus = $data['status'] ?? null;

                // Update job
                $job->update($data);

                // If status changed, record it
                if ($newStatus && $oldStatus !== $newStatus) {
                    $this->recordStatusChange($job, $oldStatus, JobStatus::from($newStatus));
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
        // Validate transition
        if (!$job->canTransitionTo($newStatus)) {
            Log::channel('workshop-jobs')->warning('Status Transition Denied',
 LoggingHelpers::jobContext($job, [
                    'from_status' => $job->status->value,
                    'to_status' => $newStatus->value,
                    'reason' => 'Invalid transition',
                ])
            );

            throw new \InvalidArgumentException(
                "Cannot transition from {$job->status->label()} to {$newStatus->label()}"
            );
        }

        LoggingHelpers::logWorkflowTransition($job, $job->status->value, $newStatus->value, [
            'notes' => $notes,
        ]);

        try {
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

                Log::channel('workshop-jobs')->info('Status Changed Successfully', [
                    'job_id' => $job->id,
                    'job_number' => $job->job_number,
                    'from_status' => $oldStatus->value,
                    'to_status' => $newStatus->value,
                ]);

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
