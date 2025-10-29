@extends('layouts.dashboard')

@section('title', 'Historique Financier')

@section('content')
<div class="space-y-6" x-data="financialHistoryManager()">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Historique Financier</h1>
                <p class="mt-1 text-sm text-gray-500">Consultez l'historique complet de vos activités financières</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ localized_route('client.financial.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i data-lucide="arrow-left" class="h-4 w-4 mr-2"></i>
                    Retour aux Finances
                </a>
                <a href="{{ localized_route('client.financial.export-transactions') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i data-lucide="download" class="h-4 w-4 mr-2"></i>
                    Exporter
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="trending-up" class="h-8 w-8 text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Déposé</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_deposited'] ?? 0, 2) }} €</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="trending-down" class="h-8 w-8 text-red-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Dépensé</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_spent'] ?? 0, 2) }} €</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="activity" class="h-8 w-8 text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Transactions</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_transactions'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="wallet" class="h-8 w-8 text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Solde Net</dt>
                            <dd class="text-lg font-medium {{ ($stats['net_balance'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($stats['net_balance'] ?? 0, 2) }} €
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type d'activité</label>
                <select x-model="filters.type" @change="applyFilters()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Tous les types</option>
                    <option value="transaction">Transactions</option>
                    <option value="deposit">Dépôts</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                <input type="date" x-model="filters.dateFrom" @change="applyFilters()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                <input type="date" x-model="filters.dateTo" @change="applyFilters()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <div class="relative">
                <input type="text"
                       x-model="filters.search"
                       @input="debounceSearch()"
                       placeholder="Rechercher par référence, description..."
                       class="block w-64 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pl-10">
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"></i>
            </div>
            <button @click="resetFilters()" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i data-lucide="x" class="h-4 w-4 mr-2"></i>
                Réinitialiser
            </button>
        </div>
    </div>

    <!-- Financial History Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Historique des Activités</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($allActivities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if(isset($activity->trx))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Transaction
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Dépôt
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if(isset($activity->trx))
                                    {{ $activity->details ?? 'Transaction' }}
                                @else
                                    Dépôt via {{ $activity->method_currency ?? 'Gateway' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(isset($activity->trx))
                                    <span class="{{ $activity->trx_type === '+' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $activity->trx_type === '+' ? '+' : '-' }}{{ number_format($activity->amount, 2) }} €
                                    </span>
                                @else
                                    <span class="text-green-600">
                                        +{{ number_format($activity->amount, 2) }} €
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if(isset($activity->trx))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Complété
                                    </span>
                                @else
                                    @if($activity->status == 1)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Approuvé
                                        </span>
                                    @elseif($activity->status == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rejeté
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i data-lucide="activity" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun historique trouvé</h3>
                                <p class="text-gray-500">Aucune activité financière ne correspond à vos critères.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function financialHistoryManager() {
    return {
        // Filters
        filters: {
            search: '',
            type: '',
            dateFrom: '',
            dateTo: ''
        },

        // Search debounce
        searchTimeout: null,

        init() {
            // Initialize component
        },

        applyFilters() {
            // Apply filters - would normally make AJAX call
            console.log('Applying filters:', this.filters);
        },

        debounceSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.applyFilters();
            }, 300);
        },

        resetFilters() {
            this.filters = {
                search: '',
                type: '',
                dateFrom: '',
                dateTo: ''
            };
            this.applyFilters();
        }
    };
}
</script>
@endsection