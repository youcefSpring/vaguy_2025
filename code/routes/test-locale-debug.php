<?php

use Illuminate\Support\Facades\Route;

// Debug route to check locale status
Route::get('/debug-locale', function() {
    return [
        'url_first_segment' => request()->segment(1),
        'session_lang' => session('lang'),
        'app_locale' => app()->getLocale(),
        'config_locale' => config('app.locale'),
        'supported_locales' => array_keys(config('laravellocalization.supportedLocales', [])),
        'current_url' => url()->current(),
        'test_translation' => __('navbar.dashboard'),
    ];
})->name('debug.locale');
