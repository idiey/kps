<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @var array<int, string>
     */
    private array $supportedLocales = ['ms', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = (string) $request->session()->get('locale', config('app.locale', 'ms'));

        if ($request->has('lang')) {
            $queryLocale = (string) $request->query('lang');

            if (in_array($queryLocale, $this->supportedLocales, true)) {
                $locale = $queryLocale;
                $request->session()->put('locale', $queryLocale);
            }
        }

        if (! in_array($locale, $this->supportedLocales, true)) {
            $locale = 'ms';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

