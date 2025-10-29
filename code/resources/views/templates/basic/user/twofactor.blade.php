@extends('layouts.dashboard')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">@lang('أمان العاملين 2FA')</h2>
        <p class="text-gray-600 mt-2">@lang('تأمين حسابك بالتحقق المزدوج')</p>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @if (!auth()->user()->ts)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">@lang('أضف حسابك')</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        @lang('استخدم رمز QR أو مفتاح الإعداد في تطبيق Google Authenticator لإضافة حسابك')
                    </p>

                    <div class="flex justify-center mb-6">
                        <div class="p-4 bg-white border border-gray-200 rounded-lg">
                            <img src="{{ $qrCodeUrl }}" alt="QR Code" class="max-w-full h-auto">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">@lang('مفتاح الإعداد')</label>
                        <div class="flex">
                            <input type="text" name="key" value="{{ $secret }}" class="input rounded-r-none referralURL" readonly>
                            <button type="button" class="btn btn-primary rounded-l-none" id="copyBoard">
                                <i data-lucide="copy" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5 mr-3"></i>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800 mb-2">@lang('المساعدة')</h4>
                                <p class="text-sm text-blue-700">
                                    @lang('Google Authenticator هو تطبيق متعدد العوامل للأجهزة المحمولة. يقوم بإنشاء رموز موقوتة تُستخدم أثناء عملية التحقق المكونة من خطوتين. لاستخدام Google Authenticator ، قم بتثبيت تطبيق Google Authenticator على جهازك المحمول.')
                                    <a class="text-blue-600 hover:text-blue-800 underline" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('تحميل')</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div>

            @if (auth()->user()->ts)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">@lang('2FA تعطيل أمن ')</h3>
                    </div>
                    <form action="{{ localized_route('user.twofactor.disable') }}" method="POST">
                        <div class="p-6">
                            @csrf
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Google Authenticatior OTP')</label>
                                <input type="text" class="input" name="code" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-full">@lang('حفظ')</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">@lang('2FA تفعيل أمن ')</h3>
                    </div>
                    <form action="{{ localized_route('user.twofactor.enable') }}" method="POST">
                        <div class="p-6">
                            @csrf
                            <input type="hidden" name="key" value="{{ $secret }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Google Authenticatior OTP')</label>
                                <input type="text" class="input" name="code" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-full">@lang('حفظ')</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .copied {
            background-color: #10b981 !important;
            color: white !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('copyBoard').addEventListener('click', function() {
                const copyText = document.querySelector('.referralURL');
                copyText.select();
                copyText.setSelectionRange(0, 99999);

                navigator.clipboard.writeText(copyText.value).then(() => {
                    this.classList.add('copied');
                    setTimeout(() => this.classList.remove('copied'), 1500);
                    showToast('@lang("تم نسخ المفتاح بنجاح")', 'success');
                }).catch(() => {
                    // Fallback for older browsers
                    document.execCommand('copy');
                    copyText.blur();
                    this.classList.add('copied');
                    setTimeout(() => this.classList.remove('copied'), 1500);
                    showToast('@lang("تم نسخ المفتاح بنجاح")', 'success');
                });
            });
        });
    </script>
@endpush
