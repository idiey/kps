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
                    'assigned_to' => $assignment->assignedUser->name,
                    'assigned_by' => $assignment->assignedByUser->name,
                    'assigned_at' => $assignment->assigned_at,
                    'notes' => $assignment->notes,
                    'is_current' => $assignment->is_current,
                ];
            }),
        ]);
    }
}
