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
use Illuminate\Support\Facades\Log;
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

        $filters = $request->only(['status', 'priority', 'assigned_to', 'customer_id', 'search', 'overdue', 'job_mode']);

        $jobs = $this->jobService->getPaginatedJobs($filters, 15);

        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs,
            'filters' => $filters,
            'statuses' => JobStatus::options(),
            'priorities' => JobPriority::options(),
            'technicians' => User::role('juruteknik')->get(['id', 'name']),
            'canCreate' => Gate::allows('create', WorkshopJob::class),
            'canEdit' => true, // Permission checks handled in methods
        ]);
    }

    /**
     * Show the form for creating a new job.
     * Redirects to mode selection (KEW.PA-10 vs Normal)
     */
    public function create(): RedirectResponse
    {
        Gate::authorize('create', WorkshopJob::class);

        // Redirect to mode selection instead of showing old form
        return redirect()->route('jobs.select-mode');
    }

    /**
     * Show job mode selection page (NEW - Architecture Simplification).
     * Users choose between KEW.PA-10 and Normal job modes.
     */
    public function selectMode(): Response
    {
        Gate::authorize('create', WorkshopJob::class);

        // Check if user has permission to create KEW.PA-10 jobs
        // Supervisors, front desk, and admins can create KEW jobs
        $canCreateKew = auth()->user()?->hasRole(['penyelia', 'pentadbiran', 'kaunter']);

        return Inertia::render('Jobs/SelectMode', [
            'canCreateKew' => $canCreateKew,
        ]);
    }

    /**
     * Show KEW.PA-10 job creation form (NEW - Architecture Simplification).
     * Government inspection job with specific required fields.
     */
    public function createKew(): Response
    {
        Gate::authorize('create', WorkshopJob::class);

        // Verify user can create KEW.PA-10 jobs
        if (!auth()->user()?->hasRole(['penyelia', 'pentadbiran', 'kaunter'])) {
            abort(403, 'Only supervisors and front desk can create KEW.PA-10 jobs.');
        }

        return Inertia::render('Jobs/CreateKewPa10', [
            'customers' => Customer::orderBy('name')->get(['id', 'name', 'phone', 'email']),
        ]);
    }

    /**
     * Show normal job creation form (NEW - Architecture Simplification).
     * Standard workshop job with customer, priority, and cost estimation.
     */
    public function createNormal(): Response
    {
        Gate::authorize('create', WorkshopJob::class);

        return Inertia::render('Jobs/CreateNormal', [
            'customers' => Customer::orderBy('name')->get(['id', 'name', 'phone', 'email']),
            'priorities' => JobPriority::options(),
        ]);
    }


    /**
     * Store a newly created job.
     */
    public function store(StoreJobRequest $request): RedirectResponse
    {
        try {
            Log::info('Job Creation Attempt', [
                'user' => auth()->id(),
                'data' => $request->all(),
            ]);

            $data = $request->validated();

            // set default title if missing
            if (empty($data['title'])) {
                $data['title'] = 'New Job';
            }

            $job = $this->jobService->createJob($data);

            Log::info('Job Created Successfully', ['job_id' => $job->id]);

            return redirect()->route('jobs.show', $job)
                ->with('success', __('jobs.created_successfully'));
        } catch (\Exception $e) {
            Log::error('Job Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'Failed to create job: ' . $e->getMessage()])->withInput();
        }
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

        // Check if user can approve KEW inspections
        $canApprove = $job->job_mode === 'KEW_PA_10' 
            && auth()->user()?->hasAnyRole(['penyelia', 'pentadbiran', 'pelulus']);

        return Inertia::render('Jobs/Show', [
            'job' => $job,
            'notes' => $job->notes,
            'assignments' => $job->assignments,
            'statusHistory' => $job->statusHistories,
            'technicians' => User::role('juruteknik')->get(['id', 'name']),
            'statuses' => JobStatus::options(),
            'priorities' => JobPriority::options(),
            'allowedStatusTransitions' => $job->status->allowedTransitions($job->job_mode), // Enum method needs update if it relies on workflow
            'canEdit' => Gate::allows('update', $job),
            'canDelete' => Gate::allows('delete', $job),
            'canApprove' => $canApprove,
            'canAssign' => Gate::allows('assign', $job),
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
            'technicians' => User::role('juruteknik')->get(['id', 'name']),
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
