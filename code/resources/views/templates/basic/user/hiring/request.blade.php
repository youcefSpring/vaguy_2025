@extends('layouts.dashboard')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <!-- Influencer Avatar -->
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                        @if(isset($influencer->image))
                        <img src="{{ getImage(getFilePath('influencerProfile') . '/' . $influencer->image, getFileSize('influencerProfile'), true) }}"
                             alt="{{ $influencer->fullname }}"
                             class="w-full h-full object-cover">
                        @else
                        <img src="{{ asset('assets/user_profile.png')}}"
                             alt="{{ $influencer->fullname }}"
                             class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">@lang('طلب توظيف مؤثر')</h1>
                        <p class="text-gray-600">{{ __($influencer->fullname) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <form action="{{ localized_route('user.hiring.influencer', $influencer->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">@lang('طلب التوظيف')</label>
                            <input type="text"
                                   name="title"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   value="@lang('طلب التوظيف')"
                                   required>
                        </div>

                        <!-- Delivery Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">@lang('تاريخ التسليم المقدر')</label>
                            <input type="text"
                                   class="datepicker-here w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   data-language='en'
                                   data-date-format="yyyy-mm-dd"
                                   data-position='bottom left'
                                   placeholder="@lang('Select date')"
                                   name="delivery_date"
                                   autocomplete="off"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">
                                <i data-lucide="info" class="h-4 w-4 inline mr-1"></i>
                                @lang('تاريخ-السنة-الشهر')
                            </p>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">@lang('أجرة')</label>
                            <div class="relative">
                                <input type="number"
                                       min="0"
                                       step="any"
                                       class="w-full px-4 py-3 pr-20 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       name="amount"
                                       value="{{ old('amount') }}"
                                       required>
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">{{ $general->cur_text }}</span>
                            </div>
                        </div>

                        <!-- Payment Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">@lang('نوع الدفع')</label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    name="payment_type"
                                    required>
                                <option value="" disabled selected>@lang('اختر واحدة')</option>
                                <option value="2">@lang('الدفع المباشر')</option>
                                <option value="1">@lang('المحفظة المودعة') ({{ showAmount(auth()->user()->balance) }} {{ $general->cur_text }})</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="description">@lang('وصف')</label>
                        <textarea rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none"
                                  name="description"
                                  id="description"
                                  placeholder="@lang('Description')">{{ old('description') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i data-lucide="send" class="h-5 w-5 inline mr-2"></i>
                            @lang('إرسال')
                        </button>
                    </div>
                </form>
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
        $('.datepicker-here').datepicker({
            changeYear: true,
            changeMonth: true,
            minDate: new Date(),
        });
    </script>
@endpush
