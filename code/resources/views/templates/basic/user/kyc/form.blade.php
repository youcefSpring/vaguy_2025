@extends('layouts.dashboard')
@section('content')

@php
$kycContent = getContent('client_kyc.content', true);
@endphp

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body">
                    @if (auth()->user()->kv == 0)
                    <div class="alert alert-info mb-4" role="alert">
                        <h4 class="alert-heading">@lang('KYC التحقق من')</h4>
                        <hr>
                        <p class="mb-0">{{ __($kycContent->data_values->verification_content) }}</p>
                    </div>
                    @endif
                    <form action="{{ localized_route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <x-viser-form identifier="act" identifierValue="kyc"></x-viser-form>
                        </div>
                        <button type="submit" class="btn btn--base w-100">@lang('حفظ')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
