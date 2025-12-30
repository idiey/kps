<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'workshop_job_id',
        'assigned_by',
        'assigned_to',
        'notes',
        'assigned_at',
        'unassigned_at',
        'is_current',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'unassigned_at' => 'datetime',
            'is_current' => 'boolean',
        ];
    }

    /**
     * Get the job that owns the assignment.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(WorkshopJob::class, 'workshop_job_id');
    }

    /**
     * Get the user who made the assignment.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the user assigned to the job.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope to filter current assignments.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope to filter by job.
     */
    public function scopeForJob($query, int $jobId)
    {
        return $query->where('workshop_job_id', $jobId);
    }

    /**
     * Scope to filter by technician.
     */
    public function scopeForTechnician($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Mark assignment as inactive.
     */
    public function markAsInactive(): void
    {
        $this->update([
            'is_current' => false,
            'unassigned_at' => now(),
        ]);
    }

    /**
     * Get assignment duration in hours.
     */
    public function getDurationAttribute(): ?float
    {
        if (!$this->assigned_at) {
            return null;
        }

        $end = $this->unassigned_at ?? now();
        return $this->assigned_at->diffInHours($end, true);
    }

    /**
     * Check if assignment is active.
     */
    public function isActive(): bool
    {
        return $this->is_current && $this->unassigned_at === null;
    }
}
