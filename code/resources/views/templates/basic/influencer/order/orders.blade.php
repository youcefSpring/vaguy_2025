@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Commandes</h1>
        <p class="text-gray-600">Gérez toutes vos commandes de services</p>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-content">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Status Filter -->
                <div class="flex gap-2">
                    <a href="{{ localized_route('influencer.order.index') }}"
                       class="btn {{ request()->routeIs('influencer.order.index') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Toutes
                    </a>
                    <a href="{{ localized_route('influencer.order.pending') }}"
                       class="btn {{ request()->routeIs('influencer.order.pending') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        En attente
                    </a>
                    <a href="{{ localized_route('influencer.order.inprogress') }}"
                       class="btn {{ request()->routeIs('influencer.order.inprogress') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        En cours
                    </a>
                    <a href="{{ localized_route('influencer.order.jobdone') }}"
                       class="btn {{ request()->routeIs('influencer.order.jobdone') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Terminées
                    </a>
                    <a href="{{ localized_route('influencer.order.completed') }}"
                       class="btn {{ request()->routeIs('influencer.order.completed') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Livrées
                    </a>
                </div>

                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <form method="GET" action="{{ request()->url() }}" class="flex gap-2">
                        <input type="text"
                               name="search"
                               value="{{ request()->search }}"
                               placeholder="Rechercher par numéro de commande..."
                               class="input flex-1">
                        <button type="submit" class="btn btn-outline btn-default">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>

                <!-- Export -->
                <button type="button" class="btn btn-outline btn-default">
                    <i data-lucide="download" class="mr-2 h-4 w-4"></i>
                    Exporter
                </button>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="grid gap-4">
        @forelse($orders as $order)
        <div class="card">
            <div class="card-content">
                <div class="flex items-start gap-4">
                    <!-- Order Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        #{{ $order->order_no }}
                                    </h3>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['badge-outline', 'En attente'],
                                            'inprogress' => ['badge-secondary', 'En cours'],
                                            'jobdone' => ['badge-default', 'Terminée'],
                                            'completed' => ['badge-default', 'Livrée'],
                                            'cancelled' => ['badge-destructive', 'Annulée'],
                                            'rejected' => ['badge-destructive', 'Rejetée']
                                        ];
                                        $config = $statusConfig[$order->status] ?? ['badge-outline', 'Inconnu'];
                                    @endphp
                                    <span class="badge {{ $config[0] }}">
                                        {{ $config[1] }}
                                    </span>
                                </div>

                                <p class="text-gray-600 text-sm mt-1">
                                    Service: <span class="font-medium">{{ $order->service->title }}</span>
                                </p>
                                <p class="text-gray-600 text-sm">
                                    Client: <span class="font-medium">{{ $order->user->fullname ?? $order->user->username }}</span>
                                </p>

                                @if($order->requirements)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Exigences:</span>
                                        {{ Str::limit($order->requirements, 100) }}
                                    </p>
                                </div>
                                @endif

                                <!-- Meta Info -->
                                <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="h-4 w-4"></i>
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </span>
                                    @if($order->delivery_date)
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="clock" class="h-4 w-4"></i>
                                        Livraison: {{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}
                                    </span>
                                    @endif
                                    @if($order->quantity > 1)
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="package" class="h-4 w-4"></i>
                                        Quantité: {{ $order->quantity }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="text-right">
                                <div class="text-xl font-bold text-gray-900">
                                    {{ number_format($order->amount, 0, ',', ' ') }} DZD
                                </div>
                                @if($order->quantity > 1)
                                <div class="text-sm text-gray-500">
                                    {{ number_format($order->amount / $order->quantity, 0, ',', ' ') }} DZD × {{ $order->quantity }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @if(in_array($order->status, ['inprogress', 'jobdone']))
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Progression</span>
                                <span>{{ $order->status == 'jobdone' ? '100' : '50' }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                     style="width: {{ $order->status == 'jobdone' ? '100' : '50' }}%"></div>
                            </div>
                        </div>
                        @endif

                        <!-- Delivered Work -->
                        @if($order->status == 'jobdone' && $order->work_delivered)
                        <div class="mt-4 p-3 bg-green-50 rounded-lg">
                            <div class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-green-900">Travail livré</p>
                                    <p class="text-sm text-green-700 mt-1">{{ $order->work_delivered }}</p>
                                    @if($order->delivered_files)
                                        <div class="mt-2 space-y-1">
                                            @foreach($order->delivered_files as $file)
                                            <a href="{{ localized_route('influencer.order.download', $file['id']) }}"
                                               class="inline-flex items-center text-xs text-green-600 hover:text-green-800">
                                                <i data-lucide="download" class="h-3 w-3 mr-1"></i>
                                                {{ $file['name'] }}
                                            </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex items-center gap-2 mt-4">
                            <a href="{{ localized_route('influencer.order.detail', $order->id) }}"
                               class="btn btn-outline btn-sm">
                                <i data-lucide="eye" class="mr-1 h-4 w-4"></i>
                                Voir détails
                            </a>

                            @if($order->status == 'pending')
                            <!-- Accept/Reject Order -->
                            <button type="button"
                                    class="btn btn-primary btn-sm"
                                    onclick="acceptOrder({{ $order->id }})">
                                <i data-lucide="check" class="mr-1 h-4 w-4"></i>
                                Accepter
                            </button>
                            <button type="button"
                                    class="btn btn-destructive btn-sm"
                                    onclick="rejectOrder({{ $order->id }})">
                                <i data-lucide="x" class="mr-1 h-4 w-4"></i>
                                Rejeter
                            </button>
                            @elseif($order->status == 'inprogress')
                            <!-- Deliver Work -->
                            <button type="button"
                                    class="btn btn-secondary btn-sm"
                                    onclick="deliverWork({{ $order->id }})">
                                <i data-lucide="upload" class="mr-1 h-4 w-4"></i>
                                Livrer le travail
                            </button>
                            @endif

                            <a href="{{ localized_route('influencer.conversation.view', $order->id) }}"
                               class="btn btn-ghost btn-sm">
                                <i data-lucide="message-circle" class="mr-1 h-4 w-4"></i>
                                Messages
                            </a>

                            @if(in_array($order->status, ['jobdone', 'completed']))
                            <button type="button"
                                    class="btn btn-ghost btn-sm"
                                    onclick="viewInvoice({{ $order->id }})">
                                <i data-lucide="file-text" class="mr-1 h-4 w-4"></i>
                                Facture
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-content text-center py-12">
                <i data-lucide="package" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
                <p class="text-gray-600 mb-4">
                    @if(request()->search)
                        Aucune commande ne correspond à votre recherche "{{ request()->search }}".
                    @else
                        Vous n'avez pas encore reçu de commandes.
                    @endif
                </p>
                @if(!request()->search)
                <a href="{{ localized_route('influencer.service.create') }}" class="btn btn-primary btn-default">
                    <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                    Créer un service
                </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="flex justify-center">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<!-- Deliver Work Modal -->
<div id="deliverModal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" x-on:click="open = false"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Livrer le travail</h3>
                </div>

                <form id="deliverForm" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <input type="hidden" name="order_id" id="deliver_order_id">

                    <div class="space-y-4">
                        <!-- Work Description -->
                        <div>
                            <label for="work_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description du travail livré *
                            </label>
                            <textarea name="work_description"
                                      id="work_description"
                                      rows="4"
                                      placeholder="Décrivez le travail que vous avez accompli..."
                                      class="input w-full"
                                      required></textarea>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label for="work_files" class="block text-sm font-medium text-gray-700 mb-2">
                                Fichiers livrables
                            </label>
                            <input type="file"
                                   name="work_files[]"
                                   id="work_files"
                                   multiple
                                   class="input w-full">
                            <p class="text-xs text-gray-500 mt-1">
                                Formats acceptés: Images, PDF, Documents. Max 10MB par fichier.
                            </p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="btn btn-primary btn-default flex-1">
                                Livrer le travail
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
function acceptOrder(orderId) {
    if (confirm('Êtes-vous sûr de vouloir accepter cette commande ?')) {
        fetch(`/influencer/orders/${orderId}/accept`, {
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
                alert('Erreur lors de l\'acceptation de la commande');
            }
        });
    }
}

function rejectOrder(orderId) {
    const reason = prompt('Raison du rejet (optionnel):');
    if (reason !== null) {
        fetch(`/influencer/orders/${orderId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors du rejet de la commande');
            }
        });
    }
}

function deliverWork(orderId) {
    document.getElementById('deliver_order_id').value = orderId;
    document.getElementById('deliverForm').action = `/influencer/orders/${orderId}/deliver`;

    const modal = document.getElementById('deliverModal');
    modal.classList.remove('hidden');
    modal.querySelector('[x-data]').__x.$data.open = true;
}

function viewInvoice(orderId) {
    window.open(`/influencer/orders/${orderId}/invoice`, '_blank');
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection