<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'workflow_id',
        'status_id',
        'name',
        'description',
        'rule_type',
        'conditions',
        'actions',
        'priority',
        'is_active',
        'metadata',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'actions' => 'array',
            'metadata' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the workflow this rule belongs to.
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Get the status this rule applies to (null = all statuses).
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(WorkflowStatus::class);
    }

    /**
     * Scope to filter active rules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by rule type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('rule_type', $type);
    }

    /**
     * Scope to filter by status (including global rules).
     */
    public function scopeForStatus($query, $statusId)
    {
        return $query->where(function ($q) use ($statusId) {
            $q->where('status_id', $statusId)
              ->orWhereNull('status_id');
        });
    }

    /**
     * Evaluate if this rule should be applied.
     */
    public function shouldApply($job, $context = []): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->conditions || count($this->conditions) === 0) {
            return true;
        }

        // TODO: Implement condition evaluation logic
        return true;
    }

    /**
     * Execute the rule's actions.
     */
    public function execute($job, $context = [])
    {
        if (!$this->shouldApply($job, $context)) {
            return;
        }

        // TODO: Implement action execution based on rule_type
        // field_required, field_visible, auto_assign, notification, etc.
    }
}
