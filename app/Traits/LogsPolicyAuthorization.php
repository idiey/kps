<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

/**
 * Trait for logging policy authorization decisions.
 * 
 * Provides configurable audit logging for authorization checks in policies.
 * Logs can be enabled/disabled via configuration and include user, action, resource, and authorization result.
 */
trait LogsPolicyAuthorization
{
    /**
     * Log an authorization decision.
     *
     * @param string $action The action being authorized (e.g., 'create', 'update', 'approve')
     * @param mixed $user The user attempting the action
     * @param string $resource The resource type being accessed (e.g., 'KewPA10', 'InspectionReport')
     * @param bool $authorized Whether authorization was granted
     * @param mixed|null $model The specific model instance (if applicable)
     * @param array $context Additional context information
     * @return void
     */
    protected function logAuthorization(
        string $action,
        $user,
        string $resource,
        bool $authorized,
        $model = null,
        array $context = []
    ): void {
        // Check if audit logging is enabled
        if (!config('auth.audit_policy_checks', false)) {
            return;
        }

        $logData = [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role->value ?? null,
            'action' => $action,
            'resource' => $resource,
            'resource_id' => $model?->id ?? null,
            'authorized' => $authorized,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
            'context' => $context,
        ];

        // Log to the policy channel
        if ($authorized) {
            Log::channel('policy')->info("Authorization GRANTED: {$user->email} can {$action} {$resource}", $logData);
        } else {
            Log::channel('policy')->warning("Authorization DENIED: {$user->email} cannot {$action} {$resource}", $logData);
        }

        // If activity log package is available, store to database
        if (class_exists('\Spatie\Activitylog\Models\Activity')) {
            activity()
                ->causedBy($user)
                ->performedOn($model)
                ->withProperties([
                    'action' => $action,
                    'resource' => $resource,
                    'authorized' => $authorized,
                    ...$context
                ])
                ->log($authorized ? 'policy_authorized' : 'policy_denied');
        }
    }

    /**
     * Log and return authorization result.
     * Convenience method that logs and returns the result in one call.
     *
     * @param string $action The action being authorized
     * @param mixed $user The user attempting the action
     * @param string $resource The resource type
     * @param bool $authorized The authorization result
     * @param mixed|null $model The model instance
     * @param array $context Additional context
     * @return bool The authorization result
     */
    protected function authorize(
        string $action,
        $user,
        string $resource,
        bool $authorized,
        $model = null,
        array $context = []
    ): bool {
        $this->logAuthorization($action, $user, $resource, $authorized, $model, $context);
        return $authorized;
    }
}
