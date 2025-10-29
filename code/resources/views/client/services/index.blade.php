{{--
    Client Services Marketplace

    This page displays all available influencer services that clients can order.
    Features advanced filtering, search, and direct ordering capabilities.

    Features:
    - Service grid with beautiful cards
    - Advanced filtering (category, price range, search)
    - Sorting options
    - Pagination
    - Direct ordering integration
    - Responsive design
    - Modern Tailwind styling
--}}

@extends('layouts.dashboard')

@section('title', __('services.services_marketplace'))

@section('page-header')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ __('services.services_marketplace') }}</h1>
            <p class="text-gray-600 text-sm">{{ __('services.discover_order_services') }}</p>
        </div>
        <div class="flex gap-2">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                    </svg>
                    {{ __('common.filters') }}
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                    <div class="p-4 space-y-4">
                        <form method="GET" action="{{ localized_route('client.influencers.index') }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('services.category') }}</label>
                                <select name="category" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                                    <option value="">{{ __('services.all_categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $filters['category'] == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('services.min_price') }}</label>
                                    <input type="number" name="min_price" value="{{ $filters['min_price'] }}" placeholder="0" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('services.max_price') }}</label>
                                    <input type="number" name="max_price" value="{{ $filters['max_price'] }}" placeholder="∞" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('services.sort_by') }}</label>
                                <select name="sort" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                                    <option value="created_at_desc" {{ $filters['sort'] == 'created_at_desc' ? 'selected' : '' }}>{{ __('services.newest_first') }}</option>
                                    <option value="price_asc" {{ $filters['sort'] == 'price_asc' ? 'selected' : '' }}>{{ __('services.price_low_high') }}</option>
                                    <option value="price_desc" {{ $filters['sort'] == 'price_desc' ? 'selected' : '' }}>{{ __('services.price_high_low') }}</option>
                                    <option value="rating_desc" {{ $filters['sort'] == 'rating_desc' ? 'selected' : '' }}>{{ __('services.highest_rated') }}</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('common.apply') }}
                                </button>
                                <a href="{{ localized_route('client.influencers.index') }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('common.clear') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Search Bar --}}
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        <form method="GET" action="{{ localized_route('client.influencers.index') }}" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ $filters['search'] }}"
                           placeholder="{{ __('services.search_services') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                {{ __('common.search') }}
            </button>
        </form>
    </div>

    {{-- Results Count --}}
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-700">
            {{ __('services.showing_results', ['total' => $services->total()]) }}
        </p>
        <div class="text-sm text-gray-500">
            {{ $services->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- Services Grid --}}
    @if($services->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    {{-- Service Image --}}
                    <div class="relative h-48 bg-gray-200">
                        @if($service->gallery && $service->gallery->first())
                            <img src="{{ asset('storage/services/' . $service->gallery->first()->image) }}"
                                 alt="{{ $service->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-200">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        {{-- Price Badge --}}
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ number_format($service->price) }} DZD
                            </span>
                        </div>
                    </div>

                    {{-- Service Content --}}
                    <div class="p-4">
                        {{-- Influencer Info --}}
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full"
                                     src="{{ $service->influencer->image ? asset('assets/images/influencer/profile/' . $service->influencer->image) : 'https://ui-avatars.com/api/?name=' . urlencode($service->influencer->fullname ?? $service->influencer->username) . '&background=random' }}"
                                     alt="{{ $service->influencer->fullname ?? $service->influencer->username }}">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $service->influencer->fullname ?? $service->influencer->username }}</p>
                                <p class="text-xs text-gray-500">{{ $service->category->name ?? 'Service' }}</p>
                            </div>
                        </div>

                        {{-- Service Title --}}
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $service->title }}</h3>

                        {{-- Service Description --}}
                        <p class="text-sm text-gray-600 mb-3 line-clamp-3">{{ $service->description }}</p>

                        {{-- Service Stats --}}
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span>{{ number_format($service->rating ?? 5.0, 1) }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $service->delivery_time ?? '3-5' }} {{ __('services.days') }}</span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <a href="{{ localized_route('client.services.show', $service->id) }}"
                               class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('common.view_details') }}
                            </a>
                            <a href="{{ localized_route('client.services.show', $service->id) }}#order"
                               class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('services.order_now') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $services->appends(request()->query())->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('services.no_services_found') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('services.try_different_filters') }}</p>
            <div class="mt-6">
                <a href="{{ localized_route('client.influencers.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('services.view_all_services') }}
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection