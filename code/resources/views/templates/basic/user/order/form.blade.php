@extends('layouts.dashboard')
@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200 mb-6">
        <nav class="flex px-6 py-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ localized_route('user.home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600">
                        <i data-lucide="home" class="h-4 w-4 mr-2"></i>
                        @lang('common.dashboard')
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="h-4 w-4 text-gray-400 mx-2"></i>
                        <a href="{{ localized_route('services') }}" class="text-sm font-medium text-gray-700 hover:text-purple-600">@lang('common.services')</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="h-4 w-4 text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">@lang('Place Order')</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="px-6 pb-6">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Left Column - Service Summary -->
            <div class="lg:col-span-4 mb-8 lg:mb-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i data-lucide="shopping-cart" class="h-5 w-5 mr-2 text-purple-600"></i>
                        @lang('Order Summary')
                    </h3>

                    <!-- Service Info -->
                    <div class="mb-6">
                        <div class="flex gap-4 mb-4">
                            <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ getImage(getFilePath('service') . '/thumb_' . $service->image, getFileThumb('service')) }}"
                                     alt="{{ __($service->title) }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 mb-1 line-clamp-2">{{ __($service->title) }}</h4>
                                <p class="text-xs text-gray-500">{{ __(@$service->category->name) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Influencer Info -->
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <p class="text-xs font-medium text-gray-500 mb-3">@lang('Provided by')</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100">
                                <img src="{{ getImage(getFilePath('influencerProfile') . '/' . @$influencer->image, getFileSize('influencerProfile'), true) }}"
                                     alt="{{ __($influencer->username) }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ __($influencer->fullname) }}</p>
                                <p class="text-xs text-gray-500">{{ __($influencer->username) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">@lang('Service Price')</span>
                            <span class="font-medium text-gray-900">{{ $general->cur_sym }}{{ showAmount($service->price) }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-900">@lang('Total')</span>
                                <span class="text-2xl font-bold text-purple-600">{{ $general->cur_sym }}{{ showAmount($service->price) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                        <div class="flex items-center text-xs text-gray-600">
                            <i data-lucide="shield-check" class="h-4 w-4 text-green-600 mr-2"></i>
                            @lang('Secure payment')
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i data-lucide="clock" class="h-4 w-4 text-blue-600 mr-2"></i>
                            @lang('Fast delivery')
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i data-lucide="headphones" class="h-4 w-4 text-purple-600 mr-2"></i>
                            @lang('24/7 Support')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Form -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <!-- Form Header -->
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i data-lucide="file-text" class="h-5 w-5 mr-2 text-purple-600"></i>
                            @lang('Order Details')
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">@lang('Please fill in the information below to place your order')</p>
                    </div>

                    <!-- Form Body -->
                    <form action="{{ localized_route('user.order.confirm', [$influencer->id, $service->id]) }}" method="POST" class="p-6">
                        @csrf
                        <div class="space-y-6">
                            <!-- Request Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    @lang('Request Title')
                                    <span class="text-purple-600">*</span>
                                </label>
                                <input type="text"
                                       name="title"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                       placeholder="@lang('Enter a descriptive title for your order')"
                                       value="{{ old('title') }}"
                                       required>
                                <p class="mt-1 text-xs text-gray-500">@lang('Provide a clear title that describes what you need')</p>
                            </div>

                            <!-- Estimated Delivery Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    @lang('Estimated Delivery Date')
                                    <span class="text-purple-600">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           class="datepicker-here w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                           data-language='en'
                                           data-date-format="yyyy-mm-dd"
                                           data-position='bottom left'
                                           placeholder="@lang('Select Date')"
                                           name="delivery_date"
                                           autocomplete="off"
                                           required>
                                    <i data-lucide="calendar" class="h-5 w-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">@lang('When would you like to receive the completed work?')</p>
                            </div>

                            <!-- Payment Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    @lang('Payment Method')
                                    <span class="text-purple-600">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                        name="payment_type"
                                        required>
                                    <option value="" disabled selected>@lang('Select payment method')</option>
                                    <option value="2">
                                        <i data-lucide="credit-card" class="inline h-4 w-4"></i>
                                        @lang('Direct Payment')
                                    </option>
                                    <option value="1">
                                        @lang('Wallet Balance') ({{ showAmount(auth()->user()->balance) }} {{ $general->cur_text }})
                                    </option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">@lang('Choose how you would like to pay for this service')</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="description">
                                    @lang('Project Description')
                                    <span class="text-purple-600">*</span>
                                </label>
                                <textarea rows="6"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none"
                                          name="description"
                                          id="description"
                                          placeholder="@lang('Describe your requirements in detail. Include any specific instructions, preferences, or materials the influencer should know about...')"
                                          required>{{ old('description') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">@lang('The more details you provide, the better the influencer can meet your expectations')</p>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-6 border-t border-gray-200">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-6 py-4 text-white text-base font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                                        style="background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);">
                                    <i data-lucide="check-circle" class="h-5 w-5 mr-2"></i>
                                    @lang('Confirm & Place Order')
                                </button>
                                <p class="mt-3 text-center text-xs text-gray-500">
                                    @lang('By placing this order, you agree to our terms of service')
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Additional Info Card -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <i data-lucide="info" class="h-5 w-5 text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-blue-900 mb-1">@lang('What happens next?')</h4>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li>• @lang('Your order will be sent to the influencer for review')</li>
                                <li>• @lang('You will be notified once the influencer accepts your order')</li>
                                <li>• @lang('You can track progress and communicate through your dashboard')</li>
                                <li>• @lang('Payment will be processed according to your selected method')</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/datepicker.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Initialize datepicker
            $('.datepicker-here').datepicker({
                changeYear: true,
                changeMonth: true,
                minDate: new Date(),
            });

            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endpush
