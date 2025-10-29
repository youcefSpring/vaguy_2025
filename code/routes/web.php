<?php

use App\Lib\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ScrapController;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Jobs\SendEmailJob;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;



Route::post('/test_mail/{details}/{destinations}',[SiteController::class,'send_campain_email_notification'])->name('send_campain_email_notification');

// Google OAuth redirect route (callback is in RouteServiceProvider, non-localized)
Route::get('auth/google/redirect/{type}', [App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
// Legacy Inertia route removed - now using Blade views
Route::controller('Admin\ManageInfluencersController')->group(function () {

    Route::put('admin/infUpdate/{id}', 'statisticInfluencersUpdate')->name('statisticInfluencersUpdate');;

});

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('getfile/{filename}', [SiteController::class,'getFile'])->name('getfile');
Route::get('downloadInfluencerFile/{id}', [SiteController::class,'download_influencer_file'])->name('public.download_influencer_file');

    Route::view('insta','home');
    Route::get('getInfo/{username}',[SocialMediaController::class,'getInfo'])->name('getInfo');

    Route::get('getinf',[SiteController::class,'influencers'])->name('influencers');
    Route::post('getinf',[SiteController::class,'influencers'])->name('influencersFilter');

    Route::get('influencers',[SiteController::class,'influencers'])->name('influencers.filter.front');
    Route::get('influencers2',[SiteController::class,'influencers2'])->name('influencers2');

    Route::post('senddata',[SocialMediaController::class,'index'])->name('senddata');

    // User Support Ticket
    Route::controller('TicketController')->prefix('ticket')->group(function () {
        Route::get('/', 'supportTicket')->name('ticket');
        Route::get('/new', 'openSupportTicket')->name('ticket.open');
        Route::get('/new/{id}', 'openSupportTicket')->name('ticket.open_type');
        Route::post('/create', 'storeSupportTicket')->name('ticket.store');
        Route::get('/view/{ticket}', 'viewTicket')->name('ticket.view');
        Route::post('/reply/{id}', 'replyTicket')->name('ticket.reply');
        Route::post('/close/{ticket}', 'closeTicket')->name('ticket.close');
        Route::get('/download/{ticket}', 'ticketDownload')->name('ticket.download');
    });

    Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');
    Route::get('/contact', [SiteController::class,'contact'])->name('contact');

    // This route is now handled by the user.php routes file
    // Route::get('/client/dashboard') is defined there

    Route::controller('SiteController')->group(function () {
        // Route::get('/contact', 'contact')->name('contact');
        Route::post('/contact', 'contactSubmit');
        Route::get('/login', 'login')->name('login');
        Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

        Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
        Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

        Route::get('/services', 'services')->name('services');
        Route::post('/services', 'services')->name('servicesFilter');
        Route::get('/services2', 'services2')->name('services2');
        Route::get('service/tags/{id}/{name}', 'serviceByTag')->name('service.tag');

        Route::post('service/filtered', 'filterService')->name('service.filter');
        Route::get('service/{slug}/{id}/{order_id?}', 'serviceDetails')->name('service.details');

        // Route::get('influencers', 'influencers')->name('influencers');
        Route::get('influencers/category/{id}/{name}', 'influencerByCategory')->name('influencer.category');
        Route::get('influencer/filtered', 'filterInfluencer')->name('influencer.filter');
        Route::post('influencer/filtered', 'filterInfluencer_new')->name('influencer.new_filter');

        Route::get('/influencer/profile/{id}', 'influencerProfile')->name('influencer.profile')->where('id', '[0-9]+');

        Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

        Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
        Route::get('attachment/download/{attachment}/{conversation_id}/{type?}', 'attachmentDownload')->name('attachment.download');

        Route::get('/', 'index')->name('home');

        // Route de test pour vérifier la sidebar
        Route::get('/test-sidebar', function () {
            return view('test-sidebar');
        })->name('test.sidebar');

        // Route de test pour vérifier les campagnes
        Route::get('/test-campaigns', function () {
            return view('test-campaigns');
        })->name('test.campaigns');

        Route::get('/{slug}', 'pages')->name('pages');
    });

// Non-localized routes (scrapers and admin utilities)
Route::get('/scrap_fb',[ScrapController::class,'scrap_fb']);
Route::get('/scrapeProfileInstagram',[ScrapController::class,'scrapeProfileInstagram']);
Route::get('/get_instagram_user_names',[ScrapController::class,'get_instagram_user_names']);



// Test route for localization
Route::get('/test-locale', function() {
    return response()->json([
        'current_locale' => app()->getLocale(),
        'url' => url()->current(),
        'route_locale' => request()->route('locale') ?? 'not set'
    ]);
})->name('test.locale');
