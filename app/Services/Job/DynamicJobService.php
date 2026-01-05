<?php

namespace App\Services\Job;

use App\Models\Template\JobTemplate;
use App\Models\Workflow\Workflow;
use App\Models\WorkshopJob;
use App\Services\Template\TemplateRenderService;
use App\Services\Workflow\WorkflowExecutor;
use Illuminate\Support\Facades\DB;

class DynamicJobService
{
    public function __construct(
        protected TemplateRenderService $templateRenderService,
        protected WorkflowExecutor $workflowExecutor
    ) {}

    /**
     * Create a new job with dynamic template and workflow.
     *
     * @param array $data
     * @return WorkshopJob
     */
    public function createJob(array $data): WorkshopJob
    {
        return DB::transaction(function () use ($data) {
            $templateId = $data['template_id'] ?? null;
            $workflowId = $data['workflow_id'] ?? null;

            if (!$templateId || !$workflowId) {
                throw new \InvalidArgumentException('template_id and workflow_id are required');
            }

            $template = JobTemplate::findOrFail($templateId);
            $workflow = Workflow::findOrFail($workflowId);

            // Validate form data against template
            $formData = $data['field_data'] ?? [];
            $errors = $this->templateRenderService->validateFormData($template, $formData);

            if (!empty($errors)) {
                throw new \InvalidArgumentException('Validation failed: ' . json_encode($errors));
            }

            // Get initial workflow status
            $initialStatus = $workflow->initialStatus();

            if (!$initialStatus) {
                throw new \InvalidArgumentException('Workflow must have an initial status');
            }

            // Create the job
            $job = WorkshopJob::create([
                'template_id' => $template->id,
                'workflow_id' => $workflow->id,
                'current_workflow_status_id' => $initialStatus->id,
                'customer_id' => $data['customer_id'] ?? null,
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? '',
                'priority' => $data['priority'] ?? 'medium',
                'assigned_to' => $data['assigned_to'] ?? null,
                'due_date' => $data['due_date'] ?? null,
            ]);

            // Save dynamic field values
            $this->templateRenderService->saveFormData($job, $formData);

            // Create initial status history
            $job->statusHistories()->create([
                'user_id' => auth()->id(),
                'from_status' => null,
                'to_status' => $initialStatus->code,
                'workflow_status_id' => $initialStatus->id,
                'changed_at' => now(),
            ]);

            return $job->load(['template', 'workflow', 'currentWorkflowStatus', 'customer']);
        });
    }

    /**
     * Update a job with dynamic fields.
     *
     * @param WorkshopJob $job
     * @param array $data
     * @return WorkshopJob
     */
    public function updateJob(WorkshopJob $job, array $data): WorkshopJob
    {
        return DB::transaction(function () use ($job, $data) {
            // Update basic job fields
            $job->update([
                'title' => $data['title'] ?? $job->title,
                'description' => $data['description'] ?? $job->description,
                'priority' => $data['priority'] ?? $job->priority,
                'assigned_to' => $data['assigned_to'] ?? $job->assigned_to,
                'due_date' => $data['due_date'] ?? $job->due_date,
            ]);

            // Update dynamic field values if provided
            if (isset($data['field_data']) && $job->template) {
                $formData = $data['field_data'];

                // Validate
                $errors = $this->templateRenderService->validateFormData($job->template, $formData);

                if (!empty($errors)) {
                    throw new \InvalidArgumentException('Validation failed: ' . json_encode($errors));
                }

                // Save
                $this->templateRenderService->saveFormData($job, $formData);
            }

            return $job->fresh(['template', 'workflow', 'currentWorkflowStatus', 'customer']);
        });
    }

    /**
     * Execute a workflow transition.
     *
     * @param WorkshopJob $job
     * @param int $transitionId
     * @param array $data
     * @return WorkshopJob
     */
    public function executeTransition(WorkshopJob $job, int $transitionId, array $data = []): WorkshopJob
    {
        if (!$job->usesDynamicWorkflow()) {
            throw new \InvalidArgumentException('Job does not use dynamic workflow');
        }

        $transition = $job->workflow->transitions()->findOrFail($transitionId);

        $this->workflowExecutor->executeTransition($job, $transition, $data);

        return $job->fresh(['currentWorkflowStatus', 'workflow']);
    }

    /**
     * Get available transitions for a job.
     */
    public function getAvailableTransitions(WorkshopJob $job)
    {
        return $this->workflowExecutor->getAvailableTransitions($job);
    }

    /**
     * Get job with all dynamic data for display.
     *
     * @param WorkshopJob $job
     * @return array
     */
    public function getJobWithDynamicData(WorkshopJob $job): array
    {
        $job->load(['template.fields.fieldType', 'workflow', 'currentWorkflowStatus', 'customer', 'fieldValues']);

        $data = [
            'id' => $job->id,
            'job_number' => $job->job_number,
            'title' => $job->title,
            'description' => $job->description,
            'priority' => $job->priority,
            'customer' => $job->customer,
            'assigned_to' => $job->assigned_to,
            'due_date' => $job->due_date,
            'created_at' => $job->created_at,
            'updated_at' => $job->updated_at,
        ];

        // Add template info
        if ($job->template) {
            $data['template'] = [
                'id' => $job->template->id,
                'name' => $job->template->name,
                'code' => $job->template->code,
            ];
        }

        // Add workflow info
        if ($job->workflow && $job->currentWorkflowStatus) {
            $data['workflow'] = [
                'id' => $job->workflow->id,
                'name' => $job->workflow->name,
                'current_status' => [
                    'id' => $job->currentWorkflowStatus->id,
                    'name' => $job->currentWorkflowStatus->name,
                    'code' => $job->currentWorkflowStatus->code,
                    'color' => $job->currentWorkflowStatus->color,
                    'icon' => $job->currentWorkflowStatus->icon,
                ],
            ];

            // Add available transitions
            $data['available_transitions'] = $this->getAvailableTransitions($job)->map(function ($transition) {
                return [
                    'id' => $transition->id,
                    'name' => $transition->name,
                    'button_label' => $transition->button_label,
                    'button_color' => $transition->button_color,
                    'to_status' => [
                        'name' => $transition->toStatus->name,
                        'color' => $transition->toStatus->color,
                    ],
                    'requires_comment' => $transition->requires_comment,
                    'confirmation_message' => $transition->confirmation_message,
                ];
            })->toArray();
        }

        // Add dynamic field values
        if ($job->template) {
            $fieldValues = $job->getAllFieldValues();
            $data['field_values'] = $fieldValues;

            // Organize by section
            $fieldsBySection = $job->template->fieldsBySection();
            $data['fields_by_section'] = $fieldsBySection->map(function ($fields) use ($fieldValues) {
                return $fields->map(function ($field) use ($fieldValues) {
                    return [
                        'code' => $field->code,
                        'name' => $field->name,
                        'type' => $field->fieldType->code,
                        'value' => $fieldValues[$field->code] ?? null,
                        'help_text' => $field->help_text,
                    ];
                })->toArray();
            })->toArray();
        }

        return $data;
    }
}
