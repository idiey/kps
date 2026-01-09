<?php

namespace App\Http\Controllers;

use App\Models\Template\JobTemplate;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowTransition;
use App\Models\WorkshopJob;
use App\Services\Job\DynamicJobService;
use App\Services\Template\TemplateRenderService;
use App\Services\Workflow\WorkflowExecutor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DynamicJobController extends Controller
{
    public function __construct(
        protected DynamicJobService $jobService,
        protected TemplateRenderService $templateService,
        protected WorkflowExecutor $workflowExecutor
    ) {
        $this->middleware('auth');
    }

    /**
     * Show job creation form with template and workflow selection.
     */
    public function create(Request $request, ?JobTemplate $template = null)
    {
        // If no template specified, show template selector
        if (!$template) {
            $templates = JobTemplate::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'description', 'icon', 'color']);

            return Inertia::render('Jobs/SelectTemplate', [
                'templates' => $templates,
            ]);
        }

        // Get available workflows for this template
        $workflowsData = $this->templateService->getTemplateWithWorkflows($template);

        // If only one workflow, auto-select it
        $selectedWorkflow = null;
        if (count($workflowsData['workflows']) === 1) {
            $selectedWorkflow = $workflowsData['workflows'][0];
        } else {
            // Find default workflow
            $defaultWorkflow = collect($workflowsData['workflows'])->firstWhere('is_default', true);
            if ($defaultWorkflow) {
                $selectedWorkflow = $defaultWorkflow;
            }
        }

        // Generate form schema
        $formSchema = $this->templateService->generateFormSchema($template);

        return Inertia::render('Jobs/Create', [
            'template' => $workflowsData['template'],
            'workflows' => $workflowsData['workflows'],
            'selectedWorkflow' => $selectedWorkflow,
            'formSchema' => $formSchema,
        ]);
    }

    /**
     * Store a newly created job with dynamic fields.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:job_templates,id',
            'workflow_id' => 'required|exists:workflows,id',
            'field_data' => 'required|array',
        ]);

        // Verify workflow is associated with template
        $template = JobTemplate::findOrFail($validated['template_id']);
        $workflowExists = $template->workflows()
            ->where('workflows.id', $validated['workflow_id'])
            ->exists();

        if (!$workflowExists) {
            return back()->withErrors([
                'workflow_id' => 'Selected workflow is not available for this template.',
            ]);
        }

        try {
            $job = $this->jobService->createJob([
                'template_id' => $validated['template_id'],
                'workflow_id' => $validated['workflow_id'],
                'field_data' => $validated['field_data'],
            ]);

            return redirect()->route('jobs.show', $job)
                ->with('success', 'Job created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Failed to create job: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Display the specified job with dynamic fields.
     */
    public function show(WorkshopJob $job)
    {
        $jobData = $this->jobService->getJobWithDynamicData($job);

        // Get available transitions
        $availableTransitions = $this->workflowExecutor->getAvailableTransitions($job);

        return Inertia::render('Jobs/Show', [
            'job' => $jobData,
            'availableTransitions' => $availableTransitions,
        ]);
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(WorkshopJob $job)
    {
        // Check if user can update this job
        $this->authorize('update', $job);

        $jobData = $this->jobService->getJobWithDynamicData($job);

        // Generate form schema with current values
        $formSchema = $this->templateService->generateFormSchema($job->template, $job);

        return Inertia::render('Jobs/Edit', [
            'job' => $jobData,
            'formSchema' => $formSchema,
        ]);
    }

    /**
     * Update the specified job.
     */
    public function update(Request $request, WorkshopJob $job)
    {
        // Check if user can update this job
        $this->authorize('update', $job);

        $validated = $request->validate([
            'field_data' => 'required|array',
        ]);

        try {
            $updatedJob = $this->jobService->updateJob($job, $validated['field_data']);

            return redirect()->route('jobs.show', $updatedJob)
                ->with('success', 'Job updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Failed to update job: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Execute a workflow transition.
     */
    public function executeTransition(
        Request $request,
        WorkshopJob $job,
        WorkflowTransition $transition
    ) {
        // Check if user can update this job
        $this->authorize('update', $job);

        $validated = $request->validate([
            'notes' => 'nullable|string',
            'field_data' => 'nullable|array',
            'metadata' => 'nullable|array',
        ]);

        try {
            $updatedJob = $this->jobService->executeTransition(
                $job,
                $transition->id,
                [
                    'notes' => $validated['notes'] ?? null,
                    'field_data' => $validated['field_data'] ?? [],
                    'metadata' => $validated['metadata'] ?? [],
                ]
            );

            return redirect()->route('jobs.show', $updatedJob)
                ->with('success', 'Workflow transition executed successfully.');
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Failed to execute transition: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get available transitions for a job (API endpoint).
     */
    public function getAvailableTransitions(WorkshopJob $job)
    {
        $transitions = $this->workflowExecutor->getAvailableTransitions($job);
        $currentStatus = $job->currentWorkflowStatus;

        return response()->json([
            'transitions' => $transitions,
            'current_status' => $currentStatus ? [
                'id' => $currentStatus->id,
                'name' => $currentStatus->name,
                'code' => $currentStatus->code,
                'required_template_id' => $currentStatus->required_template_id,
                'has_required_form' => $currentStatus->hasRequiredForm(),
            ] : null,
        ]);
    }

    /**
     * Get field visibility rules for current status (API endpoint).
     */
    public function getFieldRules(WorkshopJob $job)
    {
        if (!$job->usesDynamicWorkflow()) {
            return response()->json([
                'visibilityRules' => [],
                'requirementRules' => [],
            ]);
        }

        $ruleEngine = app(\App\Services\Workflow\WorkflowRuleEngine::class);

        return response()->json([
            'visibilityRules' => $ruleEngine->getFieldVisibilityRules($job),
            'requirementRules' => $ruleEngine->getFieldRequirementRules($job),
        ]);
    }

    /**
     * Validate field data against template (API endpoint).
     */
    public function validateFieldData(Request $request, JobTemplate $template)
    {
        $validated = $request->validate([
            'field_data' => 'required|array',
        ]);

        $errors = $this->templateService->validateFormData(
            $template,
            $validated['field_data']
        );

        return response()->json([
            'valid' => empty($errors),
            'errors' => $errors,
        ]);
    }

    /**
     * Get form schema for a template (API endpoint).
     */
    public function getFormSchema(JobTemplate $template, ?WorkshopJob $job = null)
    {
        $schema = $this->templateService->generateFormSchema($template, $job);

        return response()->json($schema);
    }

    /**
     * Get available workflows for a template (API endpoint).
     */
    public function getWorkflows(JobTemplate $template)
    {
        $data = $this->templateService->getTemplateWithWorkflows($template);

        return response()->json($data['workflows']);
    }
}
