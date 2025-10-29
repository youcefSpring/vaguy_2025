@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">@lang('تاريخ الودائع')</h2>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        <form action="" class="flex justify-end mb-4">
            <div class="flex">
                <input type="text" name="search" class="input rounded-r-none" value="{{ request()->search }}"
                    placeholder="@lang('البحث عن طريق المعاملات')">
                <button type="submit" class="btn btn-primary rounded-l-none">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('بوابة | عملية')</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('بدأت النشاط')</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('المبلغ')</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('التحويل')</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('الحالة')</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">@lang('التفاصيل')</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($deposits as $deposit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ __($deposit->gateway?->name) }}</div>
                                    <div class="text-sm text-gray-500">{{ $deposit->trx }}</div>
                                </div>
                            </td>
    
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ showDateTime($deposit->created_at) }}</div>
                                <div class="text-xs text-gray-400">{{ diffForHumans($deposit->created_at) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ __($general->cur_sym) }}{{ showAmount($deposit->amount) }} +
                                    <span class="text-red-600" title="@lang('التكلفة')">{{ showAmount($deposit->charge) }}</span>
                                </div>
                                <div class="text-sm font-medium text-gray-900" title="@lang('المبلغ مع التكلفة')">
                                    {{ showAmount($deposit->amount + $deposit->charge) }} {{ $general->cur_text }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    1 {{ __($general->cur_text) }} = {{ showAmount($deposit->rate) }} {{ __($deposit->method_currency) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ showAmount($deposit->final_amo) }} {{ __($deposit->method_currency) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php echo $deposit->statusBadge @endphp
                            </td>
                            @php
                                $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                            @endphp
    
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button type="button"
                                    class="btn btn-outline @if ($deposit->method_code >= 1000) detailBtn @else opacity-50 cursor-not-allowed @endif"
                                    @if ($deposit->method_code >= 1000) data-info="{{ $details }}" @endif
                                    @if ($deposit->status == 3) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                    @lang('التفاصيل')
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i data-lucide="inbox" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد ودائع</h3>
                                <p class="text-gray-500">{{ __($emptyMessage) }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DETAIL MODAL --}}
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-lg font-medium text-gray-900">@lang('التفاصيل')</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal()">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="mt-4">
                <div class="userData space-y-2"></div>
                <div class="feedback mt-4"></div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
    <script>
        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const detailBtns = document.querySelectorAll('.detailBtn');

            detailBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const modal = document.getElementById('detailModal');
                    const userData = this.dataset.info;

                    let html = '';
                    if (userData) {
                        const data = JSON.parse(userData);
                        data.forEach(element => {
                            if (element.type !== 'file') {
                                html += `
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span class="text-sm font-medium text-gray-900">${element.name}</span>
                                        <span class="text-sm text-gray-500">${element.value}</span>
                                    </div>`;
                            }
                        });
                    }

                    modal.querySelector('.userData').innerHTML = html;

                    let adminFeedback = '';
                    if (this.dataset.admin_feedback) {
                        adminFeedback = `
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">@lang('Admin Feedback')</h4>
                                <p class="text-sm text-gray-700">${this.dataset.admin_feedback}</p>
                            </div>
                        `;
                    }

                    modal.querySelector('.feedback').innerHTML = adminFeedback;
                    modal.classList.remove('hidden');
                });
            });
        });
    </script>
@endpush
