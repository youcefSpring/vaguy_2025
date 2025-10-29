@php
if($message->order_id){
    $type = 'order';
}elseif($message->hiring_id){
    $type = 'hiring';
}else{
    $type = 'conversation';
}
@endphp
<!-- New message from influencer -->
<div class="flex justify-end">
    <div class="max-w-xs lg:max-w-md">
        <div class="bg-blue-600 text-white rounded-lg px-4 py-2">
            @if ($message->message)
                <p class="text-sm">{{ __($message->message) }}</p>
            @endif
            @if ($message->attachments)
                <div class="mt-2 space-y-1">
                    @foreach (json_decode($message->attachments) as $key => $attachment)
                        <a href="{{ localized_route('attachment.download', [$attachment, $message->id, $type]) }}"
                           class="flex items-center space-x-2 text-blue-100 hover:text-white text-xs">
                            <i data-lucide="paperclip" class="h-3 w-3"></i>
                            <span>Attachment {{ ++$key }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="text-xs text-gray-500 text-right mt-1">
            {{ diffForHumans($message->created_at) }}
        </div>
    </div>
</div>