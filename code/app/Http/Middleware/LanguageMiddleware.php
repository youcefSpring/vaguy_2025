<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * This middleware sets the app locale from the URL or session.
     * It runs before LaravelLocalization middleware, so we detect locale manually.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Try to get locale from URL (first segment)
        $firstSegment = $request->segment(1);
        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', []));

        // If first segment is a valid locale, use it
        if ($firstSegment && in_array($firstSegment, $supportedLocales)) {
            $locale = $firstSegment;
            session()->put('lang', $locale);
            app()->setLocale($locale);
        } else {
            // Otherwise use session or database default
            $locale = $this->getCode();
            session()->put('lang', $locale);
            app()->setLocale($locale);
        }

        return $next($request);
    }

    public function getCode()
    {
        // First check session
        if (session()->has('lang')) {
            return session('lang');
        }

        // Then get default from database
        $language = Language::where('is_default', 1)->first();
        return $language ? $language->code : 'en';
    }
}
