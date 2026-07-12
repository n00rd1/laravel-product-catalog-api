<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected const SUPPORTED_LOCALES = ['en', 'ru'];

    /**
     * Resolve the request locale from the `lang` query parameter or the
     * `Accept-Language` header, falling back to the app default.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang') ?: $request->header('Accept-Language');

        if ($locale) {
            $locale = strtolower(substr($locale, 0, 2));
        }

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
