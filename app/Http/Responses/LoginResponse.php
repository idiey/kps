<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // If user is a site admin only (not global/company admin),
        // redirect them to their assigned workshop
        if ($user && $user->isSiteAdminOnly()) {
            $workshop = $user->getFirstSiteAdminWorkshop();
            
            if ($workshop) {
                $workshopUrl = route('admin.workshops.show', $workshop->id);
                
                return $request->wantsJson()
                    ? response()->json(['two_factor' => false, 'redirect' => $workshopUrl])
                    : redirect()->to($workshopUrl);
            }
        }

        // Default redirect to dashboard for other users
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(config('fortify.home'));
    }
}
