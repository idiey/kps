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
        // 1. Management Roles: Admin, Supervisor, Front Desk, Approver - View ALL
        if ($user->hasAnyRole(['pentadbiran', 'penyelia', 'kaunter', 'pelulus'])) {
            return true;
        }

        // 2. Assigned User - View THEIR jobs (Technicians, etc.)
        if ($job->assigned_to == $user->id) {
            return true;
        }

        // 3. KEW.PA-10 Special: Inspectors can view all KEW jobs
        if ($job->job_mode === \App\Enums\JobMode::KEW_PA_10) {
            if ($user->hasRole('pemeriksa')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create jobs.
     */
    public function create(User $user): bool
    {
        // Admin, supervisors, and front desk can create jobs
        // Front desk handles walk-in customers
        // Supervisors create KEW.PA-10 jobs
        return $user->hasAnyRole(['pentadbiran', 'penyelia', 'kaunter']);
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

        // Inspector can update KEW.PA-10 jobs assigned to them
        if ($user->hasRole('pemeriksa') && $job->assigned_to === $user->id) {
            if ($job->job_mode === \App\Enums\JobMode::KEW_PA_10) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update job status.
     */
    public function updateStatus(User $user, WorkshopJob $job): bool
    {
        // Admin and Supervisor can change any job status
        if ($user->hasAnyRole(['pentadbiran', 'penyelia'])) {
            return true;
        }

        // Technician can change status of jobs assigned to them
        if ($user->hasRole('juruteknik') && $job->assigned_to == $user->id) {
            return true;
        }

        // Inspector can change status of KEW.PA-10 jobs assigned to them
        if ($user->hasRole('pemeriksa') && $job->assigned_to === $user->id) {
            if ($job->job_mode === \App\Enums\JobMode::KEW_PA_10) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can assign jobs.
     */
    public function assign(User $user, WorkshopJob $job): bool
    {
        // Admin and Supervisor can assign/reassign jobs
        return $user->hasAnyRole(['pentadbiran', 'penyelia']);
    }

    /**
     * Determine whether the user can create notes on the job.
     */
    public function createNote(User $user, WorkshopJob $job): bool
    {
        // Admin, technicians, and inspectors can create notes
        return $user->hasAnyRole(['pentadbiran', 'juruteknik', 'pemeriksa']);
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
