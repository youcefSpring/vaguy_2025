@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ localized_route('influencer.campain.index') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i>
            Retour
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $campain->title }}</h1>
            <p class="text-gray-600">Détails de la campagne</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Campaign Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Main Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la campagne</h3>
                </div>
                <div class="card-content space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Description</h4>
                        <p class="text-gray-700">{{ $campain->description }}</p>
                    </div>

                    @if($campain->requirements)
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Exigences</h4>
                        <p class="text-gray-700">{{ $campain->requirements }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-1">Date de création</h4>
                            <p class="text-gray-600">{{ $campain->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($campain->deadline)
                        <div>
                            <h4 class="font-medium text-gray-900 mb-1">Date limite</h4>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($campain->deadline)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>

                    @php
                        $statusClass = '';
                        $statusText = '';
                        switch($campain->status) {
                            case 'pending':
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'En attente';
                                break;
                            case 'inprogress':
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusText = 'En cours';
                                break;
                            case 'JobDone':
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'Terminée';
                                break;
                            case 'completed':
                                $statusClass = 'bg-purple-100 text-purple-800';
                                $statusText = 'Complétée';
                                break;
                            case 'cancelled':
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'Annulée';
                                break;
                            default:
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = ucfirst($campain->status ?? 'Inconnu');
                        }
                    @endphp

                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">Statut</h4>
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>
            </div>

            <!-- Offer Section -->
            @if($campain->campain_offers->isNotEmpty())
                @php $offer = $campain->campain_offers->first(); @endphp
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Votre offre</h3>
                    </div>
                    <div class="card-content">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Montant proposé</p>
                                <p class="text-xl font-bold text-gray-900">{{ showAmount($offer->price) }} {{ $general->cur_text ?? '' }}</p>
                            </div>
                            @php
                                $offerStatusClass = '';
                                $offerStatusText = '';
                                switch($offer->status) {
                                    case 'pending':
                                        $offerStatusClass = 'bg-yellow-100 text-yellow-800';
                                        $offerStatusText = 'En attente';
                                        break;
                                    case 'accepted':
                                        $offerStatusClass = 'bg-green-100 text-green-800';
                                        $offerStatusText = 'Acceptée';
                                        break;
                                    case 'rejected':
                                        $offerStatusClass = 'bg-red-100 text-red-800';
                                        $offerStatusText = 'Refusée';
                                        break;
                                    default:
                                        $offerStatusClass = 'bg-gray-100 text-gray-800';
                                        $offerStatusText = ucfirst($offer->status ?? 'Inconnu');
                                }
                            @endphp
                            <span class="badge {{ $offerStatusClass }}">{{ $offerStatusText }}</span>
                        </div>

                        @if($offer->status == 'accepted')
                            <!-- File Upload Form -->
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-medium text-blue-900 mb-2">Télécharger les résultats</h4>
                                <form method="POST" action="{{ localized_route('influencer.campain.upload_result', $offer->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="space-y-3">
                                        <input type="file"
                                               name="campain_result_files[]"
                                               multiple
                                               class="input w-full"
                                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip">
                                        <button type="submit" class="btn btn-primary btn-sm w-full">
                                            <i data-lucide="upload" class="mr-1 h-3 w-3"></i>
                                            Télécharger les fichiers
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if($offer->status == 'pending')
                            <div class="flex gap-2">
                                <form method="POST" action="{{ localized_route('influencer.campain.change_offer_status_influencer', [$offer->id, 'accepted']) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i data-lucide="check" class="mr-1 h-4 w-4"></i>
                                        Accepter l'offre
                                    </button>
                                </form>
                                <form method="POST" action="{{ localized_route('influencer.campain.change_offer_status_influencer', [$offer->id, 'rejected']) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-destructive">
                                        <i data-lucide="x" class="mr-1 h-4 w-4"></i>
                                        Refuser l'offre
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Make Offer -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Proposer une offre</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST" action="{{ localized_route('influencer.campain.post_offer') }}">
                            @csrf
                            <input type="hidden" name="campain_id" value="{{ $campain->id }}">
                            <div class="space-y-4">
                                <div>
                                    <label for="offer" class="block text-sm font-medium text-gray-700 mb-2">
                                        Montant de votre offre ({{ $general->cur_text ?? '' }})
                                    </label>
                                    <input type="number"
                                           name="offer"
                                           id="offer"
                                           placeholder="Entrez votre prix"
                                           class="input w-full"
                                           required>
                                </div>
                                <button type="submit" class="btn btn-primary w-full">
                                    <i data-lucide="send" class="mr-2 h-4 w-4"></i>
                                    Envoyer l'offre
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Client Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations client</h3>
                </div>
                <div class="card-content">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="h-6 w-6 text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $campain->user->firstname ?? $campain->user->username }}</h4>
                            <p class="text-sm text-gray-600">@{{ $campain->user->username }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        @if($campain->user->email)
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="h-4 w-4 text-gray-400"></i>
                            <span class="text-gray-600">{{ $campain->user->email }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="h-4 w-4 text-gray-400"></i>
                            <span class="text-gray-600">Membre depuis {{ $campain->user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-content space-y-2">
                    <a href="{{ localized_route('influencer.campain.conversation.view', $campain->id) }}"
                       class="btn btn-outline w-full">
                        <i data-lucide="message-circle" class="mr-2 h-4 w-4"></i>
                        Messages
                    </a>

                    @if($campain->campain_offers->isNotEmpty() && $campain->campain_offers->first()->status == 'accepted')
                        <form method="POST" action="{{ localized_route('influencer.campain.confirm_offer') }}">
                            @csrf
                            <input type="hidden" name="campain_id" value="{{ $campain->id }}">
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn btn-primary w-full">
                                <i data-lucide="check-circle" class="mr-2 h-4 w-4"></i>
                                Confirmer l'offre
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Campaign Stats -->
            @if($campain->budget ?? false)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Budget de la campagne</h3>
                </div>
                <div class="card-content">
                    <p class="text-2xl font-bold text-gray-900">{{ showAmount($campain->budget) }} {{ $general->cur_text ?? '' }}</p>
                    <p class="text-sm text-gray-600">Budget total alloué</p>
                </div>
            </div>
            @endif
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