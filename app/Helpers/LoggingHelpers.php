<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

/**
 * Helper class for consistent logging across workshop job operations.
 */
class LoggingHelpers
{
    /**
     * Build standard context for job-related logs.
     *
     * @param mixed $job
     * @param array $additional
     * @return array
     */
    public static function jobContext($job, array $additional = []): array
    {
        $context = [
            'timestamp' => now()->toIso8601String(),
        ];

        // Add user context if authenticated
        if (auth()->check()) {
            $context['user_id'] = auth()->id();
            $context['user_name'] = auth()->user()->name;
            $context['user_email'] = auth()->user()->email;
        }

        // Add job context if job is provided
        if ($job) {
            $context['job_id'] = $job->id ?? null;
            $context['job_number'] = $job->job_number ?? null;
            $context['workflow_id'] = $job->workflow_id ?? null;
            $context['template_id'] = $job->template_id ?? null;
            $context['status'] = $job->status?->value ?? null;
            $context['customer_id'] = $job->customer_id ?? null;
        }

        return array_merge($context, $additional);
    }

    /**
     * Log a job operation with consistent formatting.
     *
     * @param string $operation
     * @param mixed $job
     * @param array $context
     * @param string $level
     * @return void
     */
    public static function logJobOperation(
        string $operation,
        $job,
        array $context = [],
        string $level = 'info'
    ): void {
        Log::channel('workshop-jobs')->{$level}(
            "Job {$operation}",
            self::jobContext($job, $context)
        );
    }

    /**
     * Log workflow transition.
     *
     * @param mixed $job
     * @param string $fromStatus
     * @param string $toStatus
     * @param array $additional
     * @return void
     */
    public static function logWorkflowTransition(
        $job,
        string $fromStatus,
        string $toStatus,
        array $additional = []
    ): void {
        Log::channel('workshop-jobs')->info(
            'Workflow Transition',
            self::jobContext($job, array_merge([
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
            ], $additional))
        );
    }

    /**
     * Log field value change.
     *
     * @param mixed $job
     * @param string $fieldCode
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return void
     */
    public static function logFieldChange(
        $job,
        string $fieldCode,
        $oldValue,
        $newValue
    ): void {
        Log::channel('workshop-jobs')->debug(
            'Field Value Changed',
            self::jobContext($job, [
                'field_code' => $fieldCode,
                'old_value' => $oldValue,
                'new_value' => $newValue,
            ])
        );
    }

    /**
     * Log job assignment.
     *
     * @param mixed $job
     * @param int $assignedToId
     * @param string $assignedToName
     * @return void
     */
    public static function logAssignment(
        $job,
        int $assignedToId,
        string $assignedToName
    ): void {
        Log::channel('workshop-jobs')->info(
            'Job Assigned',
            self::jobContext($job, [
                'assigned_to_id' => $assignedToId,
                'assigned_to_name' => $assignedToName,
            ])
        );
    }
}
