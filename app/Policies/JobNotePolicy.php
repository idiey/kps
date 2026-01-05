<?php

namespace App\Policies;

use App\Models\JobNote;
use App\Models\User;

class JobNotePolicy
{
    /**
     * Determine whether the user can view any notes.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view notes
        return true;
    }

    /**
     * Determine whether the user can view the note.
     */
    public function view(User $user, JobNote $note): bool
    {
        // Admin can view all notes
        if ($user->hasRole('pentadbiran')) {
            return true;
        }

        // Public notes can be viewed by anyone
        if ($note->is_public) {
            return true;
        }

        // Users can view their own private notes
        if ($note->user_id === $user->id) {
            return true;
        }

        // Technicians assigned to the job can view private notes
        if ($user->hasRole('juruteknik') && $note->job->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create notes.
     */
    public function create(User $user): bool
    {
        // Admin and technicians can create notes
        return $user->hasAnyRole(['pentadbiran', 'juruteknik']);
    }

    /**
     * Determine whether the user can update the note.
     */
    public function update(User $user, JobNote $note): bool
    {
        // Admin can update any note
        if ($user->hasRole('pentadbiran')) {
            return true;
        }

        // Users can update their own notes
        return $note->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the note.
     */
    public function delete(User $user, JobNote $note): bool
    {
        // Admin can delete any note
        if ($user->hasRole('pentadbiran')) {
            return true;
        }

        // Users can delete their own notes
        return $note->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the note.
     */
    public function restore(User $user, JobNote $note): bool
    {
        // Admin can restore any note
        if ($user->hasRole('pentadbiran')) {
            return true;
        }

        // Users can restore their own notes
        return $note->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the note.
     */
    public function forceDelete(User $user, JobNote $note): bool
    {
        // Only admin can force delete notes
        return $user->hasRole('pentadbiran');
    }
}
