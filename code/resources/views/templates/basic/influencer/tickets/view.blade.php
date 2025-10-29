@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ localized_route('influencer.ticket') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i>
            Retour
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $myTicket->subject }}</h1>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span>Ticket #{{ $myTicket->ticket }}</span>
                <span>•</span>
                <span>{{ $myTicket->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        @php
            $statusClass = '';
            $statusText = '';
            switch($myTicket->status) {
                case 0:
                    $statusClass = 'bg-yellow-100 text-yellow-800';
                    $statusText = 'Ouvert';
                    break;
                case 1:
                    $statusClass = 'bg-blue-100 text-blue-800';
                    $statusText = 'Réponse client';
                    break;
                case 2:
                    $statusClass = 'bg-green-100 text-green-800';
                    $statusText = 'Réponse admin';
                    break;
                case 3:
                    $statusClass = 'bg-gray-100 text-gray-800';
                    $statusText = 'Fermé';
                    break;
                default:
                    $statusClass = 'bg-gray-100 text-gray-800';
                    $statusText = 'Inconnu';
            }
        @endphp
        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Messages -->
        <div class="lg:col-span-3 space-y-4">
            <!-- Messages List -->
            @foreach($messages->reverse() as $message)
            <div class="card">
                <div class="card-content">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-{{ $message->admin_id ? 'blue' : 'green' }}-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="{{ $message->admin_id ? 'shield' : 'user' }}" class="h-5 w-5 text-{{ $message->admin_id ? 'blue' : 'green' }}-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-medium text-gray-900">
                                    {{ $message->admin_id ? 'Support Admin' : ($user->firstname ?? $user->username) }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="prose prose-sm max-w-none">
                                {!! nl2br(e($message->message)) !!}
                            </div>

                            <!-- Attachments -->
                            @if($message->attachments && $message->attachments->count() > 0)
                            <div class="mt-3">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Pièces jointes:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($message->attachments as $attachment)
                                    <a href="{{ localized_route('influencer.ticket.download', encrypt($attachment->id)) }}"
                                       class="flex items-center gap-2 px-3 py-2 bg-gray-100 rounded-lg text-sm text-gray-700 hover:bg-gray-200">
                                        <i data-lucide="paperclip" class="h-4 w-4"></i>
                                        {{ $attachment->attachment }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Reply Form -->
            @if($myTicket->status != 3)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Répondre au ticket</h3>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ localized_route('influencer.ticket.reply', $myTicket->ticket) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Votre réponse *
                            </label>
                            <textarea name="message"
                                      id="message"
                                      rows="4"
                                      class="input w-full"
                                      placeholder="Tapez votre réponse..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Attachments -->
                        <div>
                            <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                                Pièces jointes
                            </label>
                            <input type="file"
                                   name="attachments[]"
                                   id="attachments"
                                   multiple
                                   class="input w-full"
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt,.zip">
                            <p class="mt-1 text-sm text-gray-500">
                                Formats acceptés: JPG, PNG, PDF, DOC, DOCX, TXT, ZIP (max 5 fichiers, 2MB chacun)
                            </p>
                            @error('attachments')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="send" class="mr-2 h-4 w-4"></i>
                                Envoyer la réponse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Ticket Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations du ticket</h3>
                </div>
                <div class="card-content space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Numéro</p>
                        <p class="font-medium">#{{ $myTicket->ticket }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Statut</p>
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </div>

                    @if($myTicket->priority)
                    <div>
                        <p class="text-sm text-gray-600">Priorité</p>
                        @php
                            $priorityClass = '';
                            $priorityText = '';
                            switch($myTicket->priority) {
                                case 1:
                                    $priorityClass = 'bg-red-100 text-red-800';
                                    $priorityText = 'Haute';
                                    break;
                                case 2:
                                    $priorityClass = 'bg-orange-100 text-orange-800';
                                    $priorityText = 'Moyenne';
                                    break;
                                case 3:
                                    $priorityClass = 'bg-green-100 text-green-800';
                                    $priorityText = 'Basse';
                                    break;
                                default:
                                    $priorityClass = 'bg-gray-100 text-gray-800';
                                    $priorityText = 'Normal';
                            }
                        @endphp
                        <span class="badge {{ $priorityClass }}">{{ $priorityText }}</span>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Créé le</p>
                        <p class="font-medium">{{ $myTicket->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($myTicket->last_reply)
                    <div>
                        <p class="text-sm text-gray-600">Dernière réponse</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($myTicket->last_reply)->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($myTicket->status != 3)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ localized_route('influencer.ticket.close', $myTicket->ticket) }}">
                        @csrf
                        <button type="submit"
                                class="btn btn-destructive w-full"
                                onclick="return confirm('Êtes-vous sûr de vouloir fermer ce ticket ?')">
                            <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                            Fermer le ticket
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Contact Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de contact</h3>
                </div>
                <div class="card-content space-y-2">
                    @if($myTicket->name)
                    <div>
                        <p class="text-sm text-gray-600">Nom</p>
                        <p class="font-medium">{{ $myTicket->name }}</p>
                    </div>
                    @endif

                    @if($myTicket->email)
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium">{{ $myTicket->email }}</p>
                    </div>
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
});
</script>
@endpush
@endsection