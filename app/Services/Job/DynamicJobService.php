<?php

namespace App\Services\Job;

use App\Helpers\LoggingHelpers;
use App\Models\Template\JobTemplate;
use App\Models\Workflow\Workflow;
use App\Models\WorkshopJob;
use App\Services\Template\TemplateRenderService;
use App\Services\Workflow\WorkflowExecutor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::channel('workshop-jobs')->info('Dynamic Job Creation Attempt', [
            'user_id' => auth()->id(),
            'template_id' => $data['template_id'] ?? null,
            'workflow_id' => $data['workflow_id'] ?? null,
        ]);

        try {
            return DB::transaction(function () use ($data) {
                $templateId = $data['template_id'] ?? null;
                $workflowId = $data['workflow_id'] ?? null;

                if (!$templateId || !$workflowId) {
                    throw new \InvalidArgumentException('template_id and workflow_id are required');
                }

                $template = JobTemplate::findOrFail($templateId);
                $workflow = Workflow::findOrFail($workflowId);

                Log::channel('workshop-jobs')->debug('Template and Workflow Loaded', [
                    'template_name' => $template->name,
                    'workflow_name' => $workflow->name,
                ]);

                // Validate form data against template
                $formData = $data['field_data'] ?? [];
                $errors = $this->templateRenderService->validateFormData($template, $formData);

                if (!empty($errors)) {
                    Log::channel('workshop-jobs')->warning('Form Data Validation Failed', [
                        'template_id' => $templateId,
                        'errors' => $errors,
                    ]);
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

                Log::channel('workshop-jobs')->debug('Dynamic Fields Saved', [
                    'job_id' => $job->id,
                    'job_number' => $job->job_number,
                    'field_count' => count($formData),
                ]);

                // Create initial status history
                $job->statusHistories()->create([
                    'user_id' => auth()->id(),
                    'from_status' => null,
                    'to_status' => $initialStatus->code,
                    'workflow_status_id' => $initialStatus->id,
                    'changed_at' => now(),
                ]);

                LoggingHelpers::logJobOperation('Created (Dynamic)', $job, [
                    'template_name' => $template->name,
                    'workflow_name' => $workflow->name,
                    'initial_status' => $initialStatus->code,
                ]);

                return $job->load(['template', 'workflow', 'currentWorkflowStatus', 'customer']);
            });
        } catch (\Exception $e) {
            Log::channel('workshop-jobs')->error('Dynamic Job Creation Failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'template_id' => $data['template_id'] ?? null,
                'workflow_id' => $data['workflow_id'] ?? null,
            ]);
            throw $e;
        }
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

        Log::channel('workshop-jobs')->info('Workflow Transition Attempt', [
            'job_id' => $job->id,
            'job_number' => $job->job_number,
            'transition_id' => $transition->id,
            'transition_name' => $transition->name,
            'from_status' => $job->currentWorkflowStatus->code,
            'to_status' => $transition->toStatus->code,
        ]);

        try {
            // ---------------------------------------------------------------------
            // Enforce Required Form Template for Current Status
            // ---------------------------------------------------------------------
            $currentStatus = $job->currentWorkflowStatus;
            if ($currentStatus && $currentStatus->required_template_id) {
                $requiredTemplate = $currentStatus->requiredTemplate;
                
                // Get all existing values
                $existingValues = $job->getAllFieldValues();
                
                // Merge with incoming new data (if any)
                $incomingData = $data['field_data'] ?? [];
                $mergedData = array_merge($existingValues, $incomingData);

                // Validate strict rules (all required fields must be present)
                $errors = $this->templateRenderService->validateFormData($requiredTemplate, $mergedData);

                if (!empty($errors)) {
                    Log::channel('workshop-jobs')->warning('Required Form Validation Failed', [
                        'job_id' => $job->id,
                        'job_number' => $job->job_number,
                        'required_template' => $requiredTemplate->name,
                        'errors' => $errors,
                    ]);
                    // If data was provided in this request, maybe we should save it first?
                    // For now, fail hard to enforce completeness.
                    throw new \InvalidArgumentException("Cannot transition. Required form '{$requiredTemplate->name}' is incomplete or invalid. " . implode(', ', $flattenErrors ?? []));
                }
                
                // If valid and we have new data, save it before transition
                if (!empty($incomingData)) {
                     $this->templateRenderService->saveFormData($job, $incomingData);
                     Log::channel('workshop-jobs')->debug('Form Data Saved Before Transition', [
                         'job_id' => $job->id,
                         'field_count' => count($incomingData),
                     ]);
                }
            }

            $this->workflowExecutor->executeTransition($job, $transition, $data);

            LoggingHelpers::logWorkflowTransition($job->fresh(), 
                $currentStatus->code,
                $transition->toStatus->code,
                [
                    'transition_name' => $transition->name,
                    'notes' => $data['notes'] ?? null,
                ]
            );

            return $job->fresh(['currentWorkflowStatus', 'workflow']);
        } catch (\Exception $e) {
            Log::channel('workshop-jobs')->error('Workflow Transition Failed', [
                'job_id' => $job->id,
                'job_number' => $job->job_number,
                'transition_id' => $transitionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
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

        // Add dynamic field values - Load from workflow statuses, not job.template
        if ($job->workflow) {
            $fieldValues = $job->getAllFieldValues();
            $data['field_values'] = $fieldValues;

            // Get all workflow statuses that have required templates
            $statusesWithTemplates = $job->workflow->statuses()
                ->whereNotNull('required_template_id')
                ->with(['requiredTemplate.fields.fieldType'])
                ->orderBy('display_order')
                ->get();

            // Build array of templates with their data
            $data['workflow_templates'] = $statusesWithTemplates->map(function ($status) use ($fieldValues, $job) {
                $template = $status->requiredTemplate;
                $fieldsBySection = $template->fieldsBySection();

                return [
                    'status_id' => $status->id,
                    'status_name' => $status->name,
                    'status_code' => $status->code,
                    'template_id' => $template->id,
                    'template_name' => $template->name,
                    'template_code' => $template->code,
                    'template_description' => $template->description,
                    'is_current_status' => $status->id === $job->current_workflow_status_id,
                    'fields_by_section' => $this->transformFields($fieldsBySection, $fieldValues),
                ];
            })->toArray();


            // Add workflow info
            if ($job->currentWorkflowStatus) {
                $data['workflow'] = [
                    'id' => $job->workflow->id,
                    'name' => $job->workflow->name,
                    'current_status' => [
                        'id' => $job->currentWorkflowStatus->id,
                        'name' => $job->currentWorkflowStatus->name,
                        'code' => $job->currentWorkflowStatus->code,
                        'color' => $job->currentWorkflowStatus->color,
                        'icon' => $job->currentWorkflowStatus->icon,
                        'required_template_id' => $job->currentWorkflowStatus->required_template_id,
                    ],
                    'statuses' => $job->workflow->statuses->map(function($status) {
                        return [
                            'id' => $status->id,
                            'name' => $status->name,
                            'code' => $status->code,
                            'color' => $status->color,
                        ];
                    })->toArray(),
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
        }

        // Keep active status form for editing (if at current status)
        if ($job->currentWorkflowStatus && $job->currentWorkflowStatus->required_template_id) {
             $requiredTemplate = $job->currentWorkflowStatus->requiredTemplate;
             if ($requiredTemplate) {
                 $requiredFieldValues = $job->getAllFieldValues();
                 $requiredFieldsBySection = $requiredTemplate->fieldsBySection();
                 
                 $data['active_status_form'] = [
                     'template_id' => $requiredTemplate->id,
                     'name' => $requiredTemplate->name,
                     'description' => $requiredTemplate->description,
                     'fields_by_section' => $this->transformFields($requiredFieldsBySection, $requiredFieldValues),
                 ];
             }
        }

        return $data;
    }

    protected function transformFields($fieldsBySection, $fieldValues)
    {
        return $fieldsBySection->map(function ($fields) use ($fieldValues) {
                return $fields->map(function ($field) use ($fieldValues) {
                    return [
                        'id' => $field->id,
                        'code' => $field->code,
                        'name' => $field->name,
                        'type' => $field->fieldType->code,
                        'value' => $fieldValues[$field->code] ?? null,
                        'help_text' => $field->help_text,
                        'options' => $field->options,
                        'is_required' => $field->is_required,
                        'validation_rules' => $field->validation_rules,
                    ];
                })->toArray();
            })->toArray();
    }

    /**
     * Save form data for the job's current workflow status template.
     * 
     * @param WorkshopJob $job
     * @param array $data
     * @return void
     */
    public function saveCurrentStatusFormData(WorkshopJob $job, array $data): void
    {
        if (!$job->usesDynamicWorkflow() || empty($data)) {
            return;
        }

        $currentStatus = $job->currentWorkflowStatus;
        if ($currentStatus && $currentStatus->requiredTemplate) {
            $this->templateRenderService->saveFormData($job, $data, $currentStatus->requiredTemplate);
        }
    }
}
