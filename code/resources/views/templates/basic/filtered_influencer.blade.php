@php
$emptyMsgImage = getContent('empty_message.content', true);
// Favorites are already loaded in the controller as is_favorite attribute
$influencersId = $influencers->pluck('id')->filter(function($id) use ($influencers) {
    $influencer = $influencers->firstWhere('id', $id);
    return $influencer && ($influencer->is_favorite ?? false);
})->toArray();
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse ($influencers as $influencer)
    <!-- Clean Modern Card -->
    <div class="influencer-card group bg-white rounded-xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-200 hover:border-purple-300 overflow-hidden">

        <!-- Header Section -->
        <div class="relative p-6 bg-gradient-to-br from-gray-50 to-white">
            <!-- Favorite Button - Top Right -->
            @auth
            <div class="absolute top-4 left-4 z-10">
                @if (in_array($influencer->id, @$influencersId))
                <button class="favoriteBtn active p-2 rounded-lg bg-white border-2 border-red-200 text-red-500 hover:bg-red-50 hover:border-red-300 transition-all duration-200"
                        data-influencer_id="{{ $influencer->id }}" title="@lang('Remove from favorites')">
                    <i data-lucide="heart" class="h-4 w-4 fill-current"></i>
                </button>
                @else
                <button class="favoriteBtn p-2 rounded-lg bg-white border-2 border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-300 hover:bg-red-50 transition-all duration-200"
                        data-influencer_id="{{ $influencer->id }}" title="@lang('Add to favorites')">
                    <i data-lucide="heart" class="h-4 w-4"></i>
                </button>
                @endif
            </div>
            @endauth

            <!-- Profile Image - Centered -->
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full overflow-hidden ring-4 ring-white shadow-md">
                        @if(isset($influencer->image))
                        <img src="{{ getImage(getFilePath('influencerProfile') . '/' . $influencer->image, getFileSize('influencerProfile'), true) }}"
                             alt="{{ $influencer->fullname }}"
                             class="w-full h-full object-cover">
                        @else
                        <img src="{{ asset('assets/user_profile.png')}}"
                             alt="{{ $influencer->fullname }}"
                             class="w-full h-full object-cover bg-gray-100">
                        @endif
                    </div>
                    <!-- Online Status -->
                    @if ($influencer->isOnline())
                    <div class="absolute bottom-0 right-0">
                        <span class="flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-green-500 ring-2 ring-white"></span>
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Name & Profession -->
            <div class="text-center">
                <h3 class="text-base font-bold text-gray-900 mb-1 truncate" title="{{ $influencer->fullname }}">
                    {{ __($influencer->fullname) }}
                </h3>
                <p class="text-xs text-gray-500 truncate" title="{{ $influencer->profession }}">
                    {{ __($influencer->profession) }}
                </p>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="px-6 py-4 bg-white border-t border-b border-gray-100">
            <div class="grid grid-cols-2 gap-3">
                <!-- Rating Stat -->
                <div class="flex flex-col items-center p-3 bg-amber-50 rounded-lg">
                    <div class="flex items-center gap-1 mb-1">
                        <i data-lucide="star" class="h-4 w-4 text-amber-500 fill-current"></i>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($influencer->rating ?? 0, 1) }}</span>
                    </div>
                    @php
                        // Get review count - handle all possible cases
                        $reviewCount = 0;
                        if (isset($influencer->reviews_count) && is_numeric($influencer->reviews_count)) {
                            $reviewCount = $influencer->reviews_count;
                        } elseif (isset($influencer->reviews) && is_object($influencer->reviews) && method_exists($influencer->reviews, 'count')) {
                            $reviewCount = $influencer->reviews->count();
                        } elseif (isset($influencer->reviews) && is_countable($influencer->reviews)) {
                            $reviewCount = count($influencer->reviews);
                        }
                    @endphp
                    <span class="text-xs text-gray-600">{{ $reviewCount }} {{ __('influencers.reviews') }}</span>
                </div>

                <!-- Jobs Stat -->
                <div class="flex flex-col items-center p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center gap-1 mb-1">
                        <i data-lucide="briefcase" class="h-4 w-4 text-green-600"></i>
                        <span class="text-sm font-bold text-gray-900">{{ $influencer->completed_order ?? 0 }}</span>
                    </div>
                    <span class="text-xs text-gray-600">@lang('completed')</span>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        @if($influencer->categories && $influencer->categories->count() > 0)
        <div class="px-6 py-3 bg-white border-b border-gray-100">
            <div class="flex flex-wrap gap-1.5 justify-center">
                @foreach($influencer->categories->take(2) as $category)
                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                    {{ __($category->name) }}
                </span>
                @endforeach
                @if($influencer->categories->count() > 2)
                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                    +{{ $influencer->categories->count() - 2 }}
                </span>
                @endif
            </div>
        </div>
        @endif

        <!-- Social Media Section -->
        @if($influencer->socialLink && $influencer->socialLink->count() > 0)
        <div class="px-6 py-3 bg-gray-50">
            <div class="flex items-center justify-center gap-2">
                @foreach($influencer->socialLink->take(4) as $social)
                <a href="{{ $social->url }}" target="_blank"
                   class="p-2 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-purple-600 hover:border-purple-300 hover:shadow-sm transition-all duration-200"
                   title="{{ $social->followers }} followers">
                    @php echo $social->social_icon @endphp
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Action Button -->
        <div class="p-4 bg-white">
            <a href="{{ localized_route('influencer.profile', $influencer->id) }}"
               class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md group-hover:scale-105">
                <span class="flex items-center justify-center gap-2">
                    @lang('influencers.view_profile')
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </span>
            </a>
        </div>
    </div>

    @empty
    <!-- Empty State -->
    <div class="col-span-full">
        <div class="text-center py-20">
            <div class="max-w-md mx-auto">
                @if(@$emptyMsgImage->data_values->image)
                <img src="{{ getImage('assets/images/frontend/empty_message/' . @$emptyMsgImage->data_values->image, '400x300') }}"
                     alt="@lang('No results')"
                     class="mx-auto mb-6 w-64 h-48 object-contain opacity-75">
                @else
                <div class="mx-auto mb-6 w-32 h-32 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full flex items-center justify-center">
                    <i data-lucide="users-x" class="h-16 w-16 text-purple-600"></i>
                </div>
                @endif

                <h3 class="text-2xl font-bold text-gray-900 mb-3">
                    @lang('influencers.no_influencers_found')
                </h3>
                <p class="text-gray-600 mb-8 text-base">
                    @lang('influencers.try_changing_criteria')
                </p>

                <button class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        onclick="clearFilters()">
                    <i data-lucide="refresh-cw" class="h-5 w-5"></i>
                    @lang('influencers.clear_filters')
                </button>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Modern Pagination -->
@if($influencers && $influencers->hasPages())
<div class="mt-8 flex justify-center">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-3">
        {{-- Append current query parameters to pagination links --}}
        {{ $influencers->appends(request()->query())->links('partials.compact-pagination') }}
    </div>
</div>
@endif

<script>
    // Enhanced favorite functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Favorite button functionality
        document.querySelectorAll('.favoriteBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                @auth
                    const influencerId = this.dataset.influencer_id;
                    const isActive = this.classList.contains('active');

                    // Add loading state
                    this.disabled = true;
                    this.innerHTML = '<i data-lucide="loader-2" class="h-5 w-5 animate-spin"></i>';

                    // Make fetch request
                    fetch('{{ localized_route("user.favorite.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            influencer_id: influencerId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Toggle favorite state
                            this.classList.toggle('active');

                            if (isActive) {
                                // Remove from favorites
                                this.className = 'favoriteBtn p-2 rounded-full bg-white/90 backdrop-blur-sm text-gray-400 hover:text-red-500 hover:bg-white hover:scale-110 transition-all duration-200 shadow-lg';
                                this.innerHTML = '<i data-lucide="heart" class="h-5 w-5"></i>';
                            } else {
                                // Add to favorites
                                this.className = 'favoriteBtn active p-2 rounded-full bg-white/90 backdrop-blur-sm text-red-500 hover:bg-white hover:scale-110 transition-all duration-200 shadow-lg';
                                this.innerHTML = '<i data-lucide="heart" class="h-5 w-5 fill-current"></i>';
                            }

                            // Reinitialize Lucide icons
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        }

                        this.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.disabled = false;
                        // Restore original state
                        if (isActive) {
                            this.innerHTML = '<i data-lucide="heart" class="h-5 w-5 fill-current"></i>';
                        } else {
                            this.innerHTML = '<i data-lucide="heart" class="h-5 w-5"></i>';
                        }
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                @else
                    if (confirm('@lang('influencers.login_required_for_favorites')')) {
                        window.location.href = '{{ localized_route("user.login") }}';
                    }
                @endauth
            });
        });

        // Reinitialize Lucide icons for dynamically loaded content
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
