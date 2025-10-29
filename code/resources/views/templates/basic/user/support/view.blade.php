@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    @if ($layout == 'frontend')
        <div class="pt-80 pb-80">
            <div class="container">
    @endif

    <!-- Ticket Header -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start flex-wrap gap-3">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <i data-lucide="ticket" class="h-6 w-6 text-blue-600 flex-shrink-0"></i>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900 mb-1">
                            [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                        </h1>
                        <div class="flex items-center">
                            @php echo $myTicket->statusBadge; @endphp
                        </div>
                    </div>
                </div>
                @if ($myTicket->status != 3 && $myTicket->user)
                    <button class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 confirmationBtn"
                            type="button"
                            data-question="@lang('Are you sure to close this ticket?')"
                            data-action="{{ localized_route('ticket.close', $myTicket->id) }}"
                            data-btn_class="btn btn--base btn--md">
                        <i data-lucide="x-circle" class="h-4 w-4 mr-2"></i>
                        @lang('إغلاق التذكرة')
                    </button>
                @endif
            </div>
        </div>

        @if ($myTicket->status != 4)
            <div class="p-6">
                <h3 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                    <i data-lucide="message-square" class="h-5 w-5 mr-2 text-blue-600"></i>
                    @lang('إضافة رد جديد')
                </h3>

                <form method="post" action="{{ localized_route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="inputMessage" class="block text-sm font-medium text-gray-700 mb-2">@lang('رسالتك')</label>
                        <div class="relative">
                            <textarea name="message"
                                      class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 resize-vertical"
                                      id="inputMessage"
                                      placeholder="@lang('اكتب ردك هنا...')"
                                      rows="5"
                                      required>{{ old('message') }}</textarea>
                            <i data-lucide="edit-3" class="absolute top-3 left-3 h-4 w-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <label for="inputAttachments" class="block text-sm font-medium text-gray-700 mb-2">@lang('المرفقات')</label>
                        <div class="space-y-3">
                            <div class="flex gap-2" id="attachment-container">
                                <div class="flex-1 relative">
                                    <input type="file"
                                           name="attachments[]"
                                           id="inputAttachments"
                                           class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                    <i data-lucide="paperclip" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"></i>
                                </div>
                                <button type="button"
                                        class="inline-flex items-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 addFile">
                                    <i data-lucide="plus" class="h-4 w-4"></i>
                                </button>
                            </div>
                            <div id="fileUploadsContainer" class="space-y-2"></div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <i data-lucide="info" class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                                    <div class="mr-3">
                                        <p class="text-sm text-blue-800">
                                            <span class="font-medium">@lang('ملاحظات مهمة'):</span>
                                        </p>
                                        <ul class="text-sm text-blue-700 mt-1 space-y-1">
                                            <li>• @lang('الحد الأقصى للملفات'): 5 ملفات</li>
                                            <li>• @lang('الحد الأقصى لحجم الملف'): {{ ini_get('upload_max_filesize') }}</li>
                                            <li>• @lang('الامتدادات المسموحة'): .jpg, .jpeg, .png, .pdf, .doc, .docx</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm">
                            <i data-lucide="send" class="h-5 w-5 mr-2"></i>
                            @lang('إرسال الرد')
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
    <!-- Conversation History -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i data-lucide="message-circle" class="h-5 w-5 mr-2 text-blue-600"></i>
                @lang('سجل المحادثة')
            </h3>
        </div>

        <div class="p-6">
            <div class="space-y-6">
                @foreach ($messages as $message)
                    @if ($message->admin_id == 0)
                        <!-- User Message -->
                        <div class="flex justify-end">
                            <div class="max-w-3xl w-full">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                                <i data-lucide="user" class="h-4 w-4 text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-sm font-medium text-blue-900">{{ @$message->ticket->name }}</h4>
                                                <time class="text-xs text-blue-700">
                                                    {{ $message->created_at->format('j/n/Y - H:i') }}
                                                </time>
                                            </div>
                                            <div class="text-sm text-blue-800 whitespace-pre-wrap">{{ __($message->message) }}</div>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ localized_route('ticket.download', encrypt($image->id)) }}"
                                                           class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-lg text-xs font-medium text-blue-700 bg-white hover:bg-blue-50 transition-colors duration-200">
                                                            <i data-lucide="paperclip" class="h-3 w-3 mr-1"></i>
                                                            @lang('مرفق') {{ ++$k }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Admin Message -->
                        <div class="flex justify-start">
                            <div class="max-w-3xl w-full">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-amber-600 flex items-center justify-center">
                                                <i data-lucide="shield-check" class="h-4 w-4 text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-sm font-medium text-amber-900">{{ @$message->admin->name }} <span class="text-xs text-amber-700">(@lang('الدعم الفني'))</span></h4>
                                                <time class="text-xs text-amber-700">
                                                    {{ $message->created_at->format('j/n/Y - H:i') }}
                                                </time>
                                            </div>
                                            <div class="text-sm text-amber-800 whitespace-pre-wrap">{{ __($message->message) }}</div>
                                            @if ($message->attachments->count() > 0)
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach ($message->attachments as $k => $image)
                                                        <a href="{{ localized_route('ticket.download', encrypt($image->id)) }}"
                                                           class="inline-flex items-center px-3 py-1 border border-amber-300 rounded-lg text-xs font-medium text-amber-700 bg-white hover:bg-amber-50 transition-colors duration-200">
                                                            <i data-lucide="paperclip" class="h-3 w-3 mr-1"></i>
                                                            @lang('مرفق') {{ ++$k }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if($messages->isEmpty())
                    <div class="text-center py-8">
                        <i data-lucide="message-square" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('لا توجد رسائل')</h3>
                        <p class="text-gray-600">@lang('لم يتم إرسال أي رسائل في هذه التذكرة بعد')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if ($layout == 'frontend')
        </div>
        </div>
    @endif
    <x-confirmation-modal></x-confirmation-modal>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'لقد وصلت للحد الأقصى من الملفات');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="flex gap-2">
                        <div class="flex-1 relative">
                            <input type="file" name="attachments[]" class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900" required/>
                            <i data-lucide="paperclip" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"></i>
                        </div>
                        <button class="inline-flex items-center px-4 py-3 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 remove-btn" type="button">
                            <i data-lucide="x" class="h-4 w-4"></i>
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
