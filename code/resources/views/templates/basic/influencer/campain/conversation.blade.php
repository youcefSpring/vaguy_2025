@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Campaign: {{ $campain->campain_name ?? 'Campaign' }}</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <div class="h-2 w-2 rounded-full {{ $user && $user->isUserOnline() ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                <span class="text-sm text-gray-600">
                    {{ $user->fullname ?? $user->username ?? 'Client' }}
                    @if ($user->status == 0)
                        <span class="text-red-600 text-xs">(Banned)</span>
                    @else
                        <span class="text-xs">({{ $user && $user->isUserOnline() ? 'Online' : 'Offline' }})</span>
                    @endif
                </span>
            </div>
            <button class="btn btn-outline btn-sm reloadBtn">
                <i data-lucide="refresh-cw" class="h-4 w-4 mr-2"></i>
                Reload
            </button>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="card h-[70vh] flex flex-col">
        <!-- Chat Messages -->
        <div class="flex-1 flex flex-col min-h-0">
            <div class="flex-1 overflow-y-auto p-6 space-y-4" id="chat-container">
                <!-- Loading indicator -->
                <div class="message-loader-wrapper hidden">
                    <div class="flex justify-center py-4">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    </div>
                </div>

                <!-- Messages container -->
                <div id="message" class="space-y-4">
                    @if($user)
                        @include('templates.basic.influencer.conversation.message')
                    @endif
                </div>
            </div>
        </div>

        <!-- Message Input -->
        @if (!($campain->status == 1 || $campain->status == 5 || $campain->status == 6))
        <div class="border-t border-gray-200 p-4">
            <form id="messageForm" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <!-- File count display -->
                <div class="file-count text-sm text-gray-600"></div>

                <!-- Message input area -->
                <div class="flex space-x-3">
                    <div class="flex-1">
                        <textarea
                            name="message"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="Write your message..."
                            rows="3"
                            required></textarea>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label for="upload-file" class="btn btn-ghost btn-sm cursor-pointer" title="Add Attachment">
                            <i data-lucide="paperclip" class="h-4 w-4"></i>
                        </label>
                        <button type="submit" class="btn btn-primary btn-sm px-6">
                            <i data-lucide="send" class="h-4 w-4 mr-2"></i>
                            Send
                        </button>
                    </div>
                </div>

                <!-- Hidden file input -->
                <input
                    id="upload-file"
                    type="file"
                    name="attachments[]"
                    class="hidden"
                    accept=".png, .jpg, .jpeg, .pdf, .doc, .docx, .txt"
                    multiple>
            </form>
        </div>
        @else
        <div class="border-t border-gray-200 p-4 bg-gray-50 text-center">
            <p class="text-gray-600">This conversation is closed.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();

    const messageContainer = document.getElementById('message');
    const chatContainer = document.getElementById('chat-container');
    const messageForm = document.getElementById('messageForm');
    const fileInput = document.getElementById('upload-file');
    const fileCount = document.querySelector('.file-count');
    const reloadBtn = document.querySelector('.reloadBtn');
    const messageLoaderWrapper = document.querySelector('.message-loader-wrapper');

    let messageCount = 10;

    // Hide loader initially
    if (messageLoaderWrapper) {
        messageLoaderWrapper.classList.add('hidden');
    }

    // File upload handler
    fileInput.addEventListener('change', function() {
        const files = this.files.length;
        fileCount.textContent = files > 0 ? `${files} file(s) selected` : '';
    });

    // Reload button handler
    reloadBtn.addEventListener('click', function() {
        loadMore(10);
    });

    // Scroll to load more messages
    chatContainer.addEventListener('scroll', function() {
        if (this.scrollTop === 0) {
            messageCount += 10;
            loadMore(messageCount);
        }
    });

    // Load more messages function
    function loadMore(count) {
        if (messageLoaderWrapper) {
            messageLoaderWrapper.classList.remove('hidden');
        }

        const params = new URLSearchParams({
            campain_id: `{{ @$campain->id }}`,
            messageCount: count
        });

        fetch(`{{ localized_route('influencer.campain.conversation.message') }}?${params}`, {
            method: "GET",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            messageContainer.innerHTML = html;
            if (messageLoaderWrapper) {
                messageLoaderWrapper.classList.add('hidden');
            }
            // Re-initialize Lucide icons for new content
            lucide.createIcons();
        })
        .catch(error => {
            console.error('Error loading messages:', error);
            if (messageLoaderWrapper) {
                messageLoaderWrapper.classList.add('hidden');
            }
        });
    }

    // Message form submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;

        // Disable submit button
        submitButton.disabled = true;
        submitButton.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 mr-2 animate-spin"></i>Sending...';
        lucide.createIcons();

        fetch("{{ localized_route('influencer.campain.conversation.store', @$campain->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            if (html.includes('error')) {
                // Handle error response
                console.error('Message send error');
            } else {
                // Success - append new message
                messageContainer.insertAdjacentHTML('beforeend', html);
                messageForm.reset();
                fileCount.textContent = '';
                scrollToBottom();
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        })
        .finally(() => {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
            lucide.createIcons();
        });
    });

    // Scroll to bottom function
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Initial scroll to bottom
    setTimeout(scrollToBottom, 100);
});
</script>
@endpush
