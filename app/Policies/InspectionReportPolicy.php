<?php

namespace App\Policies;

use App\Models\InspectionReport;
use App\Models\User;

class InspectionReportPolicy
{
    use \App\Traits\LogsPolicyAuthorization;

    /**
     * Determine whether the user can view any inspection reports.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view inspection reports
        return $this->authorize('viewAny', $user, 'InspectionReport', true);
    }

    /**
     * Determine whether the user can view the inspection report.
     */
    public function view(User $user, InspectionReport $report): bool
    {
        // All authenticated users can view individual inspection reports
        return $this->authorize('view', $user, 'InspectionReport', true, $report);
    }

    /**
     * Determine whether the user can create inspection reports.
     */
    public function create(User $user): bool
    {
        // Only Inspectors (Pemeriksa) can create inspection reports
        return $this->authorize('create', $user, 'InspectionReport', $user->hasRole('pemeriksa'));
    }

    /**
     * Determine whether the user can update the inspection report.
     */
    public function update(User $user, InspectionReport $report): bool
    {
        $authorized = false;
        // Inspectors can update their own reports (only if not yet approved/rejected)
        if ($user->hasRole('pemeriksa') && $report->inspector_id === $user->id) {
            // Cannot update if already approved or rejected
            $authorized = $report->approval_status === 'pending';
        }

        return $this->authorize('update', $user, 'InspectionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can approve the inspection report.
     */
    public function approve(User $user, InspectionReport $report): bool
    {
        // Only Supervisors (Penyelia) can approve inspection reports
        // Cannot approve if already approved or rejected
        $authorized = $user->hasRole('penyelia') && $report->approval_status === 'pending';
        return $this->authorize('approve', $user, 'InspectionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can reject the inspection report.
     */
    public function reject(User $user, InspectionReport $report): bool
    {
        // Only Supervisors (Penyelia) can reject inspection reports
        // Cannot reject if already approved or rejected
        $authorized = $user->hasRole('penyelia') && $report->approval_status === 'pending';
        return $this->authorize('reject', $user, 'InspectionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can add photos to the inspection report.
     */
    public function addPhotos(User $user, InspectionReport $report): bool
    {
        $authorized = false;
        // Inspectors can add photos to their own reports (only if not yet approved/rejected)
        if ($user->hasRole('pemeriksa') && $report->inspector_id === $user->id) {
            $authorized = $report->approval_status === 'pending';
        }

        return $this->authorize('addPhotos', $user, 'InspectionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can delete the inspection report.
     */
    public function delete(User $user, InspectionReport $report): bool
    {
        $authorized = false;
        // Inspectors can delete their own reports (only if not yet approved/rejected)
        if ($user->hasRole('pemeriksa') && $report->inspector_id === $user->id) {
            $authorized = $report->approval_status === 'pending';
        }

        // Admin Officers can delete any inspection report
        if ($user->hasRole('pentadbiran')) {
            $authorized = true;
        }

        return $this->authorize('delete', $user, 'InspectionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can restore the inspection report.
     */
    public function restore(User $user, InspectionReport $report): bool
    {
        // Only Admin Officers can restore inspection reports
        $authorized = $user->hasRole('pentadbiran');
        return $this->authorize('restore', $user, 'InspectionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can permanently delete the inspection report.
     */
    public function forceDelete(User $user, InspectionReport $report): bool
    {
        // Only Admin Officers can force delete inspection reports
        $authorized = $user->hasRole('pentadbiran');
        return $this->authorize('forceDelete', $user, 'InspectionReport', $authorized, $report);
    }
}
