@extends('layouts.dashboard')
@section('content')
@php
$kycContent = getContent('client_kyc.content', true);
@endphp
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body">

                    @if(auth()->user()->kv == 2)
                    <div class="alert alert-warning mb-4" role="alert">
                        <p class="mb-0"> {{ __($kycContent->data_values->pending_content) }}</p>
                    </div>
                    @endif

                    @if ($user->kyc_data)
                        <h5 class="my-3 text-center "> @lang('البيانات المقدمة للتحقق من KYC')</h5>
                        <ul class="list-group-flush">
                            @foreach ($user->kyc_data as $val)
                                @continue(!$val->value)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">
                                        {{ __($val->name) }}
                                    </span>
                                    <span>
                                        @if ($val->type == 'checkbox')
                                            {{ implode(',', $val->value) }}
                                        @elseif($val->type == 'file')
                                            <a href="{{ localized_route('user.attachment.download', encrypt(getFilePath('verify') . '/' . $val->value)) }}"
                                                class="text--base me-3">
                                                <i class="fa fa-file"></i> @lang('مرفق')
                                            </a>
                                        @else
                                            <p>{{ __($val->value) }}</p>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <h5 class="text-center">@lang('لم يتم العثور على بيانات')</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
