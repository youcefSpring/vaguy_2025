@php
$content = getContent('top_influencer.content', true);
@endphp
@extends('layouts.dashboard')
@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 rtl:space-x-reverse text-sm">
                <li><a href="{{ localized_route('home') }}" class="text-gray-500 hover:text-gray-700">@lang('common.home')</a></li>
                <li><i data-lucide="chevron-right" class="h-4 w-4 text-gray-400"></i></li>
                <li><a href="{{ localized_route('services') }}" class="text-gray-500 hover:text-gray-700">@lang('services.services')</a></li>
                <li><i data-lucide="chevron-right" class="h-4 w-4 text-gray-400"></i></li>
                <li class="text-gray-900 font-medium truncate">{{ __($service->title) }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Product Detail Section -->
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">

            <!-- Image Gallery (Left Side) -->
            <div class="flex flex-col-reverse">
                <!-- Image Grid/Thumbnails -->
                <div class="mt-6 w-full max-w-2xl mx-auto lg:max-w-none">
                    <div class="grid grid-cols-4 gap-4" id="image-thumbnails">
                        <button type="button" class="thumbnail-btn active relative h-24 bg-white rounded-lg flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none border-2 border-purple-600" data-image="{{ getImage(getFilePath('service') . '/' . $service->image, getFileSize('service')) }}">
                            <img src="{{ getImage(getFilePath('service') . '/thumb_' . $service->image, getFileThumb('service')) }}" alt="image" class="object-cover object-center w-full h-full rounded-md">
                        </button>
                        @foreach ($service->gallery as $gallery)
                        <button type="button" class="thumbnail-btn relative h-24 bg-white rounded-lg flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none border-2 border-gray-300" data-image="{{ getImage(getFilePath('service') . '/' . $gallery->image, getFileSize('service')) }}">
                            <img src="{{ getImage(getFilePath('service') . '/thumb_' . $gallery->image, getFileThumb('service')) }}" alt="image" class="object-cover object-center w-full h-full rounded-md">
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Main Image -->
                <div class="w-full">
                    <div id="main-image-container" class="relative bg-gray-100 rounded-lg overflow-hidden border border-gray-200" style="min-height: 500px;">
                        <img id="main-image" src="{{ getImage(getFilePath('service') . '/' . $service->image, getFileSize('service')) }}" alt="image" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>

            <!-- Product Info (Right Side) -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <!-- Category Badge -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i data-lucide="tag" class="h-3 w-3 mr-1"></i>
                        {{ __(@$service->category->name) }}
                    </span>
                    @if($service->tags->count() > 0)
                        @foreach($service->tags->take(2) as $tag)
                        <a href="{{ localized_route('service.tag', [@$tag->id, slug(@$tag->name)]) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                            {{ __(@$tag->name) }}
                        </a>
                        @endforeach
                    @endif
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-4">
                    {{ __($service->title) }}
                </h1>

                <!-- Rating -->
                <div class="flex items-center mb-6">
                    <div class="flex items-center">
                        <div class="flex text-yellow-400 text-lg">
                            @php echo showRatings(@$service->rating); @endphp
                        </div>
                        <span class="ml-2 text-sm text-gray-600">({{ @$service->total_review ?? 0 }} @lang('services.reviews'))</span>
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-8">
                    <p class="text-sm text-gray-500 mb-1">@lang('services.price')</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $general->cur_sym }}{{ showAmount($service->price) }}</p>
                </div>

                <!-- Key Points -->
                @if($service->key_points && count($service->key_points) > 0)
                <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="check-circle" class="h-5 w-5 mr-2 text-purple-600"></i>
                        @lang('What\'s included')
                    </h3>
                    <ul class="space-y-3">
                        @foreach ($service->key_points as $point)
                        <li class="flex items-start">
                            <i data-lucide="check" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                            <span class="text-gray-700">{{ __($point) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Order Button -->
                @if(!authInfluencerId())
                <div class="mb-8 space-y-3">
                    <a href="{{ localized_route('user.order.form', $service->id) }}"
                       class="w-full flex items-center justify-center px-8 py-4 text-base font-semibold text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl"
                       style="background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);">
                        <i data-lucide="shopping-cart" class="h-5 w-5 mr-2"></i>
                        @lang('services.order_now')
                    </a>
                    <p class="text-center text-sm text-gray-500">@lang('Secure checkout') • @lang('Fast delivery')</p>
                </div>
                @endif

                <!-- Provider Info -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center">
                        <div class="relative flex-shrink-0">
                            <img src="{{ getImage(getFilePath('influencerProfile') . '/' . @$service->influencer->image, getFileSize('influencerProfile'), true) }}"
                                 alt="{{ __(@$service->influencer->fullname) }}"
                                 class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                            @if (@$service->influencer->isOnline())
                            <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-full bg-green-400 ring-2 ring-white"></span>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm text-gray-500">@lang('services.provided_by')</p>
                            <a href="{{ localized_route('influencer.profile', @$service->influencer_id) }}" class="text-base font-semibold text-gray-900 hover:text-purple-600">
                                {{ __(@$service->influencer->fullname) }}
                            </a>
                            <p class="text-sm text-gray-600">{{ __(@$service->influencer->profession) }}</p>
                            <div class="flex items-center mt-1">
                                <div class="flex text-yellow-400 text-sm">
                                    @php echo showRatings(@$service->influencer->rating); @endphp
                                </div>
                                <span class="ml-1 text-xs text-gray-600">({{ getAmount(@$service->influencer->total_review) ?? 0 }})</span>
                            </div>
                        </div>
                        <a href="{{ localized_route('influencer.profile', @$service->influencer_id) }}"
                           class="ml-4 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            @lang('influencers.view_profile')
                        </a>
                    </div>
                </div>

                <!-- Share -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">@lang('Share'):</span>
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="text-gray-400 hover:text-blue-600">
                                <i class="lab la-facebook-f text-xl"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ __($service->title) }}%0A{{ url()->current() }}" target="_blank" class="text-gray-400 hover:text-blue-400">
                                <i class="lab la-twitter text-xl"></i>
                            </a>
                            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __($service->title) }}" target="_blank" class="text-gray-400 hover:text-blue-700">
                                <i class="lab la-linkedin-in text-xl"></i>
                            </a>
                            <a href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __($service->title) }}&media={{ getImage(getFilePath('service') . '/' . $service->image, getFileSize('service')) }}" target="_blank" class="text-gray-400 hover:text-red-600">
                                <i class="lab la-pinterest text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="mt-16 border-t border-gray-200 pt-10">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 rtl:space-x-reverse" aria-label="Tabs">
                    <button onclick="switchTab('description')" id="tab-description" class="tab-button border-purple-600 text-purple-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        @lang('services.description')
                    </button>
                    <button onclick="switchTab('reviews')" id="tab-reviews" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        @lang('services.reviews') ({{ @$service->total_review ?? 0 }})
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-8">
                <!-- Description Tab -->
                <div id="content-description" class="tab-content">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        @php echo $service->description; @endphp
                    </div>
                    @if(@$service->influencer->summary)
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">@lang('About the Provider')</h3>
                        <div class="prose prose-sm max-w-none text-gray-700">
                            @php echo @$service->influencer->summary; @endphp
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Reviews Tab -->
                <div id="content-reviews" class="tab-content hidden">
                    @if ($orderId)
                    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">@lang('services.write_review')</h3>
                        <form action="{{ localized_route('user.review.service.add', $orderId) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">@lang('Your Rating')</label>
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="star" value="{{ $i }}" class="sr-only peer" {{ $i == 5 ? 'checked' : '' }}>
                                        <i class="las la-star text-3xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400"></i>
                                    </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">@lang('Your Review')</label>
                                <textarea name="review" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="@lang('Tell us about your experience...')" required>{{ old('review') }}</textarea>
                            </div>
                            <button type="submit" class="px-6 py-3 text-white font-medium rounded-lg transition-colors" style="background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);">
                                @lang('common.submit')
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- Reviews List -->
                    <div class="space-y-6">
                        @forelse($service->reviews as $review)
                        <div class="border-b border-gray-200 pb-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 rtl:space-x-reverse">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . @$review->user->image, getFileSize('userProfile'), true) }}"
                                         alt="{{ __(@$review->user->fullname) }}"
                                         class="h-12 w-12 rounded-full object-cover">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">{{ __(@$review->user->fullname) }}</h4>
                                        <p class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                                        <div class="flex text-yellow-400 text-sm mt-1">
                                            @php echo showRatings(@$review->star); @endphp
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-4 text-gray-700">{{ __($review->review) }}</p>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i data-lucide="message-square" class="h-16 w-16 text-gray-300 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('No reviews yet')</h3>
                            <p class="text-gray-500">@lang('Be the first to review this service')</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Services -->
        @if ($anotherServices->count() > 0)
        <div class="mt-16 border-t border-gray-200 pt-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">@lang('services.service_provider') @lang('Other Services')</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($anotherServices as $relatedService)
                <a href="{{ localized_route('service.details', [slug($relatedService->title), $relatedService->id]) }}" class="group">
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        <div class="aspect-w-4 aspect-h-3 bg-gray-200">
                            <img src="{{ getImage(getFilePath('service') . '/thumb_' . $relatedService->image, getFileThumb('service')) }}"
                                 alt="{{ __($relatedService->title) }}"
                                 class="w-full h-full object-cover object-center group-hover:opacity-75">
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-purple-600">
                                {{ __($relatedService->title) }}
                            </h3>
                            <p class="mt-2 text-lg font-bold text-gray-900">
                                {{ $general->cur_sym }}{{ showAmount($relatedService->price) }}
                            </p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Sticky Mobile Order Button -->
@if(!authInfluencerId())
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-lg lg:hidden z-50 transition-transform duration-300" id="sticky-order-bar" style="transform: translateY(100%);">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <div class="flex-1">
            <p class="text-sm text-gray-500">@lang('services.price')</p>
            <p class="text-2xl font-bold text-gray-900">{{ $general->cur_sym }}{{ showAmount($service->price) }}</p>
        </div>
        <a href="{{ localized_route('user.order.form', $service->id) }}"
           class="flex-shrink-0 flex items-center justify-center px-8 py-3 text-base font-semibold text-white rounded-lg transition-all duration-200 shadow-md"
           style="background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);">
            <i data-lucide="shopping-cart" class="h-5 w-5 mr-2"></i>
            @lang('services.order_now')
        </a>
    </div>
</div>
@endif

<x-confirmation-modal></x-confirmation-modal>

@endsection

@push('script')
<script>
    (function($){
        "use strict";

        // Tab Switching
        window.switchTab = function(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active state from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-purple-600', 'text-purple-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active state to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.add('border-purple-600', 'text-purple-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
        };

        // Image Gallery/Carousel
        $(document).ready(function() {
            const thumbnails = $('.thumbnail-btn');
            const mainImage = $('#main-image');

            thumbnails.on('click', function(e) {
                e.preventDefault();

                // Remove active state from all thumbnails
                thumbnails.removeClass('border-purple-600 active').addClass('border-gray-300');

                // Add active state to clicked thumbnail
                $(this).addClass('border-purple-600 active').removeClass('border-gray-300');

                // Get the full image URL from data attribute
                const fullImageUrl = $(this).data('image');

                // Update main image with fade effect
                mainImage.fadeOut(200, function() {
                    mainImage.attr('src', fullImageUrl);
                    mainImage.fadeIn(200);
                });
            });
        });

        // Sticky Order Bar - Show/Hide on scroll
        @if(!authInfluencerId())
        const stickyBar = $('#sticky-order-bar');
        const orderButton = $('a[href*="order.form"]').first();

        if (orderButton.length && stickyBar.length) {
            $(window).on('scroll', function() {
                const orderButtonOffset = orderButton.offset().top;
                const scrollPosition = $(window).scrollTop() + $(window).height();

                // Show sticky bar when order button is not visible
                if (scrollPosition < orderButtonOffset || $(window).scrollTop() < 300) {
                    stickyBar.css('transform', 'translateY(100%)');
                } else {
                    stickyBar.css('transform', 'translateY(0)');
                }
            });
        }
        @endif

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();

            // Re-initialize after a short delay to catch dynamically loaded icons
            setTimeout(function() {
                lucide.createIcons();
            }, 500);
        }

        @if(@$orderId)
            // Scroll to reviews tab if coming from order
            setTimeout(function() {
                switchTab('reviews');
                $('html, body').animate({
                    scrollTop: $('#content-reviews').offset().top - 100
                }, 500);
            }, 300);
        @endif
    })(jQuery);
</script>
@endpush
