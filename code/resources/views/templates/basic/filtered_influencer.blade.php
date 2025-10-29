@php
$emptyMsgImage = getContent('empty_message.content', true);
// Favorites are already loaded in the controller as is_favorite attribute
$influencersId = $influencers->pluck('id')->filter(function($id) use ($influencers) {
    $influencer = $influencers->firstWhere('id', $id);
    return $influencer && ($influencer->is_favorite ?? false);
})->toArray();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    @forelse ($influencers as $influencer)
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 p-6 relative" style="border: 1px solid #e5e7eb;">
        <!-- Favorite Button -->
        @auth
        <div class="absolute top-4 right-4 z-10">
            @if (in_array($influencer->id, @$influencersId))
            <button class="favoriteBtn active text-gray-400 hover:text-purple-600 transition-colors"
                    data-influencer_id="{{ $influencer->id }}">
                <i data-lucide="heart" class="h-5 w-5 fill-current"></i>
            </button>
            @else
            <button class="favoriteBtn text-gray-400 hover:text-purple-600 transition-colors"
                    data-influencer_id="{{ $influencer->id }}">
                <i data-lucide="heart" class="h-5 w-5"></i>
            </button>
            @endif
        </div>
        @endauth

        <!-- Profile Image -->
        <div class="flex justify-center mb-4 mt-2">
            <div class="relative">
                <div class="w-24 h-24 rounded-full overflow-hidden" style="border: 2px solid #f3f4f6;">
                    @if(isset($influencer->image))
                    <img src="{{ getImage(getFilePath('influencerProfile') . '/' . $influencer->image, getFileSize('influencerProfile'), true) }}"
                         alt="{{ $influencer->fullname }}"
                         class="w-full h-full object-cover">
                    @else
                    <img src="{{ asset('assets/user_profile.png')}}"
                         alt="{{ $influencer->fullname }}"
                         class="w-full h-full object-cover">
                    @endif
                </div>
                <!-- Online Status -->
                @if ($influencer->isOnline())
                <div class="absolute bottom-1 right-1">
                    <div class="w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                @endif
            </div>
        </div>

        <!-- Name and Profession -->
        <div class="text-center mb-3">
            <h3 class="text-lg font-bold text-gray-900 mb-1 truncate" style="color: #1f2937;">
                {{ __($influencer->fullname) }}
            </h3>
            <p class="text-sm text-gray-600 truncate" style="color: #6b7280;">
                {{ __($influencer->profession) }}
            </p>
        </div>

        <!-- Rating -->
        <div class="flex items-center justify-center mb-4">
            <span class="text-base font-bold text-gray-900 mr-1" style="color: #1f2937;">{{ number_format($influencer->rating, 1) }}</span>
            <span class="text-sm text-gray-600" style="color: #6b7280;">({{ getAmount($influencer->total_review) ?? 0 }})</span>
        </div>

        <!-- Social Icons -->
        <div class="flex justify-center gap-3 mb-5" style="min-height: 32px;">
            @if($influencer->socialLink && $influencer->socialLink->count() > 0)
                @foreach($influencer->socialLink->take(4) as $social)
                <a href="{{ $social->url }}" target="_blank" class="text-gray-600 hover:text-purple-600 transition-colors text-xl">
                    @php echo $social->social_icon @endphp
                </a>
                @endforeach
            @endif
        </div>

        <!-- View Profile Button -->
        <a href="{{ localized_route('influencer.profile', $influencer->id) }}"
           class="w-full inline-flex items-center justify-center px-6 py-3 text-white text-base font-semibold transition-all duration-200"
           style="background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%); border-radius: 12px; box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);">
            @lang('influencers.view_profile')
        </a>
    </div>

    @empty
    <!-- Empty State -->
    <div class="col-span-full">
        <div class="text-center py-16">
            <div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg border-2 border-dashed border-gray-300 p-12">
                @if(@$emptyMsgImage->data_values->image)
                <img src="{{ getImage('assets/images/frontend/empty_message/' . @$emptyMsgImage->data_values->image, '400x300') }}"
                     alt="@lang('لا توجد نتائج')"
                     class="mx-auto mb-6 w-64 h-48 object-contain opacity-75">
                @else
                <div class="mx-auto mb-6 w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                    <i data-lucide="users-x" class="h-16 w-16 text-blue-600"></i>
                </div>
                @endif
                <h3 class="text-2xl font-bold text-gray-900 mb-3">@lang('influencers.no_influencers_found')</h3>
                <p class="text-gray-600 mb-8 text-lg">@lang('influencers.try_changing_criteria')</p>
                <button class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
                        onclick="window.location.reload()">
                    <i data-lucide="refresh-cw" class="h-5 w-5 ml-2"></i>
                    @lang('influencers.reload')
                </button>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Modern Pagination -->
@if($influencers && $influencers->hasPages())
<div class="mt-8 flex justify-center">
    <div class="bg-white rounded-xl shadow-md border border-gray-200 px-6 py-3">
        @include('partials.compact-pagination', ['paginator' => $influencers])
    </div>
</div>
@endif

<script>
    // Campaign creation functionality
    function startCampaign(influencerId, influencerName) {
        // Check if user is authenticated
        @auth
            // Show campaign creation modal or redirect to campaign page
            if (confirm(`@lang('influencers.want_to_create_campaign_with') ${influencerName}?`)) {
                // Option 1: Redirect to campaign creation with pre-selected influencer
                window.location.href = `{{ localized_route('user_campaign') }}?influencer_id=${influencerId}&influencer_name=${encodeURIComponent(influencerName)}`;

                // Option 2: Open modal (if you prefer modal approach)
                // openCampaignModal(influencerId, influencerName);
            }
        @else
            // Redirect to login if not authenticated
            if (confirm('@lang('influencers.login_required_for_campaign')')) {
                window.location.href = '{{ localized_route("user.login") }}';
            }
        @endauth
    }

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

                    // Make AJAX request
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
                                this.className = 'favoriteBtn p-2.5 rounded-full bg-white/95 text-gray-600 hover:bg-white hover:text-red-600 shadow-lg transition-all duration-200 hover:scale-110';
                                this.innerHTML = '<i data-lucide="heart" class="h-5 w-5"></i>';
                            } else {
                                // Add to favorites
                                this.className = 'favoriteBtn active p-2.5 rounded-full bg-white/95 text-red-600 hover:bg-white shadow-lg transition-all duration-200 hover:scale-110';
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
                        lucide.createIcons();
                    });
                @else
                    if (confirm('@lang('influencers.login_required_for_favorites')')) {
                        window.location.href = '{{ localized_route("user.login") }}';
                    }
                @endauth
            });
        });

        // Initialize tooltips if Bootstrap is available
        try {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        } catch (error) {
            // Bootstrap not available
        }
    });
</script>
