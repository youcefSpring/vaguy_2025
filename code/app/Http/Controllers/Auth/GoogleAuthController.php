<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Influencer;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class GoogleAuthController extends Controller
{
    public function redirect($type = 'user')
    {
        session(['auth_type' => $type]);
        session(['auth_locale' => LaravelLocalization::getCurrentLocale()]);
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $authType = session('auth_type', 'user');
            $locale = session('auth_locale', config('app.locale'));

            if ($authType === 'influencer') {
                $user = Influencer::where('email', $googleUser->email)->first();
                $guard = 'influencer';
                $model = Influencer::class;
                $redirect = 'influencer.home';
            } else {
                $user = User::where('email', $googleUser->email)->first();
                $guard = 'web';
                $model = User::class;
                $redirect = 'user.home';
            }

            if ($user) {
                // User exists, log them in
                Auth::guard($guard)->login($user);
                $notify[] = ['success', __('auth.login_success')];

                // Get the localized dashboard URL
                $dashboardUrl = LaravelLocalization::getLocalizedURL($locale, '/' . ($authType === 'influencer' ? 'influencer' : 'client') . '/dashboard');
                return redirect()->to($dashboardUrl)->withNotify($notify);
            }

            // Create new user
            $username = $this->generateUsername($googleUser->name);

            $userData = [
                'username' => $username,
                'firstname' => $googleUser->user['given_name'] ?? $username,
                'lastname' => $googleUser->user['family_name'] ?? '',
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'ev' => 1, // Email verified
                'sv' => 1, // SMS verified (if applicable)
                'reg_step' => 1, // Mark registration as complete to skip profile completion page
                'password' => bcrypt(Str::random(16)),
                'country_code' => 'US',
                'mobile' => '',
            ];

            if ($authType === 'influencer') {
                $userData['profile_complete'] = 0;
            }

            $user = $model::create($userData);

            Auth::guard($guard)->login($user);

            $notify[] = ['success', __('auth.account_created_via_google')];

            // Redirect to dashboard or profile completion
            $dashboardUrl = LaravelLocalization::getLocalizedURL($locale, '/' . ($authType === 'influencer' ? 'influencer' : 'client') . '/dashboard');
            return redirect()->to($dashboardUrl)->withNotify($notify);

        } catch (\Exception $e) {
            $notify[] = ['error', __('auth.google_auth_failed') . ': ' . $e->getMessage()];

            // Get the localized login URL
            $locale = session('auth_locale', config('app.locale'));
            $loginUrl = LaravelLocalization::getLocalizedURL($locale, '/client/login');
            return redirect()->to($loginUrl)->withNotify($notify);
        }
    }

    private function generateUsername($name)
    {
        $username = Str::slug($name);
        $count = 1;

        while (User::where('username', $username)->exists() ||
               Influencer::where('username', $username)->exists()) {
            $username = Str::slug($name) . $count;
            $count++;
        }

        return $username;
    }
}
