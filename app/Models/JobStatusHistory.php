<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobStatusHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'workshop_job_id',
        'user_id',
        'from_status',
        'to_status',
        'workflow_status_id',
        'transition_id',
        'notes',
        'metadata',
        'changed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'from_status' => JobStatus::class,
            'to_status' => JobStatus::class,
            'metadata' => 'array',
            'changed_at' => 'datetime',
        ];
    }

    /**
     * Get the job that owns the status history.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(WorkshopJob::class, 'workshop_job_id');
    }

    /**
     * Get the user that changed the status.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by job.
     */
    public function scopeForJob($query, int $jobId)
    {
        return $query->where('workshop_job_id', $jobId);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeToStatus($query, JobStatus|string $status)
    {
        if ($status instanceof JobStatus) {
            $status = $status->value;
        }
        return $query->where('to_status', $status);
    }

    /**
     * Get a human-readable description of the status change.
     */
    public function getDescriptionAttribute(): string
    {
        $from = $this->from_status ? $this->from_status->label() : 'New';
        $to = $this->to_status->label();

        return "Changed from {$from} to {$to}";
    }
}
