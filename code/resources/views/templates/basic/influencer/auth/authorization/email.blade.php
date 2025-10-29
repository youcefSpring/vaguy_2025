@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-80 pb-80">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <h5 class="border-bottom pb-3 text-center">@lang('التحقق من عنوان البريد الإلكتروني')</h5>
                        <form action="{{ localized_route('influencer.verify.email') }}" method="POST" class="submit-form">
                            @csrf
                            <p class="mt-3">@lang('تم إرسال رمز التحقق المكون من 6 أرقام إلى عنوان بريدك الإلكتروني'): {{ showEmailAddress(authInfluencer()->email) }}</p>

                            @include($activeTemplate . 'partials.verification_code')

                            <div class="my-3">
                                <button type="submit" class="cmn--btn w-100">@lang('حفظ')</button>
                            </div>

                            <div class="mb-3">
                                <p>
                                    @lang('إذا لم تحصل على أي رمز'), <a href="{{ localized_route('influencer.send.verify.code', 'email') }}" class="text--base"> @lang('حاول مرة أخرى')</a>
                                </p>

                                @if ($errors->has('resend'))
                                    <small class="text-danger d-block">{{ $errors->first('resend') }}</small>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
