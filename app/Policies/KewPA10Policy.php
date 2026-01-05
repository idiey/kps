<?php

namespace App\Policies;

use App\Models\KewPA10;
use App\Models\User;
use App\Traits\LogsPolicyAuthorization;

class KewPA10Policy
{
    use LogsPolicyAuthorization;
    /**
     * Determine whether the user can view any KEW.PA-10 forms.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view KEW.PA-10 forms
        return true;
    }

    /**
     * Determine whether the user can view the KEW.PA-10 form.
     */
    public function view(User $user, KewPA10 $kewPA10): bool
    {
        // All authenticated users can view individual KEW.PA-10 forms
        return true;
    }

    /**
     * Determine whether the user can create KEW.PA-10 forms.
     */
    public function create(User $user): bool
    {
        $result = $user->hasRole('pentadbiran');

        // Example: Audit logging integration (optional - can be enabled per method)
        return $this->authorize(
            action: 'create',
            user: $user,
            resource: 'KewPA10',
            authorized: $result
        );
    }

    /**
     * Determine whether the user can update the KEW.PA-10 form.
     */
    public function update(User $user, KewPA10 $kewPA10): bool
    {
        // Only Admin Officers (Pentadbiran) can update KEW.PA-10 forms
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can verify the KEW.PA-10 form.
     */
    public function verify(User $user, KewPA10 $kewPA10): bool
    {
        $result = $user->hasRole('pentadbiran');

        // Example: Audit logging with model context
        return $this->authorize(
            action: 'verify',
            user: $user,
            resource: 'KewPA10',
            authorized: $result,
            model: $kewPA10,
            context: [
                'kew_pa_10_number' => $kewPA10->kew_pa_10_number,
                'is_complete' => $kewPA10->isComplete(),
            ]
        );
    }

    /**
     * Determine whether the user can process form return to department.
     */
    public function returnToDepartment(User $user, KewPA10 $kewPA10): bool
    {
        // Only Admin Officers (Pentadbiran) can process KEW.PA-10 returns
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can delete the KEW.PA-10 form.
     */
    public function delete(User $user, KewPA10 $kewPA10): bool
    {
        // Only Admin Officers (Pentadbiran) can delete KEW.PA-10 forms
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can restore the KEW.PA-10 form.
     */
    public function restore(User $user, KewPA10 $kewPA10): bool
    {
        // Only Admin Officers (Pentadbiran) can restore KEW.PA-10 forms
        return $user->hasRole('pentadbiran');
    }

    /**
     * Determine whether the user can permanently delete the KEW.PA-10 form.
     */
    public function forceDelete(User $user, KewPA10 $kewPA10): bool
    {
        // Only Admin Officers (Pentadbiran) can force delete KEW.PA-10 forms
        return $user->hasRole('pentadbiran');
    }
}
