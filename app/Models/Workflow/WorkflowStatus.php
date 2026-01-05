<?php

namespace App\Models\Workflow;

use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkflowStatus extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'workflow_id',
        'name',
        'code',
        'description',
        'color',
        'icon',
        'is_initial',
        'is_final',
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
            'is_initial' => 'boolean',
            'is_final' => 'boolean',
        ];
    }

    /**
     * Get the workflow this status belongs to.
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Get all transitions FROM this status.
     */
    public function transitionsFrom(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'from_status_id');
    }

    /**
     * Get all transitions TO this status.
     */
    public function transitionsTo(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'to_status_id');
    }

    /**
     * Get all jobs currently in this status.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(WorkshopJob::class, 'current_workflow_status_id');
    }

    /**
     * Get all rules specific to this status.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(WorkflowRule::class, 'status_id');
    }

    /**
     * Get available next statuses (via active transitions).
     */
    public function getAvailableNextStatuses()
    {
        return $this->transitionsFrom()
            ->where('is_active', true)
            ->with('toStatus')
            ->get()
            ->pluck('toStatus');
    }

    /**
     * Check if this status can transition to another status.
     */
    public function canTransitionTo(WorkflowStatus $targetStatus): bool
    {
        return $this->transitionsFrom()
            ->where('to_status_id', $targetStatus->id)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Scope to filter initial statuses.
     */
    public function scopeInitial($query)
    {
        return $query->where('is_initial', true);
    }

    /**
     * Scope to filter final statuses.
     */
    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }
}
