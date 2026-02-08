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

        // Non-global admins should land on their first assigned workshop (site context)
        if ($user && !$user->hasRole('company_admin')) {
            $workshop = $user->getFirstAssignedWorkshop();

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
