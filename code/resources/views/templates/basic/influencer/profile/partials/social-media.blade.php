<!-- Social Media Section -->
<form method="POST" action="{{ localized_route('influencer.profile.social') }}" class="space-y-6">
    @csrf

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Réseaux sociaux</h3>
            <p class="card-description">Connectez vos comptes de réseaux sociaux pour augmenter votre visibilité</p>
        </div>
        <div class="card-content space-y-6">
            <!-- Instagram -->
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-500 rounded-lg flex items-center justify-center">
                        <i data-lucide="instagram" class="h-5 w-5 text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">
                        Instagram
                    </label>
                    <input type="url"
                           name="instagram"
                           id="instagram"
                           value="{{ old('instagram', $influencer->social_media->instagram ?? '') }}"
                           placeholder="https://instagram.com/username"
                           class="input w-full">
                    @error('instagram')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-shrink-0">
                    @if($influencer->social_media->instagram ?? false)
                        <span class="badge badge-default">Connecté</span>
                    @else
                        <span class="badge badge-outline">Non connecté</span>
                    @endif
                </div>
            </div>

            <!-- YouTube -->
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="youtube" class="h-5 w-5 text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="youtube" class="block text-sm font-medium text-gray-700 mb-1">
                        YouTube
                    </label>
                    <input type="url"
                           name="youtube"
                           id="youtube"
                           value="{{ old('youtube', $influencer->social_media->youtube ?? '') }}"
                           placeholder="https://youtube.com/channel/your-channel"
                           class="input w-full">
                    @error('youtube')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-shrink-0">
                    @if($influencer->social_media->youtube ?? false)
                        <span class="badge badge-default">Connecté</span>
                    @else
                        <span class="badge badge-outline">Non connecté</span>
                    @endif
                </div>
            </div>

            <!-- TikTok -->
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                        <i data-lucide="music" class="h-5 w-5 text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="tiktok" class="block text-sm font-medium text-gray-700 mb-1">
                        TikTok
                    </label>
                    <input type="url"
                           name="tiktok"
                           id="tiktok"
                           value="{{ old('tiktok', $influencer->social_media->tiktok ?? '') }}"
                           placeholder="https://tiktok.com/@username"
                           class="input w-full">
                    @error('tiktok')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-shrink-0">
                    @if($influencer->social_media->tiktok ?? false)
                        <span class="badge badge-default">Connecté</span>
                    @else
                        <span class="badge badge-outline">Non connecté</span>
                    @endif
                </div>
            </div>

            <!-- Facebook -->
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="facebook" class="h-5 w-5 text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1">
                        Facebook
                    </label>
                    <input type="url"
                           name="facebook"
                           id="facebook"
                           value="{{ old('facebook', $influencer->social_media->facebook ?? '') }}"
                           placeholder="https://facebook.com/your-page"
                           class="input w-full">
                    @error('facebook')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-shrink-0">
                    @if($influencer->social_media->facebook ?? false)
                        <span class="badge badge-default">Connecté</span>
                    @else
                        <span class="badge badge-outline">Non connecté</span>
                    @endif
                </div>
            </div>

            <!-- Twitter/X -->
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                        <i data-lucide="twitter" class="h-5 w-5 text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="twitter" class="block text-sm font-medium text-gray-700 mb-1">
                        Twitter / X
                    </label>
                    <input type="url"
                           name="twitter"
                           id="twitter"
                           value="{{ old('twitter', $influencer->social_media->twitter ?? '') }}"
                           placeholder="https://twitter.com/username"
                           class="input w-full">
                    @error('twitter')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-shrink-0">
                    @if($influencer->social_media->twitter ?? false)
                        <span class="badge badge-default">Connecté</span>
                    @else
                        <span class="badge badge-outline">Non connecté</span>
                    @endif
                </div>
            </div>

            <!-- LinkedIn -->
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center">
                        <i data-lucide="linkedin" class="h-5 w-5 text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">
                        LinkedIn
                    </label>
                    <input type="url"
                           name="linkedin"
                           id="linkedin"
                           value="{{ old('linkedin', $influencer->social_media->linkedin ?? '') }}"
                           placeholder="https://linkedin.com/in/username"
                           class="input w-full">
                    @error('linkedin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-shrink-0">
                    @if($influencer->social_media->linkedin ?? false)
                        <span class="badge badge-default">Connecté</span>
                    @else
                        <span class="badge badge-outline">Non connecté</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Audience Statistics -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Statistiques d'audience</h3>
            <p class="card-description">Renseignez vos statistiques pour améliorer votre profil</p>
        </div>
        <div class="card-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Instagram Followers -->
                <div>
                    <label for="instagram_followers" class="block text-sm font-medium text-gray-700 mb-2">
                        Followers Instagram
                    </label>
                    <input type="number"
                           name="instagram_followers"
                           id="instagram_followers"
                           value="{{ old('instagram_followers', $influencer->social_stats->instagram_followers ?? '') }}"
                           placeholder="10000"
                           class="input w-full">
                    @error('instagram_followers')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- YouTube Subscribers -->
                <div>
                    <label for="youtube_subscribers" class="block text-sm font-medium text-gray-700 mb-2">
                        Abonnés YouTube
                    </label>
                    <input type="number"
                           name="youtube_subscribers"
                           id="youtube_subscribers"
                           value="{{ old('youtube_subscribers', $influencer->social_stats->youtube_subscribers ?? '') }}"
                           placeholder="5000"
                           class="input w-full">
                    @error('youtube_subscribers')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TikTok Followers -->
                <div>
                    <label for="tiktok_followers" class="block text-sm font-medium text-gray-700 mb-2">
                        Followers TikTok
                    </label>
                    <input type="number"
                           name="tiktok_followers"
                           id="tiktok_followers"
                           value="{{ old('tiktok_followers', $influencer->social_stats->tiktok_followers ?? '') }}"
                           placeholder="15000"
                           class="input w-full">
                    @error('tiktok_followers')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Facebook Followers -->
                <div>
                    <label for="facebook_followers" class="block text-sm font-medium text-gray-700 mb-2">
                        Followers Facebook
                    </label>
                    <input type="number"
                           name="facebook_followers"
                           id="facebook_followers"
                           value="{{ old('facebook_followers', $influencer->social_stats->facebook_followers ?? '') }}"
                           placeholder="8000"
                           class="input w-full">
                    @error('facebook_followers')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end">
        <button type="submit" class="btn btn-primary btn-default">
            <i data-lucide="save" class="mr-2 h-4 w-4"></i>
            Sauvegarder les réseaux sociaux
        </button>
    </div>
</form>