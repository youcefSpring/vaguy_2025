@extends('layouts.dashboard')
@section('title', __('KYC Verification'))

@section('content')
@php
$kycContent = getContent('client_kyc.content', true);
@endphp

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">@lang('Identity Verification')</h2>
        <p class="mt-2 text-base text-gray-600 dark:text-gray-400">@lang('Complete verification to unlock all features')</p>
    </div>

    <!-- Info Banner -->
    @if (auth()->user()->kv == 0)
    <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800/30">
        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-blue-800 dark:text-blue-300 leading-relaxed">{{ __($kycContent->data_values->verification_content ?? 'Complete verification to access all features') }}</p>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg">
        <form action="{{ localized_route('user.kyc.submit') }}" method="post" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <x-viser-form identifier="act" identifierValue="kyc"></x-viser-form>
            </div>

            <button type="submit"
                    class="mt-8 w-full flex items-center justify-center gap-3 px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-base font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-purple-500/50 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                @lang('Submit Verification')
            </button>
        </form>
    </div>

    <!-- Footer Note -->
    <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center gap-2">
        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
        </svg>
        @lang('Your data is encrypted and secure')
    </p>
</div>
@endsection

@push('style')
<style>
    /* Modern Large Form Styling */
    .viser-form-group {
        margin-bottom: 0 !important;
    }

    .viser-form-group label {
        @apply block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2.5;
    }

    .viser-form-group input[type="text"],
    .viser-form-group input[type="email"],
    .viser-form-group input[type="number"],
    .viser-form-group input[type="tel"],
    .viser-form-group input[type="date"],
    .viser-form-group select,
    .viser-form-group textarea {
        @apply w-full px-4 py-3.5 text-base border-2 border-gray-300 dark:border-gray-600 rounded-xl
               bg-white dark:bg-gray-700/50 text-gray-900 dark:text-gray-100
               placeholder:text-gray-400 dark:placeholder:text-gray-500
               focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500
               hover:border-gray-400 dark:hover:border-gray-500
               transition-all duration-200 shadow-sm;
    }

    .viser-form-group input[type="file"] {
        @apply w-full text-base border-2 border-gray-300 dark:border-gray-600 rounded-xl
               bg-white dark:bg-gray-700/50 text-gray-700 dark:text-gray-300
               file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0
               file:text-sm file:font-semibold file:bg-gradient-to-r file:from-purple-600 file:to-indigo-600 file:text-white
               hover:file:from-purple-700 hover:file:to-indigo-700
               dark:file:from-purple-500 dark:file:to-indigo-500
               cursor-pointer transition-all shadow-sm;
    }

    .viser-form-group textarea {
        @apply min-h-[120px] resize-y leading-relaxed;
    }

    /* Modern Select Dropdown */
    .viser-form-group select {
        @apply cursor-pointer appearance-none font-medium;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%237c3aed'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'%3e%3c/path%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 3.5rem;
    }

    .viser-form-group select:hover {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236d28d9'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'%3e%3c/path%3e%3c/svg%3e");
    }

    .viser-form-group select option {
        @apply py-3 px-2 text-base;
    }

    .viser-form-group .invalid-feedback,
    .viser-form-group .text-danger {
        @apply text-sm text-red-600 dark:text-red-400 mt-2 font-medium;
    }

    .viser-form-group .form-check {
        @apply flex items-center gap-3;
    }

    .viser-form-group .form-check-input {
        @apply h-5 w-5 text-purple-600 border-2 border-gray-300 dark:border-gray-600 rounded-md
               focus:ring-4 focus:ring-purple-500/20 cursor-pointer transition-all;
    }

    .viser-form-group .form-check-label {
        @apply text-sm text-gray-800 dark:text-gray-200 cursor-pointer font-medium;
    }

    /* Full width items */
    .viser-form-group.col-12 {
        grid-column: 1 / -1;
    }

    /* Enhanced focus animations */
    .viser-form-group input:focus,
    .viser-form-group select:focus,
    .viser-form-group textarea:focus {
        @apply shadow-lg transform scale-[1.01];
    }

    /* Disabled state */
    .viser-form-group input:disabled,
    .viser-form-group select:disabled,
    .viser-form-group textarea:disabled {
        @apply bg-gray-100 dark:bg-gray-800 cursor-not-allowed opacity-50;
    }

    /* Input icons/indicators */
    .viser-form-group input[type="date"]::-webkit-calendar-picker-indicator {
        @apply cursor-pointer w-5 h-5 opacity-60 hover:opacity-100;
    }
</style>
@endpush
