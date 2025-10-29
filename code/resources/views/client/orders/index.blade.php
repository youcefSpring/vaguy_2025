@extends('layouts.dashboard')

@section('title', 'Commandes')

@section('content')
<div class="space-y-6" x-data="orderIndex()" x-init="init()">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('Orders') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Manage your service orders and track their progress') }}</p>
        </div>
        <a href="{{ localized_route('client.orders.create') }}" class="btn btn-primary inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i data-lucide="plus" class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2"></i>
            {{ __('New Order') }}
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Total') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.total">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <i data-lucide="shopping-cart" class="text-blue-500 dark:text-blue-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-500">
                <i data-lucide="package" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('All orders') }}</span>
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
                <i data-lucide="hourglass" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Awaiting action') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('In Progress') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="stats.in_progress">{{ $stats['in_progress'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <i data-lucide="play-circle" class="text-green-500 dark:text-green-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-500">
                <i data-lucide="activity" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Being processed') }}</span>
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
                <span>{{ __('Finished orders') }}</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-content">
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
                               @input.debounce.500ms="loadOrders()"
                               class="input pl-10"
                               placeholder="Rechercher une commande...">
                    </div>
                </div>
                <div>
                    <label for="status" class="form-label">Statut</label>
                    <div class="relative">
                        <select id="status"
                                x-model="filters.status"
                                @change="loadOrders()"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                            <option value="" disabled class="text-gray-400">Tous les statuts</option>
                            <option value="pending">⏳ En attente</option>
                            <option value="in_progress">📝 En cours</option>
                            <option value="completed">✅ Terminée</option>
                            <option value="cancelled">❌ Annulée</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="service_type" class="form-label">Type de service</label>
                    <div class="relative">
                        <select id="service_type"
                                x-model="filters.service_type"
                                @change="loadOrders()"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                            <option value="" disabled class="text-gray-400">Tous les types</option>
                            <option value="content_creation">📝 Création de contenu</option>
                            <option value="social_media">📱 Médias sociaux</option>
                            <option value="video_production">🎥 Production vidéo</option>
                            <option value="photography">📷 Photographie</option>
                            <option value="consulting">📈 Conseil</option>
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
                    <button @click="loadOrders()" class="btn btn-outline">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="card">
        <div class="card-content p-0">
            <!-- Loading State -->
            <div x-show="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-500">Chargement des commandes...</p>
            </div>

            <!-- Empty State -->
            <div x-show="!loading && orders.length === 0" class="text-center py-12">
                <div class="flex flex-col items-center">
                    <i data-lucide="shopping-cart" class="h-12 w-12 text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
                    <p class="text-gray-500 mb-6">Commencez par créer votre première commande.</p>
                    <a href="{{ localized_route('client.orders.create') }}" class="btn btn-primary">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Créer une commande
                    </a>
                </div>
            </div>

            <!-- Orders Table -->
            <div x-show="!loading && orders.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Commande
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Service
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Influenceur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Prix
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="order in orders" :key="order.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="'#' + order.id"></div>
                                    <div class="text-sm text-gray-500" x-text="order.title"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="order.service_type"></div>
                                    <div class="text-sm text-gray-500" x-text="order.service_category"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img x-show="order.influencer_avatar"
                                             :src="order.influencer_avatar"
                                             :alt="order.influencer_name"
                                             class="h-8 w-8 rounded-full object-cover">
                                        <div x-show="!order.influencer_avatar"
                                             class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i data-lucide="user" class="h-4 w-4 text-gray-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900" x-text="order.influencer_name || 'Non assigné'"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge"
                                          :class="{
                                              'badge-warning': order.status === 'pending',
                                              'badge-primary': order.status === 'in_progress',
                                              'badge-success': order.status === 'completed',
                                              'badge-danger': order.status === 'cancelled'
                                          }"
                                          x-text="translateStatus(order.status)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span x-text="order.price + ' DZD'"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span x-text="formatDate(order.created_at)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a :href="'/client/orders/' + order.id"
                                           class="text-blue-600 hover:text-blue-900">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a :href="'/client/orders/' + order.id + '/edit'"
                                           class="text-green-600 hover:text-green-900">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <button @click="confirmDelete(order)"
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
                    <div class="text-sm text-gray-700">
                        Affichage de <span x-text="pagination.from"></span> à <span x-text="pagination.to"></span>
                        sur <span x-text="pagination.total"></span> résultats
                    </div>
                    <div class="flex space-x-2">
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
                                Supprimer la commande
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer cette commande ? Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="deleteOrder()"
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
function orderIndex() {
    return {
        // Data properties
        orders: {!! json_encode($orders->items() ?? []) !!},
        stats: {!! json_encode($stats ?? []) !!},
        pagination: {!! json_encode($orders ? [
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'from' => $orders->firstItem(),
            'to' => $orders->lastItem(),
            'total' => $orders->total()
        ] : null) !!},
        loading: false,
        deleting: false,
        orderToDelete: null,
        showDeleteModal: false,

        // Filter properties
        filters: {
            search: new URLSearchParams(window.location.search).get('search') || '',
            status: new URLSearchParams(window.location.search).get('status') || '',
            service_type: new URLSearchParams(window.location.search).get('service_type') || ''
        },

        // Initialize component
        init() {
            if (this.orders.length === 0) {
                this.loadOrders();
            }
        },

        // Load orders from server
        async loadOrders(page = 1) {
            this.loading = true;

            try {
                const params = new URLSearchParams({
                    page: page,
                    ...this.filters
                });

                const response = await fetch(`{{ localized_route('client.orders.index') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.orders = data.orders.data;
                    this.pagination = {
                        current_page: data.orders.current_page,
                        last_page: data.orders.last_page,
                        from: data.orders.from,
                        to: data.orders.to,
                        total: data.orders.total
                    };
                    this.stats = data.stats;
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                window.showToast('Erreur lors du chargement des commandes', 'error');
            } finally {
                this.loading = false;
            }
        },

        // Reset all filters
        resetFilters() {
            this.filters = {
                search: '',
                status: '',
                service_type: ''
            };
            this.loadOrders();
        },

        // Change page
        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.loadOrders(page);
            }
        },

        // Confirm order deletion
        confirmDelete(order) {
            this.orderToDelete = order;
            this.showDeleteModal = true;
        },

        // Delete order
        async deleteOrder() {
            if (!this.orderToDelete) return;

            this.deleting = true;

            try {
                const response = await fetch(`{{ url('client/orders') }}/${this.orderToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.showToast(data.message || 'Commande supprimée avec succès', 'success');
                    this.loadOrders(this.pagination.current_page);
                    this.showDeleteModal = false;
                } else {
                    window.showToast(data.message || 'Erreur lors de la suppression', 'error');
                }
            } catch (error) {
                console.error('Error deleting order:', error);
                window.showToast('Erreur lors de la suppression de la commande', 'error');
            } finally {
                this.deleting = false;
                this.orderToDelete = null;
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
                'pending': 'En attente',
                'in_progress': 'En cours',
                'completed': 'Terminée',
                'cancelled': 'Annulée'
            };
            return statusMap[status] || status;
        }
    };
}
</script>
@endpush