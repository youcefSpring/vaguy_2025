<?php

/**
 * Test routes for CheckSupportedLocale middleware
 * These routes help verify that unsupported locales redirect properly
 */

use Illuminate\Support\Facades\Route;

// Test route that shows the current locale and supported locales
Route::get('/test-locale-info', function() {
    $currentUrl = request()->fullUrl();
    $firstSegment = request()->segment(1);
    $supportedLocales = array_keys(config('laravellocalization.supportedLocales'));
    $defaultLocale = config('app.locale');

    return response()->json([
        'current_url' => $currentUrl,
        'first_segment' => $firstSegment,
        'is_supported' => in_array($firstSegment, $supportedLocales),
        'supported_locales' => $supportedLocales,
        'default_locale' => $defaultLocale,
    ]);
})->name('test.locale.info');

// Instructions for testing
Route::get('/test-locale-instructions', function() {
    $host = request()->getSchemeAndHttpHost();

    return response()->json([
        'message' => 'Test the CheckSupportedLocale middleware',
        'instructions' => [
            '1. Try supported locales (should work normally)',
            '2. Try unsupported locales (should redirect to default)',
        ],
        'test_urls' => [
            'supported' => [
                "{$host}/en",
                "{$host}/fr",
                "{$host}/ar",
            ],
            'unsupported_will_redirect' => [
                "{$host}/es → {$host}/en",
                "{$host}/de → {$host}/en",
                "{$host}/it → {$host}/en",
                "{$host}/pt → {$host}/en",
            ],
            'with_paths' => [
                "{$host}/es/admin → {$host}/en/admin",
                "{$host}/de/dashboard → {$host}/en/dashboard",
                "{$host}/it/profile → {$host}/en/profile",
            ],
        ],
    ]);
})->name('test.locale.instructions');
