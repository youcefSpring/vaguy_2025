@php
$kycContent = getContent('client_kyc.content', true);
@endphp
@extends('layouts.dashboard')
@section('content')

@if (auth()->user()->kv == 0)
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6" role="alert">
    <h4 class="text-lg font-semibold text-blue-800 mb-2">@lang('user.kyc_verification_required')</h4>
    <hr class="border-blue-200 my-2">
    <p class="text-blue-700">{{ __($kycContent->data_values->verification_content) }}<a href="{{ localized_route('user.kyc.form') }}" class="text-blue-600 hover:text-blue-800 underline"> &nbsp;@lang('user.click_here_to_verify')</a></p>
</div>
@elseif(auth()->user()->kv == 2)
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6" role="alert">
    <h4 class="text-lg font-semibold text-yellow-800 mb-2">@lang('user.kyc_verification_pending')</h4>
    <hr class="border-yellow-200 my-2">
    <p class="text-yellow-700"> {{ __($kycContent->data_values->pending_content) }} <a href="{{ localized_route('user.kyc.data') }}" class="text-yellow-600 hover:text-yellow-800 underline">&nbsp; @lang('user.view_kyc_information')</a></p>
</div>
@endif
<!-- Welcome Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
        @lang('user.welcome'), {{ auth()->user()->firstname }}!
    </h1>
    <p class="text-gray-600">
        @lang('user.dashboard_overview')
    </p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">
    <!-- Current Balance -->
    <div class="card">
        <div class="card-content pt-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-green-500 text-white">
                        <i data-lucide="wallet" class="h-5 w-5"></i>
                    </div>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            @lang('user.current_balance')
                        </dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ showAmount($data['current_balance']) }} {{ $general->cur_text }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Deposits -->
    <div class="card">
        <div class="card-content pt-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-blue-500 text-white">
                        <i data-lucide="trending-up" class="h-5 w-5"></i>
                    </div>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            @lang('user.total_deposit')
                        </dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ showAmount($data['deposit_amount']) }} {{ $general->cur_text }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Transactions -->
    <div class="card">
        <div class="card-content pt-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-purple-500 text-white">
                        <i data-lucide="arrow-right-left" class="h-5 w-5"></i>
                    </div>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            @lang('user.total_transactions')
                        </dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ $data['total_transaction'] }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="card">
        <div class="card-content pt-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-orange-500 text-white">
                        <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                    </div>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            @lang('user.total_orders')
                        </dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ $data['total_order'] }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Orders -->
    <div class="card">
        <div class="card-content pt-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-emerald-500 text-white">
                        <i data-lucide="check-circle" class="h-5 w-5"></i>
                    </div>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            @lang('user.completed_orders')
                        </dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ $data['complete_order'] }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Incomplete Orders -->
    <div class="card">
        <div class="card-content pt-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-red-500 text-white">
                        <i data-lucide="x-circle" class="h-5 w-5"></i>
                    </div>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            @lang('user.incomplete_orders')
                        </dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ $data['incomplete_order'] }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">@lang('user.recent_transactions')</h3>
        <p class="card-description">@lang('user.latest_transaction_activity')</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-500">@lang('user.trx')</th>
                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-500">@lang('user.completed')</th>
                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-500">@lang('user.amount')</th>
                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-500">@lang('user.post_balance')</th>
                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-500">@lang('user.details')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b border-gray-50 last:border-b-0 hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $trx->trx }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div>{{ showDateTime($trx->created_at) }}</div>
                        <div class="text-xs text-gray-400">{{ diffForHumans($trx->created_at) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold">
                        <span class="{{ $trx->trx_type == '+' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trx->trx_type }} {{ showAmount($trx->amount) }} {{ $general->cur_text }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ showAmount($trx->post_balance) }} {{ __($general->cur_text) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ __($trx->details) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="inbox" class="h-8 w-8 text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('user.no_transactions')</h3>
                            <p class="text-gray-500">@lang('user.transactions_will_appear_here')</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->isNotEmpty())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
            <a href="{{ localized_route('user.transactions') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200">
                @lang('user.view_all_transactions') →
            </a>
        </div>
    @endif
</div>
@endsection
