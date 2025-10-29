@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="d-flex justify-content-between align-items-center position-relative mb-4 flex-wrap gap-4">
    <span class="filter-toggle btn btn--base btn--sm h-100 d-none"> <i class="i las la-bars"></i></span>

    <div class="d-flex justify-content-between flex-wrap gap-3">

        <div class="d-flex align-items-start flex-wrap gap-2">
            <a class="btn btn--outline-custom {{ menuActive('influencer.service.order.index') }}" aria-current="page" href="{{ localized_route('influencer.service.order.index') }}">@lang('الكل')</a>
            <a class="btn btn--outline-custom {{ menuActive('influencer.service.order.pending') }}" href="{{ localized_route('influencer.service.order.pending') }}">@lang('قيد الانتظار') ({{ $pendingOrder??0 }})</a>
            <a class="btn btn--outline-custom {{ menuActive('influencer.service.order.inprogress') }}" href="{{ localized_route('influencer.service.order.inprogress') }}">@lang('قيد الانجاز')</a>
            <a class="btn btn--outline-custom {{ menuActive('influencer.service.order.jobDone') }}" href="{{ localized_route('influencer.service.order.jobDone') }}">@lang('عمل منجز')</a>
            <a class="btn btn--outline-custom {{ menuActive('influencer.service.order.completed') }}" href="{{ localized_route('influencer.service.order.completed') }}">@lang('مكتمل')</a>
            <a class="btn btn--outline-custom {{ menuActive('influencer.service.order.reported') }}" href="{{ localized_route('influencer.service.order.reported') }}">@lang('مؤجل')</a>
           <!-- <a class="btn btn--outline-custom {{ menuActive('influencer.service.order.cancelled') }}" href="{{ localized_route('influencer.service.order.cancelled') }}">@lang('ملغى ')</a>-->
        </div>

        <form action="" class="ms-auto service-search-form">
            <div class="input-group">
                <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('ابحث هنا')">
                <button class="input-group-text bg--base border-0 text-white px-4">
                    <i class="las la-search"></i>
                </button>
            </div>
        </form>
    </div>

</div>
<div class="row mt-2">

    <div class="col-lg-12">
        <table class="table--responsive--lg table">
            <thead>
                <tr>
                    <th>@lang('رقم الطلب')</th>
                    <th>@lang('اسم المستخدم')</th>
                    <th class="text-center">@lang('المبلغ | التوصيل')</th>
                    @if (request()->routeIs('influencer.service.order.index'))
                    <th>@lang('الحالة')</th>
                    @endif
                    <th>@lang('النشاط')</th>
                </tr>
            </thead>
            <tbody>

                @forelse($orders as $order)
                <tr>
                    <td data-label="@lang('رقم الطلب')">
                        <span>{{ $order->order_no }}</span>
                    </td>

                    <td data-label="@lang('اسم المستخدم')">
                        <span class="fw-bold">{{ __(@$order->user->username) }}</span>
                    </td>

                    <td data-label="@lang('المبلغ | التوصيل')" class="text-center">
                        <div>
                            <span class="fw-bold">{{ __($general->cur_sym) }}{{ showAmount($order->amount) }}</span> <br>
                            {{ $order->delivery_date }}
                        </div>
                    </td>

                    @if (request()->routeIs('influencer.service.order.index'))
                    <td data-label="@lang('الحالة')">
                        @php echo $order->statusBadge @endphp
                    </td>
                    @endif

                    <td data-label="@lang('النشاط')">
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ localized_route('influencer.service.order.detail',$order->id) }}" class="btn btn--sm btn--outline-base">
                                <i class="las la-desktop"></i> @lang('التفاصيل')
                            </a>
                            <a href="{{ localized_route('influencer.service.order.conversation.view',$order->id) }}" class="btn btn--sm btn--outline-info">
                                <i class="las la-sms"></i> @lang('الدردشة')
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="justify-content-center text-center" colspan="100%">
                        <i class="la la-4x la-frown"></i>
                        <br>
                        {{ __($emptyMessage) }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection