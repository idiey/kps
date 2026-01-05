<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkflowStatusController extends Controller
{


    /**
     * Display a listing of statuses for a workflow.
     */
    public function index(Workflow $workflow)
    {
        $statuses = $workflow->statuses()
            ->orderBy('display_order')
            ->get();

        return Inertia::render('Admin/Workflows/Statuses/Index', [
            'workflow' => $workflow,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new status.
     */
    public function create(Workflow $workflow)
    {
        return Inertia::render('Admin/Workflows/Statuses/Create', [
            'workflow' => $workflow,
        ]);
    }

    /**
     * Store a newly created status.
     */
    public function store(Request $request, Workflow $workflow)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'is_initial' => 'boolean',
            'is_final' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Ensure code is unique within workflow
        $exists = $workflow->statuses()
            ->where('code', $validated['code'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'code' => 'A status with this code already exists in this workflow.',
            ]);
        }

        // If marking as initial, unset other initial statuses
        if ($validated['is_initial'] ?? false) {
            $workflow->statuses()->update(['is_initial' => false]);
        }

        $validated['workflow_id'] = $workflow->id;

        $status = WorkflowStatus::create($validated);

        return redirect()->route('admin.workflows.statuses.index', $workflow)
            ->with('success', 'Status created successfully.');
    }

    /**
     * Display the specified status.
     */
    public function show(Workflow $workflow, WorkflowStatus $status)
    {
        // Ensure status belongs to workflow
        if ($status->workflow_id !== $workflow->id) {
            abort(404);
        }

        $status->load(['transitionsFrom', 'transitionsTo']);

        return Inertia::render('Admin/Workflows/Statuses/Show', [
            'workflow' => $workflow,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for editing the specified status.
     */
    public function edit(Workflow $workflow, WorkflowStatus $status)
    {
        // Ensure status belongs to workflow
        if ($status->workflow_id !== $workflow->id) {
            abort(404);
        }

        return Inertia::render('Admin/Workflows/Statuses/Edit', [
            'workflow' => $workflow,
            'status' => $status,
        ]);
    }

    /**
     * Update the specified status.
     */
    public function update(Request $request, Workflow $workflow, WorkflowStatus $status)
    {
        // Ensure status belongs to workflow
        if ($status->workflow_id !== $workflow->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'is_initial' => 'boolean',
            'is_final' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Ensure code is unique within workflow (excluding current status)
        $exists = $workflow->statuses()
            ->where('code', $validated['code'])
            ->where('id', '!=', $status->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'code' => 'A status with this code already exists in this workflow.',
            ]);
        }

        // If marking as initial, unset other initial statuses
        if ($validated['is_initial'] ?? false) {
            $workflow->statuses()
                ->where('id', '!=', $status->id)
                ->update(['is_initial' => false]);
        }

        $status->update($validated);

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * Remove the specified status.
     */
    public function destroy(Workflow $workflow, WorkflowStatus $status)
    {
        // Ensure status belongs to workflow
        if ($status->workflow_id !== $workflow->id) {
            abort(404);
        }

        // Check if status is in use
        if ($status->jobs()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete status that is in use by jobs.',
            ]);
        }

        // Check if status has transitions
        if ($status->transitionsFrom()->count() > 0 || $status->transitionsTo()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete status that has transitions. Delete transitions first.',
            ]);
        }

        $status->delete();

        return redirect()->route('admin.workflows.statuses.index', $workflow)
            ->with('success', 'Status deleted successfully.');
    }

    /**
     * Reorder statuses.
     */
    public function reorder(Request $request, Workflow $workflow)
    {
        $validated = $request->validate([
            'statuses' => 'required|array',
            'statuses.*.id' => 'required|exists:workflow_statuses,id',
            'statuses.*.display_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['statuses'] as $statusData) {
            WorkflowStatus::where('id', $statusData['id'])
                ->where('workflow_id', $workflow->id)
                ->update(['display_order' => $statusData['display_order']]);
        }

        return back()->with('success', 'Statuses reordered successfully.');
    }
}
