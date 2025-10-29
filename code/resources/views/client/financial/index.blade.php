@extends('layouts.dashboard')

@section('title', 'Gestion Financière')

@section('content')
<div class="space-y-6" x-data="financialManager()">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion Financière</h1>
                <p class="mt-1 text-sm text-gray-500">Gérez vos transactions, factures et paiements</p>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="showDepositModal = true"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                    Dépôt
                </button>
                <button @click="showWithdrawModal = true"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i data-lucide="minus" class="h-4 w-4 mr-2"></i>
                    Retrait
                </button>
            </div>
        </div>
    </div>

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <i data-lucide="wallet" class="h-5 w-5 text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Solde Actuel</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="formatPrice(balance.current)">0 €</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i data-lucide="trending-up" class="h-5 w-5 text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Revenus ce mois</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="formatPrice(balance.monthly_income)">0 €</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <i data-lucide="clock" class="h-5 w-5 text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En Attente</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="formatPrice(balance.pending)">0 €</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i data-lucide="credit-card" class="h-5 w-5 text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Dépensé</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="formatPrice(balance.total_spent)">0 €</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview Chart -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Évolution Financière</h3>
                <div class="flex items-center space-x-2">
                    <select x-model="chartPeriod" @change="updateChart()" class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="7d">7 derniers jours</option>
                        <option value="30d">30 derniers jours</option>
                        <option value="90d">3 derniers mois</option>
                        <option value="1y">1 an</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            <canvas id="financialChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filtres</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text"
                               x-model="filters.search"
                               @input="debounceSearch"
                               placeholder="Rechercher..."
                               class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select x-model="filters.type" @change="loadTransactions()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Tous les types</option>
                        <option value="deposit">Dépôt</option>
                        <option value="withdrawal">Retrait</option>
                        <option value="payment">Paiement</option>
                        <option value="refund">Remboursement</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select x-model="filters.status" @change="loadTransactions()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="completed">Terminé</option>
                        <option value="pending">En attente</option>
                        <option value="failed">Échoué</option>
                        <option value="cancelled">Annulé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                    <input type="date" x-model="filters.date_from" @change="loadTransactions()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                    <input type="date" x-model="filters.date_to" @change="loadTransactions()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Historique des Transactions</h3>
                <div class="flex items-center space-x-2">
                    <button @click="exportTransactions()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i data-lucide="download" class="h-4 w-4 mr-2"></i>
                        Exporter
                    </button>
                    <span class="text-sm text-gray-500">
                        <span x-text="transactions.length"></span> transaction(s) trouvée(s)
                    </span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden">
            <div x-show="loading" class="p-8 text-center">
                <div class="inline-flex items-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span class="ml-2 text-gray-600">Chargement...</span>
                </div>
            </div>

            <div x-show="!loading && transactions.length === 0" class="p-8 text-center">
                <i data-lucide="credit-card" class="mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune transaction</h3>
                <p class="mt-1 text-sm text-gray-500">Vos transactions apparaîtront ici.</p>
            </div>

            <div x-show="!loading && transactions.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="transaction in transactions" :key="transaction.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center"
                                                 :class="{
                                                     'bg-green-100': transaction.type === 'deposit',
                                                     'bg-red-100': transaction.type === 'withdrawal',
                                                     'bg-blue-100': transaction.type === 'payment',
                                                     'bg-yellow-100': transaction.type === 'refund'
                                                 }">
                                                <i :data-lucide="getTransactionIcon(transaction.type)"
                                                   class="h-5 w-5"
                                                   :class="{
                                                       'text-green-600': transaction.type === 'deposit',
                                                       'text-red-600': transaction.type === 'withdrawal',
                                                       'text-blue-600': transaction.type === 'payment',
                                                       'text-yellow-600': transaction.type === 'refund'
                                                   }"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900" x-text="transaction.reference"></div>
                                            <div class="text-sm text-gray-500" x-text="transaction.description"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-green-100 text-green-800': transaction.type === 'deposit',
                                              'bg-red-100 text-red-800': transaction.type === 'withdrawal',
                                              'bg-blue-100 text-blue-800': transaction.type === 'payment',
                                              'bg-yellow-100 text-yellow-800': transaction.type === 'refund'
                                          }"
                                          x-text="getTypeLabel(transaction.type)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                    :class="{
                                        'text-green-600': transaction.type === 'deposit' || transaction.type === 'refund',
                                        'text-red-600': transaction.type === 'withdrawal' || transaction.type === 'payment'
                                    }">
                                    <span x-text="(transaction.type === 'deposit' || transaction.type === 'refund' ? '+' : '-') + formatPrice(transaction.amount)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-green-100 text-green-800': transaction.status === 'completed',
                                              'bg-yellow-100 text-yellow-800': transaction.status === 'pending',
                                              'bg-red-100 text-red-800': transaction.status === 'failed',
                                              'bg-gray-100 text-gray-800': transaction.status === 'cancelled'
                                          }"
                                          x-text="getStatusLabel(transaction.status)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>
                                        <div x-text="formatDate(transaction.created_at)"></div>
                                        <div class="text-xs text-gray-400" x-text="formatTime(transaction.created_at)"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button @click="viewTransaction(transaction)" class="text-blue-600 hover:text-blue-900">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </button>
                                        <button x-show="transaction.status === 'completed'" @click="downloadReceipt(transaction)" class="text-green-600 hover:text-green-900">
                                            <i data-lucide="download" class="h-4 w-4"></i>
                                        </button>
                                        <button x-show="transaction.status === 'pending'" @click="cancelTransaction(transaction.id)" class="text-red-600 hover:text-red-900">
                                            <i data-lucide="x" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div x-show="!loading && transactions.length > 0" class="bg-white px-6 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button @click="previousPage()" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                            Précédent
                        </button>
                        <button @click="nextPage()" :disabled="currentPage === totalPages" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                            Suivant
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de <span class="font-medium" x-text="((currentPage - 1) * perPage) + 1"></span> à
                                <span class="font-medium" x-text="Math.min(currentPage * perPage, totalItems)"></span> sur
                                <span class="font-medium" x-text="totalItems"></span> résultats
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <button @click="previousPage()" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                    <i data-lucide="chevron-left" class="h-5 w-5"></i>
                                </button>
                                <template x-for="page in getPageNumbers()" :key="page">
                                    <button @click="goToPage(page)" :class="page === currentPage ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        <span x-text="page"></span>
                                    </button>
                                </template>
                                <button @click="nextPage()" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                    <i data-lucide="chevron-right" class="h-5 w-5"></i>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div x-show="showDepositModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Effectuer un Dépôt</h3>
                    <button @click="showDepositModal = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>

                <form @submit.prevent="makeDeposit()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Montant (€)</label>
                            <input type="number" x-model="depositForm.amount" min="10" step="0.01" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Montant minimum: 10€</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                            <select x-model="depositForm.payment_method" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <option value="">Sélectionner une méthode</option>
                                <option value="card">Carte bancaire</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Virement bancaire</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (optionnel)</label>
                            <textarea x-model="depositForm.description" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showDepositModal = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 disabled:opacity-50">
                            <span x-show="!submitting">Confirmer le dépôt</span>
                            <span x-show="submitting" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Traitement...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdraw Modal -->
    <div x-show="showWithdrawModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Demander un Retrait</h3>
                    <button @click="showWithdrawModal = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>

                <form @submit.prevent="makeWithdrawal()">
                    <div class="space-y-4">
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <div class="flex">
                                <i data-lucide="info" class="h-5 w-5 text-yellow-400"></i>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Solde disponible: <span class="font-medium" x-text="formatPrice(balance.current)"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Montant (€)</label>
                            <input type="number" x-model="withdrawForm.amount" min="20" :max="balance.current" step="0.01" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Montant minimum: 20€</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de retrait</label>
                            <select x-model="withdrawForm.withdrawal_method" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                <option value="">Sélectionner une méthode</option>
                                <option value="bank_transfer">Virement bancaire</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Informations bancaires</label>
                            <textarea x-model="withdrawForm.bank_details" rows="3" placeholder="IBAN, BIC, nom de la banque..." required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Raison du retrait (optionnel)</label>
                            <textarea x-model="withdrawForm.reason" rows="2" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showWithdrawModal = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 disabled:opacity-50">
                            <span x-show="!submitting">Demander le retrait</span>
                            <span x-show="submitting" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Traitement...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function financialManager() {
    return {
        loading: false,
        submitting: false,
        showDepositModal: false,
        showWithdrawModal: false,
        transactions: [],
        chart: null,
        chartPeriod: '30d',
        balance: {
            current: 2450.75,
            monthly_income: 850.00,
            pending: 120.50,
            total_spent: 5680.25
        },
        filters: {
            search: '',
            type: '',
            status: '',
            date_from: '',
            date_to: ''
        },
        depositForm: {
            amount: '',
            payment_method: '',
            description: ''
        },
        withdrawForm: {
            amount: '',
            withdrawal_method: '',
            bank_details: '',
            reason: ''
        },
        currentPage: 1,
        perPage: 10,
        totalItems: 0,
        totalPages: 0,
        searchTimeout: null,

        init() {
            this.loadTransactions();
            this.initChart();
        },

        async loadTransactions() {
            this.loading = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                // Mock data
                this.transactions = [
                    {
                        id: 1,
                        reference: 'TXN-001',
                        type: 'deposit',
                        amount: 500.00,
                        status: 'completed',
                        description: 'Dépôt par carte bancaire',
                        created_at: '2024-01-15T10:30:00Z'
                    },
                    {
                        id: 2,
                        reference: 'TXN-002',
                        type: 'payment',
                        amount: 150.00,
                        status: 'completed',
                        description: 'Paiement campagne Instagram',
                        created_at: '2024-01-14T14:20:00Z'
                    },
                    {
                        id: 3,
                        reference: 'TXN-003',
                        type: 'withdrawal',
                        amount: 200.00,
                        status: 'pending',
                        description: 'Retrait vers compte bancaire',
                        created_at: '2024-01-13T09:15:00Z'
                    }
                ];

                this.totalItems = this.transactions.length;
                this.totalPages = Math.ceil(this.totalItems / this.perPage);
            } catch (error) {
                console.error('Error loading transactions:', error);
            }
            this.loading = false;
        },

        initChart() {
            const ctx = document.getElementById('financialChart').getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                    datasets: [{
                        label: 'Revenus',
                        data: [1200, 1900, 3000, 2500, 2200, 3000],
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.1
                    }, {
                        label: 'Dépenses',
                        data: [800, 1200, 1500, 1800, 1100, 1400],
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + '€';
                                }
                            }
                        }
                    }
                }
            });
        },

        updateChart() {
            // Update chart data based on selected period
            console.log('Updating chart for period:', this.chartPeriod);
        },

        debounceSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.loadTransactions();
            }, 300);
        },

        async makeDeposit() {
            this.submitting = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));

                this.showDepositModal = false;
                this.resetDepositForm();
                this.loadTransactions();
                // Update balance
                this.balance.current += parseFloat(this.depositForm.amount);
            } catch (error) {
                console.error('Error making deposit:', error);
            }
            this.submitting = false;
        },

        async makeWithdrawal() {
            this.submitting = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));

                this.showWithdrawModal = false;
                this.resetWithdrawForm();
                this.loadTransactions();
            } catch (error) {
                console.error('Error making withdrawal:', error);
            }
            this.submitting = false;
        },

        resetDepositForm() {
            this.depositForm = {
                amount: '',
                payment_method: '',
                description: ''
            };
        },

        resetWithdrawForm() {
            this.withdrawForm = {
                amount: '',
                withdrawal_method: '',
                bank_details: '',
                reason: ''
            };
        },

        viewTransaction(transaction) {
            console.log('View transaction:', transaction);
        },

        downloadReceipt(transaction) {
            console.log('Download receipt for:', transaction);
        },

        async cancelTransaction(id) {
            if (confirm('Êtes-vous sûr de vouloir annuler cette transaction ?')) {
                try {
                    // Simulate API call
                    await new Promise(resolve => setTimeout(resolve, 500));
                    this.loadTransactions();
                } catch (error) {
                    console.error('Error cancelling transaction:', error);
                }
            }
        },

        exportTransactions() {
            console.log('Exporting transactions...');
        },

        getTransactionIcon(type) {
            const icons = {
                'deposit': 'plus',
                'withdrawal': 'minus',
                'payment': 'credit-card',
                'refund': 'rotate-ccw'
            };
            return icons[type] || 'circle';
        },

        getTypeLabel(type) {
            const labels = {
                'deposit': 'Dépôt',
                'withdrawal': 'Retrait',
                'payment': 'Paiement',
                'refund': 'Remboursement'
            };
            return labels[type] || type;
        },

        getStatusLabel(status) {
            const labels = {
                'completed': 'Terminé',
                'pending': 'En attente',
                'failed': 'Échoué',
                'cancelled': 'Annulé'
            };
            return labels[status] || status;
        },

        formatPrice(price) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(price);
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR');
        },

        formatTime(date) {
            return new Date(date).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.loadTransactions();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.loadTransactions();
            }
        },

        goToPage(page) {
            this.currentPage = page;
            this.loadTransactions();
        },

        getPageNumbers() {
            const pages = [];
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, this.currentPage + 2);

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            return pages;
        }
    };
}
</script>
@endsection