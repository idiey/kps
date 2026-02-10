<?php

namespace App\Policies;

use App\Models\Kps\Debt;
use App\Models\User;

class DebtPolicy
{
    private function canAccessSite(User $user, Debt $debt): bool
    {
        return $user->hasPermissionTo('kps.manage_sites') || $debt->peneroka?->site?->hasUser($user->id);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('kps.view') || $user->hasPermissionTo('kps.manage_hutang');
    }

    public function view(User $user, Debt $debt): bool
    {
        return $this->canAccessSite($user, $debt)
            && ($user->hasPermissionTo('kps.view') || $user->hasPermissionTo('kps.manage_hutang'));
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('kps.manage_hutang');
    }

    public function update(User $user, Debt $debt): bool
    {
        return $this->canAccessSite($user, $debt)
            && $user->hasPermissionTo('kps.manage_hutang');
    }

    public function delete(User $user, Debt $debt): bool
    {
        return $this->canAccessSite($user, $debt)
            && $user->hasPermissionTo('kps.manage_hutang');
    }
}
