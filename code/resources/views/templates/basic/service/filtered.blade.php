@php
$emptyMsgImage = getContent('empty_message.content', true);
@endphp

@forelse ($services as $service)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 overflow-hidden">
        <!-- Service Header -->
        <div class="p-4 border-b border-gray-100">
            <!-- Category Badge -->
            <div class="mb-3">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    <i data-lucide="tag" class="h-3 w-3 mr-1"></i>
                    {{ __(@$service->category->name) }}
                </span>
            </div>

            <div class="flex items-start gap-3 mb-3">
                <!-- Service Image -->
                <div class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                    <img src="{{ getImage(getFilePath('service') . '/thumb_' . $service->image, getFileThumb('service')) }}"
                         alt="{{ __($service->title) }}"
                         class="w-full h-full object-cover">
                </div>

                <!-- Service Title -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2 leading-tight">
                        <a href="{{ localized_route('service.details', [slug($service->title), $service->id]) }}" class="hover:text-purple-600 transition-colors duration-200">
                            {{ __(@$service->title) }}
                        </a>
                    </h3>
                </div>
            </div>

            <!-- Influencer Info -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                        <img src="{{ getImage(getFilePath('influencerProfile') . '/' . @$service->influencer->image, getFileSize('influencerProfile'), true) }}"
                             alt="{{ __(@$service->influencer->username) }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ __(@$service->influencer->username) }}</p>
                    </div>
                </div>
                <!-- Rating -->
                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                    <div class="flex text-yellow-400 text-sm">
                        @php echo showRatings(@$service->rating); @endphp
                    </div>
                    <span class="text-xs text-gray-500">({{ @$service->total_review ?? 0 }})</span>
                </div>
            </div>
        </div>

        <!-- Service Body -->
        <div class="p-4 bg-gray-50">
            <!-- Price and Action -->
            <div class="flex items-center justify-between gap-3">
                <div class="text-center">
                    <div class="text-lg font-bold text-purple-600">{{ $general->cur_sym }}{{ showAmount($service->price) }}</div>
                    <div class="text-xs text-gray-600">@lang('services.price')</div>
                </div>

                <a href="{{ localized_route('service.details', [slug($service->title), $service->id]) }}"
                   class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200"
                   style="background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%); box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);">
                    <i data-lucide="eye" class="h-4 w-4 mr-2"></i>
                    @lang('services.view_details')
                </a>
            </div>
        </div>
    </div>

@empty
    <!-- Empty State -->
    <div class="col-span-full">
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                @if(@$emptyMsgImage->data_values->image)
                <img src="{{ getImage('assets/images/frontend/empty_message/' . @$emptyMsgImage->data_values->image, '400x300') }}"
                     alt="@lang('services.no_results')"
                     class="mx-auto mb-6 w-64 h-48 object-contain">
                @else
                <div class="mx-auto mb-6 w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center">
                    <i data-lucide="briefcase" class="h-12 w-12 text-gray-400"></i>
                </div>
                @endif
                <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('services.no_services_found')</h3>
                <p class="text-gray-600 mb-6">@lang('services.try_changing_criteria')</p>
                <button class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200"
                        onclick="window.location.reload()">
                    <i data-lucide="refresh-cw" class="h-4 w-4 mr-2"></i>
                    @lang('services.reload')
                </button>
            </div>
        </div>
    </div>
@endforelse

<!-- Pagination -->
@if($services && $services->hasPages())
<div class="mt-8 flex justify-center col-span-full">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        {{ $services->links() }}
    </div>
</div>
@endif
