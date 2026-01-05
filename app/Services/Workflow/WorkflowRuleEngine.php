<?php

namespace App\Services\Workflow;

use App\Models\Workflow\WorkflowRule;
use App\Models\Workflow\WorkflowTransition;
use App\Models\WorkshopJob;

class WorkflowRuleEngine
{
    /**
     * Execute workflow rules.
     *
     * @param WorkshopJob $job
     * @param WorkflowTransition $transition
     * @param string $timing 'before' or 'after' transition
     */
    public function executeRules(WorkshopJob $job, WorkflowTransition $transition, string $timing): void
    {
        $statusId = $timing === 'before' ? $transition->from_status_id : $transition->to_status_id;

        $rules = WorkflowRule::where('workflow_id', $job->workflow_id)
            ->where(function ($query) use ($statusId) {
                $query->where('status_id', $statusId)
                    ->orWhereNull('status_id');
            })
            ->where('is_active', true)
            ->orderBy('priority')
            ->get();

        foreach ($rules as $rule) {
            if ($rule->shouldApply($job)) {
                $this->executeRule($job, $rule);
            }
        }
    }

    /**
     * Execute a single rule.
     */
    protected function executeRule(WorkshopJob $job, WorkflowRule $rule): void
    {
        match ($rule->rule_type) {
            'field_required' => $this->executeFieldRequiredRule($job, $rule),
            'field_visible' => $this->executeFieldVisibleRule($job, $rule),
            'auto_assign' => $this->executeAutoAssignRule($job, $rule),
            'notification' => $this->executeNotificationRule($job, $rule),
            'validation' => $this->executeValidationRule($job, $rule),
            default => null,
        };
    }

    /**
     * Execute field required rule.
     */
    protected function executeFieldRequiredRule(WorkshopJob $job, WorkflowRule $rule): void
    {
        $actions = $rule->actions ?? [];

        foreach ($actions as $action) {
            $fieldCode = $action['field_code'] ?? null;

            if ($fieldCode) {
                $value = $job->getFieldValue($fieldCode);

                if (empty($value)) {
                    throw new \InvalidArgumentException(
                        "Field '{$fieldCode}' is required for this transition"
                    );
                }
            }
        }
    }

    /**
     * Execute field visible rule.
     */
    protected function executeFieldVisibleRule(WorkshopJob $job, WorkflowRule $rule): void
    {
        // This is handled in the frontend based on workflow rules
        // No backend action needed
    }

    /**
     * Execute auto assign rule.
     */
    protected function executeAutoAssignRule(WorkshopJob $job, WorkflowRule $rule): void
    {
        $actions = $rule->actions ?? [];

        $assignTo = $actions['assign_to'] ?? null;
        $assignmentStrategy = $actions['strategy'] ?? 'fixed';

        if ($assignmentStrategy === 'fixed' && $assignTo) {
            $job->assigned_to = $assignTo;
            $job->save();
        } elseif ($assignmentStrategy === 'role') {
            // Assign to user with specific role
            // TODO: Implement role-based assignment
        } elseif ($assignmentStrategy === 'round_robin') {
            // TODO: Implement round-robin assignment
        }
    }

    /**
     * Execute notification rule.
     */
    protected function executeNotificationRule(WorkshopJob $job, WorkflowRule $rule): void
    {
        // TODO: Implement notification sending
    }

    /**
     * Execute validation rule.
     */
    protected function executeValidationRule(WorkshopJob $job, WorkflowRule $rule): void
    {
        $actions = $rule->actions ?? [];
        $validations = $actions['validations'] ?? [];

        foreach ($validations as $validation) {
            $fieldCode = $validation['field_code'] ?? null;
            $operator = $validation['operator'] ?? '=';
            $expectedValue = $validation['value'] ?? null;

            if ($fieldCode) {
                $actualValue = $job->getFieldValue($fieldCode);

                if (!$this->compareValues($actualValue, $operator, $expectedValue)) {
                    $message = $validation['error_message'] ?? "Validation failed for field '{$fieldCode}'";
                    throw new \InvalidArgumentException($message);
                }
            }
        }
    }

    /**
     * Compare values using operator.
     */
    protected function compareValues($actual, string $operator, $expected): bool
    {
        return match ($operator) {
            '=' => $actual == $expected,
            '!=' => $actual != $expected,
            '>' => $actual > $expected,
            '>=' => $actual >= $expected,
            '<' => $actual < $expected,
            '<=' => $actual <= $expected,
            'contains' => str_contains((string)$actual, (string)$expected),
            'not_contains' => !str_contains((string)$actual, (string)$expected),
            'in' => in_array($actual, (array)$expected),
            'not_in' => !in_array($actual, (array)$expected),
            default => false,
        };
    }

    /**
     * Get field visibility rules for a status.
     */
    public function getFieldVisibilityRules(WorkshopJob $job): array
    {
        if (!$job->usesDynamicWorkflow()) {
            return [];
        }

        $rules = WorkflowRule::where('workflow_id', $job->workflow_id)
            ->where('rule_type', 'field_visible')
            ->where(function ($query) use ($job) {
                $query->where('status_id', $job->current_workflow_status_id)
                    ->orWhereNull('status_id');
            })
            ->where('is_active', true)
            ->get();

        $visibilityRules = [];

        foreach ($rules as $rule) {
            $actions = $rule->actions ?? [];

            foreach ($actions as $action) {
                $fieldCode = $action['field_code'] ?? null;
                $visible = $action['visible'] ?? true;

                if ($fieldCode) {
                    $visibilityRules[$fieldCode] = $visible;
                }
            }
        }

        return $visibilityRules;
    }

    /**
     * Get field requirement rules for a status.
     */
    public function getFieldRequirementRules(WorkshopJob $job): array
    {
        if (!$job->usesDynamicWorkflow()) {
            return [];
        }

        $rules = WorkflowRule::where('workflow_id', $job->workflow_id)
            ->where('rule_type', 'field_required')
            ->where(function ($query) use ($job) {
                $query->where('status_id', $job->current_workflow_status_id)
                    ->orWhereNull('status_id');
            })
            ->where('is_active', true)
            ->get();

        $requiredFields = [];

        foreach ($rules as $rule) {
            $actions = $rule->actions ?? [];

            foreach ($actions as $action) {
                $fieldCode = $action['field_code'] ?? null;

                if ($fieldCode) {
                    $requiredFields[] = $fieldCode;
                }
            }
        }

        return array_unique($requiredFields);
    }
}
