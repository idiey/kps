<?php

namespace App\Services\Workflow;

use App\Models\Workflow\WorkflowTransition;
use App\Models\WorkshopJob;
use Illuminate\Support\Facades\DB;

class WorkflowExecutor
{
    public function __construct(
        protected WorkflowRuleEngine $ruleEngine
    ) {}

    /**
     * Execute a workflow transition.
     *
     * @param WorkshopJob $job
     * @param WorkflowTransition $transition
     * @param array $data Additional data (notes, etc.)
     * @return bool
     * @throws \Exception
     */
    public function executeTransition(
        WorkshopJob $job,
        WorkflowTransition $transition,
        array $data = []
    ): bool {
        return DB::transaction(function () use ($job, $transition, $data) {
            // 1. Validate transition is allowed
            if (!$this->validateTransition($job, $transition)) {
                throw new \InvalidArgumentException(
                    "Invalid transition from {$job->currentWorkflowStatus->name} to {$transition->toStatus->name}"
                );
            }

            // 2. Check user permissions
            if (!$this->checkUserPermissions($transition)) {
                throw new \UnauthorizedHttpException(
                    'You do not have permission to execute this transition'
                );
            }

            // 3. Evaluate transition conditions
            if (!$this->evaluateConditions($job, $transition, $data)) {
                throw new \InvalidArgumentException(
                    'Transition conditions not met'
                );
            }

            // 4. Execute pre-transition rules
            $this->ruleEngine->executeRules($job, $transition, 'before');

            $oldStatus = $job->currentWorkflowStatus;

            // 5. Update job status
            $job->current_workflow_status_id = $transition->to_status_id;
            $job->save();

            // 6. Record transition in history
            $this->recordTransition($job, $transition, $oldStatus, $data);

            // 7. Execute transition actions
            $this->executeTransitionActions($job, $transition, $data);

            // 8. Execute post-transition rules
            $this->ruleEngine->executeRules($job, $transition, 'after');

            // 9. Send notifications (if configured)
            // TODO: Implement notification system

            return true;
        });
    }

    /**
     * Validate that the transition is allowed.
     */
    protected function validateTransition(WorkshopJob $job, WorkflowTransition $transition): bool
    {
        // Check if job uses dynamic workflow
        if (!$job->usesDynamicWorkflow()) {
            return false;
        }

        // Check if workflows match
        if ($job->workflow_id !== $transition->workflow_id) {
            return false;
        }

        // Check if from_status matches current status
        if ($job->current_workflow_status_id !== $transition->from_status_id) {
            return false;
        }

        // Check if transition is active
        if (!$transition->is_active) {
            return false;
        }

        return true;
    }

    /**
     * Check if current user has permission to execute transition.
     */
    protected function checkUserPermissions(WorkflowTransition $transition): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return $transition->userCanExecute($user);
    }

    /**
     * Evaluate transition conditions.
     */
    protected function evaluateConditions(
        WorkshopJob $job,
        WorkflowTransition $transition,
        array $data
    ): bool {
        // Check if comment is required
        if ($transition->requires_comment && empty($data['notes'])) {
            return false;
        }

        // Evaluate custom conditions
        return $transition->evaluateConditions($job, $data);
    }

    /**
     * Record the transition in job status history.
     */
    protected function recordTransition(
        WorkshopJob $job,
        WorkflowTransition $transition,
        $oldStatus,
        array $data
    ): void {
        $job->statusHistories()->create([
            'user_id' => auth()->id(),
            'from_status' => $oldStatus->code ?? null,
            'to_status' => $transition->toStatus->code,
            'workflow_status_id' => $transition->to_status_id,
            'transition_id' => $transition->id,
            'notes' => $data['notes'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'changed_at' => now(),
        ]);
    }

    /**
     * Execute actions defined in the transition.
     */
    protected function executeTransitionActions(
        WorkshopJob $job,
        WorkflowTransition $transition,
        array $data
    ): void {
        if (!$transition->actions || count($transition->actions) === 0) {
            return;
        }

        foreach ($transition->actions as $action) {
            $this->executeAction($job, $action, $data);
        }
    }

    /**
     * Execute a single action.
     */
    protected function executeAction(WorkshopJob $job, array $action, array $data): void
    {
        $type = $action['type'] ?? null;

        if (!$type) {
            return;
        }

        match ($type) {
            'update_field' => $this->executeUpdateFieldAction($job, $action),
            'send_notification' => $this->executeSendNotificationAction($job, $action),
            'auto_assign' => $this->executeAutoAssignAction($job, $action),
            'create_task' => $this->executeCreateTaskAction($job, $action),
            default => null,
        };
    }

    /**
     * Execute update field action.
     */
    protected function executeUpdateFieldAction(WorkshopJob $job, array $action): void
    {
        $fieldCode = $action['field_code'] ?? null;
        $value = $action['value'] ?? null;

        if ($fieldCode && $value !== null) {
            try {
                $job->setFieldValue($fieldCode, $value);
            } catch (\Exception $e) {
                // Log error but don't fail the transition
                \Log::warning("Failed to update field {$fieldCode}: {$e->getMessage()}");
            }
        }
    }

    /**
     * Execute send notification action.
     */
    protected function executeSendNotificationAction(WorkshopJob $job, array $action): void
    {
        // TODO: Implement notification sending
    }

    /**
     * Execute auto assign action.
     */
    protected function executeAutoAssignAction(WorkshopJob $job, array $action): void
    {
        // TODO: Implement auto-assignment logic
    }

    /**
     * Execute create task action.
     */
    protected function executeCreateTaskAction(WorkshopJob $job, array $action): void
    {
        // TODO: Implement task creation
    }

    /**
     * Get available transitions for a job.
     */
    public function getAvailableTransitions(WorkshopJob $job)
    {
        if (!$job->usesDynamicWorkflow() || !$job->currentWorkflowStatus) {
            return collect();
        }

        $transitions = $job->workflow->getAvailableTransitionsFrom($job->currentWorkflowStatus);

        // Filter by user permissions
        if (auth()->check()) {
            $transitions = $transitions->filter(function ($transition) {
                return $transition->userCanExecute(auth()->user());
            });
        }

        return $transitions;
    }
}
