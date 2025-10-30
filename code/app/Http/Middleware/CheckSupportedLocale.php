<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CheckSupportedLocale
{
    /**
     * Handle an incoming request.
     * Redirects unsupported locales to the default locale while preserving the path.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the first segment of the URL (potential locale)
        $locale = $request->segment(1);

        // Get supported locales
        $supportedLocales = array_keys(LaravelLocalization::getSupportedLocales());

        // If first segment looks like a locale but isn't supported
        if ($locale && strlen($locale) === 2 && !in_array($locale, $supportedLocales)) {
            // Get default locale
            $defaultLocale = config('app.locale', 'en');

            // Get the rest of the path (after the locale segment)
            $segments = $request->segments();
            array_shift($segments); // Remove the unsupported locale
            $path = implode('/', $segments);

            // Preserve query string
            $query = $request->getQueryString();
            $queryString = $query ? '?' . $query : '';

            // Redirect to default locale with the same path
            $newUrl = '/' . $defaultLocale . ($path ? '/' . $path : '') . $queryString;

            return redirect($newUrl, 301);
        }

        return $next($request);
    }
}
