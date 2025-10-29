@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">@lang('المحادثة')</h2>
        <a href="{{ localized_route('user.conversation.index') }}" class="btn btn-outline">
            <i data-lucide="arrow-right" class="w-4 h-4 mr-2"></i>
            @lang('العودة للمحادثات')
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Chat Header -->
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <i data-lucide="user" class="h-5 w-5 text-gray-600"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ __($influencer->username) }}</h3>
                        @if ($influencer->status == 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i data-lucide="ban" class="w-3 h-3 mr-1"></i>
                                @lang('محظور')
                            </span>
                        @else
                            @if ($influencer && $influencer->isOnline())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                                    @lang('متصل')
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full mr-1"></div>
                                    @lang('غير متصل')
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
                <button class="btn btn-outline reloadBtn">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                    @lang('تحديث')
                </button>
            </div>
        </div>

        <!-- Chat Body -->
        <div class="relative flex flex-col h-96">
            <div class="flex-1 overflow-y-auto chat__msg-body" style="height: 400px;">
                <div class="message-loader-wrapper hidden">
                    <div class="flex justify-center p-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                </div>
                <ul class="space-y-4 p-4 msg__wrapper" id="message">
                    @if ($influencer)
                        @include($activeTemplate . 'user.conversation.message')
                    @endif
                </ul>
            </div>

            <!-- Chat Footer -->
            <div class="border-t border-gray-200 p-4">
                <div class="file-count text-sm text-blue-600 mb-2"></div>
                <form method="POST" action="{{ localized_route('user.conversation.store', @$conversation->id) }}" class="send__msg space-y-3" enctype="multipart/form-data" id="messageForm">
                    @csrf
                    <div class="flex space-x-2 rtl:space-x-reverse">
                        <div class="flex-1">
                            <textarea name="message"
                                    class="input w-full messageVal resize-none"
                                    rows="2"
                                    placeholder="@lang('اكتب رسالتك هنا...')"
                                    required></textarea>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label for="upload-file" class="btn btn-outline cursor-pointer">
                                <i data-lucide="paperclip" class="w-4 h-4"></i>
                            </label>
                            <button class="btn btn-primary send-btn" type="submit">
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <input id="upload-file"
                           type="file"
                           name="attachments[]"
                           class="hidden"
                           accept=".png, .jpg, .jpeg, .pdf, .doc, .docx, .txt"
                           multiple>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    (function($) {
        "use strict";

        // Initialize
        $('.message-loader-wrapper').addClass('hidden');

        // File upload handler
        $('#upload-file').on('change', function() {
            var fileCount = $(this)[0].files.length;
            if (fileCount > 0) {
                $('.file-count').text(`${fileCount} ملف محدد`).removeClass('hidden');
            } else {
                $('.file-count').text('').addClass('hidden');
            }
        });

        // Reload messages
        $(".reloadBtn").on('click', function() {
            loadMore(10);
        });

        var messageCount = 10;

        // Scroll to load more messages
        $(".chat__msg-body").on('scroll', function() {
            if($(this).scrollTop() == 0) {
                messageCount += 10;
                loadMore(messageCount);
            }
        });

        function loadMore(messageCount) {
            $('.message-loader-wrapper').removeClass('hidden');
            $.ajax({
                method: "GET",
                data: {
                    conversation_id: `{{ @$conversation->id }}`,
                    messageCount: messageCount
                },
                url: "{{ localized_route('user.conversation.message')}}",
                success: function(response) {
                    $("#message").html(response);
                }
            }).done(function() {
                $('.message-loader-wrapper').addClass('hidden');
            });
        }

        // Send message
        $("#messageForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);

            // Disable send button
            $('.send-btn').prop('disabled', true).addClass('opacity-50');

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                url: "{{ localized_route('user.conversation.store', @$conversation->id) }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.error) {
                        notify('error', response.error);
                    } else {
                        $('#messageForm')[0].reset();
                        $('.file-count').text('').addClass('hidden');
                        $("#message").append(response);
                        scrollHeight();
                    }
                },
                complete: function() {
                    // Re-enable send button
                    $('.send-btn').prop('disabled', false).removeClass('opacity-50');
                }
            });
        });

        // Auto-scroll to bottom function
        function scrollHeight() {
            var chatBody = $('.chat__msg-body');
            chatBody.scrollTop(chatBody[0].scrollHeight);
        }

        // Initial scroll to bottom
        setTimeout(scrollHeight, 100);

    })(jQuery);
</script>
@endpush
