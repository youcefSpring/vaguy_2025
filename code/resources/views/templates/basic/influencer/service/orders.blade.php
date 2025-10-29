@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ localized_route('influencer.service.all') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i>
            Retour aux services
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Commandes pour "{{ $service->title }}"</p>
        </div>
    </div>

    <!-- Service Info -->
    <div class="card">
        <div class="card-content">
            <div class="flex gap-4">
                @if($service->image)
                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                    <img src="{{ getImage(getFilePath('service').'/'.$service->image) }}"
                         alt="{{ $service->title }}"
                         class="w-full h-full object-cover">
                </div>
                @endif
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $service->title }}</h3>
                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($service->description, 200) }}</p>
                    <div class="flex items-center gap-6 text-sm text-gray-500">
                        <div class="flex items-center gap-1">
                            <i data-lucide="dollar-sign" class="h-4 w-4"></i>
                            <span>{{ showAmount($service->price) }} {{ $general->cur_text ?? '' }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i data-lucide="package" class="h-4 w-4"></i>
                            <span>{{ $service->total_order_count ?? 0 }} commandes totales</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i data-lucide="check-circle" class="h-4 w-4"></i>
                            <span>{{ $service->complete_order_count ?? 0 }} complétées</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    @php
                        $statusClass = '';
                        $statusText = '';
                        switch($service->status) {
                            case 1:
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'Approuvé';
                                break;
                            case 0:
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'En attente';
                                break;
                            case 2:
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'Rejeté';
                                break;
                            default:
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = 'Inconnu';
                        }
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="card">
        <div class="card-content">
            <form method="GET" class="flex gap-3">
                <div class="flex-1">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher par numéro de commande ou nom d'utilisateur..."
                           class="input w-full">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="search" class="mr-2 h-4 w-4"></i>
                    Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-ghost">
                        <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                        Effacer
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="card">
                <div class="card-content">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900">Commande #{{ $order->order_no }}</h3>
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
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </div>

                            <div class="flex items-center gap-6 text-sm text-gray-500 mb-3">
                                <div class="flex items-center gap-1">
                                    <i data-lucide="user" class="h-4 w-4"></i>
                                    <span>{{ $order->user->username }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i data-lucide="calendar" class="h-4 w-4"></i>
                                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i data-lucide="dollar-sign" class="h-4 w-4"></i>
                                    <span>{{ showAmount($order->amount) }} {{ $general->cur_text ?? '' }}</span>
                                </div>
                                @if($order->delivery_time)
                                <div class="flex items-center gap-1">
                                    <i data-lucide="clock" class="h-4 w-4"></i>
                                    <span>{{ $order->delivery_time }} jours</span>
                                </div>
                                @endif
                            </div>

                            @if($order->description)
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($order->description, 150) }}</p>
                            @endif

                            @if($order->review)
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-gray-600">Évaluation:</span>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="h-4 w-4 {{ $i <= $order->review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="ml-1 text-gray-600">({{ $order->review->rating }}/5)</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ localized_route('influencer.service.order.detail', $order->id) }}"
                               class="btn btn-outline btn-sm">
                                <i data-lucide="eye" class="mr-1 h-3 w-3"></i>
                                Voir
                            </a>

                            @if($order->status == 1)
                                <form method="POST" action="{{ localized_route('influencer.service.order.accept.status', $order->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i data-lucide="check" class="mr-1 h-3 w-3"></i>
                                        Accepter
                                    </button>
                                </form>
                                <form method="POST" action="{{ localized_route('influencer.service.order.cancel.status', $order->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-destructive btn-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                        <i data-lucide="x" class="mr-1 h-3 w-3"></i>
                                        Annuler
                                    </button>
                                </form>
                            @endif

                            @if($order->status == 2)
                                <form method="POST" action="{{ localized_route('influencer.service.order.jobDone.status', $order->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i data-lucide="check-circle" class="mr-1 h-3 w-3"></i>
                                        Terminé
                                    </button>
                                </form>
                            @endif

                            <a href="{{ localized_route('influencer.service.order.conversation.view', $order->id) }}"
                               class="btn btn-ghost btn-sm">
                                <i data-lucide="message-circle" class="mr-1 h-3 w-3"></i>
                                Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="flex justify-center">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    @else
        <div class="card">
            <div class="card-content text-center py-12">
                <i data-lucide="package" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
                <p class="text-gray-600 mb-4">
                    @if(request('search'))
                        Aucune commande ne correspond à votre recherche.
                    @else
                        Ce service n'a pas encore reçu de commandes.
                    @endif
                </p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ localized_route('influencer.service.edit', $service->id) }}" class="btn btn-primary">
                        <i data-lucide="edit" class="mr-2 h-4 w-4"></i>
                        Modifier le service
                    </a>
                    <a href="{{ localized_route('influencer.service.all') }}" class="btn btn-ghost">
                        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>
                        Retour aux services
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection