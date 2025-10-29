@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">{{ __('Campaign Details') }}</h3>
    </div>

    <div class="p-6">
        @if(isset($campain))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Campaign Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">{{ __('Campaign Information') }}</h4>
                    <p class="text-sm text-gray-600 mb-1"><strong>{{ __('Title:') }}</strong> {{ $campain->title ?? $campain->name ?? 'Campaign #' . $campain->id }}</p>
                    <p class="text-sm text-gray-600 mb-1"><strong>{{ __('Status:') }}</strong> {{ $campain->status ?? 'Active' }}</p>
                    <p class="text-sm text-gray-600"><strong>{{ __('Created:') }}</strong> {{ $campain->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <!-- Campaign Stats -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">{{ __('Campaign Statistics') }}</h4>
                    <p class="text-sm text-blue-700 mb-1"><strong>{{ __('Total Cost:') }}</strong> {{ isset($cout_totale) ? number_format($cout_totale, 2) : '0.00' }}</p>
                    <p class="text-sm text-blue-700 mb-1"><strong>{{ __('Offers:') }}</strong> {{ $campain->campain_offers ? $campain->campain_offers->count() : 0 }}</p>
                </div>
            </div>

            @if($campain->campain_offers && $campain->campain_offers->count() > 0)
                <div class="overflow-x-auto">
                    <h4 class="font-semibold text-gray-900 mb-4">{{ __('Campaign Offers') }}</h4>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Influencer') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($campain->campain_offers as $offer)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $offer->influencer->username ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($offer->price ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        if(in_array($offer->status, [1,3,4,5])) $statusClass = 'bg-green-100 text-green-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ $offer->status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $offer->created_at ? $offer->created_at->format('Y-m-d') : 'N/A' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i data-lucide="inbox" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No offers yet') }}</h3>
                    <p class="text-gray-500">{{ __('This campaign doesn\'t have any offers yet.') }}</p>
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <i data-lucide="alert-circle" class="h-12 w-12 text-red-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Campaign not found') }}</h3>
                <p class="text-gray-500">{{ __('The requested campaign could not be found.') }}</p>
            </div>
        @endif
    </div>
</div>

@endsection