<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkshopJob;

class WorkshopJobPolicy
{
    /**
     * Determine whether the user can view any jobs.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view jobs
        return true;
    }

    /**
     * Determine whether the user can view the job.
     */
    public function view(User $user, WorkshopJob $job): bool
    {
        // All authenticated users can view individual jobs
        return true;
    }

    /**
     * Determine whether the user can create jobs.
     */
    public function create(User $user): bool
    {
        // Admin and technicians can create jobs
        return $user->hasAnyRole(['pentadbiran', 'juruteknik']);
    }

    /**
     * Determine whether the user can update the job.
     */
    public function update(User $user, WorkshopJob $job): bool
    {
        // Admin can update any job
        if ($user->hasRole('pentadbiran')) {
            return true;
        }

        // Technician can update jobs assigned to them
        if ($user->hasRole('juruteknik') && $job->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update job status.
     */
    public function updateStatus(User $user, WorkshopJob $job): bool
    {
        // Admin can change any job status
        if ($user->hasRole('pentadbiran')) {
            return true;
        }

        // Technician can change status of jobs assigned to them
        if ($user->hasRole('juruteknik') && $job->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can assign jobs.
     */
    public function assign(User $user, WorkshopJob $job): bool
    {
        // Only admin can assign/reassign jobs
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can create notes on the job.
     */
    public function createNote(User $user, WorkshopJob $job): bool
    {
        // Admin and technicians can create notes
        return $user->hasAnyRole(['pentadbiran', 'juruteknik']);
    }

    /**
     * Determine whether the user can delete the job.
     */
    public function delete(User $user, WorkshopJob $job): bool
    {
        // Only admin can delete jobs
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can restore the job.
     */
    public function restore(User $user, WorkshopJob $job): bool
    {
        // Only admin can restore jobs
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can permanently delete the job.
     */
    public function forceDelete(User $user, WorkshopJob $job): bool
    {
        // Only admin can force delete jobs
        return $user->hasRole('pentadbiran');
    }
}
