@extends('layouts.dashboard')

@section('title', 'Campagnes')

@section('content')
<div class="space-y-6" x-data="campaignIndex()" x-init="init()">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('Campaigns') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Manage your marketing campaigns and track their performance') }}</p>
        </div>
        <a href="{{ localized_route('client.campaigns.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            <i data-lucide="plus" class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2"></i>
            {{ __('New Campaign') }}
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Total Campaigns') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.total">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <i data-lucide="megaphone" class="text-blue-500 dark:text-blue-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-500">
                <i data-lucide="bar-chart-3" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('All campaigns') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Active') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.active">{{ $stats['active'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <i data-lucide="play-circle" class="text-green-500 dark:text-green-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-500">
                <i data-lucide="trending-up" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Currently running') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Pending') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.pending">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                    <i data-lucide="clock" class="text-yellow-500 dark:text-yellow-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-yellow-500">
                <i data-lucide="timer" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Awaiting approval') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Completed') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.completed">{{ $stats['completed'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <i data-lucide="check-circle" class="text-purple-500 dark:text-purple-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-purple-500">
                <i data-lucide="check-check" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Finished campaigns') }}</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Filter Campaigns') }}</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="form-label">Rechercher</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="text"
                               id="search"
                               x-model="filters.search"
                               @input.debounce.500ms="loadCampaigns()"
                               class="input pl-10"
                               placeholder="Rechercher une campagne...">
                    </div>
                </div>
                <div>
                    <label for="status" class="form-label">Statut</label>
                    <div class="relative">
                        <select id="status"
                                x-model="filters.status"
                                @change="loadCampaigns()"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                            <option value="" disabled class="text-gray-400">Tous les statuts</option>
                            <option value="draft">📝 Brouillon</option>
                            <option value="active">✅ Active</option>
                            <option value="paused">⏸️ En pause</option>
                            <option value="completed">🎯 Terminée</option>
                            <option value="cancelled">❌ Annulée</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="platform" class="form-label">Plateforme</label>
                    <div class="relative">
                        <select id="platform"
                                x-model="filters.platform"
                                @change="loadCampaigns()"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                            <option value="" disabled class="text-gray-400">Toutes les plateformes</option>
                            <option value="instagram">📸 Instagram</option>
                            <option value="youtube">🎥 YouTube</option>
                            <option value="tiktok">🎵 TikTok</option>
                            <option value="facebook">👥 Facebook</option>
                            <option value="twitter">🐦 Twitter</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end space-x-2">
                    <button @click="resetFilters()" class="btn btn-outline">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </button>
                    <button @click="loadCampaigns()" class="btn btn-outline">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaigns List -->
    <div class="card">
        <div class="card-content p-0">
            <!-- Loading State -->
            <div x-show="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-500">Chargement des campagnes...</p>
            </div>

            <!-- Empty State -->
            <div x-show="!loading && campaigns.length === 0" class="text-center py-12">
                <div class="flex flex-col items-center">
                    <i data-lucide="megaphone" class="h-12 w-12 text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune campagne trouvée</h3>
                    <p class="text-gray-500 mb-6">Commencez par créer votre première campagne.</p>
                    <a href="{{ localized_route('client.campaigns.create') }}" class="btn btn-primary">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Créer une campagne
                    </a>
                </div>
            </div>

            <!-- Campaigns Table -->
            <div x-show="!loading && campaigns.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Campagne
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Entreprise
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Plateforme
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dates
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Applications
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="campaign in campaigns" :key="campaign.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img x-show="campaign.company_logo"
                                             :src="'/storage/' + campaign.company_logo"
                                             :alt="campaign.company_name"
                                             class="h-10 w-10 rounded-lg object-cover">
                                        <div x-show="!campaign.company_logo"
                                             class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i data-lucide="building" class="h-5 w-5 text-gray-400"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900" x-text="campaign.campaign_name"></div>
                                            <div class="text-sm text-gray-500" x-text="campaign.campaign_objective"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="campaign.company_name"></div>
                                    <div class="text-sm text-gray-500" x-text="campaign.company_category"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-primary" x-text="campaign.target_platform"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge"
                                          :class="{
                                              'badge-secondary': campaign.status === 'draft',
                                              'badge-success': campaign.status === 'active',
                                              'badge-warning': campaign.status === 'paused',
                                              'badge-primary': campaign.status === 'completed',
                                              'badge-danger': campaign.status === 'cancelled'
                                          }"
                                          x-text="translateStatus(campaign.status)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>
                                        <div><strong>Début:</strong> <span x-text="formatDate(campaign.campaign_start_date)"></span></div>
                                        <div><strong>Fin:</strong> <span x-text="formatDate(campaign.campaign_end_date)"></span></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-secondary" x-text="campaign.applications_count || 0"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a :href="'/client/campaigns/' + campaign.id"
                                           class="text-blue-600 hover:text-blue-900">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a :href="'/client/campaigns/' + campaign.id + '/edit'"
                                           class="text-green-600 hover:text-green-900">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <a :href="'/client/campaigns/' + campaign.id + '/analytics'"
                                           class="text-purple-600 hover:text-purple-900">
                                            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                                        </a>
                                        <button @click="confirmDelete(campaign)"
                                                class="text-red-600 hover:text-red-900">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div x-show="!loading && pagination && pagination.last_page > 1" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button @click="changePage(pagination.current_page - 1)"
                                :disabled="pagination.current_page === 1"
                                class="btn btn-outline btn-sm">
                            Précédent
                        </button>
                        <button @click="changePage(pagination.current_page + 1)"
                                :disabled="pagination.current_page === pagination.last_page"
                                class="btn btn-outline btn-sm">
                            Suivant
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de <span x-text="pagination.from"></span> à <span x-text="pagination.to"></span>
                                sur <span x-text="pagination.total"></span> résultats
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <button @click="changePage(pagination.current_page - 1)"
                                        :disabled="pagination.current_page === 1"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </button>
                                <template x-for="page in generatePageNumbers()" :key="page">
                                    <button @click="changePage(page)"
                                            :class="page === pagination.current_page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                            x-text="page">
                                    </button>
                                </template>
                                <button @click="changePage(pagination.current_page + 1)"
                                        :disabled="pagination.current_page === pagination.last_page"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Supprimer la campagne
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer cette campagne ? Cette action est irréversible.
                                </p>
                                <div x-show="campaignToDelete" class="mt-3 p-3 bg-yellow-50 rounded-md">
                                    <p class="text-sm font-medium text-gray-900" x-text="campaignToDelete?.campaign_name"></p>
                                    <p class="text-sm text-gray-500" x-text="campaignToDelete?.company_name"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="deleteCampaign()"
                            :disabled="deleting"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        <span x-show="deleting" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                        Supprimer
                    </button>
                    <button @click="showDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function campaignIndex() {
    return {
        // Data properties
        campaigns: {!! json_encode($campaigns->items() ?? []) !!},
        stats: {!! json_encode($stats ?? []) !!},
        pagination: {!! json_encode($campaigns ? [
            'current_page' => $campaigns->currentPage(),
            'last_page' => $campaigns->lastPage(),
            'from' => $campaigns->firstItem(),
            'to' => $campaigns->lastItem(),
            'total' => $campaigns->total()
        ] : null) !!},
        loading: false,
        deleting: false,
        campaignToDelete: null,
        showDeleteModal: false,

        // Filter properties
        filters: {
            search: new URLSearchParams(window.location.search).get('search') || '',
            status: new URLSearchParams(window.location.search).get('status') || '',
            platform: new URLSearchParams(window.location.search).get('platform') || ''
        },

        // Initialize component
        init() {
            // Auto-load campaigns if we have server data
            if (this.campaigns.length === 0) {
                this.loadCampaigns();
            }
        },

        // Load campaigns from server
        async loadCampaigns(page = 1) {
            this.loading = true;

            try {
                const params = new URLSearchParams({
                    page: page,
                    ...this.filters
                });

                const response = await fetch(`{{ localized_route('client.campaigns.index') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.campaigns = data.campaigns.data;
                    this.pagination = {
                        current_page: data.campaigns.current_page,
                        last_page: data.campaigns.last_page,
                        from: data.campaigns.from,
                        to: data.campaigns.to,
                        total: data.campaigns.total
                    };
                    this.stats = data.stats;

                    // Update URL without reload
                    const url = new URL(window.location);
                    Object.keys(this.filters).forEach(key => {
                        if (this.filters[key]) {
                            url.searchParams.set(key, this.filters[key]);
                        } else {
                            url.searchParams.delete(key);
                        }
                    });
                    if (page > 1) url.searchParams.set('page', page);
                    else url.searchParams.delete('page');

                    window.history.replaceState({}, '', url);
                }
            } catch (error) {
                console.error('Error loading campaigns:', error);
                window.showToast('Erreur lors du chargement des campagnes', 'error');
            } finally {
                this.loading = false;
            }
        },

        // Reset all filters
        resetFilters() {
            this.filters = {
                search: '',
                status: '',
                platform: ''
            };
            this.loadCampaigns();
        },

        // Change page
        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.loadCampaigns(page);
            }
        },

        // Generate page numbers for pagination
        generatePageNumbers() {
            const pages = [];
            const current = this.pagination.current_page;
            const last = this.pagination.last_page;

            // Always show first page
            pages.push(1);

            // Show pages around current
            for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) {
                if (!pages.includes(i)) pages.push(i);
            }

            // Always show last page
            if (last > 1 && !pages.includes(last)) pages.push(last);

            return pages.sort((a, b) => a - b);
        },

        // Confirm campaign deletion
        confirmDelete(campaign) {
            this.campaignToDelete = campaign;
            this.showDeleteModal = true;
        },

        // Delete campaign
        async deleteCampaign() {
            if (!this.campaignToDelete) return;

            this.deleting = true;

            try {
                const response = await fetch(`{{ url('client/campaigns') }}/${this.campaignToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.showToast(data.message || 'Campagne supprimée avec succès', 'success');
                    this.loadCampaigns(this.pagination.current_page);
                    this.showDeleteModal = false;
                } else {
                    window.showToast(data.message || 'Erreur lors de la suppression', 'error');
                }
            } catch (error) {
                console.error('Error deleting campaign:', error);
                window.showToast('Erreur lors de la suppression de la campagne', 'error');
            } finally {
                this.deleting = false;
                this.campaignToDelete = null;
            }
        },

        // Utility functions
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },

        translateStatus(status) {
            const statusMap = {
                'draft': 'Brouillon',
                'active': 'Active',
                'paused': 'En pause',
                'completed': 'Terminée',
                'cancelled': 'Annulée'
            };
            return statusMap[status] || status;
        }
    };
}
</script>
@endpush