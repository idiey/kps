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

        // Site-scoped users should land on their first assigned KPS site.
        if ($user && $user->isKpsSiteOnly()) {
            $kpsSite = $user->getFirstKpsSite();

            if ($kpsSite) {
                $kpsSiteUrl = route('kps.sites.show', $kpsSite->id);

                return $request->wantsJson()
                    ? response()->json(['two_factor' => false, 'redirect' => $kpsSiteUrl])
                    : redirect()->to($kpsSiteUrl);
            }
        }

        // Default redirect to the main dashboard for HQ users.
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(config('fortify.home'));
    }
}
