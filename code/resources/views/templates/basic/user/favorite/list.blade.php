@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">@lang('قائمة المفضلة')</h3>
        <p class="mt-1 text-sm text-gray-600">@lang('إدارة المؤثرين المفضلين لديك')</p>
    </div>

    <div class="p-6">
        <!-- Search Form -->
        <div class="mb-6">
            <form action="" method="GET" class="flex justify-end">
                <div class="flex">
                    <input type="text"
                           name="search"
                           value="{{ request()->search }}"
                           placeholder="@lang('البحث عن طريق المؤثر')"
                           class="block w-80 rounded-r-none border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-l-none">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Favorites Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المؤثر')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('التقييم')</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('الطلبات المنجزة')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('انضم منذ')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('النشاط')</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($favorites as $favorite)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm">
                                <a href="{{ localized_route('influencer.profile', $favorite->influencer_id) }}"
                                   class="font-medium text-blue-600 hover:text-blue-500">
                                    {{ __(@$favorite->influencer->username) }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-yellow-600">
                                @php
                                echo showRatings($favorite->influencer->rating);
                                @endphp
                                <span class="text-gray-500">({{ getAmount(@$favorite->influencer->reviews_count) }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm font-medium text-gray-900">{{ getAmount(@$favorite->influencer->completed_order )}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-gray-900">{{ showDateTime(@$favorite->influencer->created_at) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ localized_route('influencer.profile', $favorite->influencer_id) }}"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i data-lucide="external-link" class="h-4 w-4 ml-2"></i>
                                    @lang('الحساب')
                                </a>

                                <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 confirmationBtn"
                                        data-action="{{ localized_route('user.favorite.remove', $favorite->id) }}"
                                        data-question="هل أنت متأكد من حذف هذا المؤثر؟"
                                        data-btn_class="btn btn--base btn--md">
                                    <i data-lucide="x" class="h-4 w-4 ml-2"></i>
                                    @lang('حذف')
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i data-lucide="heart" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('لا توجد مفضلة')</h3>
                            <p class="text-gray-500">{{ __($emptyMessage) }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($favorites && $favorites->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $favorites->links() }}
        </div>
        @endif
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection
