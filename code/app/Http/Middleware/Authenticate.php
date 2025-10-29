<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Determine which login page to redirect to based on URL prefix
            if ($request->is('client/*') || $request->is('*/client/*')) {
                return localized_route('user.login');
            } elseif ($request->is('influencer/*') || $request->is('*/influencer/*')) {
                return localized_route('influencer.login');
            } elseif ($request->is('admin/*') || $request->is('*/admin/*')) {
                return localized_route('admin.login');
            }

            // Default fallback to general login
            return localized_route('login');
        }
    }
}
