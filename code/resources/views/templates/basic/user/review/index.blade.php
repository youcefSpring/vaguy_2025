@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">@lang('التقييمات')</h2>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <!-- Search Form -->
        <form action="" class="flex justify-end mb-6">
            <div class="flex">
                <input type="text" name="search" class="input rounded-r-none" value="{{ request()->search }}"
                    placeholder="@lang('ابحث هنا')">
                <button type="submit" class="btn btn-primary rounded-l-none">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </div>
        </form>

        <!-- Reviews Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('الطلب | رقم عرض العمل')</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المؤثر')</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('التقييم')</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('النشاط')</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($review->order_id == 0)
                                    <a href="{{ localized_route('user.hiring.detail', @$review->hiring_id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ @$review->hiring->hiring_no }}
                                    </a>
                                @else
                                    <a href="{{ localized_route('user.order.detail', @$review->order_id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ @$review->order->order_no }}
                                    </a>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i data-lucide="user" class="h-4 w-4 text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ localized_route('influencer.profile', $review->influencer_id) }}"
                                           class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                            {{ __(@$review->influencer->username) }}
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        $stars = $review->star;
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $stars)
                                            <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                        @else
                                            <i data-lucide="star" class="w-4 h-4 text-gray-300"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">({{ $stars }}/5)</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 rtl:space-x-reverse">
                                    @if ($review->order_id == 0)
                                        <a href="{{ localized_route('user.review.influencer', $review->hiring_id) }}"
                                           class="btn btn-outline btn-sm">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                            @lang('تعديل')
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm confirmationBtn"
                                                data-action="{{ localized_route('user.review.remove.influencer',$review->id) }}"
                                                data-question="@lang('هل أنت متأكد من إزالة هذه الرأي؟')"
                                                data-btn_class="btn btn-primary">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                            @lang('حذف')
                                        </button>
                                    @else
                                        <a href="{{ localized_route('user.review.service', $review->order_id) }}"
                                           class="btn btn-outline btn-sm">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                            @lang('تعديل')
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm confirmationBtn"
                                                data-action="{{ localized_route('user.review.remove.service',$review->id) }}"
                                                data-question="@lang('هل أنت متأكد من إزالة هذه الرأي؟')"
                                                data-btn_class="btn btn-primary">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                            @lang('حذف')
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <i data-lucide="star" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد تقييمات</h3>
                                <p class="text-gray-500">{{ __($emptyMessage) }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reviews && $reviews->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>

<x-confirmation-modal></x-confirmation-modal>
@endsection