<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template\JobTemplate;
use App\Services\Template\TemplateRenderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemplateController extends Controller
{
    public function __construct(
        protected TemplateRenderService $templateRenderService
    ) {}

    /**
     * Display a listing of templates.
     */
    public function index()
    {
        $templates = JobTemplate::with(['defaultWorkflow', 'workflows'])
            ->withCount('jobs')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Templates/Index', [
            'templates' => $templates,
        ]);
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        return Inertia::render('Admin/Templates/Create');
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:job_templates,code',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();

        $template = JobTemplate::create($validated);

        return redirect()->route('admin.templates.edit', $template)
            ->with('success', 'Template created successfully. Now add fields to your template.');
    }

    /**
     * Display the specified template.
     */
    public function show(JobTemplate $template)
    {
        $template->load(['fields.fieldType', 'workflows', 'defaultWorkflow']);

        return Inertia::render('Admin/Templates/Show', [
            'template' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(JobTemplate $template)
    {
        $template->load(['fields.fieldType', 'workflows']);

        return Inertia::render('Admin/Templates/Edit', [
            'template' => $template,
        ]);
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, JobTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:job_templates,code,' . $template->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'default_workflow_id' => 'nullable|exists:workflows,id',
        ]);

        $validated['updated_by'] = auth()->id();

        $template->update($validated);

        return back()->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified template.
     */
    public function destroy(JobTemplate $template)
    {
        // Check if template is in use
        if ($template->jobs()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete template that is in use by jobs.']);
        }

        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template deleted successfully.');
    }

    /**
     * Get workflows associated with a template (for workflow selection).
     */
    public function getWorkflows(JobTemplate $template)
    {
        $data = $this->templateRenderService->getTemplateWithWorkflows($template);

        return response()->json($data);
    }

    /**
     * Get form schema for a template.
     */
    public function getSchema(JobTemplate $template)
    {
        $schema = $this->templateRenderService->generateFormSchema($template);

        return response()->json($schema);
    }

    /**
     * Associate a workflow with a template.
     */
    public function attachWorkflow(Request $request, JobTemplate $template)
    {
        $validated = $request->validate([
            'workflow_id' => 'required|exists:workflows,id',
            'is_default' => 'boolean',
        ]);

        $template->workflows()->attach($validated['workflow_id'], [
            'is_default' => $validated['is_default'] ?? false,
        ]);

        return back()->with('success', 'Workflow attached to template.');
    }

    /**
     * Remove a workflow from a template.
     */
    public function detachWorkflow(JobTemplate $template, $workflowId)
    {
        $template->workflows()->detach($workflowId);

        return back()->with('success', 'Workflow removed from template.');
    }
}
