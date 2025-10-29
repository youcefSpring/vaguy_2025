@extends('layouts.dashboard')

@section('title', 'Historique des Transactions')

@section('content')
<div class="space-y-6" x-data="transactionsManager()">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Transaction History') }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('View complete history of your financial transactions') }}</p>
            </div>
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <a href="{{ localized_route('client.financial.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2"></i>
                    {{ __('Back to Finances') }}
                </a>
                <a href="{{ localized_route('client.financial.export-transactions') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <i data-lucide="download" class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2"></i>
                    {{ __('Export') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Filter Transactions') }}</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de transaction</label>
                <select x-model="filters.type" @change="applyFilters()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Tous les types</option>
                    <option value="deposit">Dépôt</option>
                    <option value="withdrawal">Retrait</option>
                    <option value="payment">Paiement</option>
                    <option value="refund">Remboursement</option>
                    <option value="commission">Commission</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select x-model="filters.status" @change="applyFilters()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="completed">Complété</option>
                    <option value="pending">En attente</option>
                    <option value="failed">Échoué</option>
                    <option value="cancelled">Annulé</option>
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
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text"
                           x-model="filters.search"
                           @input="debounceSearch()"
                           placeholder="Rechercher par référence, description..."
                           class="block w-64 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pl-10">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                </div>
            </div>
            <button @click="resetFilters()" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i data-lucide="x" class="h-4 w-4 mr-2"></i>
                Réinitialiser
            </button>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Transactions') }}</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Reference') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Type') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Description') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Amount') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="transaction in transactions" :key="transaction.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                <span x-text="transaction.reference"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="getTypeColor(transaction.type)"
                                      x-text="getTypeLabel(transaction.type)"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                <span x-text="transaction.description"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold"
                                :class="transaction.amount >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                <span x-text="formatAmount(transaction.amount)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="getStatusColor(transaction.status)"
                                      x-text="getStatusLabel(transaction.status)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="formatDate(transaction.created_at)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center space-x-3">
                                    <button @click="viewTransaction(transaction)" class="text-blue-600 hover:text-blue-900">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </button>
                                    <button @click="downloadReceipt(transaction)" class="text-gray-600 hover:text-gray-900" x-show="transaction.has_receipt">
                                        <i data-lucide="download" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div x-show="transactions.length === 0 && !loading" class="text-center py-12">
            <i data-lucide="receipt" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune transaction trouvée</h3>
            <p class="text-gray-500">Aucune transaction ne correspond à vos critères de recherche.</p>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="text-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-gray-500 mt-2">Chargement des transactions...</p>
        </div>
    </div>

    <!-- Pagination -->
    <div x-show="pagination && pagination.last_page > 1" class="bg-white px-4 py-3 border-t border-gray-200 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de <span x-text="pagination.from"></span> à <span x-text="pagination.to"></span> sur <span x-text="pagination.total"></span> résultats
            </div>
            <div class="flex items-center space-x-2">
                <button @click="changePage(pagination.current_page - 1)"
                        :disabled="pagination.current_page <= 1"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Précédent
                </button>
                <template x-for="page in getVisiblePages()" :key="page">
                    <button @click="changePage(page)"
                            :class="page === pagination.current_page ? 'bg-blue-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50'"
                            class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-md">
                        <span x-text="page"></span>
                    </button>
                </template>
                <button @click="changePage(pagination.current_page + 1)"
                        :disabled="pagination.current_page >= pagination.last_page"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Suivant
                </button>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div x-show="showDetailModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Détails de la transaction</h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>

            <div x-show="selectedTransaction" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Référence</label>
                        <p class="mt-1 text-sm text-gray-900" x-text="selectedTransaction?.reference"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <p class="mt-1 text-sm text-gray-900" x-text="getTypeLabel(selectedTransaction?.type)"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Montant</label>
                        <p class="mt-1 text-sm font-medium"
                           :class="selectedTransaction?.amount >= 0 ? 'text-green-600' : 'text-red-600'"
                           x-text="formatAmount(selectedTransaction?.amount)"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <p class="mt-1 text-sm text-gray-900" x-text="getStatusLabel(selectedTransaction?.status)"></p>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1 text-sm text-gray-900" x-text="selectedTransaction?.description"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de création</label>
                        <p class="mt-1 text-sm text-gray-900" x-text="formatDate(selectedTransaction?.created_at)"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de traitement</label>
                        <p class="mt-1 text-sm text-gray-900" x-text="formatDate(selectedTransaction?.processed_at)"></p>
                    </div>
                </div>

                <div x-show="selectedTransaction?.notes" class="border-t pt-4">
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <p class="mt-1 text-sm text-gray-900" x-text="selectedTransaction?.notes"></p>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button @click="showDetailModal = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Fermer
                </button>
                <button x-show="selectedTransaction?.has_receipt" @click="downloadReceipt(selectedTransaction)" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                    Télécharger le reçu
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function transactionsManager() {
    return {
        // Data
        transactions: [
            {
                id: 1,
                reference: 'TXN-2024-001',
                type: 'deposit',
                description: 'Dépôt via PayPal',
                amount: 500.00,
                status: 'completed',
                created_at: '2024-01-15T10:30:00Z',
                processed_at: '2024-01-15T10:35:00Z',
                has_receipt: true,
                notes: 'Dépôt automatique confirmé'
            },
            {
                id: 2,
                reference: 'TXN-2024-002',
                type: 'payment',
                description: 'Paiement campagne #CAM-001',
                amount: -250.00,
                status: 'completed',
                created_at: '2024-01-14T14:20:00Z',
                processed_at: '2024-01-14T14:25:00Z',
                has_receipt: true,
                notes: 'Paiement pour campagne Instagram'
            },
            {
                id: 3,
                reference: 'TXN-2024-003',
                type: 'withdrawal',
                description: 'Retrait vers compte bancaire',
                amount: -150.00,
                status: 'pending',
                created_at: '2024-01-13T09:15:00Z',
                processed_at: null,
                has_receipt: false,
                notes: 'En cours de traitement'
            }
        ],
        pagination: {
            current_page: 1,
            last_page: 1,
            from: 1,
            to: 3,
            total: 3
        },
        loading: false,

        // Filters
        filters: {
            search: '',
            type: '',
            status: '',
            dateFrom: '',
            dateTo: ''
        },

        // Modal
        showDetailModal: false,
        selectedTransaction: null,

        // Search debounce
        searchTimeout: null,

        init() {
            // Initialize component
        },

        applyFilters() {
            this.loading = true;
            // Simulate API call
            setTimeout(() => {
                // Filter transactions based on current filters
                this.loading = false;
            }, 500);
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
                status: '',
                dateFrom: '',
                dateTo: ''
            };
            this.applyFilters();
        },

        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.pagination.current_page = page;
                this.applyFilters();
            }
        },

        getVisiblePages() {
            const pages = [];
            const current = this.pagination.current_page;
            const last = this.pagination.last_page;

            for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
                pages.push(i);
            }

            return pages;
        },

        viewTransaction(transaction) {
            this.selectedTransaction = transaction;
            this.showDetailModal = true;
        },

        downloadReceipt(transaction) {
            // Simulate receipt download
            alert(`Téléchargement du reçu pour ${transaction.reference}`);
        },

        getTypeLabel(type) {
            const types = {
                deposit: 'Dépôt',
                withdrawal: 'Retrait',
                payment: 'Paiement',
                refund: 'Remboursement',
                commission: 'Commission'
            };
            return types[type] || type;
        },

        getTypeColor(type) {
            const colors = {
                deposit: 'bg-green-100 text-green-800',
                withdrawal: 'bg-red-100 text-red-800',
                payment: 'bg-blue-100 text-blue-800',
                refund: 'bg-yellow-100 text-yellow-800',
                commission: 'bg-purple-100 text-purple-800'
            };
            return colors[type] || 'bg-gray-100 text-gray-800';
        },

        getStatusLabel(status) {
            const statuses = {
                completed: 'Complété',
                pending: 'En attente',
                failed: 'Échoué',
                cancelled: 'Annulé'
            };
            return statuses[status] || status;
        },

        getStatusColor(status) {
            const colors = {
                completed: 'bg-green-100 text-green-800',
                pending: 'bg-yellow-100 text-yellow-800',
                failed: 'bg-red-100 text-red-800',
                cancelled: 'bg-gray-100 text-gray-800'
            };
            return colors[status] || 'bg-gray-100 text-gray-800';
        },

        formatAmount(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(Math.abs(amount));
        },

        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    };
}
</script>
@endsection