@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i data-lucide="help-circle" class="h-5 w-5 ml-2 text-blue-600"></i>
                    @lang('تذاكر الدعم الفني')
                </h3>
                <p class="mt-1 text-sm text-gray-600">@lang('إدارة جميع تذاكرك ومتابعة حالة الدعم')</p>
            </div>
            <a href="{{ localized_route('ticket.open') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <i data-lucide="plus" class="h-4 w-4 ml-2"></i>
                @lang('تذكرة جديدة')
            </a>
        </div>
    </div>

    <div class="p-6">
        @if($supports && $supports->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @lang('الموضوع')
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @lang('الحالة')
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @lang('الأولوية')
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @lang('آخر رد')
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @lang('الإجراءات')
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($supports as $support)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">
                                    <a href="{{ localized_route('ticket.view', $support->ticket) }}" class="text-blue-600 hover:text-blue-800">
                                        #{{ $support->ticket }}
                                    </a>
                                </div>
                                <div class="text-gray-600 mt-1 line-clamp-2">{{ __($support->subject) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php echo $support->statusBadge; @endphp
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if ($support->priority == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    @lang('أدنى')
                                </span>
                            @elseif($support->priority == 2)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    @lang('متوسط')
                                </span>
                            @elseif($support->priority == 3)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    @lang('عالي')
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ localized_route('ticket.view', $support->ticket) }}"
                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <i data-lucide="eye" class="h-4 w-4 ml-2"></i>
                                @lang('عرض')
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($supports->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $supports->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i data-lucide="ticket" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('لا توجد تذاكر دعم')</h3>
            <p class="text-gray-600 mb-6">@lang('لم تقم بإنشاء أي تذاكر دعم بعد')</p>
            <a href="{{ localized_route('ticket.open') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i data-lucide="plus" class="h-4 w-4 ml-2"></i>
                @lang('إنشاء تذكرة جديدة')
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
