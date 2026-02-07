<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workshop;

class WorkshopPolicy
{
    /**
     * Check if user is a global admin (pentadbiran without company restriction).
     */
    private function isGlobalAdmin(User $user): bool
    {
        return $user->company_id === null && $user->hasRole('pentadbiran');
    }

    /**
     * Check if user is an HQ-level user for the workshop's company.
     */
    private function isHqUser(User $user, Workshop $workshop): bool
    {
        return $user->company_id !== null && $user->company_id === $workshop->company_id;
    }

    /**
     * Check if user is assigned to the workshop.
     */
    private function isAssignedUser(User $user, Workshop $workshop): bool
    {
        return $workshop->hasUser($user->id);
    }

    /**
     * Check if user is a site admin for this workshop (assigned with site_admin role).
     */
    private function isSiteAdmin(User $user, Workshop $workshop): bool
    {
        return $workshop->isSiteAdmin($user->id);
    }

    /**
     * Check if user is a supervisor for this workshop (can manage jobs only).
     */
    private function isSupervisor(User $user, Workshop $workshop): bool
    {
        return $workshop->getUserRole($user->id) === 'supervisor';
    }

    /**
     * Determine whether the user can view any workshops.
     */
    public function viewAny(User $user): bool
    {
        // Global admins can view all workshops
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        // HQ users can view their company's workshops
        if ($user->company_id !== null) {
            return true;
        }

        // Users with workshop assignments can view their assigned workshops
        return $user->assignedWorkshops()->exists();
    }

    /**
     * Determine whether the user can view the workshop.
     */
    public function view(User $user, Workshop $workshop): bool
    {
        // Global admins can view any workshop
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        // HQ users can view workshops from their company
        if ($this->isHqUser($user, $workshop)) {
            return true;
        }

        // Assigned users can view their assigned workshop
        return $this->isAssignedUser($user, $workshop);
    }

    /**
     * Determine whether the user can create workshops.
     */
    public function create(User $user): bool
    {
        // Only global admins can create workshops
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        // HQ users with pentadbiran or company_admin role can create workshops in their company
        if ($user->company_id !== null && $user->hasRole(['pentadbiran', 'company_admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the workshop.
     */
    public function update(User $user, Workshop $workshop): bool
    {
        // Global admins can update any workshop
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        // HQ admins can update workshops from their company
        if ($this->isHqUser($user, $workshop) && $user->hasRole(['pentadbiran', 'company_admin'])) {
            return true;
        }

        // Site admins can update their assigned workshop
        if ($this->isSiteAdmin($user, $workshop)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the workshop.
     */
    public function delete(User $user, Workshop $workshop): bool
    {
        // Global admins can delete any workshop
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        // HQ admins can delete workshops from their company
        if ($this->isHqUser($user, $workshop) && $user->hasRole(['pentadbiran', 'company_admin'])) {
            return true;
        }

        // Assigned users cannot delete
        return false;
    }

    /**
     * Determine whether the user can restore the workshop.
     */
    public function restore(User $user, Workshop $workshop): bool
    {
        return $this->delete($user, $workshop);
    }

    /**
     * Determine whether the user can permanently delete the workshop.
     */
    public function forceDelete(User $user, Workshop $workshop): bool
    {
        // Only global admins can force delete
        return $this->isGlobalAdmin($user);
    }

    /**
     * Determine whether the user can manage users for the workshop.
     */
    public function manageUsers(User $user, Workshop $workshop): bool
    {
        // Global admins can manage users
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        // HQ admins can manage users for their company's workshops
        if ($this->isHqUser($user, $workshop) && $user->hasRole(['pentadbiran', 'company_admin'])) {
            return true;
        }

        // Site admins can manage users for their workshop
        if ($this->isSiteAdmin($user, $workshop)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can manage jobs for the workshop.
     * Site admin AND supervisor can manage jobs.
     */
    public function manageJobs(User $user, Workshop $workshop): bool
    {
        // Global admins can manage jobs
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        // HQ admins can manage jobs
        if ($this->isHqUser($user, $workshop) && $user->hasRole('pentadbiran')) {
            return true;
        }

        // Site admins can manage jobs
        if ($this->isSiteAdmin($user, $workshop)) {
            return true;
        }

        // Supervisors can manage jobs (but not users or other site settings)
        if ($this->isSupervisor($user, $workshop)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view analytics.
     */
    public function viewAnalytics(User $user, ?Workshop $workshop = null): bool
    {
        // Global admins can view analytics
        if ($this->isGlobalAdmin($user)) {
            return true;
        }

        // If workshop provided, check site-level access
        if ($workshop) {
            // HQ admins for this company
            if ($this->isHqUser($user, $workshop) && $user->hasRole(['pentadbiran', 'company_admin'])) {
                return true;
            }

            // Site admin for this workshop
            if ($this->isSiteAdmin($user, $workshop)) {
                return true;
            }
        }

        // Pentadbiran role can view analytics generally
        return $user->hasRole(['pentadbiran', 'company_admin']);
    }
}
