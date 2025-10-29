@extends($activeTemplate . 'layouts.master')
@section('content')
    <form action="" class="d-flex justify-content-end ms-auto table--form mb-3 flex-wrap">
        <div class="input-group">
            <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}"
                   placeholder="@lang('البحث عن طريق المعاملات')">
            <button class="input-group-text bg--base border-0 px-4 text-white">
                <i class="las la-search"></i>
            </button>
        </div>
    </form>
    <table class="table--responsive--lg table">
        <thead>
            <tr>
                <th>@lang('بوابة | عملية')</th>
                <th>@lang('بدأت')</th>
                <th>@lang('المبلغ')</th>
                <th>@lang('المحادثة')</th>
                <th>@lang('الوضع')</th>
                <th>@lang('النشاط')</th>
            </tr>
        </thead>
        <tbody>

            @forelse($withdraws as $withdraw)
                <tr>
                    <td data-label="@lang('بوابة | عملية')">
                        <div>
                            <p class="text--base fw-bold"> {{ __(@$withdraw->method->name) }}</p>
                            <small>{{ $withdraw->trx }}</small>
                        </div>
                    </td>
                    <td data-label="@lang('بدأت')">
                        {{ showDateTime($withdraw->created_at) }} <br> {{ diffForHumans($withdraw->created_at) }}
                    </td>
                    <td data-label="@lang('المبلغ')">
                        <div>
                            {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount) }} - <span class="text-danger"
                                  title="@lang('التكلفة')">{{ showAmount($withdraw->charge) }} </span><br>
                            <strong title="@lang('المبلغ بعد الدفع')">
                                {{ showAmount($withdraw->amount - $withdraw->charge) }} {{ __($general->cur_text) }}
                            </strong>
                        </div>

                    </td>
                    <td data-label="@lang('المحادثة')">
                        <div>
                            1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate) }}
                            {{ __($withdraw->currency) }}<br>
                            <strong>{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}</strong>
                        </div>
                    </td>
                    <td data-label="@lang('الوضع')">
                        @php echo $withdraw->statusBadge @endphp
                    </td>
                    <td data-label="@lang('النشاط')">
                        <div>
                            <button class="btn btn--sm btn--outline-base detailBtn"  data-bs-toggle="tooltip" data-placement="top" title="@lang('View')"
                                    data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                    @if ($withdraw->status == 3 || $withdraw->status == 1) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                <i class="las la-desktop"></i> @lang('التفاصيل')
                            </button>
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
    {{ $withdraws->links() }}

    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('التفاصيل')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group-flush userData">

                    </ul>
                    <div class="feedback"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
