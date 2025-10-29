{{--
    Client Service Details & Ordering Page

    This page displays detailed information about a specific influencer service
    and provides ordering functionality for clients.

    Features:
    - Service gallery and details
    - Influencer profile information
    - Pricing and package details
    - Ordering form with requirements
    - Related services from same influencer
    - Reviews and ratings
    - Responsive design
--}}

@extends('layouts.dashboard')

@section('title', $service->title)

@section('page-header')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $service->title }}</h1>
            <p class="text-gray-600 text-sm">{{ __('services.by') }} {{ $service->influencer->fullname ?? $service->influencer->username }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ localized_route('client.influencers.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<div x-data="serviceOrderData()" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Service Gallery --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($service->gallery && $service->gallery->count() > 0)
                <div class="relative h-96" x-data="{ currentImage: 0 }">
                    {{-- Main Image --}}
                    <div class="h-full">
                        @foreach($service->gallery as $index => $image)
                            <img x-show="currentImage === {{ $index }}"
                                 src="{{ asset('storage/services/' . $image->image) }}"
                                 alt="{{ $service->title }}"
                                 class="w-full h-full object-cover">
                        @endforeach
                    </div>

                    {{-- Navigation Arrows --}}
                    @if($service->gallery->count() > 1)
                        <button @click="currentImage = currentImage > 0 ? currentImage - 1 : {{ $service->gallery->count() - 1 }}"
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-opacity">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button @click="currentImage = currentImage < {{ $service->gallery->count() - 1 }} ? currentImage + 1 : 0"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-opacity">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    @endif

                    {{-- Thumbnails --}}
                    @if($service->gallery->count() > 1)
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                            @foreach($service->gallery as $index => $image)
                                <button @click="currentImage = {{ $index }}"
                                        :class="currentImage === {{ $index }} ? 'ring-2 ring-white' : ''"
                                        class="w-12 h-12 rounded-lg overflow-hidden bg-black bg-opacity-50">
                                    <img src="{{ asset('storage/services/' . $image->image) }}"
                                         alt="Thumbnail {{ $index + 1 }}"
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="h-96 bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-600">{{ __('services.no_images_available') }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Service Description --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('services.service_description') }}</h3>
            <div class="prose prose-sm max-w-none text-gray-700">
                {!! nl2br(e($service->description)) !!}
            </div>

            {{-- Key Points --}}
            @if($service->key_points && is_array($service->key_points))
                <div class="mt-6">
                    <h4 class="font-medium text-gray-900 mb-3">{{ __('services.key_points') }}</h4>
                    <ul class="space-y-2">
                        @foreach($service->key_points as $point)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-700">{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Service Details --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('services.service_details') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($service->price) }} DZD</div>
                    <div class="text-sm text-gray-600">{{ __('services.starting_price') }}</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $service->delivery_time ?? '3-5' }}</div>
                    <div class="text-sm text-gray-600">{{ __('services.delivery_days') }}</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">{{ number_format($service->rating ?? 5.0, 1) }}</span>
                    </div>
                    <div class="text-sm text-gray-600">{{ __('services.rating') }}</div>
                </div>
            </div>
        </div>

        {{-- Related Services --}}
        @if($relatedServices->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('services.more_from_influencer') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($relatedServices as $relatedService)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                            <h4 class="font-medium text-gray-900 mb-2">{{ $relatedService->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($relatedService->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-green-600">{{ number_format($relatedService->price) }} DZD</span>
                                <a href="{{ localized_route('client.services.show', $relatedService->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    {{ __('common.view_details') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar: Influencer & Order Form --}}
    <div class="space-y-6">
        {{-- Influencer Profile --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <img class="w-20 h-20 rounded-full mx-auto mb-4"
                     src="{{ $service->influencer->image ? asset('assets/images/influencer/profile/' . $service->influencer->image) : 'https://ui-avatars.com/api/?name=' . urlencode($service->influencer->fullname ?? $service->influencer->username) . '&background=random' }}"
                     alt="{{ $service->influencer->fullname ?? $service->influencer->username }}">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $service->influencer->fullname ?? $service->influencer->username }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ $service->category->name ?? 'Content Creator' }}</p>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-lg font-bold text-gray-900">{{ number_format($service->influencer->socialLink->first()->followers ?? 50000) }}</div>
                        <div class="text-xs text-gray-500">{{ __('influencers.followers') }}</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-gray-900">{{ number_format($service->influencer->rating ?? 4.8, 1) }}</div>
                        <div class="text-xs text-gray-500">{{ __('influencers.rating') }}</div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ localized_route('client.influencers.show', $service->influencer->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('influencers.view_profile') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Order Form --}}
        <div id="order" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('services.order_this_service') }}</h3>

            <form @submit.prevent="submitOrder" class="space-y-4">
                @csrf
                {{-- Requirements --}}
                <div>
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('services.project_requirements') }}
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea id="requirements"
                              name="requirements"
                              rows="4"
                              x-model="form.requirements"
                              required
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                              placeholder="{{ __('services.describe_requirements') }}"></textarea>
                </div>

                {{-- Deadline --}}
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('services.project_deadline') }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           id="deadline"
                           name="deadline"
                           x-model="form.deadline"
                           required
                           :min="new Date().toISOString().split('T')[0]"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                {{-- Budget --}}
                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('services.your_budget') }}
                    </label>
                    <div class="flex rounded-md shadow-sm">
                        <input type="number"
                               id="budget"
                               name="budget"
                               x-model="form.budget"
                               :placeholder="basePrice"
                               min="1"
                               step="1"
                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <span class="inline-flex items-center px-3 py-2 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">DZD</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">{{ __('services.minimum_price') }}: {{ number_format($service->price) }} DZD</p>
                </div>

                {{-- Extras (if available) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('services.extras') }}</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="extras[]" value="express_delivery" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ __('services.express_delivery') }} (+50 DZD)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="extras[]" value="additional_revision" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ __('services.additional_revision') }} (+50 DZD)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="extras[]" value="commercial_license" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ __('services.commercial_license') }} (+50 DZD)</span>
                        </label>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-sm">
                        <span>{{ __('services.base_price') }}:</span>
                        <span>{{ number_format($service->price) }} DZD</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 mt-1">
                        <span>{{ __('services.extras') }}:</span>
                        <span x-text="extrasTotal + ' DZD'"></span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg mt-2 pt-2 border-t border-gray-200">
                        <span>{{ __('services.total') }}:</span>
                        <span x-text="(basePrice + extrasTotal) + ' DZD'"></span>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        :disabled="loading || !isFormValid()"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="loading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <svg class="w-4 h-4 mr-2" x-show="!loading" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    {{ __('services.place_order') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function serviceOrderData() {
        return {
            basePrice: {{ $service->price }},
            extrasTotal: 0,
            loading: false,
            form: {
                requirements: '',
                deadline: '',
                budget: '',
                extras: []
            },

            init() {
                this.$watch('form.extras', () => {
                    this.calculateExtras();
                });
            },

            calculateExtras() {
                // Calculate extras total (50 DZD per extra in this example)
                this.extrasTotal = (this.form.extras || []).length * 50;
            },

            isFormValid() {
                return this.form.requirements.trim() !== '' &&
                       this.form.deadline !== '' &&
                       new Date(this.form.deadline) > new Date();
            },

            async submitOrder() {
                if (!this.isFormValid()) return;

                this.loading = true;

                try {
                    const formData = new FormData();
                    formData.append('requirements', this.form.requirements);
                    formData.append('deadline', this.form.deadline);
                    if (this.form.budget) {
                        formData.append('budget', this.form.budget);
                    }
                    if (this.form.extras && this.form.extras.length > 0) {
                        this.form.extras.forEach((extra, index) => {
                            formData.append(`extras[${index}]`, extra);
                        });
                    }

                    const response = await fetch('{{ localized_route("client.services.order", $service->id) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        window.location.href = '{{ localized_route("client.orders.index") }}';
                    } else {
                        const data = await response.json();
                        alert(data.message || '{{ __("services.order_error") }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('{{ __("common.error_occurred") }}');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush