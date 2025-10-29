@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Historique de toutes vos transactions</p>
        </div>
    </div>

    <!-- Balance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-content text-center py-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="wallet" class="h-6 w-6 text-green-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-1">Solde actuel</h3>
                <p class="text-2xl font-bold text-green-600">{{ showAmount(authInfluencer()->balance) }} {{ $general->cur_text ?? '' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-content text-center py-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="trending-up" class="h-6 w-6 text-blue-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-1">Total reçu</h3>
                <p class="text-2xl font-bold text-blue-600">
                    {{ showAmount($transactions->where('trx_type', '+')->sum('amount')) }} {{ $general->cur_text ?? '' }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-content text-center py-6">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="trending-down" class="h-6 w-6 text-red-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-1">Total dépensé</h3>
                <p class="text-2xl font-bold text-red-600">
                    {{ showAmount($transactions->where('trx_type', '-')->sum('amount')) }} {{ $general->cur_text ?? '' }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-content text-center py-6">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="list" class="h-6 w-6 text-purple-600"></i>
                </div>
                <h3 class="font-medium text-gray-900 mb-1">Transactions</h3>
                <p class="text-2xl font-bold text-purple-600">{{ $transactions->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtres</h3>
        </div>
        <div class="card-content">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search by Transaction ID -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Rechercher par transaction
                    </label>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="ID de transaction..."
                           class="input w-full">
                </div>

                <!-- Transaction Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Type de transaction
                    </label>
                    <select name="type" id="type" class="input w-full">
                        <option value="">Tous les types</option>
                        <option value="+" {{ request('type') == '+' ? 'selected' : '' }}>Crédit (+)</option>
                        <option value="-" {{ request('type') == '-' ? 'selected' : '' }}>Débit (-)</option>
                    </select>
                </div>

                <!-- Remark -->
                <div>
                    <label for="remark" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie
                    </label>
                    <select name="remark" id="remark" class="input w-full">
                        <option value="">Toutes les catégories</option>
                        @foreach($remarks as $remarkItem)
                            <option value="{{ $remarkItem->remark }}" {{ request('remark') == $remarkItem->remark ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $remarkItem->remark)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i data-lucide="search" class="mr-2 h-4 w-4"></i>
                        Filtrer
                    </button>
                    @if(request()->hasAny(['search', 'type', 'remark']))
                        <a href="{{ localized_route('influencer.transactions') }}" class="btn btn-ghost">
                            <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-content p-0">
            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaction
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Montant
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catégorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Solde après
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Détails
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-{{ $transaction->trx_type == '+' ? 'green' : 'red' }}-100 flex items-center justify-center mr-3">
                                            <i data-lucide="{{ $transaction->trx_type == '+' ? 'plus' : 'minus' }}" class="h-4 w-4 text-{{ $transaction->trx_type == '+' ? 'green' : 'red' }}-600"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 font-mono">{{ $transaction->trx }}</div>
                                            @if($transaction->charge > 0)
                                            <div class="text-xs text-gray-500">Frais: {{ showAmount($transaction->charge) }} {{ $general->cur_text ?? '' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-{{ $transaction->trx_type == '+' ? 'green' : 'red' }}-600">
                                        {{ $transaction->trx_type }}{{ showAmount($transaction->amount) }} {{ $general->cur_text ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $remarkClass = '';
                                        $remarkText = ucfirst(str_replace('_', ' ', $transaction->remark));
                                        switch($transaction->remark) {
                                            case 'order_payment':
                                                $remarkClass = 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'hiring_payment':
                                                $remarkClass = 'bg-green-100 text-green-800';
                                                break;
                                            case 'withdraw':
                                                $remarkClass = 'bg-red-100 text-red-800';
                                                break;
                                            case 'commission':
                                                $remarkClass = 'bg-purple-100 text-purple-800';
                                                break;
                                            default:
                                                $remarkClass = 'bg-gray-100 text-gray-800';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $remarkClass }}">
                                        {{ $remarkText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ showAmount($transaction->post_balance) }} {{ $general->cur_text ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    {{ $transaction->details }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i data-lucide="receipt" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune transaction trouvée</h3>
                    <p class="text-gray-600 mb-4">
                        @if(request()->hasAny(['search', 'type', 'remark']))
                            Aucune transaction ne correspond à vos filtres.
                        @else
                            Vous n'avez pas encore de transactions.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'type', 'remark']))
                        <a href="{{ localized_route('influencer.transactions') }}" class="btn btn-primary">
                            <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                            Effacer les filtres
                        </a>
                    @endif
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
