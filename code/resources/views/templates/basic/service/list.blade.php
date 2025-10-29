@extends('layouts.dashboard')
@section('content')
    <!-- Compact Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">@lang('services.browse_services')</h1>
                    <p class="text-sm sm:text-base text-purple-100 mt-1">@lang('Find the perfect services for your needs')</p>
                </div>

                <!-- Enhanced Search Bar -->
                <div class="w-full sm:w-auto sm:max-w-lg">
                    <div class="flex gap-3">
                        <div class="flex-1 relative group">
                            <input type="text" id="searchinput" name="searchinput"
                                class="mySearch w-full px-12 py-3 rounded-xl border-2 border-white/30 bg-white/95 backdrop-blur-sm text-gray-900 placeholder-gray-500 focus:ring-4 focus:ring-pink-300/50 focus:border-white shadow-lg transition-all duration-200"
                                placeholder="@lang('services.search_services')" value="{{ request()->search }}">
                            <button
                                class="absolute right-4 rtl:right-auto rtl:left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-purple-600 searchBtn transition-colors duration-200"
                                type="button">
                                <i data-lucide="search" class="h-5 w-5"></i>
                            </button>
                        </div>
                        <button
                            class="px-5 py-3 bg-white text-purple-600 font-semibold rounded-xl hover:bg-purple-50 hover:shadow-lg transition-all duration-200 filter-btn shadow-md"
                            x-data @click="$dispatch('open-filter-modal')">
                            <i data-lucide="filter" class="h-5 w-5 inline-block ml-1 rtl:ml-0 rtl:mr-1"></i>
                            <span class="hidden sm:inline">@lang('common.filter')</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <!-- Mobile Filter Button -->
            <div class="lg:hidden mb-4">
                <button
                    class="w-full flex items-center justify-center px-4 py-3 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-700 hover:bg-purple-50 hover:border-pink-300 hover:text-purple-600 transition-all duration-200"
                    x-data @click="$dispatch('open-filter-modal')">
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
                            @lang('services.search_results_for') <span
                                class="font-bold bg-purple-100 px-2 py-1 rounded-lg">"{{ request()->search }}"</span>
                        </p>
                    </div>
                    <div class="mt-2 flex items-center text-purple-700">
                        <i data-lucide="briefcase" class="h-4 w-4 mr-2"></i>
                        <span>@lang('services.found') <span
                                class="font-bold text-pink-900">{{ $services->total() ?? 0 }}</span>
                            @lang('services.services')</span>
                    </div>
                </div>
            @endif

            <!-- Loading Indicator -->
            <div class="loader-wrapper hidden">
                <div class="flex flex-col justify-center items-center py-16">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-pink-200 border-t-purple-600 shadow-lg">
                    </div>
                    <p class="mt-4 text-gray-600 font-medium">@lang('common.loading_please_wait')</p>
                </div>
            </div>

            <!-- Grille des services -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="services">
                @include($activeTemplate . 'service.filtered')
            </div>
        </div>
    </div>

    <!-- Modal de filtres -->
    <div x-data="{ open: false }"
         @open-filter-modal.window="open = true"
         x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">

        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="w-full max-w-lg bg-white rounded-xl shadow-xl">

                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i data-lucide="sliders-horizontal" class="h-5 w-5 mr-2 text-purple-600"></i>
                        @lang('influencers.filter_influencers')
                    </h3>
                    <button @click="open = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">

                    <!-- Categories -->
                    @if (@$allCategory)
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('services.categories')</h4>
                        <div class="space-y-2 max-h-32 overflow-y-auto">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded sortCategory"
                                       type="checkbox"
                                       name="category"
                                       value=""
                                       id="modal_category0"
                                       checked>
                                <label class="mr-3 text-sm font-medium text-gray-700" for="modal_category0">@lang('services.all_categories')</label>
                            </div>
                            @foreach ($allCategory as $category)
                                <div class="flex items-center">
                                    <input class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded sortCategory"
                                           type="checkbox"
                                           name="category"
                                           value="{{ $category->id }}"
                                           id="modal_category{{ $category->id }}">
                                    <label class="mr-3 text-sm text-gray-600 hover:text-gray-900 cursor-pointer" for="modal_category{{ $category->id }}">{{ __($category->name) }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Sort By -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('influencers.sort_by')</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 sortService"
                                       type="radio"
                                       value="id_desc"
                                       name="sort"
                                       id="modal_service1"
                                       checked>
                                <label class="mr-3 text-sm text-gray-700" for="modal_service1">
                                    @lang('influencers.latest')
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 sortService"
                                       type="radio"
                                       value="price_asc"
                                       name="sort"
                                       id="modal_service2">
                                <label class="mr-3 text-sm text-gray-700" for="modal_service2">
                                    @lang('Price: Low to High')
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 sortService"
                                       type="radio"
                                       value="price_desc"
                                       name="sort"
                                       id="modal_service3">
                                <label class="mr-3 text-sm text-gray-700" for="modal_service3">
                                    @lang('Price: High to Low')
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">@lang('Price Range')</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-gray-600">@lang('Min')</label>
                                <input type="number"
                                       name="min"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600">@lang('Max')</label>
                                <input type="number"
                                       name="max"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500"
                                       placeholder="10000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            onclick="applyFilters()"
                            @click="open = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                        @lang('influencers.apply_filters')
                    </button>
                    <button type="button"
                            onclick="clearAllFilters()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:w-auto sm:text-sm">
                        @lang('influencers.clear_filters')
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
@endpush

@push('script')
<script>
(function($) {
    "use strict";
    let page = null;
    $('.loader-wrapper').addClass('hidden');

    // Fonction pour mettre à jour les chips de filtres
    function updateFilterChips() {
        const chipsContainer = $('#filter-chips');
        const activeFiltersContainer = $('#active-filters');
        chipsContainer.empty();

        let hasActiveFilters = false;

        // Catégories sélectionnées
        $("[name=category]:checked").each(function() {
            if ($(this).val()) {
                hasActiveFilters = true;
                const label = $("label[for='" + $(this).attr('id') + "']").text();
                chipsContainer.append(`
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${label}
                        <button type="button" class="ml-2 text-blue-600 hover:text-blue-800" onclick="$(this).closest('span').remove(); $('#${$(this).closest('span').data('id')}').click();">
                            <i data-lucide="x" class="h-3 w-3"></i>
                        </button>
                    </span>
                `);
            }
        });

        // Recherche
        const searchValue = $('.mySearch').val();
        if (searchValue) {
            hasActiveFilters = true;
            chipsContainer.append(`
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    "${searchValue}"
                    <button type="button" class="ml-2 text-green-600 hover:text-green-800" onclick="$('.mySearch').val(''); fetchService();">
                        <i data-lucide="x" class="h-3 w-3"></i>
                    </button>
                </span>
            `);
        }

        // Gamme de prix
        const minPrice = $("[name=min]").val();
        const maxPrice = $("[name=max]").val();
        if (minPrice || maxPrice) {
            hasActiveFilters = true;
            const priceText = `${minPrice || '0'} - ${maxPrice || '∞'}`;
            chipsContainer.append(`
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    ${priceText}
                    <button type="button" class="ml-2 text-purple-600 hover:text-purple-800" onclick="$('[name=min], [name=max]').val(''); fetchService();">
                        <i data-lucide="x" class="h-3 w-3"></i>
                    </button>
                </span>
            `);
        }

        activeFiltersContainer.toggle(hasActiveFilters);

        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Fonction pour appliquer les filtres
    window.applyFilters = function() {
        fetchService();
    }

    // Fonction pour effacer tous les filtres
    window.clearAllFilters = function() {
        $("[name=category]").prop('checked', false);
        $('#modal_category0').prop('checked', true);
        $("[name=min], [name=max]").val('');
        $('.mySearch').val('');
        $("[name=sort]").prop('checked', false);
        $('#modal_service1').prop('checked', true);
        fetchService();
    }

    $('.sortCategory, .sortService').on('click', function() {
        $('#modal_category0').removeAttr('checked','checked');
        if ($('#modal_category0').is(':checked')) {
            $("input[type='checkbox'][name='category']").not(this).prop('checked', false);
        }

        if($("input[type='checkbox'][name='category']:checked").length == 0){
            $('#modal_category0').attr('checked','checked');
        }
    });

    $('.searchBtn').on('click', function() {
        $(this).attr('disabled', 'disabled');
        fetchService();
    });

    // Recherche en temps réel avec débounce
    let searchTimeout;
    $('.mySearch').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchService();
        }, 500);
    });

    // Recherche sur Enter
    $('.mySearch').on('keypress', function(e) {
        if(e.which == 13) {
            fetchService();
        }
    });

    function fetchService() {
        $('.loader-wrapper').removeClass('hidden');
        let data = {};
        data.categories = [];

        $.each($("[name=category]:checked"), function() {
            if ($(this).val()) {
                data.categories.push($(this).val());
            }
        });

        data.search = $('.mySearch').val();
        data.sort = $('.sortService:checked').val();
        data.min = $("[name=min]").val();
        data.max = $("[name=max]").val();
        data.tagId = "{{ @$id }}";

        let url = `{{ localized_route('service.filter') }}`;

        if (page) {
            url = `{{ localized_route('service.filter') }}?page=${page}`;
        }

        $.ajax({
            method: "GET",
            url: url,
            data: data,
            success: function(response) {
                $('#services').html(response);
                $('.searchBtn').removeAttr('disabled');
                updateFilterChips();

                // Re-initialize Lucide icons for new elements
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                // Smooth scroll to results
                $('html, body').animate({
                    scrollTop: $('#services').offset().top - 100
                }, 300);
            }
        }).done(function() {
            $('.loader-wrapper').addClass('hidden')
        });
    }

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        page = $(this).attr('href').split('page=')[1];
        fetchService();
    });

    // Initialize filter chips on page load
    updateFilterChips();

})(jQuery);
</script>
@endpush