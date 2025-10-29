@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-80 pb-80">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <h5 class="pb-3 text-center border-bottom">@lang('2FA التحقق من')</h5>
                        <form action="{{ localized_route('influencer.go2fa.verify') }}" method="POST" class="submit-form">
                            @csrf
                            @include($activeTemplate . 'partials.verification_code')
                            <div class="form--group">
                                <button type="submit" class="btn btn--base w-100">@lang('حفظ')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        (function($) {
            "use strict";
            $('#code').on('input change', function() {
                var xx = document.getElementById('code').value;

                $(this).val(function(index, value) {
                    value = value.substr(0, 7);
                    return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
                });

            });
        })(jQuery)
    </script>
@endpush
