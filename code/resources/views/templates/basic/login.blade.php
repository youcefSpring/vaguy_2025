@php
    $loginContent = getContent('login.content', true);
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="account-section pt-80 pb-80">
        <div class="container">
            <div class="account-wrapper">
                <div class="row gy-5 justify-content-center">
                    <div class="col-lg-8">
                        <div class="account-content text-center">
                            <h3 class="mb-4">{{ __(@$loginContent->data_values->title ?? 'تسجيل الدخول') }}</h3>
                            <p class="mb-5">{{ __(@$loginContent->data_values->subtitle ?? 'اختر نوع حسابك للمتابعة') }}</p>

                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="login-option-card">
                                        <div class="card h-100 text-center p-4">
                                            <div class="card-body">
                                                <i class="las la-user-circle display-4 text-primary mb-3"></i>
                                                <h4 class="mb-3">@lang('عميل')</h4>
                                                <p class="mb-4">@lang('تسجيل الدخول كعميل للوصول إلى الخدمات والمؤثرين')</p>
                                                <a href="{{ localized_route('user.login') }}" class="btn btn--base w-100">@lang('تسجيل الدخول كعميل')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="login-option-card">
                                        <div class="card h-100 text-center p-4">
                                            <div class="card-body">
                                                <i class="las la-star display-4 text-warning mb-3"></i>
                                                <h4 class="mb-3">@lang('مؤثر')</h4>
                                                <p class="mb-4">@lang('تسجيل الدخول كمؤثر لإدارة خدماتك وحملاتك')</p>
                                                <a href="{{ localized_route('influencer.login') }}" class="btn btn--outline-base w-100">@lang('تسجيل الدخول كمؤثر')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <p>@lang('ليس لديك حساب؟')</p>
                                <a href="{{ localized_route('user.register') }}" class="text--base me-3">@lang('إنشاء حساب عميل')</a>
                                <span>@lang('أو')</span>
                                <a href="{{ localized_route('influencer.register') }}" class="text--base ms-3">@lang('إنشاء حساب مؤثر')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
.login-option-card .card {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.login-option-card .card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
    transform: translateY(-5px);
}
</style>
@endpush