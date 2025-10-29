@foreach ($conversationMessage->reverse() as $conversation)
@php
if($conversation->order_id){
    $type = 'order';
}elseif($conversation->hiring_id){
    $type = 'hiring';
}else{
    $type = 'conversation';
}
@endphp
    @if ($conversation->sender == 'influencer')
        <!-- Outgoing message (from influencer) -->
        <div class="flex justify-end">
            <div class="max-w-xs lg:max-w-md">
                <div class="bg-blue-600 text-white rounded-lg px-4 py-2">
                    @if ($conversation->message)
                        <p class="text-sm">{{ __($conversation->message) }}</p>
                    @endif
                    @if ($conversation->attachments)
                        <div class="mt-2 space-y-1">
                            @foreach (json_decode($conversation->attachments) as $key => $attachment)
                                <a href="{{ localized_route('attachment.download', [$attachment, $conversation->id, $type]) }}"
                                   class="flex items-center space-x-2 text-blue-100 hover:text-white text-xs">
                                    <i data-lucide="paperclip" class="h-3 w-3"></i>
                                    <span>Attachment {{ ++$key }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="text-xs text-gray-500 text-right mt-1">
                    {{ diffForHumans($conversation->created_at) }}
                </div>
            </div>
        </div>
    @else
        <!-- Incoming message (from client/admin) -->
        <div class="flex justify-start">
            <div class="max-w-xs lg:max-w-md">
                <div class="bg-gray-100 text-gray-900 rounded-lg px-4 py-2">
                    @if ($conversation->message)
                        @if ($conversation->sender == 'admin')
                            <div class="flex items-center space-x-2 mb-1">
                                <i data-lucide="shield-check" class="h-3 w-3 text-red-600"></i>
                                <span class="text-xs font-medium text-red-600">Admin</span>
                            </div>
                            <p class="text-sm font-medium">{{ __($conversation->message) }}</p>
                        @else
                            <p class="text-sm">{{ __($conversation->message) }}</p>
                        @endif
                    @endif
                    @if ($conversation->attachments)
                        <div class="mt-2 space-y-1">
                            @foreach (json_decode($conversation->attachments) as $key => $attachment)
                                <a href="{{ localized_route('attachment.download', [$attachment, $conversation->id, $type]) }}"
                                   class="flex items-center space-x-2 text-blue-600 hover:text-blue-800 text-xs">
                                    <i data-lucide="paperclip" class="h-3 w-3"></i>
                                    <span>Attachment {{ ++$key }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    {{ diffForHumans($conversation->created_at) }}
                </div>
            </div>
        </div>
    @endif
@endforeach
