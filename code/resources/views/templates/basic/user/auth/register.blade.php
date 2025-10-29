@extends($activeTemplate . 'layouts.app')

@section('app')
@php
    $policyPages = getContent('policy_pages.element', false, null, true);
    $register = getContent('user_register.content', true);
    $registerInfluencer = getContent('influencer_register.content', true);
@endphp

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        padding: 20px 0;
    }

    .form-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 700px;
        width: 90%;
        margin: 20px auto;
        overflow: hidden;
        max-height: 95vh;
        overflow-y: auto;
    }

    .form-header {
        background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);
        padding: 25px 20px;
        text-align: center;
        color: white;
    }

    .form-header h1 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 5px 0;
    }

    .form-header p {
        font-size: 13px;
        opacity: 0.95;
        margin: 0;
    }

    .form-body {
        padding: 25px 25px 20px;
    }

    .account-type-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .account-type-btn {
        flex: 1;
        padding: 10px 16px;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
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
        margin-bottom: 12px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: #374151;
        margin-bottom: 5px;
    }

    .form-control {
        width: 100%;
        padding: 9px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
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

    .input-group {
        display: flex;
    }

    .input-group-text {
        background: #f3f4f6;
        border: 2px solid #e5e7eb;
        border-right: none;
        padding: 12px 16px;
        border-radius: 10px 0 0 10px;
        font-weight: 600;
        color: #6b7280;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(155, 135, 245, 0.4);
        margin-top: 5px;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(155, 135, 245, 0.5);
    }

    .google-btn {
        width: 100%;
        padding: 11px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        color: #374151;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 15px;
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
        margin: 15px 0;
        color: #9ca3af;
        font-size: 13px;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e5e7eb;
    }

    .divider span {
        padding: 0 12px;
    }

    .login-link {
        text-align: center;
        margin-top: 15px;
        font-size: 13px;
        color: #6b7280;
    }

    .login-link a {
        color: #9b87f5;
        font-weight: 600;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    .custom--checkbox {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 12px 0;
    }

    .custom--checkbox input[type="checkbox"] {
        margin-top: 3px;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .custom--checkbox label {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.4;
        margin: 0;
    }

    .custom--checkbox label a {
        color: #9b87f5;
        font-weight: 600;
        text-decoration: none;
    }

    .custom--checkbox label a:hover {
        text-decoration: underline;
    }

    .security-badge {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 8px;
        padding: 10px 12px;
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .security-badge i {
        color: #22c55e;
        font-size: 18px;
    }

    .security-badge p {
        margin: 0;
        font-size: 12px;
        color: #16a34a;
        font-weight: 500;
    }

    .text-danger, .text-info {
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .input-popup {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        margin-top: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .input-popup p {
        margin: 6px 0;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .input-popup p::before {
        content: '✗';
        color: #ef4444;
        font-weight: bold;
    }

    .input-popup p.success::before {
        content: '✓';
        color: #22c55e;
    }

    .hover-input-popup .input-popup {
        display: block;
    }

    .form-group:not(.hover-input-popup) .input-popup {
        display: none;
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
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .col-md-6 {
        width: 50%;
        padding: 0 10px;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
        }
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1 class="this-page-title">@lang('auth.create_account')</h1>
        <p>@lang('auth.join_our_community')</p>
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

        <!-- Registration Form -->
        <form action="{{ localized_route('user.register') }}" method="POST" class="verify-gcaptcha">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="username">@lang('auth.username')</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control checkUser" required minlength="6">
                        <small class="text-danger usernameExist"></small>
                        <small class="text-info username-info" style="display: none;">@lang('auth.username_min_length')</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="email">@lang('auth.email')</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control checkUser" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="gender">@lang('auth.gender')</label>
                        <select name="gender" class="form-control">
                            <option value="man">@lang('auth.male')</option>
                            <option value="woman">@lang('auth.female')</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="country">@lang('auth.country')</label>
                        <select name="country" class="form-control">
                            @foreach ($countries as $key => $country)
                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}"
                                {{ strtolower($country->country) === 'algeria' ? 'selected' : '' }}>
                                    {{ __($country->country) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="mobile">@lang('auth.mobile')</label>
                        <div class="input-group">
                            <span class="input-group-text mobile-code"></span>
                            <input type="hidden" name="mobile_code">
                            <input type="hidden" name="country_code">
                            <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control checkUser" required>
                        </div>
                        <small class="text-danger mobileExist"></small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="password">@lang('auth.password')</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <i class="las la-eye password-toggle" data-target="password"></i>
                        </div>
                        @if ($general->secure_password)
                            <div class="input-popup">
                                <p class="error lower">@lang('auth.password_lowercase')</p>
                                <p class="error capital">@lang('auth.password_uppercase')</p>
                                <p class="error number">@lang('auth.password_number')</p>
                                <p class="error special">@lang('auth.password_special')</p>
                                <p class="error minimum">@lang('auth.password_min_length')</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="password_confirm">@lang('auth.confirm_password')</label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirm" name="password_confirmation" class="form-control" required>
                            <i class="las la-eye password-toggle" data-target="password_confirm"></i>
                        </div>
                    </div>
                </div>

                <x-captcha></x-captcha>

                @if ($general->agree)
                    <div class="custom--checkbox">
                        <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                        <label for="agree">@lang('auth.i_agree_to')
                            @foreach ($policyPages as $policy)
                                <a href="{{ localized_route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                    {{ __(@$policy->data_values->title) }}</a>
                                @if (!$loop->last),@endif
                            @endforeach
                        </label>
                    </div>
                @endif
            </div>

            <button type="submit" id="recaptcha" class="submit-btn">@lang('auth.create_account')</button>
        </form>

        <div class="divider">
            <span>@lang('auth.or')</span>
        </div>

        <!-- Google OAuth Button -->
        <a href="{{ localized_route('auth.google.redirect', ['type' => 'user']) }}" class="google-btn google-oauth-btn">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                <path d="M19.8 10.2273C19.8 9.51818 19.7364 8.83636 19.6182 8.18182H10.2V12.05H15.5818C15.3364 13.3 14.5909 14.3591 13.4727 15.0682V17.5773H16.7636C18.7182 15.8364 19.8 13.2727 19.8 10.2273Z" fill="#4285F4"/>
                <path d="M10.2 20C12.9 20 15.1727 19.1045 16.7636 17.5773L13.4727 15.0682C12.5227 15.6682 11.2909 16.0227 10.2 16.0227C7.59545 16.0227 5.38182 14.2636 4.54091 11.9H1.14545V14.4909C2.72727 17.6318 6.19091 20 10.2 20Z" fill="#34A853"/>
                <path d="M4.54091 11.9C4.32727 11.3 4.20455 10.6591 4.20455 10C4.20455 9.34091 4.32727 8.7 4.54091 8.1V5.50909H1.14545C0.418182 6.95909 0 8.43182 0 10C0 11.5682 0.418182 13.0409 1.14545 14.4909L4.54091 11.9Z" fill="#FBBC05"/>
                <path d="M10.2 3.97727C11.3909 3.97727 12.4409 4.38182 13.2682 5.17273L16.1818 2.25909C15.1682 1.34091 12.9 0 10.2 0C6.19091 0 2.72727 2.36818 1.14545 5.50909L4.54091 8.1C5.38182 5.73636 7.59545 3.97727 10.2 3.97727Z" fill="#EA4335"/>
            </svg>
            @lang('auth.register_with_google')
        </a>

        <!-- Security Badge -->
        <div class="security-badge">
            <i class="las la-shield-alt"></i>
            <p>@lang('auth.security_note')</p>
        </div>

        <!-- Login Link -->
        <div class="login-link">
            @lang('auth.already_have_account')
            <a href="{{ localized_route('user.login') }}" class="login-url">@lang('auth.login_here')</a>
        </div>
    </div>
</div>

<!-- Existing User Modal -->
<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existModalLongTitle">@lang('auth.are_you_with_us')</h5>
                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <div class="modal-body">
                <h6 class="text-center">@lang('auth.you_have_account_please_login')</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn--sm" data-bs-dismiss="modal">@lang('auth.close')</button>
                <a href="{{ localized_route('user.login') }}" class="btn btn--base btn--sm">@lang('auth.login')</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobile_code)
                $(`option[data-code={{ $mobile_code }}]`).attr('selected', '');
            @endif

            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            @if ($general->secure_password)
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });

                $('[name=password]').focus(function() {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });

                $('[name=password]').focusout(function() {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif

            // Password visibility toggle
            $('.password-toggle').on('click', function() {
                const target = $(this).data('target');
                const input = $('#' + target);
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                $(this).toggleClass('la-eye la-eye-slash');
            });

            // Username validation
            $('#username').on('input', function() {
                const username = $(this).val();
                const usernameInfo = $('.username-info');

                if (username.length > 0 && username.length < 6) {
                    usernameInfo.show();
                } else {
                    usernameInfo.hide();
                }
            });

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ localized_route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        let errorMessage = response.type === 'username' ? '@lang("auth.username_exists")' :
                                         response.type === 'email' ? '@lang("auth.email_exists")' :
                                         response.type === 'mobile' ? '@lang("auth.mobile_exists")' :
                                         `${response.type} @lang("auth.already_exists")`;
                        $(`.${response.type}Exist`).text(errorMessage);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });

            // Set default to client registration
            let defaultAction = `{{ localized_route('user.register') }}`;
            let defaultLoginUrl = `{{ localized_route('user.login') }}`;
            let defaultPageTitle = `@lang('auth.create_account')`;
            let defaultGoogleUrl = `{{ localized_route('auth.google.redirect', ['type' => 'user']) }}`;

            $('form')[0].action = defaultAction;
            $('.login-url').attr('href', defaultLoginUrl);
            $('.this-page-title').text(defaultPageTitle);
            $('.google-oauth-btn').attr('href', defaultGoogleUrl);

            $('.actionBtn').on('click', function() {
                let action;
                let loginUrl;
                let pageTitle;
                let googleUrl;

                if ($(this).data('type') == 'influencer') {
                    action = `{{ localized_route('influencer.register') }}`;
                    loginUrl = `{{ localized_route('influencer.login') }}`;
                    pageTitle = `@lang('auth.create_influencer_account')`;
                    googleUrl = `{{ localized_route('auth.google.redirect', ['type' => 'influencer']) }}`;
                } else {
                    action = `{{ localized_route('user.register') }}`;
                    loginUrl = `{{ localized_route('user.login') }}`;
                    pageTitle = `@lang('auth.create_account')`;
                    googleUrl = `{{ localized_route('auth.google.redirect', ['type' => 'user']) }}`;
                }

                $('form')[0].action = action;
                $(this).addClass('active');
                $('.login-url').attr('href', loginUrl);
                $('.this-page-title').text(pageTitle);
                $('.google-oauth-btn').attr('href', googleUrl);
                $('.actionBtn').not($(this)).removeClass('active');
            });

        })(jQuery);
    </script>
@endpush
