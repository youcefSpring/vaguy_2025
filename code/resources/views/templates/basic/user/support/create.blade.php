@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">@lang('أنشئ بطاقة')</h2>
        <a href="{{ localized_route('ticket') }}" class="btn btn-outline">
            <i data-lucide="ticket" class="w-4 h-4 mr-2"></i>
            @lang('بطاقاتي')
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ localized_route('ticket.store') }}" method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();" class="space-y-6">
            @csrf
            <input type="hidden" name="name" value="{{ @$user->fullname }}">
            <input type="hidden" name="email" value="{{ @$user->email }}">
            <input type="hidden" name="campain_offer_id" value="{{ @$campain_offer_id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">@lang('الموضوع')</label>
                    <div class="relative">
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                               class="input w-full" required placeholder="@lang('أدخل موضوع التذكرة')">
                        <i data-lucide="type" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('الأولوية')</label>
                    <div class="relative">
                        <select name="priority" class="input w-full" required>
                            <option value="3">@lang('أقصى')</option>
                            <option value="2">@lang('متوسط')</option>
                            <option value="1">@lang('أدنى')</option>
                        </select>
                        <i data-lucide="flag" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">@lang('الرسالة')</label>
                <div class="relative">
                    <textarea id="message" name="message" rows="6" class="input w-full" required placeholder="@lang('اكتب رسالتك هنا...')">{{ old('message') }}</textarea>
                    <i data-lucide="message-square" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">@lang('المرفقات')</label>
                <div class="space-y-3">
                    <div class="flex gap-2" id="attachment-container">
                        <div class="flex-1 relative">
                            <input type="file" class="input w-full" name="attachments[]" id="file2">
                            <i data-lucide="paperclip" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                        <button class="btn btn-outline addFile" type="button">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div id="more-attachment" class="space-y-2"></div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i data-lucide="info" class="w-4 h-4 text-blue-600 mr-2"></i>
                            <span class="text-sm text-blue-800">
                                @lang('امتدادات الملفات المسموح بها'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="btn btn-primary w-full md:w-auto" type="submit" id="recaptcha">
                    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                    @lang('حفظ')
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#more-attachment").append(`
                    <div class="flex gap-2">
                        <div class="flex-1 relative">
                            <input type="file" class="input w-full" name="attachments[]">
                            <i data-lucide="paperclip" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                        <button class="btn btn-outline-danger remove-btn" type="button">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                `);
                // Re-initialize Lucide icons for new elements
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.flex').remove();
            });
        })(jQuery);
    </script>
@endpush
