@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ localized_route('influencer.service.order.detail', $order->id) }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i>
            Retour
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Commande #{{ $order->order_no }} - {{ $user->username }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Messages -->
        <div class="lg:col-span-3">
            <div class="card h-[600px] flex flex-col">
                <div class="card-header">
                    <h3 class="card-title">Conversation avec {{ $user->username }}</h3>
                </div>
                <div class="card-content flex-1 flex flex-col">
                    <!-- Messages List -->
                    <div class="flex-1 overflow-y-auto space-y-4 mb-4" id="messages-container">
                        @forelse($conversationMessage->reverse() as $message)
                        <div class="flex {{ $message->sender == 'influencer' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="flex items-start gap-2 {{ $message->sender == 'influencer' ? 'flex-row-reverse' : 'flex-row' }}">
                                    <div class="w-8 h-8 rounded-full bg-{{ $message->sender == 'influencer' ? 'blue' : 'gray' }}-100 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="{{ $message->sender == 'influencer' ? 'user' : 'user-check' }}" class="h-4 w-4 text-{{ $message->sender == 'influencer' ? 'blue' : 'gray' }}-600"></i>
                                    </div>
                                    <div class="flex flex-col {{ $message->sender == 'influencer' ? 'items-end' : 'items-start' }}">
                                        <div class="bg-{{ $message->sender == 'influencer' ? 'blue-500 text-white' : 'gray-100 text-gray-900' }} rounded-lg px-3 py-2">
                                            <p class="text-sm">{{ $message->message }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('H:i') }}</span>

                                        @if($message->attachments)
                                            @php $attachments = json_decode($message->attachments, true); @endphp
                                            @if(is_array($attachments))
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    @foreach($attachments as $attachment)
                                                    <a href="{{ asset(getFilePath('conversation').'/'.$attachment) }}"
                                                       target="_blank"
                                                       class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded hover:bg-gray-300">
                                                        <i data-lucide="paperclip" class="h-3 w-3 inline mr-1"></i>
                                                        {{ basename($attachment) }}
                                                    </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i data-lucide="message-circle" class="h-12 w-12 text-gray-400 mx-auto mb-2"></i>
                            <p class="text-gray-600">Aucun message pour le moment</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Message Form -->
                    <form method="POST" action="{{ localized_route('influencer.service.order.conversation.store', $order->id) }}" enctype="multipart/form-data" class="border-t pt-4">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <textarea name="message"
                                          rows="3"
                                          class="input w-full resize-none"
                                          placeholder="Tapez votre message..."
                                          required></textarea>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <input type="file"
                                           name="attachments[]"
                                           multiple
                                           class="input w-full"
                                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Formats: JPG, PNG, PDF, DOC, DOCX, TXT (max 5 fichiers)
                                    </p>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="send" class="mr-2 h-4 w-4"></i>
                                    Envoyer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la commande</h3>
                </div>
                <div class="card-content space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Numéro</p>
                        <p class="font-medium">#{{ $order->order_no }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Service</p>
                        <p class="font-medium">{{ $order->service->title ?? 'Service supprimé' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Montant</p>
                        <p class="font-medium">{{ showAmount($order->amount) }} {{ $general->cur_text ?? '' }}</p>
                    </div>

                    @php
                        $statusClass = '';
                        $statusText = '';
                        switch($order->status) {
                            case 1:
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'En attente';
                                break;
                            case 2:
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusText = 'En cours';
                                break;
                            case 3:
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'Terminée';
                                break;
                            case 4:
                                $statusClass = 'bg-purple-100 text-purple-800';
                                $statusText = 'Complétée';
                                break;
                            case 5:
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'Annulée';
                                break;
                            default:
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = 'Inconnu';
                        }
                    @endphp

                    <div>
                        <p class="text-sm text-gray-600">Statut</p>
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>
            </div>

            <!-- Client Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Client</h3>
                </div>
                <div class="card-content">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="h-5 w-5 text-gray-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $user->firstname ?? $user->username }}</h4>
                            <p class="text-sm text-gray-600">@{{ $user->username }}</p>
                        </div>
                    </div>

                    @if($user->email)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i data-lucide="mail" class="h-4 w-4"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions rapides</h3>
                </div>
                <div class="card-content space-y-2">
                    <a href="{{ localized_route('influencer.service.order.detail', $order->id) }}"
                       class="btn btn-outline w-full">
                        <i data-lucide="eye" class="mr-2 h-4 w-4"></i>
                        Voir la commande
                    </a>

                    @if($order->status == 1)
                        <form method="POST" action="{{ localized_route('influencer.service.order.accept.status', $order->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full">
                                <i data-lucide="check" class="mr-2 h-4 w-4"></i>
                                Accepter
                            </button>
                        </form>
                    @endif

                    @if($order->status == 2)
                        <form method="POST" action="{{ localized_route('influencer.service.order.jobDone.status', $order->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full">
                                <i data-lucide="check-circle" class="mr-2 h-4 w-4"></i>
                                Marquer terminé
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    // Auto scroll to bottom of messages
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
});
</script>
@endpush
@endsection