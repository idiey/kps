<?php

namespace App\Http\Controllers;

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Requests\UpdateJobStatusRequest;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkshopJob;
use App\Services\JobService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller for managing workshop jobs.
 * Follows Laravel conventions and SOLID principles.
 */
class JobController extends Controller
{
    public function __construct(
        protected JobService $jobService
    ) {}

    /**
     * Display a listing of jobs.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', WorkshopJob::class);

        $filters = $request->only(['status', 'priority', 'assigned_to', 'customer_id', 'search', 'overdue']);

        $jobs = $this->jobService->getPaginatedJobs($filters, 15);

        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs,
            'filters' => $filters,
            'statuses' => JobStatus::options(),
            'priorities' => JobPriority::options(),
            'technicians' => User::where('role', 'juruteknik')->get(['id', 'name']),
        ]);
    }

    /**
     * Show the form for creating a new job.
     */
    public function create(): Response
    {
        Gate::authorize('create', WorkshopJob::class);

        return Inertia::render('Jobs/Create', [
            'customers' => Customer::orderBy('name')->get(['id', 'name', 'phone', 'email']),
            'technicians' => User::where('role', 'juruteknik')->get(['id', 'name']),
            'statuses' => JobStatus::options(),
            'priorities' => JobPriority::options(),
        ]);
    }

    /**
     * Store a newly created job.
     */
    public function store(StoreJobRequest $request): RedirectResponse
    {
        $job = $this->jobService->createJob($request->validated());

        return redirect()->route('jobs.show', $job)
            ->with('success', __('jobs.created_successfully'));
    }

    /**
     * Display the specified job.
     */
    public function show(WorkshopJob $job): Response
    {
        Gate::authorize('view', $job);

        $job->load([
            'customer',
            'assignedUser',
            'notes.user',
            'statusHistories.user',
            'assignments.assignedTo',
            'assignments.assignedBy',
        ]);

        return Inertia::render('Jobs/Show', [
            'job' => $job,
            'statuses' => JobStatus::options(),
            'priorities' => JobPriority::options(),
            'allowedStatusTransitions' => $job->status->allowedTransitions(),
        ]);
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(WorkshopJob $job): Response
    {
        Gate::authorize('update', $job);

        $job->load(['customer', 'assignedUser']);

        return Inertia::render('Jobs/Edit', [
            'job' => $job,
            'customers' => Customer::orderBy('name')->get(['id', 'name', 'phone', 'email']),
            'technicians' => User::where('role', 'juruteknik')->get(['id', 'name']),
            'statuses' => JobStatus::options(),
            'priorities' => JobPriority::options(),
        ]);
    }

    /**
     * Update the specified job.
     */
    public function update(UpdateJobRequest $request, WorkshopJob $job): RedirectResponse
    {
        $this->jobService->updateJob($job, $request->validated());

        return redirect()->route('jobs.show', $job)
            ->with('success', __('jobs.updated_successfully'));
    }

    /**
     * Remove the specified job.
     */
    public function destroy(WorkshopJob $job): RedirectResponse
    {
        Gate::authorize('delete', $job);

        $this->jobService->deleteJob($job);

        return redirect()->route('jobs.index')
            ->with('success', __('jobs.deleted_successfully'));
    }

    /**
     * Update job status with validation.
     */
    public function updateStatus(UpdateJobStatusRequest $request, WorkshopJob $job): RedirectResponse
    {
        $newStatus = JobStatus::from($request->validated('status'));

        $this->jobService->changeStatus($job, $newStatus, $request->validated('notes'));

        return redirect()->back()
            ->with('success', __('jobs.status_updated_successfully'));
    }

    /**
     * Get job timeline (combined activity feed).
     */
    public function timeline(WorkshopJob $job)
    {
        Gate::authorize('view', $job);

        $timeline = [];

        // Add status changes
        foreach ($job->statusHistories as $history) {
            $timeline[] = [
                'type' => 'status_change',
                'timestamp' => $history->changed_at,
                'data' => [
                    'from_status' => $history->from_status?->label(),
                    'to_status' => $history->to_status->label(),
                    'user' => $history->user->name,
                    'notes' => $history->notes,
                ],
            ];
        }

        // Add assignments
        foreach ($job->assignments as $assignment) {
            $timeline[] = [
                'type' => 'assignment',
                'timestamp' => $assignment->assigned_at,
                'data' => [
                    'assigned_to' => $assignment->assignedTo?->name,
                    'assigned_by' => $assignment->assignedBy?->name,
                    'notes' => $assignment->notes,
                    'is_current' => $assignment->is_current,
                ],
            ];
        }

        // Add notes
        foreach ($job->notes as $note) {
            $timeline[] = [
                'type' => 'note',
                'timestamp' => $note->created_at,
                'data' => [
                    'content' => $note->content,
                    'user' => $note->user->name,
                    'is_public' => $note->is_public,
                    'note_type' => $note->note_type,
                ],
            ];
        }

        // Sort by timestamp descending
        usort($timeline, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return response()->json(['timeline' => $timeline]);
    }
}
