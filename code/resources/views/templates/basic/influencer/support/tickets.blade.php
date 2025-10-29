@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Support</h1>
        <p class="text-gray-600">Gérez vos tickets de support et obtenez de l'aide</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Create New Ticket -->
        <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="openNewTicketModal()">
            <div class="card-content text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="plus" class="h-6 w-6 text-blue-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-2">Nouveau ticket</h3>
                <p class="text-sm text-gray-600">Créer une nouvelle demande de support</p>
            </div>
        </div>

        <!-- FAQ -->
        <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ localized_route('influencer.support.faq') }}'">
            <div class="card-content text-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="help-circle" class="h-6 w-6 text-green-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-2">FAQ</h3>
                <p class="text-sm text-gray-600">Consultez les questions fréquentes</p>
            </div>
        </div>

        <!-- Contact -->
        <div class="card hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ localized_route('influencer.support.contact') }}'">
            <div class="card-content text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="phone" class="h-6 w-6 text-purple-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-2">Contact direct</h3>
                <p class="text-sm text-gray-600">Appelez ou écrivez-nous directement</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-content">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Status Filter -->
                <div class="flex gap-2">
                    <a href="{{ localized_route('influencer.support.index') }}"
                       class="btn {{ !request()->status ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Tous
                    </a>
                    <a href="{{ localized_route('influencer.support.index', ['status' => 'open']) }}"
                       class="btn {{ request()->status == 'open' ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Ouverts
                    </a>
                    <a href="{{ localized_route('influencer.support.index', ['status' => 'answered']) }}"
                       class="btn {{ request()->status == 'answered' ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Répondus
                    </a>
                    <a href="{{ localized_route('influencer.support.index', ['status' => 'closed']) }}"
                       class="btn {{ request()->status == 'closed' ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Fermés
                    </a>
                </div>

                <!-- Priority Filter -->
                <div class="flex gap-2">
                    <select name="priority" onchange="filterByPriority(this.value)" class="input">
                        <option value="">Toutes les priorités</option>
                        <option value="1" {{ request()->priority == '1' ? 'selected' : '' }}>Faible</option>
                        <option value="2" {{ request()->priority == '2' ? 'selected' : '' }}>Normale</option>
                        <option value="3" {{ request()->priority == '3' ? 'selected' : '' }}>Élevée</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <form method="GET" action="{{ request()->url() }}" class="flex gap-2">
                        <input type="text"
                               name="search"
                               value="{{ request()->search }}"
                               placeholder="Rechercher dans les tickets..."
                               class="input flex-1">
                        <button type="submit" class="btn btn-outline btn-default">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="grid gap-4">
        @forelse($tickets as $ticket)
        <div class="card">
            <div class="card-content">
                <div class="flex items-start gap-4">
                    <!-- Ticket Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        #{{ $ticket->ticket }}
                                    </h3>
                                    @php
                                        $statusConfig = [
                                            0 => ['badge-outline', 'Ouvert'],
                                            1 => ['badge-secondary', 'Répondu'],
                                            2 => ['badge-default', 'Fermé'],
                                            3 => ['badge-destructive', 'Fermé par le client']
                                        ];
                                        $config = $statusConfig[$ticket->status] ?? ['badge-outline', 'Inconnu'];
                                    @endphp
                                    <span class="badge {{ $config[0] }}">
                                        {{ $config[1] }}
                                    </span>

                                    @php
                                        $priorityConfig = [
                                            1 => ['badge-outline text-green-600', 'Faible'],
                                            2 => ['badge-outline text-yellow-600', 'Normale'],
                                            3 => ['badge-outline text-red-600', 'Élevée']
                                        ];
                                        $priorityConf = $priorityConfig[$ticket->priority] ?? ['badge-outline', 'Normale'];
                                    @endphp
                                    <span class="badge {{ $priorityConf[0] }}">
                                        {{ $priorityConf[1] }}
                                    </span>
                                </div>

                                <h4 class="text-base font-medium text-gray-900 mb-2">{{ $ticket->subject }}</h4>

                                <p class="text-gray-600 text-sm line-clamp-2">
                                    {{ Str::limit($ticket->message, 150) }}
                                </p>

                                <!-- Meta Info -->
                                <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="h-4 w-4"></i>
                                        {{ $ticket->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    @if($ticket->last_reply)
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="message-circle" class="h-4 w-4"></i>
                                        Dernière réponse: {{ $ticket->last_reply->diffForHumans() }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="hash" class="h-4 w-4"></i>
                                        {{ $ticket->replies_count }} réponses
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                <a href="{{ localized_route('influencer.support.view', $ticket->id) }}"
                                   class="btn btn-outline btn-sm">
                                    <i data-lucide="eye" class="mr-1 h-4 w-4"></i>
                                    Voir
                                </a>

                                @if($ticket->status != 2)
                                <button type="button"
                                        class="btn btn-ghost btn-sm"
                                        onclick="closeTicket({{ $ticket->id }})">
                                    <i data-lucide="x" class="mr-1 h-4 w-4"></i>
                                    Fermer
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Unread indicator -->
                        @if($ticket->unread_replies > 0)
                        <div class="mt-3 p-2 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <i data-lucide="bell" class="h-4 w-4 inline mr-1"></i>
                                {{ $ticket->unread_replies }} nouvelle(s) réponse(s)
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-content text-center py-12">
                <i data-lucide="headphones" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun ticket trouvé</h3>
                <p class="text-gray-600 mb-4">
                    @if(request()->search)
                        Aucun ticket ne correspond à votre recherche "{{ request()->search }}".
                    @else
                        Vous n'avez pas encore créé de tickets de support.
                    @endif
                </p>
                @if(!request()->search)
                <button type="button" class="btn btn-primary btn-default" onclick="openNewTicketModal()">
                    <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                    Créer un ticket
                </button>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tickets->hasPages())
    <div class="flex justify-center">
        {{ $tickets->links() }}
    </div>
    @endif
</div>

<!-- New Ticket Modal -->
<div id="newTicketModal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" x-on:click="open = false"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Nouveau ticket de support</h3>
                </div>

                <form method="POST" action="{{ localized_route('influencer.support.store') }}" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <div class="space-y-4">
                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Sujet *
                            </label>
                            <input type="text"
                                   name="subject"
                                   id="subject"
                                   placeholder="Décrivez brièvement votre problème"
                                   class="input w-full"
                                   required>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                Priorité *
                            </label>
                            <select name="priority" id="priority" class="input w-full" required>
                                <option value="1">Faible</option>
                                <option value="2" selected>Normale</option>
                                <option value="3">Élevée</option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie
                            </label>
                            <select name="category" id="category" class="input w-full">
                                <option value="">Sélectionner une catégorie</option>
                                <option value="technical">Problème technique</option>
                                <option value="billing">Facturation/Paiement</option>
                                <option value="account">Compte utilisateur</option>
                                <option value="service">Service/Commande</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message *
                            </label>
                            <textarea name="message"
                                      id="message"
                                      rows="5"
                                      placeholder="Décrivez votre problème en détail..."
                                      class="input w-full"
                                      required></textarea>
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
                                   accept="image/*,.pdf,.doc,.docx"
                                   class="input w-full">
                            <p class="text-xs text-gray-500 mt-1">
                                Max 5MB par fichier. Formats: Images, PDF, Word
                            </p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="btn btn-primary btn-default flex-1">
                                <i data-lucide="send" class="mr-2 h-4 w-4"></i>
                                Créer le ticket
                            </button>
                            <button type="button" class="btn btn-outline btn-default" x-on:click="open = false">
                                Annuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
function openNewTicketModal() {
    const modal = document.getElementById('newTicketModal');
    modal.classList.remove('hidden');
    modal.querySelector('[x-data]').__x.$data.open = true;
}

function closeTicket(ticketId) {
    if (confirm('Êtes-vous sûr de vouloir fermer ce ticket ?')) {
        fetch(`/influencer/support/tickets/${ticketId}/close`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la fermeture du ticket');
            }
        });
    }
}

function filterByPriority(priority) {
    const url = new URL(window.location);
    if (priority) {
        url.searchParams.set('priority', priority);
    } else {
        url.searchParams.delete('priority');
    }
    window.location.href = url.toString();
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection