@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">@lang('قائمة الطلبات')</h3>
        <p class="mt-1 text-sm text-gray-600">@lang('إدارة طلباتك وتتبع حالتها')</p>
    </div>

    <div class="p-6">
        <!-- Search Form -->
        <div class="mb-6">
            <form action="" method="GET" class="flex justify-end">
                <div class="flex">
                    <input type="text"
                           name="search"
                           value="{{ request()->search }}"
                           placeholder="@lang('رقم الطلب / المؤثر')"
                           class="block w-80 rounded-r-none border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-l-none">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Orders Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('رقم الطلب')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المؤثر')</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المبلغ | التوصيل')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('الحالة')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('النشاط')</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_no }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm">
                                <a href="{{ localized_route('influencer.profile', $order->influencer_id) }}"
                                   class="font-medium text-blue-600 hover:text-blue-500">
                                    {{ __(@$order->influencer->username) }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ __($general->cur_sym) }}{{ showAmount($order->amount) }}</div>
                                <div class="text-gray-500">
                                    @lang('التوصيل'): {{ showDateTime($order->delivery_time, 'd M Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @php
                                $statusClasses = [
                                    0 => 'bg-yellow-100 text-yellow-800', // Pending
                                    1 => 'bg-green-100 text-green-800',   // Completed
                                    2 => 'bg-blue-100 text-blue-800',     // Processing
                                    3 => 'bg-red-100 text-red-800',       // Cancelled
                                ];
                                $statusClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                @if($order->status == 0)
                                    @lang('معلق')
                                @elseif($order->status == 1)
                                    @lang('مكتمل')
                                @elseif($order->status == 2)
                                    @lang('قيد التنفيذ')
                                @else
                                    @lang('ملغى')
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ localized_route('user.order.detail', $order->id) }}"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i data-lucide="eye" class="h-4 w-4 ml-2"></i>
                                    @lang('عرض')
                                </a>
                                @if($order->status != 3 && $order->status != 1)
                                <a href="{{ localized_route('user.order.conversation.view', $order->id) }}"
                                   class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i data-lucide="message-circle" class="h-4 w-4 ml-2"></i>
                                    @lang('الدردشة')
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i data-lucide="shopping-cart" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('لا توجد طلبات')</h3>
                            <p class="text-gray-500">@lang('لم تقم بأي طلبات بعد')</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders && $orders->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

@endsection