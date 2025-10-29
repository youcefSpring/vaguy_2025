@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">@lang('قائمة المحادثات')</h3>
    </div>

    <div class="p-6">
        <!-- Search Form -->
        <div class="mb-6">
            <form action="" method="GET" class="flex justify-end">
                <div class="flex">
                    <input type="text"
                           name="search"
                           value="{{ request()->search }}"
                           placeholder="@lang('البحث عن طريق الإسم')"
                           class="block w-80 rounded-r-none border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-l-none">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Conversations Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المؤثر')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('الرسالة')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('آخر إرسال')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('النشاط')</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($conversations as $conversation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">{{ __(@$conversation->influencer->fullname) }}</div>
                                <div class="text-sm text-gray-500">{{ __(@$conversation->influencer->username) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 text-right max-w-xs truncate">
                                {{ strLimit(@$conversation->lastMessage->message, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-gray-900">{{ showDateTime(@$conversation->lastMessage->created_at) }}</div>
                            <div class="text-sm text-gray-500">{{ diffForHumans(@$conversation->lastMessage->created_at) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ localized_route('user.conversation.view', $conversation->id) }}"
                               class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i data-lucide="message-circle" class="h-4 w-4 ml-2"></i>
                                @lang('الدردشة')
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <i data-lucide="inbox" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('لا توجد محادثات')</h3>
                            <p class="text-gray-500">@lang('لا توجد رسائل حتى الآن')</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($conversations && $conversations->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $conversations->links() }}
        </div>
        @endif
    </div>
</div>

@endsection