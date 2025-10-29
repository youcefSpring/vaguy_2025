@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-80 pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="d-flex justify-content-center">
                        <div class="verification-code-wrapper">
                            <div class="verification-area">
                                <h5 class="border-bottom mb-3 pb-3 text-center">@lang('Verify Email Address')</h5>
                                <form action="{{ localized_route('influencer.password.verify.code') }}" method="POST" class="submit-form">
                                    @csrf
                                    <p>@lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}</p>
                                    <input type="hidden" name="email" value="{{ $email }}">

                                    @include($activeTemplate . 'partials.verification_code')

                                    <div class="form-group">
                                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                    </div>

                                    <div class="form-group mt-2">
                                        @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                        <a href="{{ localized_route('influencer.password.request') }}" class="text--base">@lang('Try to send again')</a>
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
