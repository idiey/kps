<?php

namespace App\Policies;

use App\Models\Kps\Site;
use App\Models\User;

class KpsSitePolicy
{
    private function isHq(User $user): bool
    {
        return $user->hasPermissionTo('kps.manage_sites');
    }

    private function isAssigned(User $user, Site $site): bool
    {
        return $site->hasUser($user->id);
    }

    public function viewAny(User $user): bool
    {
        return $this->isHq($user) || $user->kpsSites()->exists();
    }

    public function view(User $user, Site $site): bool
    {
        return $this->isHq($user) || $this->isAssigned($user, $site);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('kps.manage_sites');
    }

    public function update(User $user, Site $site): bool
    {
        return $user->hasPermissionTo('kps.manage_sites') || $site->isSiteAdmin($user->id);
    }

    public function delete(User $user, Site $site): bool
    {
        return $user->hasPermissionTo('kps.manage_sites');
    }

    public function manageUsers(User $user, Site $site): bool
    {
        return $user->hasPermissionTo('kps.manage_sites') || $site->isSiteAdmin($user->id);
    }
}
