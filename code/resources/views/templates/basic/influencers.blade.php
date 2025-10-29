@extends('layouts.dashboard')
@section('content')
    {{-- Updated: Modern Design v2.0 - Dashboard Layout --}}

    <!-- Compact Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Title and Subtitle in one line for larger screens -->
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">@lang('influencers.discover_best_influencers')
                    </h1>
                    <p class="text-sm sm:text-base text-purple-100 mt-1">@lang('influencers.find_perfect_influencers')</p>
                </div>

                <!-- Enhanced Search Bar -->
                <div class="w-full sm:w-auto sm:max-w-lg">
                    <div class="flex gap-3">
                        <div class="flex-1 relative group">
                            <input type="text" id="searchinput" name="searchinput"
                                class="mySearch w-full px-12 py-3 rounded-xl border-2 border-white/30 bg-white/95 backdrop-blur-sm text-gray-900 placeholder-gray-500 focus:ring-4 focus:ring-pink-300/50 focus:border-white shadow-lg transition-all duration-200"
                                placeholder="@lang('influencers.search_influencers')" value="{{ request()->search }}">
                            <button
                                class="absolute right-4 rtl:right-auto rtl:left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-purple-600 searchBtn transition-colors duration-200"
                                type="button">
                                <i data-lucide="search" class="h-5 w-5"></i>
                            </button>
                        </div>
                        <button
                            class="px-5 py-3 bg-white text-purple-600 font-semibold rounded-xl hover:bg-purple-50 hover:shadow-lg transition-all duration-200 filter-btn shadow-md"
                            onclick="toggleFilterModal()">
                            <i data-lucide="filter" class="h-5 w-5 inline-block ml-1 rtl:ml-0 rtl:mr-1"></i>
                            <span class="hidden sm:inline">@lang('influencers.filter')</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <!-- Mobile Filter Button -->
            <div class="lg:hidden mb-4">
                <button
                    class="w-full flex items-center justify-center px-4 py-3 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-700 hover:bg-purple-50 hover:border-pink-300 hover:text-purple-600 transition-all duration-200"
                    onclick="toggleFilterModal()">
                    <i data-lucide="sliders-horizontal" class="h-5 w-5 ml-2"></i>
                    @lang('influencers.show_filters')
                </button>
            </div>

            <!-- Search Results Info -->
            @if (request()->search)
                <div class="mb-8 p-6 bg-gradient-to-r from-purple-50 to-purple-50 border border-pink-200 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <i data-lucide="search" class="h-5 w-5 text-purple-600 mr-3"></i>
                        <p class="text-purple-800 text-lg">
                            @lang('influencers.search_results_for') <span
                                class="font-bold bg-purple-100 px-2 py-1 rounded-lg">"{{ request()->search }}"</span>
                        </p>
                    </div>
                    <div class="mt-2 flex items-center text-purple-700">
                        <i data-lucide="users" class="h-4 w-4 mr-2"></i>
                        <span>@lang('influencers.found') <span
                                class="font-bold text-pink-900">{{ $influencers->total() ?? 0 }}</span>
                            @lang('influencers.influencer')</span>
                    </div>
                </div>
            @endif

            <!-- Loading Indicator -->
            <div class="loader-wrapper hidden">
                <div class="flex flex-col justify-center items-center py-16">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-pink-200 border-t-purple-600 shadow-lg">
                    </div>
                    <p class="mt-4 text-gray-600 font-medium">@lang('influencers.searching_influencers')</p>
                </div>
            </div>

            <!-- Influencers Grid -->
            <div id="influencers">
                @include($activeTemplate . 'filtered_influencer')
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleFilterModal()"></div>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">@lang('influencers.filter_influencers')</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleFilterModal()">
                            <i data-lucide="x" class="h-6 w-6"></i>
                        </button>
                    </div>

                    <div class="space-y-6 max-h-[60vh] overflow-y-auto pr-2">
                        <!-- Social Media Platforms -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Social Media Platforms')</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input id="social_instagram" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded filterSocial" value="instagram">
                                    <label for="social_instagram" class="mr-3 text-sm text-gray-700">Instagram</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="social_tiktok" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded filterSocial" value="tiktok">
                                    <label for="social_tiktok" class="mr-3 text-sm text-gray-700">TikTok</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="social_youtube" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded filterSocial" value="youtube">
                                    <label for="social_youtube" class="mr-3 text-sm text-gray-700">YouTube</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="social_facebook" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded filterSocial" value="facebook">
                                    <label for="social_facebook" class="mr-3 text-sm text-gray-700">Facebook</label>
                                </div>
                            </div>
                        </div>

                        <!-- Followers Range -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Followers Range')</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-600">@lang('Min')</label>
                                    <input type="number" id="followers_min" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="0">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600">@lang('Max')</label>
                                    <input type="number" id="followers_max" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="1000000">
                                </div>
                            </div>
                        </div>

                        <!-- Categories Filter -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('influencers.categories')</h4>
                            <div class="space-y-2 max-h-32 overflow-y-auto">
                                @if($allCategory ?? false)
                                    @foreach($allCategory as $category)
                                        <div class="flex items-center">
                                            <input id="cat_{{ $category->id }}" type="checkbox"
                                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded filterCategory"
                                                value="{{ $category->id }}">
                                            <label for="cat_{{ $category->id }}"
                                                class="mr-3 text-sm text-gray-700">{{ __($category->name) }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Wilaya (Location) -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Location (Wilaya)')</h4>
                            <select id="filter_wilaya" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="">@lang('All Locations')</option>
                                @if($wilayas ?? false)
                                    @foreach($wilayas as $wilaya)
                                        <option value="{{ $wilaya->id }}">{{ __($wilaya->name) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Gender Filter -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Gender')</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input id="gender_all" type="radio" name="gender" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterGender" value="" checked>
                                    <label for="gender_all" class="mr-3 text-sm text-gray-700">@lang('All')</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="gender_male" type="radio" name="gender" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterGender" value="male">
                                    <label for="gender_male" class="mr-3 text-sm text-gray-700">@lang('Male')</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="gender_female" type="radio" name="gender" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterGender" value="female">
                                    <label for="gender_female" class="mr-3 text-sm text-gray-700">@lang('Female')</label>
                                </div>
                            </div>
                        </div>

                        <!-- Age Range -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Age Range')</h4>
                            <select id="filter_age" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="">@lang('All Ages')</option>
                                <option value="18-24">18-24</option>
                                <option value="25-34">25-34</option>
                                <option value="35-44">35-44</option>
                                <option value="45+">45+</option>
                            </select>
                        </div>

                        <!-- Language -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Language')</h4>
                            <select id="filter_lang" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="">@lang('All Languages')</option>
                                <option value="ar">@lang('Arabic')</option>
                                <option value="fr">@lang('French')</option>
                                <option value="en">@lang('English')</option>
                            </select>
                        </div>

                        <!-- Audience Gender -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Audience Gender')</h4>
                            <select id="filter_gender_audience" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="">@lang('All')</option>
                                <option value="male">@lang('Mostly Male')</option>
                                <option value="female">@lang('Mostly Female')</option>
                            </select>
                        </div>

                        <!-- Audience Age -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Audience Age')</h4>
                            <select id="filter_audience_age" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="">@lang('All Ages')</option>
                                <option value="18-24">18-24</option>
                                <option value="25-34">25-34</option>
                                <option value="35-44">35-44</option>
                                <option value="45+">45+</option>
                            </select>
                        </div>

                        <!-- Audience Location -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Audience Location')</h4>
                            <select id="filter_wilaya_audience" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="">@lang('All Locations')</option>
                                @if($wilayas ?? false)
                                    @foreach($wilayas as $wilaya)
                                        <option value="{{ $wilaya->id }}">{{ __($wilaya->name) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Average Interactions -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Avg Interactions')</h4>
                            <input type="number" id="average_interactions" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="0">
                        </div>

                        <!-- Rating Filter -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('influencers.rating')</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input id="rating_5" type="radio" name="rating"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterRating"
                                        value="5">
                                    <label for="rating_5" class="mr-3 text-sm text-gray-700 flex items-center">
                                        <span class="text-yellow-400">★★★★★</span>
                                        <span class="mr-2">5 @lang('influencers.stars')</span>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="rating_4" type="radio" name="rating"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterRating"
                                        value="4">
                                    <label for="rating_4" class="mr-3 text-sm text-gray-700 flex items-center">
                                        <span class="text-yellow-400">★★★★☆</span>
                                        <span class="mr-2">4+ @lang('influencers.stars')</span>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="rating_3" type="radio" name="rating"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterRating"
                                        value="3">
                                    <label for="rating_3" class="mr-3 text-sm text-gray-700 flex items-center">
                                        <span class="text-yellow-400">★★★☆☆</span>
                                        <span class="mr-2">3+ @lang('influencers.stars')</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Jobs -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Min Completed Jobs')</h4>
                            <input type="number" id="completed_job" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="0">
                        </div>

                        <!-- Sort Options -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('influencers.sort_by')</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input id="sort_latest" type="radio" name="sort"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterSort"
                                        value="latest" checked>
                                    <label for="sort_latest"
                                        class="mr-3 text-sm text-gray-700">@lang('influencers.latest')</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="sort_rating" type="radio" name="sort"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterSort"
                                        value="rating">
                                    <label for="sort_rating"
                                        class="mr-3 text-sm text-gray-700">@lang('influencers.highest_rated')</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="sort_popular" type="radio" name="sort"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 filterSort"
                                        value="popular">
                                    <label for="sort_popular"
                                        class="mr-3 text-sm text-gray-700">@lang('influencers.most_popular')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="applyFilters()">
                        @lang('influencers.apply_filters')
                    </button>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:w-auto sm:text-sm"
                        onclick="clearFilters()">
                        @lang('influencers.clear_filters')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections -->
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

@push('style')
    <style>
        /* Enhanced influencer card animations */
        .influencer-card {
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            transition: all 0.3s ease;
        }

        .influencer-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .influencer-card:hover::before {
            opacity: 1;
        }

        .influencer-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Smooth image loading */
        .influencer-card img {
            transition: transform 0.3s ease;
        }

        .influencer-card:hover img {
            transform: scale(1.05);
        }

        /* Enhanced button effects */
        .group\/btn {
            transition: all 0.3s ease;
        }

        .group\/btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }

        /* Loading animation enhancement */
        .loader-wrapper .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Custom scrollbar for modal */
        #filterModal .space-y-2.max-h-40 {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        #filterModal .space-y-2.max-h-40::-webkit-scrollbar {
            width: 6px;
        }

        #filterModal .space-y-2.max-h-40::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        #filterModal .space-y-2.max-h-40::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        /* Improved base styles for better performance */
        .filter-modal {
            max-width: 700px;
            max-height: 100vh;
            overflow-y: auto;
        }

        .filter {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 50;
        }

        /* Custom scrollbar for better UX */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem !important;
            }

            .hero-section p {
                font-size: 1rem !important;
            }

            .hero-section {
                padding: 3rem 0 !important;
            }

            .influencer-card {
                margin-bottom: 1rem;
            }

            .grid.grid-cols-1 {
                gap: 1rem;
            }
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            let page = null;

            // Search functionality
            $('.searchBtn').on('click', function () {
                fetchInfluencer();
            });

            // Search on Enter key
            $('.mySearch').on('keypress', function (e) {
                if (e.which == 13) {
                    fetchInfluencer();
                }
            });

            // Pagination
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                page = $(this).attr('href').split('page=')[1];
                fetchInfluencer();
            });

            function fetchInfluencer() {
                $('.loader-wrapper').removeClass('hidden');

                let data = {};

                // Social Media Platforms
                data.social = [];
                $('.filterSocial:checked').each(function () {
                    data.social.push($(this).val());
                });

                // Followers Range
                data.followers_min = $('#followers_min').val() || "";
                data.followers_max = $('#followers_max').val() || "";

                // Categories
                data.category = [];
                $('.filterCategory:checked').each(function () {
                    data.category.push($(this).val());
                });

                // Location (Wilaya)
                data.wilaya = $('#filter_wilaya').val() || "";

                // Gender
                data.gender = $('.filterGender:checked').val() || "";

                // Age Range
                data.age = $('#filter_age').val() || "";

                // Language
                data.lang = $('#filter_lang').val() || "";

                // Audience Gender
                data.gender_audience = $('#filter_gender_audience').val() || "";

                // Audience Age
                data.audience_age = $('#filter_audience_age').val() || "";

                // Audience Location
                data.wilaya_audience = $('#filter_wilaya_audience').val() || "";

                // Average Interactions
                data.average_interactions = $('#average_interactions').val() || "";

                // Rating
                data.rating = $('.filterRating:checked').val() || "";

                // Completed Jobs
                data.completedJob = $('#completed_job').val() || "";

                // Search
                data.search = $('.mySearch').val() || "";

                // Sort
                data.sort = $('.filterSort:checked').val() || "";

                // Category ID (if needed)
                data.categoryId = "";

                let url = `{{ localized_route('influencer.filter') }}`;

                if (page) {
                    url = `{{ localized_route('influencer.filter') }}?page=${page}`;
                }

                $.ajax({
                    method: "GET",
                    url: url,
                    data: data,
                    success: function (response) {
                        $('#influencers').html(response);
                        // Re-initialize Lucide icons for new elements
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching influencers:', error);
                        $('.loader-wrapper').addClass('hidden');
                    }
                }).done(function () {
                    $('.loader-wrapper').addClass('hidden');
                });
            }

        })(jQuery);

        // Modal functionality
        function toggleFilterModal() {
            const modal = document.getElementById('filterModal');
            modal.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        function applyFilters() {
            toggleFilterModal();
            $('.loader-wrapper').removeClass('hidden');

            let data = {};

            // Social Media Platforms
            data.social = [];
            $('.filterSocial:checked').each(function () {
                data.social.push($(this).val());
            });

            // Followers Range
            data.followers_min = $('#followers_min').val() || "";
            data.followers_max = $('#followers_max').val() || "";

            // Categories
            data.category = [];
            $('.filterCategory:checked').each(function () {
                data.category.push($(this).val());
            });

            // Location (Wilaya)
            data.wilaya = $('#filter_wilaya').val() || "";

            // Gender
            data.gender = $('.filterGender:checked').val() || "";

            // Age Range
            data.age = $('#filter_age').val() || "";

            // Language
            data.lang = $('#filter_lang').val() || "";

            // Audience Gender
            data.gender_audience = $('#filter_gender_audience').val() || "";

            // Audience Age
            data.audience_age = $('#filter_audience_age').val() || "";

            // Audience Location
            data.wilaya_audience = $('#filter_wilaya_audience').val() || "";

            // Average Interactions
            data.average_interactions = $('#average_interactions').val() || "";

            // Rating
            data.rating = $('.filterRating:checked').val() || "";

            // Completed Jobs
            data.completedJob = $('#completed_job').val() || "";

            // Search
            data.search = $('.mySearch').val() || "";

            // Sort
            data.sort = $('.filterSort:checked').val() || "";

            // Category ID (if needed)
            data.categoryId = "";

            let url = `{{ localized_route('influencer.filter') }}`;

            if (page) {
                url = `{{ localized_route('influencer.filter') }}?page=${page}`;
            }

            $.ajax({
                method: "GET",
                url: url,
                data: data,
                success: function (response) {
                    $('#influencers').html(response);
                    // Re-initialize Lucide icons for new elements
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching influencers:', error);
                    $('.loader-wrapper').addClass('hidden');
                }
            }).done(function () {
                $('.loader-wrapper').addClass('hidden');
            });
        }

        function clearFilters() {
            // Clear all checkboxes
            document.querySelectorAll('.filterCategory').forEach(cb => cb.checked = false);
            document.querySelectorAll('.filterSocial').forEach(cb => cb.checked = false);
            document.querySelectorAll('.filterRating').forEach(rb => rb.checked = false);
            document.querySelectorAll('.filterGender').forEach(rb => rb.checked = false);

            // Reset text/number inputs
            document.getElementById('followers_min').value = '';
            document.getElementById('followers_max').value = '';
            document.getElementById('average_interactions').value = '';
            document.getElementById('completed_job').value = '';

            // Reset select dropdowns
            document.getElementById('filter_wilaya').value = '';
            document.getElementById('filter_age').value = '';
            document.getElementById('filter_lang').value = '';
            document.getElementById('filter_gender_audience').value = '';
            document.getElementById('filter_audience_age').value = '';
            document.getElementById('filter_wilaya_audience').value = '';

            // Reset gender to "All"
            document.getElementById('gender_all').checked = true;

            // Reset sort to latest
            document.getElementById('sort_latest').checked = true;

            // Apply cleared filters
            applyFilters();
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('filterModal');
                if (!modal.classList.contains('hidden')) {
                    toggleFilterModal();
                }
            }
        });

        // Initialize Lucide icons when page loads
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Restore filter values from URL parameters
            const urlParams = new URLSearchParams(window.location.search);

            // Social Media
            if (urlParams.has('social')) {
                const socialArray = urlParams.getAll('social[]') || [urlParams.get('social')];
                socialArray.forEach(social => {
                    const checkbox = document.querySelector(`.filterSocial[value="${social}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }

            // Followers
            if (urlParams.has('followers_min')) {
                document.getElementById('followers_min').value = urlParams.get('followers_min');
            }
            if (urlParams.has('followers_max')) {
                document.getElementById('followers_max').value = urlParams.get('followers_max');
            }

            // Categories
            if (urlParams.has('category')) {
                const categoryArray = urlParams.getAll('category[]') || [urlParams.get('category')];
                categoryArray.forEach(cat => {
                    const checkbox = document.querySelector(`.filterCategory[value="${cat}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }

            // Wilaya
            if (urlParams.has('wilaya')) {
                document.getElementById('filter_wilaya').value = urlParams.get('wilaya');
            }

            // Gender
            if (urlParams.has('gender')) {
                const genderRadio = document.querySelector(`.filterGender[value="${urlParams.get('gender')}"]`);
                if (genderRadio) genderRadio.checked = true;
            }

            // Age
            if (urlParams.has('age')) {
                document.getElementById('filter_age').value = urlParams.get('age');
            }

            // Language
            if (urlParams.has('lang')) {
                document.getElementById('filter_lang').value = urlParams.get('lang');
            }

            // Audience Gender
            if (urlParams.has('gender_audience')) {
                document.getElementById('filter_gender_audience').value = urlParams.get('gender_audience');
            }

            // Audience Age
            if (urlParams.has('audience_age')) {
                document.getElementById('filter_audience_age').value = urlParams.get('audience_age');
            }

            // Audience Wilaya
            if (urlParams.has('wilaya_audience')) {
                document.getElementById('filter_wilaya_audience').value = urlParams.get('wilaya_audience');
            }

            // Average Interactions
            if (urlParams.has('average_interactions')) {
                document.getElementById('average_interactions').value = urlParams.get('average_interactions');
            }

            // Rating
            if (urlParams.has('rating')) {
                const ratingRadio = document.getElementById(`rating_${urlParams.get('rating')}`);
                if (ratingRadio) ratingRadio.checked = true;
            }

            // Completed Jobs
            if (urlParams.has('completedJob')) {
                document.getElementById('completed_job').value = urlParams.get('completedJob');
            }

            // Sort
            if (urlParams.has('sort')) {
                const sortRadio = document.getElementById(`sort_${urlParams.get('sort')}`);
                if (sortRadio) sortRadio.checked = true;
            }
        });
    </script>
@endpush
