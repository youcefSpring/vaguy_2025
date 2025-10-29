@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ localized_route('influencer.service.order.index') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i>
            Retour
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Commande #{{ $order->order_no }}</h1>
            <p class="text-gray-600">Détails de la commande</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Service Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service commandé</h3>
                </div>
                <div class="card-content">
                    <div class="flex gap-4">
                        @if($order->service && $order->service->image)
                        <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            <img src="{{ getImage(getFilePath('service').'/'.$order->service->image) }}"
                                 alt="{{ $order->service->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-1">{{ $order->service->title ?? 'Service supprimé' }}</h4>
                            @if($order->service && $order->service->description)
                            <p class="text-gray-600 text-sm">{{ Str::limit($order->service->description, 200) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statut et actions</h3>
                </div>
                <div class="card-content">
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
                            case 6:
                                $statusClass = 'bg-orange-100 text-orange-800';
                                $statusText = 'Signalée';
                                break;
                            default:
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = 'Inconnu';
                        }
                    @endphp

                    <div class="flex items-center justify-between mb-4">
                        <span class="badge {{ $statusClass }} text-lg px-4 py-2">{{ $statusText }}</span>
                        <span class="text-xl font-bold text-gray-900">{{ showAmount($order->amount) }} {{ $general->cur_text ?? '' }}</span>
                    </div>

                    @if($order->status == 1)
                        <div class="flex gap-3">
                            <form method="POST" action="{{ localized_route('influencer.service.order.accept.status', $order->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="btn btn-primary w-full">
                                    <i data-lucide="check" class="mr-2 h-4 w-4"></i>
                                    Accepter la commande
                                </button>
                            </form>
                            <form method="POST" action="{{ localized_route('influencer.service.order.cancel.status', $order->id) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="btn btn-destructive w-full"
                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                    <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                                    Annuler la commande
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($order->status == 2)
                        <form method="POST" action="{{ localized_route('influencer.service.order.jobDone.status', $order->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full">
                                <i data-lucide="check-circle" class="mr-2 h-4 w-4"></i>
                                Marquer comme terminé
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Timeline de la commande</h3>
                </div>
                <div class="card-content">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i data-lucide="plus" class="h-4 w-4 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Commande créée</p>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        @if($order->status >= 2)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i data-lucide="check" class="h-4 w-4 text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Commande acceptée</p>
                                <p class="text-sm text-gray-600">{{ $order->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->status >= 3)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <i data-lucide="flag" class="h-4 w-4 text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Travail terminé</p>
                                <p class="text-sm text-gray-600">{{ $order->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 4)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i data-lucide="check-circle" class="h-4 w-4 text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Commande complétée</p>
                                <p class="text-sm text-gray-600">{{ $order->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Review -->
            @if($order->review)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Évaluation du client</h3>
                </div>
                <div class="card-content">
                    <div class="flex items-center gap-2 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i data-lucide="star" class="h-5 w-5 {{ $i <= $order->review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="font-medium">{{ $order->review->rating }}/5</span>
                    </div>
                    @if($order->review->review)
                    <p class="text-gray-700">{{ $order->review->review }}</p>
                    @endif
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
                            <h4 class="font-medium text-gray-900">{{ $order->user->firstname ?? $order->user->username }}</h4>
                            <p class="text-sm text-gray-600">@{{ $order->user->username }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        @if($order->user->email)
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="h-4 w-4 text-gray-400"></i>
                            <span class="text-gray-600">{{ $order->user->email }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="h-4 w-4 text-gray-400"></i>
                            <span class="text-gray-600">Membre depuis {{ $order->user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de la commande</h3>
                </div>
                <div class="card-content space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Numéro de commande</p>
                        <p class="font-medium">#{{ $order->order_no }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Montant</p>
                        <p class="font-medium">{{ showAmount($order->amount) }} {{ $general->cur_text ?? '' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Date de commande</p>
                        <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($order->delivery_time)
                    <div>
                        <p class="text-sm text-gray-600">Délai de livraison</p>
                        <p class="font-medium">{{ $order->delivery_time }} jours</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-content space-y-2">
                    <a href="{{ localized_route('influencer.service.order.conversation.view', $order->id) }}"
                       class="btn btn-outline w-full">
                        <i data-lucide="message-circle" class="mr-2 h-4 w-4"></i>
                        Messages
                    </a>

                    @if($order->service)
                    <a href="{{ localized_route('influencer.service.edit', $order->service->id) }}"
                       class="btn btn-ghost w-full">
                        <i data-lucide="edit" class="mr-2 h-4 w-4"></i>
                        Modifier le service
                    </a>
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