<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowTransition;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class WorkflowTransitionController extends Controller
{


    /**
     * Display a listing of transitions for a workflow.
     */
    public function index(Workflow $workflow)
    {
        $transitions = $workflow->transitions()
            ->with(['fromStatus', 'toStatus'])
            ->orderBy('display_order')
            ->get();

        return Inertia::render('Admin/Workflows/Transitions/Index', [
            'workflow' => $workflow,
            'transitions' => $transitions,
        ]);
    }

    /**
     * Show the form for creating a new transition.
     */
    public function create(Workflow $workflow)
    {
        $statuses = $workflow->statuses()
            ->orderBy('display_order')
            ->get();

        $roles = Role::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'description']);

        return Inertia::render('Admin/Workflows/Transitions/Create', [
            'workflow' => $workflow,
            'statuses' => $statuses,
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created transition.
     */
    public function store(Request $request, Workflow $workflow)
    {
        $validated = $request->validate([
            'from_status_id' => 'required|exists:workflow_statuses,id',
            'to_status_id' => 'required|exists:workflow_statuses,id|different:from_status_id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requires_permission' => 'nullable|string|max:255',
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'exists:roles,id',
            'conditions' => 'nullable|array',
            'actions' => 'nullable|array',
            'button_label' => 'nullable|string|max:255',
            'button_color' => 'nullable|string|max:50',
            'confirmation_message' => 'nullable|string',
            'requires_comment' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Verify statuses belong to this workflow
        $fromStatus = $workflow->statuses()->find($validated['from_status_id']);
        $toStatus = $workflow->statuses()->find($validated['to_status_id']);

        if (!$fromStatus || !$toStatus) {
            return back()->withErrors([
                'error' => 'Selected statuses must belong to this workflow.',
            ]);
        }

        // Check if transition already exists
        $exists = $workflow->transitions()
            ->where('from_status_id', $validated['from_status_id'])
            ->where('to_status_id', $validated['to_status_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'error' => 'A transition between these statuses already exists.',
            ]);
        }

        $validated['workflow_id'] = $workflow->id;

        $transition = WorkflowTransition::create($validated);

        return redirect()->route('admin.workflows.transitions.index', $workflow)
            ->with('success', 'Transition created successfully.');
    }

    /**
     * Display the specified transition.
     */
    public function show(Workflow $workflow, WorkflowTransition $transition)
    {
        // Ensure transition belongs to workflow
        if ($transition->workflow_id !== $workflow->id) {
            abort(404);
        }

        $transition->load(['fromStatus', 'toStatus']);

        // Load role names if allowed_roles is set
        if ($transition->allowed_roles) {
            $transition->allowed_role_names = Role::whereIn('id', $transition->allowed_roles)
                ->pluck('name')
                ->toArray();
        }

        return Inertia::render('Admin/Workflows/Transitions/Show', [
            'workflow' => $workflow,
            'transition' => $transition,
        ]);
    }

    /**
     * Show the form for editing the specified transition.
     */
    public function edit(Workflow $workflow, WorkflowTransition $transition)
    {
        // Ensure transition belongs to workflow
        if ($transition->workflow_id !== $workflow->id) {
            abort(404);
        }

        $transition->load(['fromStatus', 'toStatus']);

        $statuses = $workflow->statuses()
            ->orderBy('display_order')
            ->get();

        $roles = Role::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'description']);

        return Inertia::render('Admin/Workflows/Transitions/Edit', [
            'workflow' => $workflow,
            'transition' => $transition,
            'statuses' => $statuses,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified transition.
     */
    public function update(Request $request, Workflow $workflow, WorkflowTransition $transition)
    {
        // Ensure transition belongs to workflow
        if ($transition->workflow_id !== $workflow->id) {
            abort(404);
        }

        $validated = $request->validate([
            'from_status_id' => 'required|exists:workflow_statuses,id',
            'to_status_id' => 'required|exists:workflow_statuses,id|different:from_status_id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requires_permission' => 'nullable|string|max:255',
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'exists:roles,id',
            'conditions' => 'nullable|array',
            'actions' => 'nullable|array',
            'button_label' => 'nullable|string|max:255',
            'button_color' => 'nullable|string|max:50',
            'confirmation_message' => 'nullable|string',
            'requires_comment' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Verify statuses belong to this workflow
        $fromStatus = $workflow->statuses()->find($validated['from_status_id']);
        $toStatus = $workflow->statuses()->find($validated['to_status_id']);

        if (!$fromStatus || !$toStatus) {
            return back()->withErrors([
                'error' => 'Selected statuses must belong to this workflow.',
            ]);
        }

        // Check if transition already exists (excluding current transition)
        $exists = $workflow->transitions()
            ->where('from_status_id', $validated['from_status_id'])
            ->where('to_status_id', $validated['to_status_id'])
            ->where('id', '!=', $transition->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'error' => 'A transition between these statuses already exists.',
            ]);
        }

        $transition->update($validated);

        return back()->with('success', 'Transition updated successfully.');
    }

    /**
     * Remove the specified transition.
     */
    public function destroy(Workflow $workflow, WorkflowTransition $transition)
    {
        // Ensure transition belongs to workflow
        if ($transition->workflow_id !== $workflow->id) {
            abort(404);
        }

        // Check if transition is in use in job history
        if ($transition->statusHistories()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete transition that has been used in job history.',
            ]);
        }

        $transition->delete();

        return redirect()->route('admin.workflows.transitions.index', $workflow)
            ->with('success', 'Transition deleted successfully.');
    }

    /**
     * Reorder transitions.
     */
    public function reorder(Request $request, Workflow $workflow)
    {
        $validated = $request->validate([
            'transitions' => 'required|array',
            'transitions.*.id' => 'required|exists:workflow_transitions,id',
            'transitions.*.display_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['transitions'] as $transitionData) {
            WorkflowTransition::where('id', $transitionData['id'])
                ->where('workflow_id', $workflow->id)
                ->update(['display_order' => $transitionData['display_order']]);
        }

        return back()->with('success', 'Transitions reordered successfully.');
    }

    /**
     * Bulk update transition conditions.
     */
    public function updateConditions(Request $request, Workflow $workflow, WorkflowTransition $transition)
    {
        // Ensure transition belongs to workflow
        if ($transition->workflow_id !== $workflow->id) {
            abort(404);
        }

        $validated = $request->validate([
            'conditions' => 'nullable|array',
        ]);

        $transition->update($validated);

        return back()->with('success', 'Transition conditions updated successfully.');
    }

    /**
     * Bulk update transition actions.
     */
    public function updateActions(Request $request, Workflow $workflow, WorkflowTransition $transition)
    {
        // Ensure transition belongs to workflow
        if ($transition->workflow_id !== $workflow->id) {
            abort(404);
        }

        $validated = $request->validate([
            'actions' => 'nullable|array',
        ]);

        $transition->update($validated);

        return back()->with('success', 'Transition actions updated successfully.');
    }
}
