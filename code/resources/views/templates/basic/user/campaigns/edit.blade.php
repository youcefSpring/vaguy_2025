@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">{{ __('Edit Campaign') }}</h3>
    </div>

    <div class="p-6">
        <p class="text-gray-600 mb-4">{{ __('Edit your existing campaign.') }}</p>

        @if(isset($campain))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="text-lg font-semibold text-blue-800 mb-2">{{ __('Campaign Details') }}</h4>
                <p class="text-blue-700">{{ __('Editing campaign:') }} {{ $campain->title ?? $campain->name ?? 'Campaign #' . $campain->id }}</p>
            </div>
        @endif

        @livewireStyles
        @livewire('wizard')
        @livewireScripts
    </div>
</div>

@endsection