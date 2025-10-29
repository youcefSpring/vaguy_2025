@extends('layouts.dashboard')

@section('title', 'Mes Influenceurs Favoris')

@section('content')
<div class="space-y-6" x-data="favoriteInfluencers()">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mes Influenceurs Favoris</h1>
                <p class="mt-1 text-sm text-gray-500">Gérez votre liste d'influenceurs favoris</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ localized_route('client.influencers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i data-lucide="search" class="h-4 w-4 mr-2"></i>
                    Découvrir
                </a>
            </div>
        </div>
    </div>

    @if($favorites->isNotEmpty())
    <!-- Stats Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $favorites->count() }}</div>
                <div class="text-sm text-gray-500">Favoris</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($favorites->avg('followers')) }}</div>
                <div class="text-sm text-gray-500">Abonnés Moyen</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ round($favorites->avg('engagement_rate'), 1) }}%</div>
                <div class="text-sm text-gray-500">Engagement Moyen</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ number_format($favorites->avg('rate_per_post')) }}€</div>
                <div class="text-sm text-gray-500">Prix Moyen</div>
            </div>
        </div>
    </div>

    <!-- Favorites Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($favorites as $influencer)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <!-- Profile Header -->
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <img class="h-12 w-12 rounded-full object-cover" src="{{ $influencer['avatar'] }}" alt="{{ $influencer['name'] }}">
                        <div>
                            <div class="flex items-center space-x-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $influencer['name'] }}</h3>
                                @if($influencer['verified'])
                                <i data-lucide="verified" class="h-4 w-4 text-blue-500"></i>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ $influencer['username'] }}</p>
                        </div>
                    </div>

                    <!-- Remove from Favorites -->
                    <button @click="removeFromFavorites({{ $influencer['id'] }})"
                            class="p-2 rounded-full text-red-500 bg-red-50 hover:bg-red-100 transition-colors"
                            title="Retirer des favoris">
                        <i data-lucide="heart" class="h-5 w-5 fill-current"></i>
                    </button>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-lg font-semibold text-gray-900">{{ number_format($influencer['followers']) }}</div>
                        <div class="text-xs text-gray-500">Abonnés</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-semibold text-gray-900">{{ $influencer['engagement_rate'] }}%</div>
                        <div class="text-xs text-gray-500">Engagement</div>
                    </div>
                </div>

                <!-- Bio -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $influencer['bio'] }}</p>

                <!-- Category & Location -->
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $influencer['category'] }}
                    </span>
                    <span class="flex items-center">
                        <i data-lucide="map-pin" class="h-3 w-3 mr-1"></i>
                        {{ $influencer['location'] }}
                    </span>
                </div>

                <!-- Platforms -->
                <div class="flex items-center space-x-2 mb-4">
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
                </div>

                <!-- Price & Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div>
                        <span class="text-lg font-bold text-green-600">{{ number_format($influencer['rate_per_post']) }}€</span>
                        <span class="text-sm text-gray-500">/post</span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ localized_route('client.influencers.show', $influencer['id']) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i data-lucide="eye" class="h-4 w-4 mr-1"></i>
                            Voir
                        </a>
                        <button class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i data-lucide="mail" class="h-4 w-4 mr-1"></i>
                            Contacter
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @else
    <!-- Empty State -->
    <div class="text-center py-12">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <i data-lucide="heart" class="h-16 w-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun influenceur favori</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                Vous n'avez pas encore ajouté d'influenceurs à vos favoris. Explorez notre base de données pour découvrir des influenceurs correspondant à votre marque.
            </p>
            <a href="{{ localized_route('client.influencers.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i data-lucide="search" class="h-5 w-5 mr-2"></i>
                Découvrir des Influenceurs
            </a>
        </div>
    </div>
    @endif
</div>

<script>
function favoriteInfluencers() {
    return {
        async removeFromFavorites(influencerId) {
            try {
                const response = await fetch(`{{ localized_route('client.influencers.unfavorite', '') }}/${influencerId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Reload the page to update the favorites list
                    window.location.reload();
                } else {
                    console.error('Failed to remove from favorites');
                }
            } catch (error) {
                console.error('Error removing from favorites:', error);
            }
        }
    };
}
</script>
@endsection