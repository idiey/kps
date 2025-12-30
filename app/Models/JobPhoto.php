<?php

namespace App\Models;

use App\Enums\PhotoStage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class JobPhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workshop_job_id',
        'user_id',
        'inspection_report_id',
        'photo_stage',
        'category',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'description',
        'location_context',
        'is_public',
        'taken_at',
    ];

    protected function casts(): array
    {
        return [
            'photo_stage' => PhotoStage::class,
            'is_public' => 'boolean',
            'taken_at' => 'datetime',
        ];
    }

    /**
     * Get the workshop job this photo belongs to.
     */
    public function workshopJob(): BelongsTo
    {
        return $this->belongsTo(WorkshopJob::class);
    }

    /**
     * Get the user who uploaded this photo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the inspection report this photo is attached to.
     */
    public function inspectionReport(): BelongsTo
    {
        return $this->belongsTo(InspectionReport::class);
    }

    /**
     * Get the full URL to the photo.
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Scope to filter by photo stage.
     */
    public function scopeOfStage($query, PhotoStage|string $stage)
    {
        if ($stage instanceof PhotoStage) {
            $stage = $stage->value;
        }
        return $query->where('photo_stage', $stage);
    }

    /**
     * Scope to filter public photos only.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to filter by job.
     */
    public function scopeForJob($query, int $jobId)
    {
        return $query->where('workshop_job_id', $jobId);
    }
}
