@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-4">

        @if (!authInfluencer()->ts)
            <div class="col-md-6">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="title">@lang('اضافة حساب جديد')</h5>
                    </div>

                    <div class="card-body">
                        <h6 class="mb-3">
                            @lang('لاضافة حسابك Google Authenticator استخدم رمز الاستجابة السريعة أو مفتاح الإعداد في تطبيق')
                        </h6>

                        <div class="form-group mx-auto text-center">
                            <img class="mx-auto" src="{{ $qrCodeUrl }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">@lang('مفتاح الإعدادات')</label>
                            <div class="input-group">
                                <input type="text" name="key" value="{{ $secret }}" class="form-control form--control referralURL" readonly>
                                <button type="button" class="input-group-text copytext" id="copyBoard"> <i class="fa fa-copy"></i> </button>
                            </div>
                        </div>

                        <label><i class="fa fa-info-circle"></i> @lang('المساعدة')</label>
                        <p>@lang('Google Authenticator هو تطبيق متعدد العوامل للأجهزة المحمولة. يقوم بإنشاء رموز موقوتة تُستخدم أثناء عملية التحقق المكونة من خطوتين. لاستخدام Google Authenticator ، قم بتثبيت تطبيق Google Authenticator على جهازك المحمول.') <a class="text--base" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('تحميل')</a></p>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6">

            @if (authInfluencer()->ts)
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="title">@lang('2FA تعطيل أمن')</h5>
                    </div>
                    <form action="{{ localized_route('influencer.twofactor.disable') }}" method="POST">
                        <div class="card-body">
                            @csrf
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Google Authenticatior OTP')</label>
                                <input type="text" class="form-control form--control" name="code" required>
                            </div>
                            <button type="submit" class="btn btn--base w-100">@lang('حفظ')</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="title">@lang('2FA تشغيل أمن')</h5>
                    </div>
                    <form action="{{ localized_route('influencer.twofactor.enable') }}" method="POST">
                        <div class="card-body">
                            @csrf
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Google Authenticatior OTP')</label>
                                <input type="text" class="form-control form--control" name="code" required>
                            </div>
                            <button type="submit" class="btn btn--base w-100">@lang('حفظ')</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style')
    <style>
        .copied::after {
            background-color: #{{ $general->base_color }};
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#copyBoard').click(function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
