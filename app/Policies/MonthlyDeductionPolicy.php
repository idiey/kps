<?php

namespace App\Policies;

use App\Models\Kps\MonthlyDeduction;
use App\Models\User;

class MonthlyDeductionPolicy
{
    private function canAccessSite(User $user, MonthlyDeduction $deduction): bool
    {
        return $user->hasPermissionTo('kps.manage_sites') || $deduction->site?->hasUser($user->id);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('kps.view') || $user->hasPermissionTo('kps.manage_potongan');
    }

    public function view(User $user, MonthlyDeduction $deduction): bool
    {
        return $this->canAccessSite($user, $deduction)
            && ($user->hasPermissionTo('kps.view') || $user->hasPermissionTo('kps.manage_potongan'));
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('kps.manage_potongan');
    }

    public function update(User $user, MonthlyDeduction $deduction): bool
    {
        return $this->canAccessSite($user, $deduction)
            && $user->hasPermissionTo('kps.manage_potongan');
    }

    public function delete(User $user, MonthlyDeduction $deduction): bool
    {
        return $this->canAccessSite($user, $deduction)
            && $user->hasPermissionTo('kps.manage_potongan');
    }
}
