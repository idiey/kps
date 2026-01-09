<?php

namespace App\Models;

use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Models\Job\JobFieldValue;
use App\Models\Template\JobTemplate;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopJob extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'job_number',
        'job_reference',
        'template_id',
        'workflow_id',
        'current_workflow_status_id',
        'customer_id',
        'government_department_id',
        'asset_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'vehicle_registration',
        'asset_tag',
        'estimated_cost',
        'actual_cost',
        'started_at',
        'completed_at',
        'invoiced_at',
        'due_date',
        'inspection_required',
        'inspection_approved',
        'estimated_completion_date',
        'kew_pa_10_returned_at',
        'estimated_hours',
        'actual_hours',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => JobStatus::class,
            'priority' => JobPriority::class,
            'estimated_cost' => 'decimal:2',
            'actual_cost' => 'decimal:2',
            'estimated_hours' => 'decimal:2',
            'actual_hours' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'invoiced_at' => 'datetime',
            'kew_pa_10_returned_at' => 'datetime',
            'due_date' => 'date',
            'estimated_completion_date' => 'date',
            'inspection_required' => 'boolean',
            'inspection_approved' => 'boolean',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate job number and set default status on creation
        static::creating(function ($job) {
            if (empty($job->job_number)) {
                $job->job_number = self::generateJobNumber();
            }

            // Set default status if not provided
            if (empty($job->status)) {
                $job->status = JobStatus::NEW;
            }
        });
    }

    /**
     * Generate a unique job number.
     */
    public static function generateJobNumber(): string
    {
        $prefix = 'WJ';
        $date = now()->format('Ymd');
        $lastJob = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastJob ? (int)substr($lastJob->job_number, -4) + 1 : 1;

        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    /**
     * Get the customer that owns the job.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user assigned to this job.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all notes for this job.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(JobNote::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get public notes only.
     */
    public function publicNotes(): HasMany
    {
        return $this->hasMany(JobNote::class)->where('is_public', true)->orderBy('created_at', 'desc');
    }

    /**
     * Get private notes only.
     */
    public function privateNotes(): HasMany
    {
        return $this->hasMany(JobNote::class)->where('is_public', false)->orderBy('created_at', 'desc');
    }

    /**
     * Get status history for this job.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(JobStatusHistory::class)->orderBy('changed_at', 'desc');
    }

    /**
     * Get assignment history for this job.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(JobAssignment::class)->orderBy('assigned_at', 'desc');
    }

    /**
     * Get current assignment.
     */
    public function currentAssignment(): HasMany
    {
        return $this->hasMany(JobAssignment::class)->where('is_current', true);
    }

    /**
     * Get the government department for this job.
     */
    public function governmentDepartment(): BelongsTo
    {
        return $this->belongsTo(GovernmentDepartment::class);
    }

    /**
     * Get the asset for this job.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the inspection report for this job.
     */
    public function inspectionReport(): HasOne
    {
        return $this->hasOne(InspectionReport::class);
    }

    /**
     * Get all photos for this job.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(JobPhoto::class)->orderBy('taken_at', 'desc');
    }

    /**
     * Get the completion report for this job.
     */
    public function completionReport(): HasOne
    {
        return $this->hasOne(RepairCompletionReport::class);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeOfStatus($query, JobStatus|string $status)
    {
        if ($status instanceof JobStatus) {
            $status = $status->value;
        }
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by priority.
     */
    public function scopeOfPriority($query, JobPriority|string $priority)
    {
        if ($priority instanceof JobPriority) {
            $priority = $priority->value;
        }
        return $query->where('priority', $priority);
    }

    /**
     * Scope to filter by assigned user.
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope to filter by customer.
     */
    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope to search jobs.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('job_number', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('vehicle_registration', 'like', "%{$search}%")
                ->orWhere('asset_tag', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for overdue jobs.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', [JobStatus::COMPLETED->value, JobStatus::INVOICED->value]);
    }

    /**
     * Check if job is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date &&
            $this->due_date->isPast() &&
            !in_array($this->status, [JobStatus::COMPLETED, JobStatus::INVOICED]);
    }

    /**
     * Check if job can transition to new status.
     */
    public function canTransitionTo(JobStatus $targetStatus): bool
    {
        return $this->status->canTransitionTo($targetStatus);
    }

    /**
     * Scope to filter jobs pending inspection.
     */
    public function scopePendingInspection($query)
    {
        return $query->where('inspection_required', true)
            ->where('inspection_approved', null);
    }

    /**
     * Scope to filter jobs with approved inspections.
     */
    public function scopeInspectionApproved($query)
    {
        return $query->where('inspection_approved', true);
    }

    /**
     * Get the template for this job.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(JobTemplate::class);
    }

    /**
     * Get the workflow for this job.
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Get the current workflow status.
     */
    public function currentWorkflowStatus(): BelongsTo
    {
        return $this->belongsTo(WorkflowStatus::class, 'current_workflow_status_id');
    }

    /**
     * Get all field values for this job.
     */
    public function fieldValues(): HasMany
    {
        return $this->hasMany(JobFieldValue::class, 'job_id');
    }

    /**
     * Get a specific field value by field code.
     */
    public function getFieldValue(string $fieldCode)
    {
        $field = $this->template?->fields()->where('code', $fieldCode)->first();

        if (!$field) {
            return null;
        }

        $fieldValue = $this->fieldValues()->where('field_id', $field->id)->first();

        return $fieldValue?->getValue();
    }

    /**
     * Set a specific field value by field code.
     */
    public function setFieldValue(string $fieldCode, $value): void
    {
        $field = $this->template?->fields()->where('code', $fieldCode)->first();

        if (!$field) {
            throw new \InvalidArgumentException("Field with code '{$fieldCode}' not found in template");
        }

        $fieldValue = $this->fieldValues()->firstOrNew(['field_id' => $field->id]);
        $fieldValue->setValue($value);
        $fieldValue->save();
    }

    /**
     * Get all field values as associative array.
     */
    public function getAllFieldValues(): array
    {
        return JobFieldValue::getJobFieldValues($this);
    }

    /**
     * Check if this job uses dynamic workflow system.
     */
    public function usesDynamicWorkflow(): bool
    {
        return $this->workflow_id !== null;
    }

    /**
     * Get available workflow transitions from current status.
     */
    public function getAvailableTransitions()
    {
        if (!$this->usesDynamicWorkflow() || !$this->currentWorkflowStatus) {
            return collect();
        }

        return $this->workflow->getAvailableTransitionsFrom($this->currentWorkflowStatus);
    }
}
