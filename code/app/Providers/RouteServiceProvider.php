<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Laramin\Utility\VugiChugi;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */

    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // API routes (no localization)
            Route::prefix('api')
                ->middleware(['api','maintenance'])
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // IPN routes (no localization)
            Route::middleware(['web','maintenance'])
                ->namespace($this->namespace . '\Gateway')
                ->prefix('ipn')
                ->name('ipn.')
                ->group(base_path('routes/ipn.php'));

            // Google OAuth callback (must be non-localized for Google)
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(function () {
                    Route::get('auth/google/callback', 'Auth\GoogleAuthController@callback')
                        ->name('auth.google.callback');
                });

            // All localized routes wrapped with LaravelLocalization
            Route::group([
                'prefix' => '{locale}',
                'where' => ['locale' => implode('|', array_keys(config('laravellocalization.supportedLocales')))],
                'middleware' => ['web', 'localeSessionRedirect', 'localizationRedirectFilter', 'localize']
            ], function() {
                // Admin routes
                Route::namespace($this->namespace . '\Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));

                // Client routes
                Route::middleware('maintenance')
                    ->namespace($this->namespace)
                    ->prefix('client')
                    ->group(base_path('routes/user.php'));

                // Influencer routes
                Route::middleware('maintenance')
                    ->namespace($this->namespace)
                    ->prefix('influencer')
                    ->group(base_path('routes/influencer.php'));

                // Public routes
                Route::middleware('maintenance')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/web.php'));

                // Test/diagnostic routes (temporary)
                if (file_exists(base_path('routes/test-auth.php'))) {
                    require base_path('routes/test-auth.php');
                }
                if (file_exists(base_path('routes/test-detail-routes.php'))) {
                    require base_path('routes/test-detail-routes.php');
                }
            });

            // Locale redirect test routes (temporary - can be removed after testing)
            if (file_exists(base_path('routes/test-locale-redirect.php'))) {
                require base_path('routes/test-locale-redirect.php');
            }

        });

        // Root redirect to localized URL
        Route::get('/', function() {
            return redirect(LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale()));
        });

        Route::get('maintenance-mode','App\Http\Controllers\SiteController@maintenance')->name('maintenance');

        // DEBUG ROUTES (without locale prefix for easier testing)
        Route::prefix('debug')->group(function() {
            Route::get('test1', function() {
                return '<h1>✅ Test 1 Works!</h1><p>Basic routing is functional.</p>';
            });

            Route::get('test2-auth', function() {
                $output = '<h1>Authentication Status</h1>';
                $output .= '<p><strong>Admin Guard:</strong> ' . (auth()->guard('admin')->check() ? '✅ LOGGED IN' : '❌ NOT LOGGED IN') . '</p>';

                if (auth()->guard('admin')->check()) {
                    $admin = auth()->guard('admin')->user();
                    $output .= '<p>Admin ID: ' . $admin->id . '</p>';
                    $output .= '<p>Admin Username: ' . $admin->username . '</p>';
                } else {
                    $output .= '<p style="color: red;">You need to login as admin first!</p>';
                    $output .= '<p>Go to: <a href="/fr/admin">/fr/admin</a> and login</p>';
                }

                return $output;
            });

            Route::get('test3-user/{id}', function($id) {
                try {
                    $user = App\Models\User::findOrFail($id);
                    return '<h1>✅ User Found!</h1><p>ID: ' . $user->id . '</p><p>Username: ' . $user->username . '</p>';
                } catch (\Exception $e) {
                    return '<h1>❌ Error</h1><p>' . $e->getMessage() . '</p>';
                }
            });

            Route::get('test4-influencer/{id}', function($id) {
                try {
                    $influencer = App\Models\Influencer::findOrFail($id);
                    return '<h1>✅ Influencer Found!</h1><p>ID: ' . $influencer->id . '</p><p>Username: ' . $influencer->username . '</p>';
                } catch (\Exception $e) {
                    return '<h1>❌ Error</h1><p>' . $e->getMessage() . '</p>';
                }
            });

            Route::get('test5-real-routes', function() {
                $output = '<h1>Real Route URLs</h1>';
                $output .= '<p>Try these links AFTER logging in as admin:</p>';
                $output .= '<ul>';
                $output .= '<li><a href="/fr/admin/clients/detail/117" target="_blank">/fr/admin/clients/detail/117</a></li>';
                $output .= '<li><a href="/fr/admin/influencers/detail/655" target="_blank">/fr/admin/influencers/detail/655</a></li>';
                $output .= '</ul>';

                $output .= '<p>Current auth status: ' . (auth()->guard('admin')->check() ? '✅ Logged in as ' . auth()->guard('admin')->user()->username : '❌ Not logged in') . '</p>';

                return $output;
            });
        });

        // Catch-all for unsupported locales (must be LAST route)
        // This only triggers if no other routes match
        Route::fallback(function() {
            $firstSegment = request()->segment(1);
            $supportedLocales = array_keys(config('laravellocalization.supportedLocales'));

            // Check if first segment looks like an unsupported locale (2 letters)
            if ($firstSegment && strlen($firstSegment) === 2 && ctype_alpha($firstSegment) && !in_array($firstSegment, $supportedLocales)) {
                $defaultLocale = config('app.locale', 'en');

                // Get the path after the locale
                $segments = request()->segments();
                array_shift($segments); // Remove the unsupported locale
                $path = implode('/', $segments);

                // Build new URL with default locale
                $newUrl = '/' . $defaultLocale . ($path ? '/' . $path : '');

                // Preserve query string
                $query = request()->getQueryString();
                if ($query) {
                    $newUrl .= '?' . $query;
                }

                return redirect($newUrl, 301);
            }

            // Otherwise, return standard 404
            abort(404);
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
