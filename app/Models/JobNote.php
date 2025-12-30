<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobNote extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'workshop_job_id',
        'user_id',
        'content',
        'is_public',
        'note_type',
        'attachments',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'attachments' => 'array',
        ];
    }

    /**
     * Get the job that owns the note.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(WorkshopJob::class, 'workshop_job_id');
    }

    /**
     * Get the user that created the note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter public notes.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to filter private notes.
     */
    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    /**
     * Scope to filter by note type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('note_type', $type);
    }

    /**
     * Check if note has attachments.
     */
    public function hasAttachments(): bool
    {
        return !empty($this->attachments);
    }

    /**
     * Get attachment count.
     */
    public function getAttachmentCountAttribute(): int
    {
        return count($this->attachments ?? []);
    }
}
