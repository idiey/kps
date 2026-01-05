<?php

namespace App\Models\Workflow;

use App\Models\Template\JobTemplate;
use App\Models\Template\TemplateWorkflow;
use App\Models\User;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workflow extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'is_default',
        'metadata',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the user who created this workflow.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this workflow.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all statuses for this workflow.
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(WorkflowStatus::class)->orderBy('display_order');
    }

    /**
     * Get the initial status for this workflow.
     */
    public function initialStatus()
    {
        return $this->statuses()->where('is_initial', true)->first();
    }

    /**
     * Get all transitions for this workflow.
     */
    public function transitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class)->orderBy('display_order');
    }

    /**
     * Get all rules for this workflow.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(WorkflowRule::class)->orderBy('priority');
    }

    /**
     * Get all templates associated with this workflow.
     */
    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(JobTemplate::class, 'template_workflows')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    /**
     * Get all jobs using this workflow.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(WorkshopJob::class);
    }

    /**
     * Scope to filter active workflows.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get default workflow.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get available transitions from a specific status.
     */
    public function getAvailableTransitionsFrom(WorkflowStatus $status)
    {
        return $this->transitions()
            ->where('from_status_id', $status->id)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Check if transition is allowed from one status to another.
     */
    public function canTransition(WorkflowStatus $fromStatus, WorkflowStatus $toStatus): bool
    {
        return $this->transitions()
            ->where('from_status_id', $fromStatus->id)
            ->where('to_status_id', $toStatus->id)
            ->where('is_active', true)
            ->exists();
    }
}
