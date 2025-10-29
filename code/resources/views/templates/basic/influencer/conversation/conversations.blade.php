@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Messages</h1>
        <p class="text-gray-600">Gérez vos conversations avec les clients</p>
    </div>

    <!-- Conversations List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Conversations Sidebar -->
        <div class="lg:col-span-1">
            <div class="card h-[calc(100vh-12rem)]">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h2 class="card-title">Conversations</h2>
                        <span class="badge badge-default">{{ $conversations->total() }}</span>
                    </div>
                    <!-- Search -->
                    <div class="mt-4">
                        <form method="GET" class="relative">
                            <input type="text"
                                   name="search"
                                   value="{{ request()->search }}"
                                   placeholder="Rechercher..."
                                   class="input w-full pl-10">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                        </form>
                    </div>
                </div>
                <div class="card-content p-0 overflow-y-auto">
                    @forelse($conversations as $conversation)
                    <div class="conversation-item {{ request()->route('id') == $conversation->id ? 'active' : '' }}"
                         onclick="window.location.href='{{ localized_route('influencer.conversation.view', $conversation->id) }}'">
                        <div class="flex items-center space-x-3 p-4 hover:bg-gray-50 cursor-pointer border-b">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                @if($conversation->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile').'/'.$conversation->user->image, getFileSize('userProfile')) }}"
                                         alt="{{ $conversation->user->fullname }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">
                                            {{ substr($conversation->user->fullname ?? $conversation->user->username, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                @if($conversation->unread_count > 0)
                                    <div class="w-3 h-3 bg-red-500 rounded-full border-2 border-white -mt-8 ml-7"></div>
                                @endif
                            </div>

                            <!-- Conversation Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $conversation->user->fullname ?? $conversation->user->username }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : 'Aucun message' }}
                                    </p>
                                </div>
                                <p class="text-sm text-gray-600 truncate">
                                    {{ $conversation->last_message ?? 'Aucun message' }}
                                </p>
                                @if($conversation->subject)
                                <p class="text-xs text-blue-600 truncate">
                                    Sujet: {{ $conversation->subject }}
                                </p>
                                @endif
                            </div>

                            <!-- Unread Badge -->
                            @if($conversation->unread_count > 0)
                            <div class="flex-shrink-0">
                                <span class="badge badge-destructive">{{ $conversation->unread_count }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <i data-lucide="message-circle" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune conversation</h3>
                        <p class="text-gray-600">
                            @if(request()->search)
                                Aucune conversation trouvée pour "{{ request()->search }}"
                            @else
                                Vous n'avez pas encore de conversations
                            @endif
                        </p>
                    </div>
                    @endforelse
                </div>
                @if($conversations->hasPages())
                <div class="card-footer">
                    {{ $conversations->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-2">
            @if(isset($currentConversation))
                <div class="card h-[calc(100vh-12rem)] flex flex-col">
                    <!-- Chat Header -->
                    <div class="card-header border-b">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                @if($currentConversation->user->image)
                                    <img src="{{ getImage(getFilePath('userProfile').'/'.$currentConversation->user->image, getFileSize('userProfile')) }}"
                                         alt="{{ $currentConversation->user->fullname }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">
                                            {{ substr($currentConversation->user->fullname ?? $currentConversation->user->username, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        {{ $currentConversation->user->fullname ?? $currentConversation->user->username }}
                                    </h3>
                                    @if($currentConversation->subject)
                                        <p class="text-sm text-gray-600">{{ $currentConversation->subject }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" class="btn btn-ghost btn-sm">
                                    <i data-lucide="phone" class="h-4 w-4"></i>
                                </button>
                                <button type="button" class="btn btn-ghost btn-sm">
                                    <i data-lucide="video" class="h-4 w-4"></i>
                                </button>
                                <button type="button" class="btn btn-ghost btn-sm">
                                    <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messagesContainer">
                        @forelse($messages as $message)
                        <div class="message {{ $message->influencer_id ? 'message-sent' : 'message-received' }}">
                            <div class="flex {{ $message->influencer_id ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="message-bubble {{ $message->influencer_id ? 'sent' : 'received' }}">
                                        <p class="text-sm">{{ $message->message }}</p>
                                        @if($message->attachments && is_array($message->attachments))
                                            <div class="mt-2 space-y-1">
                                                @foreach($message->attachments as $attachment)
                                                <a href="{{ localized_route('influencer.conversation.download', $attachment['id']) }}"
                                                   class="flex items-center space-x-2 text-xs text-blue-600 hover:text-blue-800">
                                                    <i data-lucide="paperclip" class="h-3 w-3"></i>
                                                    <span>{{ $attachment['name'] }}</span>
                                                </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 {{ $message->influencer_id ? 'text-right' : 'text-left' }}">
                                        {{ $message->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <i data-lucide="message-circle" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                                <p class="text-gray-600">Aucun message dans cette conversation</p>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Message Input -->
                    <div class="card-footer border-t">
                        <form method="POST" action="{{ localized_route('influencer.conversation.store', $currentConversation->id) }}"
                              enctype="multipart/form-data" class="space-y-3">
                            @csrf

                            <div class="flex items-end space-x-3">
                                <!-- Attachment Button -->
                                <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('attachments').click()">
                                    <i data-lucide="paperclip" class="h-4 w-4"></i>
                                </button>

                                <!-- Message Input -->
                                <div class="flex-1">
                                    <textarea name="message"
                                              id="messageInput"
                                              rows="1"
                                              placeholder="Tapez votre message..."
                                              class="input w-full resize-none"
                                              required></textarea>
                                </div>

                                <!-- Send Button -->
                                <button type="submit" class="btn btn-primary btn-default">
                                    <i data-lucide="send" class="h-4 w-4"></i>
                                </button>
                            </div>

                            <!-- Hidden File Input -->
                            <input type="file" id="attachments" name="attachments[]" multiple class="hidden" onchange="showSelectedFiles()">

                            <!-- Selected Files Display -->
                            <div id="selectedFiles" class="hidden space-y-2"></div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card h-[calc(100vh-12rem)] flex items-center justify-center">
                    <div class="text-center">
                        <i data-lucide="message-square" class="h-16 w-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Sélectionnez une conversation</h3>
                        <p class="text-gray-600">Choisissez une conversation dans la liste pour commencer à discuter</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('style')
<style>
.conversation-item.active {
    @apply bg-blue-50 border-l-4 border-blue-500;
}

.message-bubble {
    @apply px-4 py-2 rounded-lg;
}

.message-bubble.sent {
    @apply bg-blue-500 text-white;
}

.message-bubble.received {
    @apply bg-gray-200 text-gray-900;
}

#messagesContainer {
    scroll-behavior: smooth;
}
</style>
@endpush

@push('script')
<script>
function showSelectedFiles() {
    const fileInput = document.getElementById('attachments');
    const selectedFilesDiv = document.getElementById('selectedFiles');

    if (fileInput.files.length > 0) {
        selectedFilesDiv.classList.remove('hidden');
        selectedFilesDiv.innerHTML = '';

        Array.from(fileInput.files).forEach((file, index) => {
            const fileDiv = document.createElement('div');
            fileDiv.className = 'flex items-center justify-between bg-gray-50 p-2 rounded';
            fileDiv.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i data-lucide="file" class="h-4 w-4 text-gray-500"></i>
                    <span class="text-sm text-gray-700">${file.name}</span>
                </div>
                <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            `;
            selectedFilesDiv.appendChild(fileDiv);
        });

        lucide.createIcons();
    } else {
        selectedFilesDiv.classList.add('hidden');
    }
}

function removeFile(index) {
    const fileInput = document.getElementById('attachments');
    const dt = new DataTransfer();

    Array.from(fileInput.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });

    fileInput.files = dt.files;
    showSelectedFiles();
}

// Auto-resize textarea
document.getElementById('messageInput').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

// Scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    lucide.createIcons();
});

// Send message on Enter (but not Shift+Enter)
document.getElementById('messageInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        this.closest('form').submit();
    }
});
</script>
@endpush
@endsection