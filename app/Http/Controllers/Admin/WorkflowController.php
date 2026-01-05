<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Workflow;
use Illuminate\Http\Request;
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
    public function builder(Workflow $workflow)
    {
        $workflow->load(['statuses', 'transitions.fromStatus', 'transitions.toStatus']);

        return Inertia::render('Admin/Workflows/Builder', [
            'workflow' => $workflow,
        ]);
    }
}
