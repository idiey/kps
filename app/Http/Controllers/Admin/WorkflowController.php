<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use App\Models\Workflow\WorkflowTransition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WorkflowController extends Controller
{


    /**
     * Display a listing of workflows.
     */
    public function index()
    {
        $workflows = Workflow::with(['creator', 'updater'])
            ->withCount(['statuses', 'transitions', 'jobs'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Workflows/Index', [
            'workflows' => $workflows,
        ]);
    }

    /**
     * Show the form for creating a new workflow.
     */
    public function create()
    {
        return Inertia::render('Admin/Workflows/Create');
    }

    /**
     * Store a newly created workflow.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:workflows,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        $validated['created_by'] = auth()->id();

        $workflow = Workflow::create($validated);

        return redirect()->route('admin.workflows.edit', $workflow)
            ->with('success', 'Workflow created successfully. Now add statuses and transitions.');
    }

    /**
     * Display the specified workflow.
     */
    public function show(Workflow $workflow)
    {
        $workflow->load(['statuses', 'transitions.fromStatus', 'transitions.toStatus', 'rules']);

        return Inertia::render('Admin/Workflows/Show', [
            'workflow' => $workflow,
        ]);
    }

    /**
     * Show the form for editing the specified workflow.
     */
    public function edit(Workflow $workflow)
    {
        $workflow->load(['statuses', 'transitions.fromStatus', 'transitions.toStatus', 'rules']);

        return Inertia::render('Admin/Workflows/Edit', [
            'workflow' => $workflow,
        ]);
    }

    /**
     * Update the specified workflow.
     */
    public function update(Request $request, Workflow $workflow)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:workflows,code,' . $workflow->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        $validated['updated_by'] = auth()->id();

        $workflow->update($validated);

        return back()->with('success', 'Workflow updated successfully.');
    }

    /**
     * Remove the specified workflow.
     */
    public function destroy(Workflow $workflow)
    {
        // Check if workflow is in use
        if ($workflow->jobs()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete workflow that is in use by jobs.']);
        }

        $workflow->delete();

        return redirect()->route('admin.workflows.index')
            ->with('success', 'Workflow deleted successfully.');
    }

    /**
     * Visual workflow builder page.
     */
    /**
     * Visual workflow builder page.
     */
    public function builder(Workflow $workflow)
    {
        $workflow->load(['statuses', 'transitions.fromStatus', 'transitions.toStatus']);

        $availableWorkflows = Workflow::where('id', '!=', $workflow->id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Workflows/Builder', [
            'workflow' => $workflow,
            'availableWorkflows' => $availableWorkflows,
        ]);
    }

    /**
     * Import statuses and transitions from another workflow.
     */
    public function import(Request $request, Workflow $workflow)
    {
        $validated = $request->validate([
            'source_workflow_id' => 'required|exists:workflows,id',
            'mode' => 'required|in:replace,append',
        ]);

        $sourceWorkflow = Workflow::with(['statuses', 'transitions'])->findOrFail($validated['source_workflow_id']);
        $mode = $validated['mode'];

        DB::transaction(function () use ($workflow, $sourceWorkflow, $mode) {
            // If replace mode, delete existing statuses and transitions
            if ($mode === 'replace') {
                $workflow->transitions()->forceDelete(); // Force delete to completely remove and allow re-import
                $workflow->statuses()->forceDelete();
            }

            // Map old status IDs to new status objects to reconstruct transitions
            $statusMap = [];

            // Duplicate Statuses
            foreach ($sourceWorkflow->statuses as $status) {
                $newStatus = $workflow->statuses()->create([
                    'name' => $status->name . ($mode === 'append' ? ' (Copy)' : ''),
                    'code' => $status->code . ($mode === 'append' ? '_' . uniqid() : ''), // Ensure unique code if needed or just copy
                    'description' => $status->description,
                    'color' => $status->color,
                    'icon' => $status->icon,
                    'is_initial' => $mode === 'replace' ? $status->is_initial : false, // Only keep initial if replacing
                    'is_final' => $status->is_final,
                    'display_order' => $status->display_order,
                    'metadata' => $status->metadata,
                    // 'created_by' is handled by Eloquent boot or manually if needed, usually auth user
                ]);

                $statusMap[$status->id] = $newStatus->id;
            }

            // Duplicate Transitions
            foreach ($sourceWorkflow->transitions as $transition) {
                // Ensure both from and to statuses were part of the import (or handle dangling edges if filtering)
                if (isset($statusMap[$transition->from_status_id]) && isset($statusMap[$transition->to_status_id])) {
                    $workflow->transitions()->create([
                        'name' => $transition->name,
                        'from_status_id' => $statusMap[$transition->from_status_id],
                        'to_status_id' => $statusMap[$transition->to_status_id],
                        'description' => $transition->description,
                        'conditions' => $transition->conditions,
                        'actions' => $transition->actions,
                        'display_order' => $transition->display_order,
                        'metadata' => $transition->metadata,
                    ]);
                }
            }
        });

        return back()->with('success', 'Workflow imported successfully.');
    }
}
