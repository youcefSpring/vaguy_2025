@extends($activeTemplate . 'layouts.master')
@section('content')
    <table class="table table--responsive--lg">
        <thead>
            <tr>
                <th>@lang('الموضوع')</th>
                <th>@lang('الوضع')</th>
                <th>@lang('الأولوية')</th>
                <th>@lang('آخر رد')</th>
                <th>@lang('النشاط')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($supports as $key => $support)
                <tr>
                    <td data-label="@lang('الموضوع')"> <a href="{{ localized_route('influencer.ticket.view', $support->ticket) }}"
                            class="fw-bold"> [@lang('البطاقة')#{{ $support->ticket }}]
                            {{ __($support->subject) }} </a></td>
                    <td data-label="@lang('الوضع')">
                        @php echo $support->statusBadge; @endphp
                    </td>
                    <td data-label="@lang('الأولوية')">
                        @if ($support->priority == 1)
                            <span class="badge badge--dark">@lang('الأدنى')</span>
                        @elseif($support->priority == 2)
                            <span class="badge badge--success">@lang('المتوسط')</span>
                        @elseif($support->priority == 3)
                            <span class="badge badge--primary">@lang('الأعلى')</span>
                        @endif
                    </td>
                    <td data-label="@lang('آخر رد')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }}
                    </td>

                    <td data-label="@lang('النشاط')">
                        <a href="{{ localized_route('influencer.ticket.view', $support->ticket) }}" class="btn btn--sm btn--outline-base">
                            <i class="la la-desktop"></i> @lang('View')
                        </a>
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
    {{ $supports->links() }}
@endsection
