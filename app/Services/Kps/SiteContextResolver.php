<?php

namespace App\Services\Kps;

use App\Models\Kps\Site;
use Illuminate\Http\Request;

class SiteContextResolver
{
    /**
     * Resolve the current site context and user role at that site.
     */
    public function resolve(Request $request, ?Site $site = null): array
    {
        $user = $request->user();

        if ($site && $user) {
            $siteRole = $site->getUserRole($user->id);

            if (!$siteRole && $user->hasPermissionTo('kps.manage_sites')) {
                $siteRole = 'site_admin';
            }

            return [
                'site' => $site,
                'siteRole' => $siteRole,
            ];
        }

        if ($user && $user->isKpsSiteOnly()) {
            $assignedSite = $user->getFirstKpsSite();
            $role = $assignedSite?->getUserRole($user->id);

            return [
                'site' => $assignedSite,
                'siteRole' => $role,
            ];
        }

        return [
            'site' => null,
            'siteRole' => null,
        ];
    }
}
