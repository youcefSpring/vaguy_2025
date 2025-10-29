@extends('layouts.dashboard')
@section('content')
    <div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">@lang('تغيير كلمة المرور')</h3>
    </div>
    <div class="p-6">
        <form action="" method="post" class="space-y-6">
                        @csrf
            <div class="form-group">
                <label for="current_password" class="form-label">@lang('كلمة المرور الحالية')</label>
                <input class="input" name="current_password" id="current_password"
                       type="password" placeholder="@lang('إدخل كلمة السر الحالية')" required>
            </div>
            <div class="form-group">
                <label for="new_password" class="form-label">@lang('كلمة المرور الجديدة')</label>
                <input class="input" name="password" id="new_password"
                       type="password" placeholder="@lang('أدخل كلمة المرور الجديدة')" required autocomplete="off">
                            @if ($general->secure_password)
                <div class="input-popup">
                    <p class="error lower text-xs text-red-600">@lang('1 small letter minimum')</p>
                    <p class="error capital text-xs text-red-600">@lang('1 capital letter minimum')</p>
                    <p class="error number text-xs text-red-600">@lang('1 number minimum')</p>
                    <p class="error special text-xs text-red-600">@lang('1 special character minimum')</p>
                    <p class="error minimum text-xs text-red-600">@lang('6 character password')</p>
                </div>
                            @endif
            </div>
            <div class="form-group">
                <label for="confirm_password" class="form-label">@lang('تأكيد كلمة المرور')</label>
                <input class="input" name="password_confirmation"
                       id="confirm_password" type="password" placeholder="@lang('أدخل تأكيد كلمة المرور')" required>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary px-6">@lang('حفظ')</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script-lib')
    <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
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
        })(jQuery);
    </script>
@endpush
