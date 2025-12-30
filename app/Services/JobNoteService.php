<?php

namespace App\Services;

use App\Models\JobNote;
use App\Models\WorkshopJob;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service class for managing job notes and documentation.
 */
class JobNoteService
{
    /**
     * Create a new note for a job.
     */
    public function createNote(
        WorkshopJob $job,
        string $content,
        bool $isPublic = false,
        string $noteType = 'general',
        ?array $attachments = null
    ): JobNote {
        return $job->notes()->create([
            'user_id' => auth()->id(),
            'content' => $content,
            'is_public' => $isPublic,
            'note_type' => $noteType,
            'attachments' => $attachments,
        ]);
    }

    /**
     * Update an existing note.
     */
    public function updateNote(JobNote $note, array $data): JobNote
    {
        $note->update($data);
        return $note->fresh(['user', 'job']);
    }

    /**
     * Delete a note.
     */
    public function deleteNote(JobNote $note): bool
    {
        // Delete associated attachments if needed
        if ($note->hasAttachments()) {
            foreach ($note->attachments as $attachment) {
                // TODO: Implement file deletion from storage
                // Storage::delete($attachment);
            }
        }

        return $note->delete();
    }

    /**
     * Get all notes for a job.
     */
    public function getNotesForJob(WorkshopJob $job, ?bool $publicOnly = null): Collection
    {
        $query = $job->notes()->with('user');

        if ($publicOnly !== null) {
            $query = $publicOnly ? $query->public() : $query->private();
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Add attachment to a note.
     */
    public function addAttachment(JobNote $note, string $filePath): JobNote
    {
        $attachments = $note->attachments ?? [];
        $attachments[] = $filePath;

        $note->update(['attachments' => $attachments]);

        return $note->fresh();
    }

    /**
     * Remove attachment from a note.
     */
    public function removeAttachment(JobNote $note, string $filePath): JobNote
    {
        $attachments = $note->attachments ?? [];
        $attachments = array_filter($attachments, fn($path) => $path !== $filePath);

        $note->update(['attachments' => array_values($attachments)]);

        // TODO: Delete file from storage
        // Storage::delete($filePath);

        return $note->fresh();
    }

    /**
     * Get notes timeline for a job (combined with status changes).
     */
    public function getJobTimeline(WorkshopJob $job): Collection
    {
        $notes = $job->notes()->with('user')->get()->map(function ($note) {
            return [
                'type' => 'note',
                'timestamp' => $note->created_at,
                'data' => $note,
            ];
        });

        $statusChanges = $job->statusHistories()->with('user')->get()->map(function ($history) {
            return [
                'type' => 'status_change',
                'timestamp' => $history->changed_at,
                'data' => $history,
            ];
        });

        $assignments = $job->assignments()->with(['assignedBy', 'assignedTo'])->get()->map(function ($assignment) {
            return [
                'type' => 'assignment',
                'timestamp' => $assignment->assigned_at,
                'data' => $assignment,
            ];
        });

        return collect($notes)
            ->concat($statusChanges)
            ->concat($assignments)
            ->sortByDesc('timestamp')
            ->values();
    }
}
