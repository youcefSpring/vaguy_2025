@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="" class="d-flex flex-wrap justify-content-end ms-auto table--form mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}"
                        placeholder="@lang('البحث بالاسم')">
                    <button class="input-group-text bg--base text-white border-0 px-4"><i class="las la-search"></i></button>
                </div>
            </form>
            <table class="table table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('العميل')</th>
                        <th>@lang('الرسالة')</th>
                        <th>@lang('آخرارسال')</th>
                        <th>@lang('النشاط')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conversations as $conversation)
                        <tr>
                            <td data-label="@lang('العميل')">
                                <div>
                                    <span class="fw-bold">{{ __(@$conversation->user->fullname) }}</span>
                                    <br>
                                    <small> {{ __(@$conversation->user->username) }} </small>
                                </div>
                            </td>

                            <td data-label="@lang('الرسالة')">
                                <span>{{ strLimit(@$conversation->lastMessage->message,30) }}</span>
                            </td>

                            <td data-label="@lang('آخرارسال')">
                                {{ showDateTime(@$conversation->lastMessage->created_at) }}<br>{{ diffForHumans(@$conversation->lastMessage->created_at) }}
                            </td>

                            <td data-label="@lang('النشاط')">
                                <a href="{{ localized_route('influencer.conversation.view',$conversation->id) }}" class="btn btn--sm btn--outline-base">
                                    <i class="las la-sms"></i> @lang('الدردشة')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="justify-content-center text-center" colspan="100%">
                                <i class="la la-4x la-frown"></i>
                                <br>
                                @lang('لا توجد رسائل')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($conversations)
        {{ $conversations->links() }}
        @endif
    </div>
@endsection
