<?php

use Illuminate\Support\Facades\Route;

// Temporary diagnostic route - access at /en/check-admin-auth
Route::get('/check-admin-auth', function() {
    $output = [];

    $output[] = '=== ADMIN AUTHENTICATION STATUS ===';
    $output[] = '';

    // Check if admin is authenticated
    $isAdminLoggedIn = auth()->guard('admin')->check();
    $output[] = 'Admin Logged In: ' . ($isAdminLoggedIn ? 'YES ✅' : 'NO ❌');

    if ($isAdminLoggedIn) {
        $admin = auth()->guard('admin')->user();
        $output[] = 'Admin ID: ' . $admin->id;
        $output[] = 'Admin Username: ' . $admin->username;
        $output[] = 'Admin Email: ' . $admin->email;
    } else {
        $output[] = 'No admin session found';
    }

    $output[] = '';
    $output[] = '=== OTHER GUARDS ===';

    // Check web guard
    $output[] = 'Web Guard (User): ' . (auth()->guard('web')->check() ? 'Logged in as ' . auth()->guard('web')->user()->username : 'Not logged in');

    // Check influencer guard
    $output[] = 'Influencer Guard: ' . (auth()->guard('influencer')->check() ? 'Logged in as ' . auth()->guard('influencer')->user()->username : 'Not logged in');

    $output[] = '';
    $output[] = '=== SESSION INFO ===';
    $output[] = 'Session ID: ' . session()->getId();
    $output[] = 'Session Driver: ' . config('session.driver');

    $output[] = '';
    $output[] = '=== NEXT STEPS ===';
    if (!$isAdminLoggedIn) {
        $output[] = '1. Go to: ' . url(app()->getLocale() . '/admin');
        $output[] = '2. Login with admin credentials';
        $output[] = '3. After login, come back to this page to verify';
        $output[] = '4. Then try accessing the detail pages';
    } else {
        $output[] = 'Admin is logged in! Detail pages should work now.';
        $output[] = 'Try: ' . url(app()->getLocale() . '/admin/clients/detail/117');
        $output[] = 'Try: ' . url(app()->getLocale() . '/admin/influencers/detail/655');
    }

    return '<pre>' . implode("\n", $output) . '</pre>';
})->name('check.admin.auth');
