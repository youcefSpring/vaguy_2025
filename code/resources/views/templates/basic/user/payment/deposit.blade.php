@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 flex items-center">
            <i data-lucide="credit-card" class="h-5 w-5 ml-2 text-green-600"></i>
            @lang('إيداع أموال')
        </h3>
        <p class="mt-1 text-sm text-gray-600">@lang('قم بإيداع الأموال في محفظتك لبدء استخدام المنصة')</p>
    </div>

    <div class="p-6">
        <div class="max-w-2xl mx-auto">
            <form action="{{ localized_route('user.deposit.insert') }}" method="post" class="space-y-6">
                @csrf
                <input type="hidden" name="method_code">
                <input type="hidden" name="currency">

                <!-- Payment Method Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="wallet" class="inline h-4 w-4 ml-1 text-blue-600"></i>
                        @lang('حدد طريقة الدفع')
                    </label>
                    <select class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white"
                            name="gateway" required>
                        <option value="">@lang('اختر طريقة الدفع المناسبة')</option>
                        @foreach ($gatewayCurrency as $data)
                            <option value="{{ $data->method_code }}" @selected(old('gateway') == $data->method_code) data-gateway="{{ $data }}">
                                {{ $data->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="dollar-sign" class="inline h-4 w-4 ml-1 text-green-600"></i>
                        @lang('المبلغ')
                    </label>
                    <div class="relative">
                        <input type="number"
                               step="any"
                               name="amount"
                               class="block w-full pl-3 pr-20 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-lg font-medium"
                               value="{{ old('amount', @$amount) }}"
                               autocomplete="off"
                               required
                               @isset($amount) readonly @endisset
                               placeholder="0.00">
                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-3 rounded-r-lg border border-l-0 border-gray-300 text-sm font-medium">
                                {{ $general->cur_text }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Preview -->
                <div class="preview-details hidden">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="calculator" class="h-4 w-4 ml-2 text-blue-600"></i>
                            @lang('ملخص العملية')
                        </h4>

                        <div class="space-y-3">
                            <!-- Limits -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-600">@lang('الحد المسموح')</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    <span class="min">0</span> - <span class="max">0</span> {{ __($general->cur_text) }}
                                </span>
                            </div>

                            <!-- Charge -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-600">@lang('رسوم المعاملة')</span>
                                <span class="text-sm font-semibold text-orange-600">
                                    <span class="charge">0</span> {{ __($general->cur_text) }}
                                </span>
                            </div>

                            <!-- Total Payable -->
                            <div class="flex justify-between items-center py-3 bg-blue-50 px-4 rounded-lg">
                                <span class="text-md font-semibold text-gray-900">@lang('إجمالي المبلغ المستحق')</span>
                                <span class="text-lg font-bold text-blue-600">
                                    <span class="payable">0</span> {{ __($general->cur_text) }}
                                </span>
                            </div>

                            <!-- Rate Element (Hidden by default) -->
                            <div class="rate-element hidden flex justify-between items-center py-2">
                                <!-- Rate info will be populated by JavaScript -->
                            </div>

                            <!-- In Site Currency -->
                            <div class="in-site-cur hidden flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">
                                    @lang('المبلغ المستلم بـ') <span class="base-currency font-semibold"></span>
                                </span>
                                <span class="text-sm font-semibold text-green-600 final_amo">0</span>
                            </div>

                            <!-- Crypto Currency Note -->
                            <div class="crypto_currency hidden">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <i data-lucide="info" class="h-5 w-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                                        <div class="mr-3">
                                            <p class="text-sm text-yellow-800">
                                                @lang('سيتم التحويل باستخدام') <span class="method_currency font-semibold"></span>
                                                @lang('وستظهر القيمة النهائية في الخطوة التالية')
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                            class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 shadow-sm">
                        <i data-lucide="arrow-right" class="h-5 w-5 ml-2"></i>
                        @lang('متابعة عملية الإيداع')
                    </button>
                </div>
            </form>

            <!-- Help Section -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <i data-lucide="help-circle" class="h-6 w-6 text-blue-600 flex-shrink-0"></i>
                    <div class="mr-3">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">@lang('هل تحتاج للمساعدة؟')</h4>
                        <p class="text-sm text-blue-800 mb-3">@lang('تأكد من اختيار طريقة الدفع المناسبة واتباع التعليمات بعناية')</p>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• @lang('تحقق من المبلغ المدخل قبل المتابعة')</li>
                            <li>• @lang('احتفظ بمعلومات المعاملة للمراجعة')</li>
                            <li>• @lang('تواصل مع الدعم في حالة وجود أي مشكلة')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('select[name=gateway]').change(function() {
                if (!$('select[name=gateway]').val()) {
                    $('.preview-details').addClass('hidden');
                    return false;
                }
                var resource = $('select[name=gateway] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat(resource.rate)
                if (resource.method.crypto == 1) {
                    var toFixedDigit = 8;
                    $('.crypto_currency').removeClass('hidden');
                } else {
                    var toFixedDigit = 2;
                    $('.crypto_currency').addClass('hidden');
                }
                $('.min').text(parseFloat(resource.min_amount).toFixed(2));
                $('.max').text(parseFloat(resource.max_amount).toFixed(2));
                var amount = parseFloat($('input[name=amount]').val());
                if (!amount) {
                    amount = 0;
                }
                if (amount <= 0) {
                    $('.preview-details').addClass('hidden');
                    return false;
                }
                $('.preview-details').removeClass('hidden');
                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                var payable = parseFloat((parseFloat(amount) + parseFloat(charge))).toFixed(2);
                $('.payable').text(payable);
                var final_amo = (parseFloat((parseFloat(amount) + parseFloat(charge))) * rate).toFixed(
                    toFixedDigit);
                $('.final_amo').text(final_amo);
                if (resource.currency != '{{ $general->cur_text }}') {
                    var rateElement =
                        `<span class="text-sm font-medium text-gray-600">@lang('معدل التحويل')</span> <span class="text-sm font-semibold text-gray-900">1 {{ __($general->cur_text) }} = <span class="rate">${rate}</span> <span class="base-currency">${resource.currency}</span></span>`;
                    $('.rate-element').html(rateElement)
                    $('.rate-element').removeClass('hidden');
                    $('.in-site-cur').removeClass('hidden');
                } else {
                    $('.rate-element').html('')
                    $('.rate-element').addClass('hidden');
                    $('.in-site-cur').addClass('hidden');
                }
                $('.base-currency').text(resource.currency);
                $('.method_currency').text(resource.currency);
                $('input[name=currency]').val(resource.currency);
                $('input[name=method_code]').val(resource.method_code);
                $('input[name=amount]').on('input');
            });
            $('input[name=amount]').on('input', function() {
                $('select[name=gateway]').change();
                $('.amount').text(parseFloat($(this).val()).toFixed(2));
            });
        })(jQuery);
    </script>
@endpush
