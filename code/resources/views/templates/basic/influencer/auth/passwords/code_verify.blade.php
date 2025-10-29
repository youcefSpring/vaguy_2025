@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-80 pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="d-flex justify-content-center">
                        <div class="verification-code-wrapper">
                            <div class="verification-area">
                                <h5 class="border-bottom mb-3 pb-3 text-center">@lang('التحقق من عنوان البريد الإلكتروني')</h5>
                                <form action="{{ localized_route('influencer.password.verify.code') }}" method="POST" class="submit-form">
                                    @csrf
                                    <p>@lang('تم إرسال رمز التحقق المكون من 6 أرقام إلى عنوان بريدك الإلكتروني') : {{ showEmailAddress($email) }}</p>
                                    <input type="hidden" name="email" value="{{ $email }}">

                                    @include($activeTemplate . 'partials.verification_code')

                                    <div class="form-group">
                                        <button type="submit" class="btn btn--base w-100">@lang('حفظ')</button>
                                    </div>

                                    <div class="form-group mt-2">
                                        @lang('إذا لم يتم العثور عليها ، يمكنكJunk/Spam يرجى التحقق من تضمين مجلد ')
                                        <a href="{{ localized_route('influencer.password.request') }}" class="text--base">@lang('حاول الإرسال مرة أخرى')</a>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
