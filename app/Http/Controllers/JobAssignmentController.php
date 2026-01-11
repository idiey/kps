<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobAssignmentRequest;
use App\Models\WorkshopJob;
use App\Services\JobAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/**
 * Controller for managing job assignments.
 */
class JobAssignmentController extends Controller
{
    public function __construct(
        protected JobAssignmentService $assignmentService
    ) {}

    /**
     * Assign or reassign a job to a technician.
     */
    public function store(StoreJobAssignmentRequest $request, WorkshopJob $job): RedirectResponse
    {
        $validated = $request->validated();

        \Illuminate\Support\Facades\Log::channel('workshop-jobs')->info('Job Assignment Attempt', [
            'job_id' => $job->id,
            'job_number' => $job->job_number,
            'assigned_to' => $validated['assigned_to'],
            'is_reassignment' => (bool) $job->assigned_to,
        ]);

        if ($job->assigned_to) {
            // Reassign
            $this->assignmentService->reassignJob(
                $job,
                $validated['assigned_to'],
                $validated['notes'] ?? null
            );
        } else {
            // Initial assignment
            $this->assignmentService->assignJob(
                $job,
                $validated['assigned_to'],
                $validated['notes'] ?? null
            );
        }

        \App\Helpers\LoggingHelpers::logAssignment(
            $job->fresh(),
            $validated['assigned_to'],
            \App\Models\User::find($validated['assigned_to'])?->name ?? 'Unknown'
        );

        return redirect()->back()
            ->with('success', __('assignments.job_assigned_successfully'));
    }

    /**
     * Get assignment history for a job.
     */
    public function history(WorkshopJob $job)
    {
        Gate::authorize('view', $job);

        $assignments = $this->assignmentService->getAssignmentHistory($job);

        return response()->json([
            'assignments' => $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'assigned_to' => $assignment->assignedTo?->name,
                    'assigned_by' => $assignment->assignedBy?->name,
                    'assigned_at' => $assignment->assigned_at,
                    'notes' => $assignment->notes,
                    'is_current' => $assignment->is_current,
                ];
            }),
        ]);
    }
}
