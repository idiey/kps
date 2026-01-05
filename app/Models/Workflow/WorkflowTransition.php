<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowTransition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'workflow_id',
        'from_status_id',
        'to_status_id',
        'name',
        'description',
        'requires_permission',
        'allowed_roles',
        'conditions',
        'actions',
        'button_label',
        'button_color',
        'confirmation_message',
        'requires_comment',
        'metadata',
        'is_active',
        'display_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'allowed_roles' => 'array',
            'conditions' => 'array',
            'actions' => 'array',
            'metadata' => 'array',
            'requires_comment' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the workflow this transition belongs to.
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Get the "from" status.
     */
    public function fromStatus(): BelongsTo
    {
        return $this->belongsTo(WorkflowStatus::class, 'from_status_id');
    }

    /**
     * Get the "to" status.
     */
    public function toStatus(): BelongsTo
    {
        return $this->belongsTo(WorkflowStatus::class, 'to_status_id');
    }

    /**
     * Get the button label or generate from status names.
     */
    public function getButtonLabelAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Auto-generate if not set
        $this->loadMissing(['fromStatus', 'toStatus']);
        return "Move to {$this->toStatus->name}";
    }

    /**
     * Check if user has permission to execute this transition.
     */
    public function userCanExecute($user): bool
    {
        // Check if specific permission is required
        if ($this->requires_permission && !$user->hasPermission($this->requires_permission)) {
            return false;
        }

        // Check if user has one of the allowed roles
        if ($this->allowed_roles && count($this->allowed_roles) > 0) {
            $userRoleIds = $user->roles->pluck('id')->toArray();
            $hasAllowedRole = !empty(array_intersect($userRoleIds, $this->allowed_roles));

            if (!$hasAllowedRole) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate conditions for this transition.
     */
    public function evaluateConditions($job, $data = []): bool
    {
        if (!$this->conditions || count($this->conditions) === 0) {
            return true;
        }

        // TODO: Implement condition evaluation logic
        // For now, return true
        return true;
    }

    /**
     * Scope to filter active transitions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
