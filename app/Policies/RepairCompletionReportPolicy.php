<?php

namespace App\Policies;

use App\Models\RepairCompletionReport;
use App\Models\User;

class RepairCompletionReportPolicy
{
    use \App\Traits\LogsPolicyAuthorization;

    /**
     * Determine whether the user can view any repair completion reports.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view repair completion reports
        return $this->authorize('viewAny', $user, 'RepairCompletionReport', true);
    }

    /**
     * Determine whether the user can view the repair completion report.
     */
    public function view(User $user, RepairCompletionReport $report): bool
    {
        // All authenticated users can view individual repair completion reports
        return $this->authorize('view', $user, 'RepairCompletionReport', true, $report);
    }

    /**
     * Determine whether the user can create repair completion reports.
     */
    public function create(User $user): bool
    {
        // Only Technicians (Juruteknik) can create repair completion reports
        return $this->authorize('create', $user, 'RepairCompletionReport', $user->role->canRepair());
    }

    /**
     * Determine whether the user can update the repair completion report.
     */
    public function update(User $user, RepairCompletionReport $report): bool
    {
        $authorized = false;
        // Technicians can update their own reports only if not yet signed
        if ($user->role->canRepair() && $report->technician_id === $user->id) {
            // Cannot update if already signed
            $authorized = !$report->isSigned();
        }

        return $this->authorize('update', $user, 'RepairCompletionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can add parts to the repair completion report.
     */
    public function addParts(User $user, RepairCompletionReport $report): bool
    {
        $authorized = false;
        // Technicians can add parts to their own reports only if not yet signed
        if ($user->role->canRepair() && $report->technician_id === $user->id) {
            $authorized = !$report->isSigned();
        }

        return $this->authorize('addParts', $user, 'RepairCompletionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can sign the repair completion report.
     */
    public function sign(User $user, RepairCompletionReport $report): bool
    {
        $authorized = false;
        // Technicians can sign their own reports only if not yet signed
        if ($user->role->canRepair() && $report->technician_id === $user->id) {
            $authorized = !$report->isSigned();
        }

        return $this->authorize('sign', $user, 'RepairCompletionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can review the repair completion report.
     */
    public function review(User $user, RepairCompletionReport $report): bool
    {
        // Only Supervisors (Penyelia) can review repair completion reports
        $authorized = $user->role->canSupervise();
        return $this->authorize('review', $user, 'RepairCompletionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can delete the repair completion report.
     */
    public function delete(User $user, RepairCompletionReport $report): bool
    {
        $authorized = false;
        // Technicians can delete their own reports only if not yet signed
        if ($user->role->canRepair() && $report->technician_id === $user->id) {
            $authorized = !$report->isSigned();
        }

        // Admin Officers can delete any repair completion report
        if ($user->role->canManageKewPA10()) {
            $authorized = true;
        }

        return $this->authorize('delete', $user, 'RepairCompletionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can restore the repair completion report.
     */
    public function restore(User $user, RepairCompletionReport $report): bool
    {
        // Only Admin Officers can restore repair completion reports
        $authorized = $user->role->canManageKewPA10();
        return $this->authorize('restore', $user, 'RepairCompletionReport', $authorized, $report);
    }

    /**
     * Determine whether the user can permanently delete the repair completion report.
     */
    public function forceDelete(User $user, RepairCompletionReport $report): bool
    {
        // Only Admin Officers can force delete repair completion reports
        $authorized = $user->role->canManageKewPA10();
        return $this->authorize('forceDelete', $user, 'RepairCompletionReport', $authorized, $report);
    }
}
