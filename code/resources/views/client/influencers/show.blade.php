@extends('layouts.dashboard')

@section('title', $influencer['name'])

@section('content')
<div class="space-y-6" x-data="influencerProfile()">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ localized_route('client.influencers.index') }}" class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="users" class="h-5 w-5"></i>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="h-5 w-5 text-gray-400"></i>
                    <span class="ml-4 text-sm font-medium text-gray-500">{{ $influencer['name'] }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Profile Header -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="relative h-48 bg-gradient-to-r from-blue-500 to-purple-600">
            <div class="absolute inset-0 bg-black opacity-20"></div>
        </div>

        <div class="relative px-6 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:space-x-6 -mt-12">
                <!-- Avatar -->
                <div class="relative">
                    <img class="h-24 w-24 rounded-full border-4 border-white object-cover" src="{{ $influencer['avatar'] }}" alt="{{ $influencer['name'] }}">
                    @if($influencer['verified'])
                    <div class="absolute -bottom-1 -right-1 bg-blue-500 rounded-full p-1">
                        <i data-lucide="verified" class="h-4 w-4 text-white"></i>
                    </div>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="mt-4 sm:mt-0 flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $influencer['name'] }}</h1>
                            <p class="text-lg text-gray-500">{{ $influencer['username'] }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 sm:mt-0 flex space-x-3">
                            <button @click="toggleFavorite()"
                                    :class="isFavorite ? 'text-red-500 bg-red-50 border-red-200' : 'text-gray-400 bg-white border-gray-300'"
                                    class="inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium hover:bg-gray-50">
                                <i data-lucide="heart" class="h-4 w-4 mr-2" :class="isFavorite ? 'fill-current' : ''"></i>
                                <span x-text="isFavorite ? 'Favori' : 'Ajouter aux favoris'"></span>
                            </button>
                            <button class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i data-lucide="mail" class="h-4 w-4 mr-2"></i>
                                Contacter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bio -->
            <div class="mt-6">
                <p class="text-gray-700">{{ $influencer['bio'] }}</p>
            </div>

            <!-- Basic Info -->
            <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($influencer['followers']) }}</div>
                    <div class="text-sm text-gray-500">Abonnés</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($influencer['following']) }}</div>
                    <div class="text-sm text-gray-500">Abonnements</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($influencer['posts']) }}</div>
                    <div class="text-sm text-gray-500">Publications</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $influencer['engagement_rate'] }}%</div>
                    <div class="text-sm text-gray-500">Engagement</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Posts -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Publications Récentes</h3>
                </div>
                <div class="p-6">
                    @if(isset($influencer->recent_posts) && is_array($influencer->recent_posts) && count($influencer->recent_posts) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($influencer->recent_posts as $post)
                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                            <img class="w-full h-48 object-cover" src="{{ $post['image'] }}" alt="Post">
                            <div class="p-4">
                                <p class="text-sm text-gray-700 mb-3">{{ $post['caption'] }}</p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i data-lucide="heart" class="h-4 w-4 mr-1"></i>
                                        {{ number_format($post['likes']) }}
                                    </span>
                                    <span class="flex items-center">
                                        <i data-lucide="message-circle" class="h-4 w-4 mr-1"></i>
                                        {{ number_format($post['comments']) }}
                                    </span>
                                    <span>{{ \Carbon\Carbon::parse($post['date'])->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500 mb-4">{{ __('influencers.no_recent_posts') }}</p>
                            <button id="load-instagram-posts" onclick="influencerProfile().loadInstagramData()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ __('influencers.load_instagram_data') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Audience Demographics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Démographie de l'Audience</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Age Groups -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Groupes d'Âge</h4>
                        <div class="space-y-2">
                            @if(isset($influencer['audience_demographics']['age_groups']) && is_array($influencer['audience_demographics']['age_groups']))
                                @foreach($influencer['audience_demographics']['age_groups'] as $age => $percentage)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $age }} ans</span>
                                <div class="flex items-center space-x-2 flex-1 ml-4">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 w-10 text-right">{{ $percentage }}%</span>
                                </div>
                            </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500">Données démographiques non disponibles</p>
                            @endif
                        </div>
                    </div>

                    <!-- Gender -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Genre</h4>
                        <div class="space-y-2">
                            @if(isset($influencer['audience_demographics']['gender']) && is_array($influencer['audience_demographics']['gender']))
                                @foreach($influencer['audience_demographics']['gender'] as $gender => $percentage)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 capitalize">{{ $gender === 'female' ? 'Femmes' : 'Hommes' }}</span>
                                <div class="flex items-center space-x-2 flex-1 ml-4">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-{{ $gender === 'female' ? 'pink' : 'blue' }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 w-10 text-right">{{ $percentage }}%</span>
                                </div>
                            </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500">Données démographiques non disponibles</p>
                            @endif
                        </div>
                    </div>

                    <!-- Top Countries -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Pays Principaux</h4>
                        <div class="space-y-2">
                            @if(isset($influencer['audience_demographics']['top_countries']) && is_array($influencer['audience_demographics']['top_countries']))
                                @foreach($influencer['audience_demographics']['top_countries'] as $country => $percentage)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $country }}</span>
                                <div class="flex items-center space-x-2 flex-1 ml-4">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 w-10 text-right">{{ $percentage }}%</span>
                                </div>
                            </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500">Données démographiques non disponibles</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Profile Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Détails du Profil</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Catégorie</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $influencer['category'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Localisation</span>
                        <span class="text-sm text-gray-900 flex items-center">
                            <i data-lucide="map-pin" class="h-4 w-4 mr-1"></i>
                            {{ $influencer['location'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Âge</span>
                        <span class="text-sm text-gray-900">{{ $influencer['age'] }} ans</span>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-500">Langues</span>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @if(isset($influencer['languages']) && is_array($influencer['languages']))
                                @foreach($influencer['languages'] as $language)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $language }}
                            </span>
                                @endforeach
                            @else
                                <span class="text-sm text-gray-500">Non disponible</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-500">Plateformes</span>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @if(isset($influencer['platforms']) && is_array($influencer['platforms']))
                                @foreach($influencer['platforms'] as $platform)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                @switch($platform)
                                    @case('instagram') 📸 Instagram @break
                                    @case('youtube') 🎥 YouTube @break
                                    @case('tiktok') 🎵 TikTok @break
                                    @case('facebook') 👥 Facebook @break
                                    @case('twitter') 🐦 Twitter @break
                                    @default {{ $platform }} @break
                                @endswitch
                            </span>
                                @endforeach
                            @else
                                <span class="text-sm text-gray-500">Non disponible</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tarification</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ number_format($influencer['rate_per_post']) }}€</div>
                        <div class="text-sm text-gray-500">par publication</div>
                    </div>
                    <div class="mt-4">
                        <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i data-lucide="mail" class="h-4 w-4 mr-2"></i>
                            Demander un Devis
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actions Rapides</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i data-lucide="download" class="h-4 w-4 mr-2"></i>
                        Télécharger le Media Kit
                    </button>
                    <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i data-lucide="share-2" class="h-4 w-4 mr-2"></i>
                        Partager le Profil
                    </button>
                    <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i data-lucide="flag" class="h-4 w-4 mr-2"></i>
                        Signaler le Profil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function influencerProfile() {
    return {
        isFavorite: {{ $influencer['is_favorite'] ? 'true' : 'false' }},

        async toggleFavorite() {
            try {
                const method = this.isFavorite ? 'DELETE' : 'POST';
                const url = this.isFavorite
                    ? `{{ localized_route('client.influencers.unfavorite', $influencer['id']) }}`
                    : `{{ localized_route('client.influencers.favorite', $influencer['id']) }}`;

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    this.isFavorite = !this.isFavorite;
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
            }
        },

        async loadInstagramData() {
            const button = document.getElementById('load-instagram-posts');
            if (!button) return;

            button.disabled = true;
            button.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Chargement...';

            try {
                const response = await fetch(`/client/influencers/{{ $influencer['id'] }}/fetch-instagram`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Reload the page to show new data
                    window.location.reload();
                } else {
                    alert('Erreur lors du chargement des données Instagram: ' + (data.message || 'Erreur inconnue'));
                }
            } catch (error) {
                console.error('Error loading Instagram data:', error);
                alert('Erreur lors du chargement des données Instagram');
            } finally {
                button.disabled = false;
                button.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Charger les données Instagram';
            }
        }
    };
}
</script>
@endsection