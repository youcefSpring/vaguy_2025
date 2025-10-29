@extends('layouts.dashboard')

@section('title', 'Top Influencers')

@section('content')
<div class="space-y-6" x-data="influencerDiscovery()">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Top Influencers</h1>
                <p class="mt-1 text-sm text-gray-500">Discover the most influential personalities across various domains</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ localized_route('client.influencers.favorites') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i data-lucide="heart" class="h-4 w-4 mr-2"></i>
                    Mes Favoris
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text"
                           placeholder="Search for influencers..."
                           x-model="searchQuery"
                           @input="search"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filter Button -->
            <button @click="showFilterModal = true"
                    class="inline-flex items-center px-6 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                </svg>
                Filter
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="users" class="h-8 w-8 text-blue-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Influenceurs</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="verified" class="h-8 w-8 text-green-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Vérifiés</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['verified'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="trending-up" class="h-8 w-8 text-indigo-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Engagement Moyen</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['avg_engagement'] ?? 0 }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="tag" class="h-8 w-8 text-orange-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Catégories</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['categories'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Influencers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($influencers as $influencer)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <!-- Influencer Card -->
                <div class="relative">
                    <!-- Cover Photo -->
                    <div class="h-32 bg-gradient-to-br from-blue-400 to-purple-500"></div>

                    <!-- Profile Photo -->
                    <div class="absolute -bottom-8 left-4">
                        <img class="h-16 w-16 rounded-full border-4 border-white object-cover"
                             src="{{ $influencer->image ? asset('assets/images/influencer/profile/' . $influencer->image) : 'https://ui-avatars.com/api/?name=' . urlencode($influencer->fullname ?? $influencer->username) . '&background=random' }}"
                             alt="{{ $influencer->fullname ?? $influencer->username }}">
                    </div>

                    <!-- Favorite Button -->
                    <button @click="toggleFavorite({{ $influencer->id }})"
                            class="absolute top-3 right-3 p-2 bg-white rounded-full shadow-sm hover:bg-gray-50">
                        <svg class="w-4 h-4" :class="favorites.includes({{ $influencer->id }}) ? 'text-red-500 fill-current' : 'text-gray-400'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="px-4 pt-12 pb-4">
                    <!-- Name and Category -->
                    <div class="mb-3">
                        <h3 class="font-semibold text-gray-900 truncate">{{ $influencer->fullname ?? $influencer->username }}</h3>
                        <p class="text-sm text-gray-600">{{ $influencer->categories->first()->name ?? 'model' }}</p>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= ($influencer->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">({{ number_format($influencer->rating ?? 0, 1) }})</span>
                    </div>

                    <!-- Social Stats -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            @if($influencer->socialLink && $influencer->socialLink->first() && $influencer->socialLink->first()->social_icon)
                                <i data-lucide="{{ strtolower($influencer->socialLink->first()->social_icon) }}" class="h-4 w-4 mr-1"></i>
                            @else
                                <i data-lucide="instagram" class="h-4 w-4 mr-1"></i>
                            @endif
                            {{ number_format($influencer->socialLink->first()->followers ?? 0) }}
                        </div>
                        @if($influencer->kv == 1)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i data-lucide="verified" class="h-3 w-3 mr-1"></i>
                                Vérifié
                            </span>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <a href="{{ localized_route('client.influencers.show', $influencer->id) }}"
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        View profile
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun influenceur trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($influencers->hasPages())
        <div class="flex justify-center">
            {{ $influencers->links() }}
        </div>
    @endif
</div>

<!-- Advanced Filter Modal -->
<div x-show="showFilterModal"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">

    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showFilterModal = false"></div>

    <!-- Modal content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="showFilterModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">

            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Filter</h3>
                <button @click="showFilterModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Social Media Platforms -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Sélectionnez les plateformes de médias sociaux que vous souhaitez filtrer.
                    </label>
                    <div class="relative">
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                            <option>Sélectionner les médias sociaux</option>
                            <option value="instagram">Instagram</option>
                            <option value="tiktok">TikTok</option>
                            <option value="youtube">YouTube</option>
                            <option value="facebook">Facebook</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Influencer Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Catégorie d'influenceurs.
                    </label>
                    <div class="relative">
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                            <option>Catégorie</option>
                            <option value="fashion">Fashion</option>
                            <option value="beauty">Beauty</option>
                            <option value="travel">Travel</option>
                            <option value="food">Food</option>
                            <option value="fitness">Fitness</option>
                            <option value="tech">Technology</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Followers Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Nombre minimum d'abonnés.
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option>Nombre minimum d'abonnés</option>
                                <option value="1000">1K+</option>
                                <option value="10000">10K+</option>
                                <option value="100000">100K+</option>
                                <option value="1000000">1M+</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Nombre maximum d'abonnés.
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option>Nombre maximum d'abonnés</option>
                                <option value="10000">10K</option>
                                <option value="100000">100K</option>
                                <option value="1000000">1M</option>
                                <option value="10000000">10M+</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Interactions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Nombre moyen d'interactions.
                    </label>
                    <div class="relative">
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                            <option>Interactions moyennes</option>
                            <option value="100">100+</option>
                            <option value="1000">1K+</option>
                            <option value="10000">10K+</option>
                            <option value="100000">100K+</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Geographic Filters -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Influenceurs de régions spécifiques.
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option>Région</option>
                                <option value="north">Nord</option>
                                <option value="south">Sud</option>
                                <option value="east">Est</option>
                                <option value="west">Ouest</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Audience dans des régions spécifiques.
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option>Audience par wilaya</option>
                                <option value="algiers">Alger</option>
                                <option value="oran">Oran</option>
                                <option value="constantine">Constantine</option>
                                <option value="annaba">Annaba</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            % d'interactions.
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option>Pourcentage...</option>
                                <option value="1">1%+</option>
                                <option value="3">3%+</option>
                                <option value="5">5%+</option>
                                <option value="10">10%+</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gender Filters -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Genre des influenceurs.
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option>Genre</option>
                                <option value="male">Homme</option>
                                <option value="female">Femme</option>
                                <option value="other">Autre</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Genre de l'audience cible.
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                                <option>% genre.</option>
                                <option value="male_majority">Majorité Homme</option>
                                <option value="female_majority">Majorité Femme</option>
                                <option value="balanced">Équilibré</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 px-6 py-4 border-t border-gray-200">
                <button @click="clearFilters()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Clear
                </button>
                <button @click="applyFilters()"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function influencerDiscovery() {
    return {
        searchQuery: '',
        showFilterModal: false,
        favorites: [],

        init() {
            // Load favorites from localStorage
            this.loadFavorites();
        },

        search() {
            // Implement search functionality
            console.log('Searching for:', this.searchQuery);
        },

        toggleFavorite(influencerId) {
            const index = this.favorites.indexOf(influencerId);
            if (index === -1) {
                this.favorites.push(influencerId);
                this.addToFavorites(influencerId);
            } else {
                this.favorites.splice(index, 1);
                this.removeFromFavorites(influencerId);
            }
            this.saveFavorites();
        },

        async addToFavorites(influencerId) {
            try {
                const response = await fetch(`/client/influencers/${influencerId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to add to favorites');
                }
            } catch (error) {
                console.error('Error adding to favorites:', error);
            }
        },

        async removeFromFavorites(influencerId) {
            try {
                const response = await fetch(`/client/influencers/${influencerId}/favorite`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to remove from favorites');
                }
            } catch (error) {
                console.error('Error removing from favorites:', error);
            }
        },

        loadFavorites() {
            const saved = localStorage.getItem('influencer_favorites');
            if (saved) {
                this.favorites = JSON.parse(saved);
            }
        },

        saveFavorites() {
            localStorage.setItem('influencer_favorites', JSON.stringify(this.favorites));
        },

        clearFilters() {
            // Reset all filter values
            this.showFilterModal = false;
        },

        applyFilters() {
            // Apply selected filters
            this.showFilterModal = false;
            // Implement filter logic
        }
    }
}
</script>
@endpush
@endsection