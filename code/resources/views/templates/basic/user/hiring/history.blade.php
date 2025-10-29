@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">@lang('تاريخ التوظيفات')</h3>
        <p class="mt-1 text-sm text-gray-600">@lang('إدارة تاريخ عروض العمل ومتابعة حالتها')</p>
    </div>

    <div class="p-6">
        <!-- Search Form -->
        <div class="mb-6">
            <form action="" method="GET" class="flex justify-end">
                <div class="flex">
                    <input type="text"
                           name="search"
                           value="{{ request()->search }}"
                           placeholder="@lang('رقم عرض العمل / المؤثر')"
                           class="block w-80 rounded-r-none border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-l-none">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Hirings Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('رقم عرض العمل')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المؤثر')</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المبلغ | التوصيل')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('الحالة')</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('النشاط')</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($hirings as $hiring)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-medium text-gray-900">{{ $hiring->hiring_no }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm">
                                <a href="{{ localized_route('influencer.profile', $hiring->influencer_id) }}"
                                   class="font-medium text-blue-600 hover:text-blue-500">
                                    {{ __(@$hiring->influencer->username) }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ __($general->cur_sym) }}{{ showAmount($hiring->amount) }}</div>
                                <div class="text-gray-500">
                                    @lang('التوصيل'): {{ $hiring->delivery_date }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @php echo $hiring->statusBadge @endphp
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                @if ($hiring->status == 1)
                                    <a href="{{ localized_route('user.review.influencer', $hiring->id) }}"
                                       class="inline-flex items-center px-3 py-2 border border-yellow-300 shadow-sm text-sm leading-4 font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        <i data-lucide="star" class="h-4 w-4 ml-2"></i>
                                        @lang('الآراء')
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed"
                                          title="@lang('You can review it after the hiring is completed.')">
                                        <i data-lucide="star" class="h-4 w-4 ml-2"></i>
                                        @lang('الآراء')
                                    </span>
                                @endif

                                <a href="{{ localized_route('user.hiring.detail',$hiring->id) }}"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i data-lucide="eye" class="h-4 w-4 ml-2"></i>
                                    @lang('التفاصيل')
                                </a>

                                <a href="{{ localized_route('user.hiring.conversation.view',$hiring->id) }}"
                                   class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i data-lucide="message-circle" class="h-4 w-4 ml-2"></i>
                                    @lang('الدردشة')
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i data-lucide="briefcase" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('لا توجد توظيفات')</h3>
                            <p class="text-gray-500">{{ __($emptyMessage) }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($hirings && $hirings->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $hirings->links() }}
        </div>
        @endif
    </div>
</div>

@endsection