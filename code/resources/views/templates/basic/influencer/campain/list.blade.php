@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Campagnes</h1>
        <p class="text-gray-600">Gérez vos campagnes et offres publicitaires</p>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-content">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Status Filter -->
                <div class="flex gap-2">
                    <a href="{{ localized_route('influencer.campain.index') }}"
                       class="btn {{ request()->routeIs('influencer.campain.index') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Toutes
                    </a>
                    <a href="{{ localized_route('influencer.campain.pending') }}"
                       class="btn {{ request()->routeIs('influencer.campain.pending') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        En attente
                    </a>
                    <a href="{{ localized_route('influencer.campain.inprogress') }}"
                       class="btn {{ request()->routeIs('influencer.campain.inprogress') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        En cours
                    </a>
                    <a href="{{ localized_route('influencer.campain.completed') }}"
                       class="btn {{ request()->routeIs('influencer.campain.completed') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Terminées
                    </a>
                </div>

                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <form method="GET" action="{{ request()->url() }}" class="flex gap-2">
                        <input type="text"
                               name="search"
                               value="{{ request()->search }}"
                               placeholder="Rechercher des campagnes..."
                               class="input flex-1">
                        <button type="submit" class="btn btn-outline btn-default">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaigns List -->
    <div class="grid gap-4">
        @forelse($campains as $campain)
        <div class="card">
            <div class="card-content">
                <div class="flex items-start gap-4">
                    <!-- Campaign Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $campain->title }}
                                </h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    Par: <span class="font-medium">{{ $campain->user->fullname ?? $campain->user->username }}</span>
                                </p>
                                <p class="text-gray-600 text-sm mt-2 line-clamp-2">
                                    {{ Str::limit($campain->description, 150) }}
                                </p>

                                <!-- Meta Info -->
                                <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="h-4 w-4"></i>
                                        {{ $campain->created_at->format('d/m/Y') }}
                                    </span>
                                    @if($campain->delivery_date)
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="clock" class="h-4 w-4"></i>
                                        Livraison: {{ \Carbon\Carbon::parse($campain->delivery_date)->format('d/m/Y') }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="users" class="h-4 w-4"></i>
                                        {{ $campain->campain_offers_count ?? 0 }} offres
                                    </span>
                                </div>
                            </div>

                            <!-- Price and Status -->
                            <div class="text-right">
                                <div class="text-xl font-bold text-gray-900">
                                    {{ number_format($campain->amount, 0, ',', ' ') }} DZD
                                </div>
                                <div class="mt-2">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['badge-outline', 'En attente'],
                                            'approved' => ['badge-default', 'Approuvée'],
                                            'inprogress' => ['badge-secondary', 'En cours'],
                                            'completed' => ['badge-default', 'Terminée'],
                                            'cancelled' => ['badge-destructive', 'Annulée'],
                                            'rejected' => ['badge-destructive', 'Rejetée']
                                        ];
                                        $config = $statusConfig[$campain->status] ?? ['badge-outline', 'Inconnu'];
                                    @endphp
                                    <span class="badge {{ $config[0] }}">
                                        {{ $config[1] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- My Offer Section -->
                        @php
                            $myOffer = $campain->campain_offers->first();
                        @endphp

                        @if($myOffer)
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Mon offre</p>
                                    <p class="text-lg font-bold text-blue-800">{{ number_format($myOffer->price, 0, ',', ' ') }} DZD</p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $offerStatusConfig = [
                                            'pending' => ['badge-outline', 'En attente'],
                                            'accepted' => ['badge-default', 'Acceptée'],
                                            'rejected' => ['badge-destructive', 'Rejetée'],
                                            'completed' => ['badge-default', 'Terminée']
                                        ];
                                        $offerConfig = $offerStatusConfig[$myOffer->status] ?? ['badge-outline', 'Inconnu'];
                                    @endphp
                                    <span class="badge {{ $offerConfig[0] }}">
                                        {{ $offerConfig[1] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex items-center gap-2 mt-4">
                            <a href="{{ localized_route('influencer.campain.detail', $campain->id) }}"
                               class="btn btn-outline btn-sm">
                                <i data-lucide="eye" class="mr-1 h-4 w-4"></i>
                                Voir détails
                            </a>

                            @if(!$myOffer)
                            <!-- Submit Offer Button -->
                            <button type="button"
                                    class="btn btn-primary btn-sm"
                                    onclick="openOfferModal({{ $campain->id }})">
                                <i data-lucide="hand-heart" class="mr-1 h-4 w-4"></i>
                                Soumettre une offre
                            </button>
                            @elseif($myOffer->status == 'accepted')
                            <!-- Upload Results Button -->
                            <button type="button"
                                    class="btn btn-secondary btn-sm"
                                    onclick="openUploadModal({{ $myOffer->id }})">
                                <i data-lucide="upload" class="mr-1 h-4 w-4"></i>
                                Livrer le travail
                            </button>
                            @elseif($myOffer->status == 'pending')
                            <!-- Edit Offer Button -->
                            <button type="button"
                                    class="btn btn-secondary btn-sm"
                                    onclick="editOffer({{ $campain->id }}, {{ $myOffer->price }})">
                                <i data-lucide="edit" class="mr-1 h-4 w-4"></i>
                                Modifier l'offre
                            </button>
                            @endif

                            @if($campain->user_id != authInfluencerId())
                            <a href="{{ localized_route('influencer.campain.conversation.view', $campain->id) }}"
                               class="btn btn-ghost btn-sm">
                                <i data-lucide="message-circle" class="mr-1 h-4 w-4"></i>
                                Messages
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-content text-center py-12">
                <i data-lucide="megaphone" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune campagne trouvée</h3>
                <p class="text-gray-600 mb-4">
                    @if(request()->search)
                        Aucune campagne ne correspond à votre recherche "{{ request()->search }}".
                    @else
                        Il n'y a pas encore de campagnes disponibles.
                    @endif
                </p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($campains->hasPages())
    <div class="flex justify-center">
        {{ $campains->links() }}
    </div>
    @endif
</div>

<!-- Submit Offer Modal -->
<div id="offerModal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" x-on:click="open = false"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Soumettre une offre</h3>
                </div>

                <form id="offerForm" method="POST" action="{{ localized_route('influencer.campain.post_offer') }}" class="p-6">
                    @csrf
                    <input type="hidden" name="campain_id" id="modal_campain_id">

                    <div class="space-y-4">
                        <div>
                            <label for="offer_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Votre prix (DZD) *
                            </label>
                            <input type="number"
                                   name="offer"
                                   id="offer_price"
                                   placeholder="5000"
                                   min="0"
                                   step="0.01"
                                   class="input w-full"
                                   required>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="btn btn-primary btn-default flex-1">
                                Soumettre l'offre
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
function openOfferModal(campainId) {
    document.getElementById('modal_campain_id').value = campainId;
    const modal = document.getElementById('offerModal');
    modal.classList.remove('hidden');
    // Trigger Alpine.js
    modal.querySelector('[x-data]').__x.$data.open = true;
}

function openUploadModal(offerId) {
    const form = document.getElementById('uploadForm');
    form.action = `/influencer/campains/upload_result/${offerId}`;
    const modal = document.getElementById('uploadModal');
    modal.classList.remove('hidden');
    // Trigger Alpine.js
    modal.querySelector('[x-data]').__x.$data.open = true;
}

function editOffer(campainId, currentPrice) {
    document.getElementById('modal_campain_id').value = campainId;
    document.getElementById('offer_price').value = currentPrice;
    openOfferModal(campainId);
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection