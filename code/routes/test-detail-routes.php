<?php

use Illuminate\Support\Facades\Route;

// Test routes to diagnose the issue
Route::prefix('test-detail')->group(function() {

    // Test 1: Can we reach a simple route in admin area?
    Route::get('/simple', function() {
        return 'Simple test route works! ✅';
    });

    // Test 2: Can we reach a route with same pattern as detail routes?
    Route::get('/clients/detail/{id}', function($id) {
        return "Test client detail route works! ID: $id ✅";
    });

    Route::get('/influencers/detail/{id}', function($id) {
        return "Test influencer detail route works! ID: $id ✅";
    });

    // Test 3: Can we call the actual controller?
    Route::get('/real-client-detail/{id}', function($id) {
        try {
            $controller = new App\Http\Controllers\Admin\ManageUsersController();
            $user = App\Models\User::findOrFail($id);
            return "Controller works! User: {$user->username} ✅";
        } catch (\Exception $e) {
            return "ERROR: " . $e->getMessage();
        }
    });

    Route::get('/real-influencer-detail/{id}', function($id) {
        try {
            $controller = new App\Http\Controllers\Admin\ManageInfluencersController();
            $influencer = App\Models\Influencer::findOrFail($id);
            return "Controller works! Influencer: {$influencer->username} ✅";
        } catch (\Exception $e) {
            return "ERROR: " . $e->getMessage();
        }
    });

    // Test 4: Authentication check
    Route::get('/auth-check', function() {
        $output = [];
        $output[] = 'Admin logged in: ' . (auth()->guard('admin')->check() ? 'YES ✅' : 'NO ❌');

        if (auth()->guard('admin')->check()) {
            $output[] = 'Admin: ' . auth()->guard('admin')->user()->username;
        }

        $output[] = '';
        $output[] = 'Now try accessing:';
        $output[] = app()->getLocale() . '/admin/clients/detail/117';
        $output[] = app()->getLocale() . '/admin/influencers/detail/655';

        return '<pre>' . implode("\n", $output) . '</pre>';
    });
});
