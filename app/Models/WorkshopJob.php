<?php

namespace App\Models;

use App\Enums\JobMode;
use App\Enums\JobPriority;
use App\Enums\JobStatus;
use App\Enums\KewPa10Priority;
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
        // Core job fields
        'job_number',
        'job_reference',
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
        
        // Static job mode
        'job_mode',
        
        // Static KEW.PA-10 fields
        'kew_pa_10_number',
        'kew_pa_10_received_date',
        'kew_pa_10_government_department_id',
        'kew_pa_10_asset_id',
        'kew_pa_10_description',
        'kew_approval_status',
        'kew_approved_by_id',
        'kew_approved_at',
        'kew_rejection_reason',

        'kew_pa_10_priority',
        'kew_pa_10_budget_reference',
        'kew_pa_10_document_path',
        'kew_pa_10_form_verified',
        'kew_pa_10_signatures_verified',
        
        // Extended KEW fields
        'kew_vehicle_registration',
        'kew_asset_tag',
        'kew_department_name',
        'kew_inspection_date',
        'kew_inspector_name',
        'kew_inspector_ic',
        'kew_findings',
        'kew_recommendations',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Core fields
            'status' => JobStatus::class,
            'priority' => JobPriority::class,
            'job_mode' => JobMode::class,
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
            
            // KEW.PA-10 static fields
            'kew_pa_10_received_date' => 'date',
            'kew_pa_10_priority' => KewPa10Priority::class,
            'kew_pa_10_form_verified' => 'boolean',

            'kew_pa_10_signatures_verified' => 'boolean',
            'kew_inspection_date' => 'date',
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
     * Get the KEW.PA-10 government department.
     */
    public function kewPa10GovernmentDepartment(): BelongsTo
    {
        return $this->belongsTo(GovernmentDepartment::class, 'kew_pa_10_government_department_id');
    }

    /**
     * Get the KEW.PA-10 asset.
     */
    public function kewPa10Asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'kew_pa_10_asset_id');
    }

    /**
     * Check if this is a KEW.PA-10 job.
     */
    public function isKewPa10(): bool
    {
        return $this->job_mode === JobMode::KEW_PA_10;
    }

    /**
     * Check if this is a normal job.
     */
    public function isNormal(): bool
    {
        return $this->job_mode === JobMode::NORMAL;
    }

    /**
     * Scope to filter KEW.PA-10 jobs only.
     */
    public function scopeKewPa10($query)
    {
        return $query->where('job_mode', JobMode::KEW_PA_10);
    }

    /**
     * Scope to filter normal jobs only.
     */
    public function scopeNormal($query)
    {
        return $query->where('job_mode', JobMode::NORMAL);
    }

    /**
     * Check if KEW.PA-10 form is complete and validated.
     */
    public function isKewPa10FormComplete(): bool
    {
        if (!$this->isKewPa10()) {
            return false;
        }

        return $this->kew_pa_10_form_verified === true
            && $this->kew_pa_10_signatures_verified === true
            && !empty($this->kew_pa_10_number)
            && !empty($this->kew_pa_10_received_date);
    }

    /**
     * Get KEW.PA-10 completion percentage.
     */
    public function getKewPa10CompletionPercentage(): int
    {
        if (!$this->isKewPa10()) {
            return 0;
        }

        $fields = [
            'kew_pa_10_number',
            'kew_pa_10_received_date',
            'kew_pa_10_government_department_id',
            'kew_pa_10_asset_id',
            'kew_pa_10_description',
            'kew_pa_10_priority',
            'kew_pa_10_form_verified',
            'kew_pa_10_signatures_verified',
        ];

        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filled++;
            }
        }

        return (int) (($filled / count($fields)) * 100);
    }
}
