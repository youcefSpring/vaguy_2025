@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Historique de vos retraits</p>
        </div>
        <a href="{{ localized_route('influencer.withdraw') }}" class="btn btn-primary">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            Nouveau retrait
        </a>
    </div>

    <!-- Balance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card">
            <div class="card-content text-center py-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="wallet" class="h-6 w-6 text-green-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-1">Solde disponible</h3>
                <p class="text-2xl font-bold text-green-600">{{ showAmount(authInfluencer()->balance) }} {{ $general->cur_text ?? '' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-content text-center py-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="trending-down" class="h-6 w-6 text-blue-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-1">Total retiré</h3>
                <p class="text-2xl font-bold text-blue-600">
                    {{ showAmount($withdraws->where('status', 1)->sum('amount')) }} {{ $general->cur_text ?? '' }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-content text-center py-6">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="clock" class="h-6 w-6 text-yellow-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-1">En attente</h3>
                <p class="text-2xl font-bold text-yellow-600">
                    {{ showAmount($withdraws->where('status', 2)->sum('amount')) }} {{ $general->cur_text ?? '' }}
                </p>
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
                           placeholder="Rechercher par numéro de transaction..."
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

    <!-- Withdrawals Table -->
    <div class="card">
        <div class="card-content p-0">
            @if($withdraws->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaction
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Méthode
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Montant demandé
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Frais
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Montant reçu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($withdraws as $withdraw)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 font-mono">{{ $withdraw->trx }}</div>
                                    @if($withdraw->rate != 1)
                                    <div class="text-xs text-gray-500">Taux: 1:{{ $withdraw->rate }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $withdraw->method->name ?? 'Méthode supprimée' }}</div>
                                    <div class="text-xs text-gray-500">{{ $withdraw->currency }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ showAmount($withdraw->amount) }} {{ $general->cur_text ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-red-600">
                                        {{ showAmount($withdraw->charge) }} {{ $general->cur_text ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">
                                        {{ showAmount($withdraw->final_amount) }} {{ $withdraw->currency }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch($withdraw->status) {
                                            case 1:
                                                $statusClass = 'bg-green-100 text-green-800';
                                                $statusText = 'Approuvé';
                                                break;
                                            case 2:
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                $statusText = 'En attente';
                                                break;
                                            case 3:
                                                $statusClass = 'bg-red-100 text-red-800';
                                                $statusText = 'Rejeté';
                                                break;
                                            default:
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                $statusText = 'Inconnu';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $withdraw->created_at->format('d/m/Y H:i') }}</div>
                                    @if($withdraw->status == 1 && $withdraw->updated_at != $withdraw->created_at)
                                    <div class="text-xs text-gray-500">Traité: {{ $withdraw->updated_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        @if($withdraw->withdraw_information)
                                        <button onclick="showWithdrawDetails({{ $withdraw->id }})"
                                                class="text-indigo-600 hover:text-indigo-900"
                                                title="Voir détails">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </button>
                                        @endif

                                        @if($withdraw->status == 1)
                                        <span class="text-green-600" title="Complété">
                                            <i data-lucide="check-circle" class="h-4 w-4"></i>
                                        </span>
                                        @elseif($withdraw->status == 2)
                                        <span class="text-yellow-600" title="En cours">
                                            <i data-lucide="clock" class="h-4 w-4"></i>
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @if($withdraw->admin_feedback && $withdraw->status == 3)
                            <tr>
                                <td colspan="8" class="px-6 py-2 bg-red-50">
                                    <div class="text-sm text-red-800">
                                        <strong>Raison du rejet:</strong> {{ $withdraw->admin_feedback }}
                                    </div>
                                </td>
                            </tr>
                            @endif

                            <!-- Hidden Details Modal Content -->
                            <div id="withdraw-details-{{ $withdraw->id }}" class="hidden">
                                @if($withdraw->withdraw_information)
                                <div class="space-y-4">
                                    <h4 class="font-medium text-gray-900">Informations de paiement soumises</h4>
                                    <div class="space-y-2">
                                        @foreach($withdraw->withdraw_information as $key => $value)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                            <span class="font-medium">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($withdraws->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $withdraws->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i data-lucide="credit-card" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun retrait trouvé</h3>
                    <p class="text-gray-600 mb-4">
                        @if(request('search'))
                            Aucun retrait ne correspond à votre recherche.
                        @else
                            Vous n'avez pas encore effectué de retrait.
                        @endif
                    </p>
                    <a href="{{ localized_route('influencer.withdraw') }}" class="btn btn-primary">
                        <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                        Effectuer un retrait
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for withdraw details -->
<div id="details-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Détails du retrait</h3>
            <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <div id="modal-content">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});

function showWithdrawDetails(withdrawId) {
    const content = document.getElementById('withdraw-details-' + withdrawId);
    const modal = document.getElementById('details-modal');
    const modalContent = document.getElementById('modal-content');

    if (content && modal && modalContent) {
        modalContent.innerHTML = content.innerHTML;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeDetailsModal() {
    const modal = document.getElementById('details-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Close modal when clicking outside
document.getElementById('details-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailsModal();
    }
});
</script>
@endpush
@endsection