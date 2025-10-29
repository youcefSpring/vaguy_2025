@extends($activeTemplate . 'layouts.master')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Campain Name')</th>
                            <th>@lang('Client')</th>
                             <th>@lang('Objective')</th>
                             <th>@lang('Status')</th>
                            <th>@lang('Details')</th>
                            {{--<th>@lang('Delivery Date')</th>
                            @if(request()->routeIs('admin.hiring.index'))
                            <th>@lang('الوضع')</th>
                            @endif --}}
                            <th>@lang('النشاط')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($hirings as $hiring)
                            <tr>
                                <td data-label="@lang('عدد الوظائف')">
                                    <span class="fw-bold">{{ $hiring->campain_name }}</span>
                                </td>

                                <td data-label="@lang('المستخدم')">
                                    {{-- <span class="small">
                                        <a href="{{ localized_route('admin.users.detail', $hiring->user_id) }}"><span>@</span>{{ @$hiring->user->username }}</a>
                                    </span> --}}
                                    {{ @$hiring->user->firstname }}
                                    {{ @$hiring->user->lastname }}
                                </td>

                                <td data-label="@lang('هدف الحملة')">
                                    <span class="small">
                                        <span class="fw-bold">{{ $hiring->campain_objective }}</span>
                                    </span>
                                </td>

                                <td data-label="@lang('حالة')">
                                    <span class="small">
                                        @php
                                            $of=\App\Models\CampainInfluencerOffer::where('influencer_id',authInfluencerId())->where('campain_id',$hiring->id)->first();
                                        @endphp
                                        @if (isset($of))
                                        <span class="fw-bold">{{ status_to_letters( $of->status) }}</span>
                                        @endif

                                    </span>
                                </td>
                                <td data-label="@lang('تفاصيل الحملة')">
                                    <span class="small">
                                        <span class="fw-bold">{{ $hiring->campain_details }}</span>
                                    </span>
                                </td>


                                <td data-label="@lang('النشاط')">
                                    {{-- <a href="{{ localized_route('influencer.campain.detail', $hiring->id) }}" class="btn btn-sm btn-outline--primary">
                                        <i class="las la-edit text--shadow"></i> @lang('التفاصيل')
                                    </a> --}}
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        <a href="{{ localized_route('influencer.campain.detail', $hiring->id) }}" class="btn btn--sm btn--outline-base">
                                            <i class="la la-info-circle "></i> @lang('التفاصيل')
                                        </a>

                                        {{-- <a href="{{ localized_route('influencer.service.orders', $service->id) }}" class="btn btn--sm btn--outline-info @if ($service->status != 1) disabled @endif">
                                            <i class="las la-list-ul"></i> @lang('الأوامر')
                                        </a> --}}
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($hirings->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($hirings) }}
            </div>
            @endif
        </div>
    </div>


</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection
@push('style')
<style>
    .status-square {
        background: #1ca0f278;
        border: #1ca0f278;
    }

    .status-times {
        background: #ea535378;
        border: #ea535378;
    }

    .nav-link {
        color: rgb(var(--base));
    }
</style>
@endpush
