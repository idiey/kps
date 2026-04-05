<?php

namespace App\Http\Middleware;

use App\Models\Kps\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKpsSiteContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        $isHq = $user->hasPermissionTo('kps.manage_sites');
        $isAdmin = $user->hasRole(['pentadbiran', 'company_admin']);
        $routeSite = $request->route('site');

        $isKpsProfileRoute = $request->routeIs('kps.profile.*');

        // Non-global admins should be redirected to their assigned site (if any).
        if (!$routeSite && !$isAdmin && !$isKpsProfileRoute) {
            $site = $user->getFirstKpsSite();
            if ($site) {
                return redirect()->route('kps.sites.show', $site->id);
            }
        }

        // If a site is in the route, enforce access for non-HQ users.
        if ($routeSite && !$isHq) {
            $site = $routeSite instanceof Site ? $routeSite : Site::find($routeSite);
            if (!$site || !$site->hasUser($user->id)) {
                abort(403);
            }
        }

        return $next($request);
    }
}
