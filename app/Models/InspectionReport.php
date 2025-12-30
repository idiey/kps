<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_job_id',
        'inspector_id',
        'asset_condition_current',
        'visual_damage_assessment',
        'functional_testing_results',
        'safety_hazards_identified',
        'additional_issues_discovered',
        'recommended_repairs',
        'approval_status',
        'approval_notes',
        'digital_signature',
        'signed_at',
        'inspection_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
            'inspection_completed_at' => 'datetime',
        ];
    }

    /**
     * Get the workshop job this inspection is for.
     */
    public function workshopJob(): BelongsTo
    {
        return $this->belongsTo(WorkshopJob::class);
    }

    /**
     * Get the inspector who performed this inspection.
     */
    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    /**
     * Get all photos associated with this inspection.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(JobPhoto::class);
    }

    /**
     * Approve this inspection.
     */
    public function approve(?string $notes = null): void
    {
        $this->update([
            'approval_status' => 'approved',
            'approval_notes' => $notes,
            'signed_at' => now(),
        ]);
    }

    /**
     * Reject this inspection.
     */
    public function reject(string $reason): void
    {
        $this->update([
            'approval_status' => 'rejected',
            'approval_notes' => $reason,
            'signed_at' => now(),
        ]);
    }

    /**
     * Check if inspection is approved.
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if inspection is pending.
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }
}
