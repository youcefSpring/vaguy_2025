@extends($activeTemplate .'layouts.frontend')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <h5 class="border-bottom pb-3 text-center">@lang('التحقق من رقم الهاتف')</h5>
                    <form action="{{localized_route('influencer.verify.mobile')}}" method="POST" class="submit-form">
                        @csrf
                        <p class="mt-3">@lang('رمز التحقق مكون من 6 أرقام يتم إرساله إلى رقم هاتفك المحمول') :  +{{ showMobileNumber(authInfluencer()->mobile) }}</p>
                        @include($activeTemplate.'partials.verification_code')
                        <div class="mb-3">
                            <button type="submit" class="btn btn--base w-100">@lang('حفظ')</button>
                        </div>
                        <div class="form-group">
                            <p>
                                @lang('إذا لم تحصل على أي رمز'), <a href="{{localized_route('user.send.verify.code', 'phone')}}" class="forget-pass text--base"> @lang('حاول مرة أخرى')</a>
                            </p>
                            @if($errors->has('resend'))
                                <br/>
                                <small class="text-danger">{{ $errors->first('resend') }}</small>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
