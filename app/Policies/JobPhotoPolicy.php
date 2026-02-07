<?php

namespace App\Policies;

use App\Models\JobPhoto;
use App\Models\User;

class JobPhotoPolicy
{
    use \App\Traits\LogsPolicyAuthorization;

    /**
     * Determine whether the user can view any job photos.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view job photos
        return $this->authorize('viewAny', $user, 'JobPhoto', true);
    }

    /**
     * Determine whether the user can view the job photo.
     */
    public function view(User $user, JobPhoto $photo): bool
    {
        $authorized = false;
        
        // Admin Officers can view all photos
        if ($user->hasRole('pentadbiran')) {
            $authorized = true;
        }
        // Public photos can be viewed by anyone
        elseif ($photo->is_public) {
            $authorized = true;
        }
        // Users can view their own photos
        elseif ($photo->user_id === $user->id) {
            $authorized = true;
        }
        // Technicians assigned to the job can view private photos
        elseif ($user->hasRole('juruteknik') && $photo->workshopJob && $photo->workshopJob->assigned_to === $user->id) {
            $authorized = true;
        }
        // Supervisors can view all photos
        elseif ($user->hasRole('penyelia')) {
            $authorized = true;
        }

        return $this->authorize('view', $user, 'JobPhoto', $authorized, $photo);
    }

    /**
     * Determine whether the user can create job photos.
     */
    public function create(User $user): bool
    {
        // Inspectors and Technicians can create job photos
        $authorized = $user->hasAnyRole(['pemeriksa', 'juruteknik']);
        return $this->authorize('create', $user, 'JobPhoto', $authorized);
    }

    /**
     * Determine whether the user can update the job photo.
     */
    public function update(User $user, JobPhoto $photo): bool
    {
        $authorized = false;
        // Admin Officers can update any photo metadata
        if ($user->hasRole('pentadbiran')) {
            $authorized = true;
        }
        // Users can update their own photos (metadata only)
        elseif ($photo->user_id === $user->id) {
            $authorized = true;
        }

        return $this->authorize('update', $user, 'JobPhoto', $authorized, $photo);
    }

    /**
     * Determine whether the user can change photo visibility.
     */
    public function changeVisibility(User $user, JobPhoto $photo): bool
    {
        $authorized = false;
        // Admin Officers can change visibility of any photo
        if ($user->hasRole('pentadbiran')) {
            $authorized = true;
        }
        // Photo owners can change visibility of their own photos
        elseif ($photo->user_id === $user->id) {
            $authorized = true;
        }

        return $this->authorize('changeVisibility', $user, 'JobPhoto', $authorized, $photo);
    }

    /**
     * Determine whether the user can delete the job photo.
     */
    public function delete(User $user, JobPhoto $photo): bool
    {
        $authorized = false;
        // Admin Officers can delete any photo
        if ($user->hasRole('pentadbiran')) {
            $authorized = true;
        }
        // Users can soft-delete their own photos
        elseif ($photo->user_id === $user->id) {
            $authorized = true;
        }

        return $this->authorize('delete', $user, 'JobPhoto', $authorized, $photo);
    }

    /**
     * Determine whether the user can restore the job photo.
     */
    public function restore(User $user, JobPhoto $photo): bool
    {
        $authorized = false;
        // Admin Officers can restore any photo
        if ($user->hasRole('pentadbiran')) {
            $authorized = true;
        }
        // Users can restore their own photos
        elseif ($photo->user_id === $user->id) {
            $authorized = true;
        }

        return $this->authorize('restore', $user, 'JobPhoto', $authorized, $photo);
    }

    /**
     * Determine whether the user can permanently delete the job photo.
     */
    public function forceDelete(User $user, JobPhoto $photo): bool
    {
        // Only Admin Officers can permanently delete photos
        $authorized = $user->hasRole('pentadbiran');
        return $this->authorize('forceDelete', $user, 'JobPhoto', $authorized, $photo);
    }
}
