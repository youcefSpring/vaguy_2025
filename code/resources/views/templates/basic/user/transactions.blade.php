@extends('layouts.dashboard')
@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">@lang('المعاملات')</h2>
        <button type="button" class="btn btn-outline showFilterBtn">
            <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
            @lang('فلتر')
        </button>
    </div>
</div>
<div class="bg-white shadow rounded-lg mb-6 responsive-filter-card">
    <div class="p-6">
        <form action="" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-group">
                    <label class="form-label">@lang('رقم العملية')</label>
                    <input type="text" name="search" value="{{ request()->search }}" class="input">
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('الصنف')</label>
                    <select name="type" class="input">
                        <option value="">@lang('الكل')</option>
                        <option value="+" @selected(request()->type == '+')>@lang('زيادة')</option>
                        <option value="-" @selected(request()->type == '-')>@lang('نقصان')</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('ملاحظة')</label>
                    <select class="input" name="remark">
                        <option value="">@lang('الكل')</option>
                        @foreach ($remarks as $remark)
                            <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                {{ __(keyToTitle($remark->remark)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary w-full">
                        <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                        @lang('فلتر')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('رقم العملية')</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('عملية منجزة')</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المبلغ')</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('بعد الميزانية')</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('التفاصيل')</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $trx->trx }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span>{{ showDateTime($trx->created_at) }}</span><br>
                            <span class="text-xs text-gray-400">{{ diffForHumans($trx->created_at) }}</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <span class="font-bold {{ $trx->trx_type == '+' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trx->trx_type }} {{ showAmount($trx->amount) }} {{ $general->cur_text }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ showAmount($trx->post_balance) }} {{ __($general->cur_text) }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">{{ __($trx->details) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i data-lucide="inbox" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد معاملات</h3>
                            <p class="text-gray-500">{{ __($emptyMessage) }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showFilterBtn = document.querySelector('.showFilterBtn');
        const filterCard = document.querySelector('.responsive-filter-card');

        // Initially hide the filter card on small screens
        if (window.innerWidth < 768) {
            filterCard.style.display = 'none';
        }

        showFilterBtn.addEventListener('click', function() {
            if (filterCard.style.display === 'none') {
                filterCard.style.display = 'block';
                this.innerHTML = '<i data-lucide="x" class="w-4 h-4 mr-2"></i>@lang("إخفاء الفلتر")';
            } else {
                filterCard.style.display = 'none';
                this.innerHTML = '<i data-lucide="filter" class="w-4 h-4 mr-2"></i>@lang("فلتر")';
            }

            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Show filter card on larger screens
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                filterCard.style.display = 'block';
                showFilterBtn.innerHTML = '<i data-lucide="filter" class="w-4 h-4 mr-2"></i>@lang("فلتر")';
            } else {
                filterCard.style.display = 'none';
                showFilterBtn.innerHTML = '<i data-lucide="filter" class="w-4 h-4 mr-2"></i>@lang("فلتر")';
            }

            // Re-initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    });
</script>
@endpush
