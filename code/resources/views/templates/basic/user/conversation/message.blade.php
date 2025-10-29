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

@if ($conversation->sender == 'client')
    <!-- Outgoing Message (Client) -->
    <li class="flex justify-end">
        <div class="max-w-xs lg:max-w-md">
            <div class="bg-blue-600 text-white rounded-lg p-3 shadow">
                @if ($conversation->message)
                    <p class="text-sm">{{ __($conversation->message) }}</p>
                @endif

                @if ($conversation->attachments)
                    <div class="mt-2 space-y-1">
                        @foreach (json_decode($conversation->attachments) as $key => $attachment)
                            <a href="{{ localized_route('attachment.download', [$attachment, $conversation->id, $type]) }}"
                               class="flex items-center text-blue-100 hover:text-white text-xs">
                                <i data-lucide="paperclip" class="w-3 h-3 mr-1"></i>
                                @lang('مرفق') {{ ++$key }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="text-xs text-blue-100 mt-2">
                    {{ diffForHumans($conversation->created_at) }}
                </div>
            </div>
        </div>
    </li>
@else
    <!-- Incoming Message (Influencer/Admin) -->
    <li class="flex justify-start">
        <div class="max-w-xs lg:max-w-md">
            @if ($conversation->sender == 'admin')
                <div class="bg-red-100 border border-red-300 rounded-lg p-3 shadow">
                    <div class="flex items-center mb-2">
                        <i data-lucide="shield" class="w-4 h-4 text-red-600 mr-2"></i>
                        <span class="text-xs font-medium text-red-800">@lang('المدير')</span>
                    </div>
                    @if ($conversation->message)
                        <p class="text-sm text-red-900">{{ __($conversation->message) }}</p>
                    @endif
                </div>
            @else
                <div class="bg-gray-100 rounded-lg p-3 shadow">
                    @if ($conversation->message)
                        <p class="text-sm text-gray-900">{{ __($conversation->message) }}</p>
                    @endif
                </div>
            @endif

            @if ($conversation->attachments)
                <div class="mt-2 space-y-1">
                    @foreach (json_decode($conversation->attachments) as $key => $attachment)
                        <a href="{{ localized_route('attachment.download', [$attachment, $conversation->id, $type]) }}"
                           class="flex items-center text-blue-600 hover:text-blue-800 text-xs">
                            <i data-lucide="paperclip" class="w-3 h-3 mr-1"></i>
                            @lang('مرفق') {{ ++$key }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="text-xs text-gray-500 mt-2">
                {{ diffForHumans($conversation->created_at) }}
            </div>
        </div>
    </li>
@endif
@endforeach
