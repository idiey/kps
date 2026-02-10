<?php

namespace App\Policies;

use App\Models\Kps\Peneroka;
use App\Models\User;

class PenerokaPolicy
{
    private function canAccessSite(User $user, Peneroka $peneroka): bool
    {
        return $user->hasPermissionTo('kps.manage_sites') || $peneroka->site?->hasUser($user->id);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('kps.view') || $user->hasPermissionTo('kps.manage_peneroka');
    }

    public function view(User $user, Peneroka $peneroka): bool
    {
        return $this->canAccessSite($user, $peneroka)
            && ($user->hasPermissionTo('kps.view') || $user->hasPermissionTo('kps.manage_peneroka'));
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('kps.manage_peneroka');
    }

    public function update(User $user, Peneroka $peneroka): bool
    {
        return $this->canAccessSite($user, $peneroka)
            && $user->hasPermissionTo('kps.manage_peneroka');
    }

    public function delete(User $user, Peneroka $peneroka): bool
    {
        return $this->canAccessSite($user, $peneroka)
            && $user->hasPermissionTo('kps.manage_peneroka');
    }
}
