@extends('layouts.dashboard')

@section('title', 'Gestion des Embauches')

@section('content')
<div class="space-y-6" x-data="hiringManager()">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Hiring Management') }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('Manage your hiring requests and collaborations') }}</p>
            </div>
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <button @click="showCreateModal = true"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <i data-lucide="plus" class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2"></i>
                    {{ __('New Hiring') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Total Hirings') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.total">0</h3>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <i data-lucide="users" class="text-blue-500 dark:text-blue-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-500">
                <i data-lucide="briefcase" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('All requests') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Pending') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.pending">0</h3>
                </div>
                <div class="p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                    <i data-lucide="clock" class="text-yellow-500 dark:text-yellow-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-yellow-500">
                <i data-lucide="hourglass" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Awaiting response') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Accepted') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.accepted">0</h3>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <i data-lucide="check-circle" class="text-green-500 dark:text-green-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-500">
                <i data-lucide="user-check" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Approved collaborations') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Rejected') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.rejected">0</h3>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-900/30 rounded-lg">
                    <i data-lucide="x-circle" class="text-red-500 dark:text-red-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-red-500">
                <i data-lucide="user-x" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Declined requests') }}</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Filter Hiring Requests') }}</h3>
        </div>
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filtres</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <div class="relative">
                        <select x-model="filters.status" @change="loadHirings()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                            <option value="" disabled class="text-gray-400">Tous les statuts</option>
                            <option value="pending">⏳ En attente</option>
                            <option value="accepted">✅ Acceptée</option>
                            <option value="rejected">❌ Refusée</option>
                            <option value="completed">🎯 Terminée</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <div class="relative">
                        <select x-model="filters.type" @change="loadHirings()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                            <option value="" disabled class="text-gray-400">Tous les types</option>
                            <option value="collaboration">🤝 Collaboration</option>
                            <option value="sponsorship">🎆 Parrainage</option>
                            <option value="review">⭐ Test produit</option>
                            <option value="event">🎉 Événement</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <div class="relative">
                        <select x-model="filters.date" @change="loadHirings()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                            <option value="" disabled class="text-gray-400">Toutes les dates</option>
                            <option value="today">📅 Aujourd'hui</option>
                            <option value="week">📆 Cette semaine</option>
                            <option value="month">📅 Ce mois</option>
                            <option value="quarter">📊 Ce trimestre</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hirings Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Liste des Embauches</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">
                        <span x-text="hirings.length"></span> embauche(s) trouvée(s)
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

            <div x-show="!loading && hirings.length === 0" class="p-8 text-center">
                <i data-lucide="users" class="mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune embauche</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par créer votre première embauche.</p>
                <div class="mt-6">
                    <button @click="showCreateModal = true" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                        Nouvelle Embauche
                    </button>
                </div>
            </div>

            <div x-show="!loading && hirings.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Influenceur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="hiring in hirings" :key="hiring.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" :src="hiring.influencer.avatar || 'https://ui-avatars.com/api/?name=' + hiring.influencer.name" :alt="hiring.influencer.name">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900" x-text="hiring.influencer.name"></div>
                                            <div class="text-sm text-gray-500" x-text="hiring.influencer.email"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-blue-100 text-blue-800': hiring.type === 'collaboration',
                                              'bg-purple-100 text-purple-800': hiring.type === 'sponsorship',
                                              'bg-green-100 text-green-800': hiring.type === 'review',
                                              'bg-orange-100 text-orange-800': hiring.type === 'event'
                                          }"
                                          x-text="getTypeLabel(hiring.type)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span x-text="formatPrice(hiring.budget)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': hiring.status === 'pending',
                                              'bg-green-100 text-green-800': hiring.status === 'accepted',
                                              'bg-red-100 text-red-800': hiring.status === 'rejected',
                                              'bg-blue-100 text-blue-800': hiring.status === 'completed'
                                          }"
                                          x-text="getStatusLabel(hiring.status)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span x-text="formatDate(hiring.created_at)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button @click="viewHiring(hiring)" class="text-blue-600 hover:text-blue-900">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </button>
                                        <button @click="editHiring(hiring)" class="text-indigo-600 hover:text-indigo-900">
                                            <i data-lucide="edit" class="h-4 w-4"></i>
                                        </button>
                                        <button @click="deleteHiring(hiring.id)" class="text-red-600 hover:text-red-900">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div x-show="!loading && hirings.length > 0" class="bg-white px-6 py-3 border-t border-gray-200">
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

    <!-- Create Hiring Modal -->
    <div x-show="showCreateModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Nouvelle Embauche</h3>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>

                <form @submit.prevent="createHiring()">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Influenceur</label>
                            <div class="relative">
                                <select x-model="newHiring.influencer_id" required
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                                    <option value="" disabled class="text-gray-400">Sélectionner un influenceur</option>
                                    <template x-for="influencer in influencers" :key="influencer.id">
                                        <option :value="influencer.id" x-text="'👤 ' + influencer.name"></option>
                                    </template>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type de collaboration</label>
                            <div class="relative">
                                <select x-model="newHiring.type" required
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                                    <option value="" disabled class="text-gray-400">Sélectionner un type</option>
                                    <option value="collaboration">🤝 Collaboration</option>
                                    <option value="sponsorship">🎆 Parrainage</option>
                                    <option value="review">⭐ Test produit</option>
                                    <option value="event">🎉 Événement</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                            <input type="text" x-model="newHiring.title" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea x-model="newHiring.description" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Budget (€)</label>
                                <input type="number" x-model="newHiring.budget" min="0" step="0.01" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date limite</label>
                                <input type="date" x-model="newHiring.deadline" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Exigences</label>
                            <textarea x-model="newHiring.requirements" rows="3" placeholder="Décrivez vos exigences spécifiques..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" :disabled="creating" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50">
                            <span x-show="!creating">Créer</span>
                            <span x-show="creating" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Création...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function hiringManager() {
    return {
        loading: false,
        creating: false,
        showCreateModal: false,
        hirings: [],
        influencers: [],
        stats: {
            total: 0,
            pending: 0,
            accepted: 0,
            rejected: 0
        },
        filters: {
            search: '',
            status: '',
            type: '',
            date: ''
        },
        newHiring: {
            influencer_id: '',
            type: '',
            title: '',
            description: '',
            budget: '',
            deadline: '',
            requirements: ''
        },
        currentPage: 1,
        perPage: 10,
        totalItems: 0,
        totalPages: 0,
        searchTimeout: null,

        init() {
            this.loadHirings();
            this.loadInfluencers();
            this.loadStats();
        },

        async loadHirings() {
            this.loading = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                // Mock data
                this.hirings = [
                    {
                        id: 1,
                        influencer: {
                            name: 'Sarah Martin',
                            email: 'sarah@example.com',
                            avatar: null
                        },
                        type: 'collaboration',
                        title: 'Promotion produit beauté',
                        budget: 1500.00,
                        status: 'pending',
                        created_at: '2024-01-15'
                    },
                    {
                        id: 2,
                        influencer: {
                            name: 'Thomas Dubois',
                            email: 'thomas@example.com',
                            avatar: null
                        },
                        type: 'sponsorship',
                        title: 'Partenariat long terme',
                        budget: 5000.00,
                        status: 'accepted',
                        created_at: '2024-01-14'
                    }
                ];

                this.totalItems = this.hirings.length;
                this.totalPages = Math.ceil(this.totalItems / this.perPage);
            } catch (error) {
                console.error('Error loading hirings:', error);
            }
            this.loading = false;
        },

        async loadInfluencers() {
            try {
                // Mock data
                this.influencers = [
                    { id: 1, name: 'Sarah Martin' },
                    { id: 2, name: 'Thomas Dubois' },
                    { id: 3, name: 'Lisa Rodriguez' }
                ];
            } catch (error) {
                console.error('Error loading influencers:', error);
            }
        },

        loadStats() {
            this.stats = {
                total: 25,
                pending: 8,
                accepted: 12,
                rejected: 5
            };
        },

        debounceSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.loadHirings();
            }, 300);
        },

        async createHiring() {
            this.creating = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                this.showCreateModal = false;
                this.resetNewHiring();
                this.loadHirings();
                this.loadStats();
            } catch (error) {
                console.error('Error creating hiring:', error);
            }
            this.creating = false;
        },

        resetNewHiring() {
            this.newHiring = {
                influencer_id: '',
                type: '',
                title: '',
                description: '',
                budget: '',
                deadline: '',
                requirements: ''
            };
        },

        viewHiring(hiring) {
            // Implement view logic
            console.log('View hiring:', hiring);
        },

        editHiring(hiring) {
            // Implement edit logic
            console.log('Edit hiring:', hiring);
        },

        async deleteHiring(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette embauche ?')) {
                try {
                    // Simulate API call
                    await new Promise(resolve => setTimeout(resolve, 500));

                    this.loadHirings();
                    this.loadStats();
                } catch (error) {
                    console.error('Error deleting hiring:', error);
                }
            }
        },

        getTypeLabel(type) {
            const labels = {
                'collaboration': 'Collaboration',
                'sponsorship': 'Parrainage',
                'review': 'Test produit',
                'event': 'Événement'
            };
            return labels[type] || type;
        },

        getStatusLabel(status) {
            const labels = {
                'pending': 'En attente',
                'accepted': 'Acceptée',
                'rejected': 'Refusée',
                'completed': 'Terminée'
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

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.loadHirings();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.loadHirings();
            }
        },

        goToPage(page) {
            this.currentPage = page;
            this.loadHirings();
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