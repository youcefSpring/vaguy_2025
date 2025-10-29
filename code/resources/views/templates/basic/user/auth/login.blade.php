@php
    $login = getContent('user_login.content', true);
    $influencerLogin = getContent('influencer_login.content', true);
@endphp

@extends($activeTemplate . 'layouts.app')

@section('app')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .form-container {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 90%;
        margin: 40px auto;
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);
        padding: 40px 30px;
        text-align: center;
        color: white;
    }

    .form-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .form-header p {
        font-size: 14px;
        opacity: 0.95;
        margin: 0;
    }

    .form-body {
        padding: 40px 30px;
    }

    .account-type-selector {
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
    }

    .account-type-btn {
        flex: 1;
        padding: 14px 20px;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #6b7280;
    }

    .account-type-btn:hover {
        border-color: #9b87f5;
        color: #9b87f5;
        transform: translateY(-2px);
    }

    .account-type-btn.active {
        background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);
        border-color: #9b87f5;
        color: white;
        box-shadow: 0 4px 12px rgba(155, 135, 245, 0.4);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        color: #1f2937 !important;
    }

    .form-control:focus {
        outline: none;
        border-color: #9b87f5;
        box-shadow: 0 0 0 4px rgba(155, 135, 245, 0.1);
    }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #9ca3af;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: #9b87f5;
    }

    .submit-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(155, 135, 245, 0.4);
        margin-top: 10px;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(155, 135, 245, 0.5);
    }

    .google-btn {
        width: 100%;
        padding: 14px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        color: #374151;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        text-decoration: none;
    }

    .google-btn:hover {
        border-color: #9b87f5;
        color: #9b87f5;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(155, 135, 245, 0.2);
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 25px 0;
        color: #9ca3af;
        font-size: 14px;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e5e7eb;
    }

    .divider span {
        padding: 0 15px;
    }

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0;
        flex-wrap: wrap;
        gap: 10px;
    }

    .custom--checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .custom--checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .custom--checkbox label {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
        cursor: pointer;
    }

    .forgot-link {
        color: #9b87f5;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    .register-link {
        text-align: center;
        margin-top: 25px;
        font-size: 14px;
        color: #6b7280;
    }

    .register-link a {
        color: #9b87f5;
        font-weight: 600;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    .security-badge {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .security-badge i {
        color: #22c55e;
        font-size: 20px;
    }

    .security-badge p {
        margin: 0;
        font-size: 13px;
        color: #16a34a;
        font-weight: 500;
    }

    @media (max-width: 640px) {
        .form-container {
            width: 95%;
            margin: 20px auto;
        }

        .form-header {
            padding: 30px 20px;
        }

        .form-header h1 {
            font-size: 24px;
        }

        .form-body {
            padding: 30px 20px;
        }

        .account-type-selector {
            flex-direction: column;
        }

        .remember-forgot {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1 class="this-page-title">@lang('auth.login_account')</h1>
        <p>@lang('auth.welcome_back')</p>
    </div>

    <div class="form-body">
        <!-- Account Type Selector -->
        <div class="account-type-selector">
            <button type="button" class="account-type-btn actionBtn active" data-type="client">
                <i class="las la-user"></i> @lang('auth.client')
            </button>
            <button type="button" class="account-type-btn actionBtn" data-type="influencer">
                <i class="las la-star"></i> @lang('auth.influencer')
            </button>
        </div>

        <!-- Google OAuth Button -->
        <a href="{{ localized_route('auth.google.redirect', ['type' => 'user']) }}" class="google-btn google-oauth-btn">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M19.8 10.2273C19.8 9.51818 19.7364 8.83636 19.6182 8.18182H10.2V12.05H15.5818C15.3364 13.3 14.5909 14.3591 13.4727 15.0682V17.5773H16.7636C18.7182 15.8364 19.8 13.2727 19.8 10.2273Z" fill="#4285F4"/>
                <path d="M10.2 20C12.9 20 15.1727 19.1045 16.7636 17.5773L13.4727 15.0682C12.5227 15.6682 11.2909 16.0227 10.2 16.0227C7.59545 16.0227 5.38182 14.2636 4.54091 11.9H1.14545V14.4909C2.72727 17.6318 6.19091 20 10.2 20Z" fill="#34A853"/>
                <path d="M4.54091 11.9C4.32727 11.3 4.20455 10.6591 4.20455 10C4.20455 9.34091 4.32727 8.7 4.54091 8.1V5.50909H1.14545C0.418182 6.95909 0 8.43182 0 10C0 11.5682 0.418182 13.0409 1.14545 14.4909L4.54091 11.9Z" fill="#FBBC05"/>
                <path d="M10.2 3.97727C11.3909 3.97727 12.4409 4.38182 13.2682 5.17273L16.1818 2.25909C15.1682 1.34091 12.9 0 10.2 0C6.19091 0 2.72727 2.36818 1.14545 5.50909L4.54091 8.1C5.38182 5.73636 7.59545 3.97727 10.2 3.97727Z" fill="#EA4335"/>
            </svg>
            @lang('auth.login_with_google')
        </a>

        <div class="divider">
            <span>@lang('auth.or_login_with_email')</span>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ localized_route('user.login') }}" class="verify-gcaptcha">
            @csrf
            <div class="form-group">
                <label class="form-label">@lang('auth.username_or_email')</label>
                <input type="text" name="username" value="{{ old('username') }}" class="form-control" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">@lang('auth.password')</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control" required autocomplete="off">
                    <i class="las la-eye password-toggle" data-target="password"></i>
                </div>
            </div>

            <x-captcha></x-captcha>

            <div class="remember-forgot">
                <div class="custom--checkbox">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">@lang('auth.remember_me')</label>
                </div>
                <a class="forgot-link forgot-url" href="{{ localized_route('user.password.request') }}">@lang('auth.forgot_password')</a>
            </div>

            <button type="submit" id="recaptcha" class="submit-btn">@lang('auth.login')</button>
        </form>

        <!-- Security Badge -->
        <div class="security-badge">
            <i class="las la-shield-alt"></i>
            <p>@lang('auth.security_note')</p>
        </div>

        <!-- Register Link -->
        <div class="register-link">
            @lang('auth.dont_have_account')
            <a href="{{ localized_route('user.register') }}" class="register-url">@lang('auth.create_account')</a>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            // Password visibility toggle
            $('.password-toggle').on('click', function() {
                const target = $(this).data('target');
                const input = $('#' + target);
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                $(this).toggleClass('la-eye la-eye-slash');
            });

            // Set default to client login
            let defaultAction = `{{ localized_route('user.login') }}`;
            let defaultForgotUrl = `{{ localized_route('user.password.request') }}`;
            let defaultRegisterUrl = `{{ localized_route('user.register') }}`;
            let defaultPageTitle = `@lang('auth.login_account')`;
            let defaultGoogleUrl = `{{ localized_route('auth.google.redirect', ['type' => 'user']) }}`;

            $('form')[0].action = defaultAction;
            $('.forgot-url').attr('href', defaultForgotUrl);
            $('.register-url').attr('href', defaultRegisterUrl);
            $('.this-page-title').text(defaultPageTitle);
            $('.google-oauth-btn').attr('href', defaultGoogleUrl);

            $('.actionBtn').on('click', function() {
                let action;
                let forgotUrl;
                let registerUrl;
                let pageTitle;
                let googleUrl;

                if ($(this).data('type') == 'influencer') {
                    action = `{{ localized_route('influencer.login') }}`;
                    forgotUrl = `{{ localized_route('influencer.password.request') }}`;
                    registerUrl = `{{ localized_route('influencer.register') }}`;
                    pageTitle = `@lang('auth.login_influencer_account')`;
                    googleUrl = `{{ localized_route('auth.google.redirect', ['type' => 'influencer']) }}`;
                } else {
                    action = `{{ localized_route('user.login') }}`;
                    forgotUrl = `{{ localized_route('user.password.request') }}`;
                    registerUrl = `{{ localized_route('user.register') }}`;
                    pageTitle = `@lang('auth.login_account')`;
                    googleUrl = `{{ localized_route('auth.google.redirect', ['type' => 'user']) }}`;
                }

                $('form')[0].action = action;
                $('.forgot-url').attr('href', forgotUrl);
                $('.register-url').attr('href', registerUrl);
                $('.this-page-title').text(pageTitle);
                $('.google-oauth-btn').attr('href', googleUrl);
                $(this).addClass('active');
                $('.actionBtn').not($(this)).removeClass('active');
            });

        })(jQuery);
    </script>
@endpush
