@extends('layouts.dashboard')
@section('content')

<!-- Profile Header -->
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Profile Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Profile Header -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <!-- Avatar -->
                    <div class="relative flex-shrink-0">
                        <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100">
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
                        @if ($influencer->isOnline())
                        <div class="absolute -bottom-1 -right-1">
                            <div class="w-6 h-6 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900 truncate">{{ $influencer->fullname }}</h1>
                        <p class="text-lg text-blue-600 font-medium">{{ '@'.$influencer->username }}</p>
                        <p class="text-gray-600">{{ $influencer->profession }}</p>

                        <!-- Rating -->
                        <div class="flex items-center mt-2">
                            <div class="flex text-yellow-400 text-sm mr-2">
                                @php echo showRatings($influencer->rating); @endphp
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ number_format($influencer->rating, 1) }}</span>
                            <span class="text-xs text-gray-500 mr-1">({{ getAmount($influencer->total_review) }} @lang('influencers.review'))</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if (!authInfluencerId())
                    <div class="flex-shrink-0 flex flex-col space-y-2">
                        <a href="{{ localized_route('user.hiring.request', [slug($influencer->username), $influencer->id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i data-lucide="user-plus" class="h-4 w-4 ml-2"></i>
                            @lang('influencers.hire_me_now')
                        </a>
                        <a href="{{ localized_route('user.conversation.create', $influencer->id) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i data-lucide="message-circle" class="h-4 w-4 ml-2"></i>
                            @lang('influencers.contact')
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Categories -->
                @if($influencer->categories && $influencer->categories->count() > 0)
                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach ($influencer->categories as $category)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ __($category->name) }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Stats Grid -->
            <div class="p-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $data['pending_job'] }}</div>
                        <div class="text-sm text-gray-600">@lang('influencers.pending_jobs')</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $data['ongoing_job'] }}</div>
                        <div class="text-sm text-gray-600">@lang('influencers.ongoing_jobs')</div>
                    </div>
                    <div class="text-center p-4 bg-indigo-50 rounded-lg">
                        <div class="text-2xl font-bold text-indigo-600">{{ $data['queue_job'] }}</div>
                        <div class="text-sm text-gray-600">@lang('influencers.queue_jobs')</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $data['completed_job'] }}</div>
                        <div class="text-sm text-gray-600">@lang('influencers.completed_jobs')</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="space-y-6">

                <!-- About -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="user" class="w-5 h-5 ml-2 text-blue-600"></i>
                        @lang('influencers.about')
                    </h3>
                    <div class="text-gray-700 leading-relaxed">
                        @if ($influencer->summary)
                        @php echo $influencer->summary; @endphp
                        @else
                        <p class="text-gray-500 italic">@lang('influencers.no_summary_added')</p>
                        @endif
                    </div>
                </div>

                <!-- Skills -->
                @if ($influencer->skills && count($influencer->skills) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="star" class="w-5 h-5 ml-2 text-yellow-600"></i>
                        @lang('influencers.skills')
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($influencer->skills as $skill)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            {{ __($skill) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Languages -->
                @if($influencer->languages && count($influencer->languages) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="globe" class="w-5 h-5 ml-2 text-green-600"></i>
                        @lang('influencers.languages')
                    </h3>
                    <div class="space-y-3">
                        @foreach ($influencer->languages as $language => $proficiencies)
                        <div class="border-b border-gray-100 pb-3 last:border-b-0">
                            <h4 class="font-medium text-gray-900 mb-2">{{ __($language) }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($proficiencies as $skill => $level)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ keyToTitle($skill) }}: {{ $level }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Education -->
                @if ($influencer->education->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="graduation-cap" class="w-5 h-5 ml-2 text-purple-600"></i>
                        @lang('influencers.education')
                    </h3>
                    <div class="space-y-4">
                        @foreach ($influencer->education as $education)
                        <div class="border-b border-gray-100 pb-4 last:border-b-0">
                            <h4 class="font-semibold text-gray-900">{{ __($education->degree) }}</h4>
                            <p class="text-gray-600">{{ __($education->institute) }}, {{ $education->country }}</p>
                            <p class="text-sm text-gray-500">{{ __($education->start_year) }} - {{ __($education->end_year) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Qualifications -->
                @if ($influencer->qualification->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="award" class="w-5 h-5 ml-2 text-orange-600"></i>
                        @lang('influencers.qualifications')
                    </h3>
                    <div class="space-y-4">
                        @foreach ($influencer->qualification as $qualification)
                        <div class="border-b border-gray-100 pb-4 last:border-b-0">
                            <h4 class="font-semibold text-gray-900">{{ __($qualification->certificate) }}</h4>
                            <p class="text-gray-600">{{ __($qualification->organization) }} ({{ $qualification->year }})</p>
                            @if($qualification->summary)
                            <p class="text-sm text-gray-700 mt-2">{{ __($qualification->summary) }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>

        <!-- Statistics -->
        <div class="lg:col-span-2">
            @if($statistic || $statistic_instagram || $statistic_tiktok || $statistic_youtube)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        @if ($statistic)
                        <button class="platform-tab active py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                data-platform="facebook"
                                data-target="#facebook-stats">
                            <i class="fab fa-facebook-f text-blue-600 ml-2"></i>
                            Facebook
                        </button>
                        @endif
                        @if ($statistic_instagram)
                        <button class="platform-tab py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-colors duration-200"
                                data-platform="instagram"
                                data-target="#instagram-stats">
                            <i class="fab fa-instagram text-purple-600 ml-2"></i>
                            Instagram
                        </button>
                        @endif
                        @if ($statistic_tiktok)
                        <button class="platform-tab py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-colors duration-200"
                                data-platform="tiktok"
                                data-target="#tiktok-stats">
                            <span class="w-4 h-4 ml-2 bg-black rounded-sm inline-flex items-center justify-center">
                                <span class="text-white text-xs font-bold">T</span>
                            </span>
                            TikTok
                        </button>
                        @endif
                        @if ($statistic_youtube)
                        <button class="platform-tab py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-colors duration-200"
                                data-platform="youtube"
                                data-target="#youtube-stats">
                            <i class="fab fa-youtube text-red-600 ml-2"></i>
                            YouTube
                        </button>
                        @endif
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Facebook Stats -->
                    @if ($statistic)
                    <div id="facebook-stats" class="platform-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-blue-50 rounded-lg p-6 text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ number_format($data['followers']) }}</div>
                                <div class="text-sm text-gray-600 mt-1">@lang('influencers.follower')</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $data['average_interactions'] }}%</div>
                                <div class="text-sm text-gray-600 mt-1">@lang('influencers.avg_interactions')</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_gender')</h4>
                                <div id="genderChart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.top_cities')</h4>
                                <div id="citiesChart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_age')</h4>
                                <div id="agechart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_age_gender')</h4>
                                <div id="ageMenWomenchart"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Instagram Stats -->
                    @if ($statistic_instagram)
                    <div id="instagram-stats" class="platform-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-purple-50 rounded-lg p-6 text-center">
                                <div class="text-3xl font-bold text-purple-600">{{ number_format($data['instagram_followers']) }}</div>
                                <div class="text-sm text-gray-600 mt-1">@lang('influencers.follower')</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $data['instagram_average_interactions'] }}%</div>
                                <div class="text-sm text-gray-600 mt-1">@lang('influencers.avg_interactions')</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_gender')</h4>
                                <div id="instagram_genderChart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.top_cities')</h4>
                                <div id="instagram_citiesChart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_age')</h4>
                                <div id="instagram_agechart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_age_gender')</h4>
                                <div id="instagram_ageMenWomenchart"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- TikTok Stats -->
                    @if ($statistic_tiktok)
                    <div id="tiktok-stats" class="platform-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <div class="text-3xl font-bold text-gray-900">{{ number_format($data['tiktok_followers']) }}</div>
                                <div class="text-sm text-gray-600 mt-1">@lang('influencers.follower')</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $data['tiktok_average_interactions'] }}%</div>
                                <div class="text-sm text-gray-600 mt-1">@lang('influencers.avg_interactions')</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_gender')</h4>
                                <div id="tiktok_genderChart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.top_cities')</h4>
                                <div id="tiktok_citiesChart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_age')</h4>
                                <div id="tiktok_agechart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_age_gender')</h4>
                                <div id="tiktok_ageMenWomenchart"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- YouTube Stats -->
                    @if ($statistic_youtube)
                    <div id="youtube-stats" class="platform-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-red-50 rounded-lg p-6 text-center">
                                <div class="text-3xl font-bold text-red-600">{{ number_format($data['youtube_followers']) }}</div>
                                <div class="text-sm text-gray-600 mt-1">@lang('influencers.follower')</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $data['youtube_average_interactions'] }}%</div>
                                <div class="text-sm text-gray-600 mt-1">@lang('influencers.avg_interactions')</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_gender')</h4>
                                <div id="youtube_genderChart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.top_cities')</h4>
                                <div id="youtube_citiesChart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_age')</h4>
                                <div id="youtube_agechart"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">@lang('influencers.audience_age_gender')</h4>
                                <div id="youtube_ageMenWomenchart"></div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <!-- No Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <i data-lucide="bar-chart-3" class="h-16 w-16 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">@lang('influencers.no_statistics_available')</h3>
                <p class="text-gray-600">@lang('influencers.no_social_stats_updated')</p>
            </div>
            @endif
        </div>

    </div>
</div>

@php
if($statistic){
    $city_1=$data['city_1'];
    $city_2=$data['city_2'];
    $city_3=$data['city_3'];
    $city_4=$data['city_4'];
}

if($statistic_instagram){
    $instagram_city_1=$data['instagram_city_1'];
    $instagram_city_2=$data['instagram_city_2'];
    $instagram_city_3=$data['instagram_city_3'];
    $instagram_city_4=$data['instagram_city_4'];
}
if($statistic_tiktok){
    $tiktok_city_1=$data['tiktok_city_1'];
    $tiktok_city_2=$data['tiktok_city_2'];
    $tiktok_city_3=$data['tiktok_city_3'];
    $tiktok_city_4=$data['tiktok_city_4'];
}
if($statistic_youtube){
    $youtube_city_1=$data['youtube_city_1'];
    $youtube_city_2=$data['youtube_city_2'];
    $youtube_city_3=$data['youtube_city_3'];
    $youtube_city_4=$data['youtube_city_4'];
}
@endphp

@endsection

@push('style')
<style>
.platform-tab.active {
    border-color: #3B82F6;
    color: #1D4ED8;
}
</style>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.platform-tab');
    const contents = document.querySelectorAll('.platform-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => {
                t.classList.remove('active');
                t.classList.add('border-transparent', 'text-gray-500');
                t.classList.remove('border-blue-500', 'text-blue-600');
            });

            // Hide all content
            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // Add active class to clicked tab
            this.classList.add('active');
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-blue-500', 'text-blue-600');

            // Show corresponding content
            const target = this.getAttribute('data-target');
            const targetContent = document.querySelector(target);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
});
</script>

@if ($statistic)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Age chart (all audience)
    var ageOptions = {
        chart: {
            height: 280,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "(%)",
            data: [{{ $data['age_g_13'] }},
                   {{ $data['age_g_18'] }},
                   {{ $data['age_g_25'] }},
                   {{ $data['age_g_35'] }},
                   {{ $data['age_g_45'] }},
                   {{ $data['age_g_55'] }}]
        }],
        xaxis: {
            categories: ["13-17", "18-24", "25-34", "35-44", "45-54", "55-64"]
        },
        colors: ['#3B82F6'],
        responsive: [{
            breakpoint: 1000,
            options: {
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                }
            }
        }]
    };
    var ageChart = new ApexCharts(document.querySelector("#agechart"), ageOptions);
    ageChart.render();

    // Age chart (men/women)
    var ageMenWomenOptions = {
        chart: {
            height: 280,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "M(%)",
            data: [{{ $data['age_m_13'] }},
                   {{ $data['age_m_18'] }},
                   {{ $data['age_m_25'] }},
                   {{ $data['age_m_35'] }},
                   {{ $data['age_m_45'] }},
                   {{ $data['age_m_55'] }}]
        }, {
            name: "F(%)",
            data: [{{ $data['age_w_13'] }},
                   {{ $data['age_w_18'] }},
                   {{ $data['age_w_25'] }},
                   {{ $data['age_w_35'] }},
                   {{ $data['age_w_45'] }},
                   {{ $data['age_w_55'] }}]
        }],
        xaxis: {
            categories: ["13-17", "18-24", "25-34", "35-44", "45-54", "55-64"]
        },
        colors: ['#3B82F6', '#EC4899'],
        responsive: [{
            breakpoint: 1000,
            options: {
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                }
            }
        }]
    };
    var ageMenWomenChart = new ApexCharts(document.querySelector("#ageMenWomenchart"), ageMenWomenOptions);
    ageMenWomenChart.render();

    // Cities chart
    var citiesOptions = {
        chart: {
            height: 220,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "(%)",
            data: [{{ $data['nomber_followers_1'] }},
                   {{ $data['nomber_followers_2'] }},
                   {{ $data['nomber_followers_3'] }},
                   {{ $data['nomber_followers_4'] }}]
        }],
        xaxis: {
            categories: ['{{$city_1}}', '{{$city_2}}', '{{$city_3}}', '{{$city_4}}']
        },
        colors: ['#3B82F6'],
        responsive: [{
            breakpoint: 1000,
            options: {
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                }
            }
        }]
    };
    var citiesChart = new ApexCharts(document.querySelector("#citiesChart"), citiesOptions);
    citiesChart.render();

    // Gender chart
    var genderOptions = {
        series: [{{ $data['gender_men'] }}, {{ $data['gender_women'] }}],
        chart: {
            type: 'donut',
        },
        labels: ['(%)M', '(%)F'],
        colors: ['#3B82F6', '#EC4899'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270
            }
        },
        dataLabels: {
            enabled: false
        },
        fill: {
            type: 'gradient',
        },
        legend: {
            formatter: function(val, opts) {
                return val + " - " + opts.w.globals.series[opts.seriesIndex]
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    var genderChart = new ApexCharts(document.querySelector("#genderChart"), genderOptions);
    genderChart.render();
});
</script>
@endif

@if ($statistic_instagram)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Instagram Gender Chart
    var instagramGenderOptions = {
        series: [{{ $data['instagram_gender_men'] }}, {{ $data['instagram_gender_women'] }}],
        chart: {
            
            type: 'donut',
        },
        labels: ['(%)M', '(%)F'],
        colors: ['#3B82F6', '#EC4899'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270
            }
        },
        dataLabels: {
            enabled: false
        },
        fill: {
            type: 'gradient',
        },
        legend: {
            formatter: function(val, opts) {
                return val + " - " + opts.w.globals.series[opts.seriesIndex]
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    var instagramGenderChart = new ApexCharts(document.querySelector("#instagram_genderChart"), instagramGenderOptions);
    instagramGenderChart.render();

    // Instagram Cities Chart
    var instagramCitiesOptions = {
        chart: {
            
            height: 220,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "(%)",
            data: [{{ $data['instagram_nomber_followers_1'] }},
                   {{ $data['instagram_nomber_followers_2'] }},
                   {{ $data['instagram_nomber_followers_3'] }},
                   {{ $data['instagram_nomber_followers_4'] }}]
        }],
        xaxis: {
            categories: ['{{$data['instagram_city_1']}}', '{{$data['instagram_city_2']}}', '{{$data['instagram_city_3']}}', '{{$data['instagram_city_4']}}']
        },
        colors: ['#EC4899'],
        responsive: [{
            breakpoint: 1000,
            options: {
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                }
            }
        }]
    };
    var instagramCitiesChart = new ApexCharts(document.querySelector("#instagram_citiesChart"), instagramCitiesOptions);
    instagramCitiesChart.render();

    // Instagram Age Chart (all audience)
    var instagramAgeOptions = {
        chart: {
            
            height: 280,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "(%)",
            data: [{{ $data['instagram_age_g_13'] }},
                   {{ $data['instagram_age_g_18'] }},
                   {{ $data['instagram_age_g_25'] }},
                   {{ $data['instagram_age_g_35'] }},
                   {{ $data['instagram_age_g_45'] }},
                   {{ $data['instagram_age_g_55'] }}]
        }],
        xaxis: {
            categories: ["13-17", "18-24", "25-34", "35-44", "45-54", "55-64"]
        },
        colors: ['#EC4899']
    };
    var instagramAgeChart = new ApexCharts(document.querySelector("#instagram_agechart"), instagramAgeOptions);
    instagramAgeChart.render();

    // Instagram Age Chart (men/women)
    var instagramAgeMenWomenOptions = {
        chart: {
            
            height: 280,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "Male (%)",
            data: [{{ $data['instagram_age_m_13'] }},
                   {{ $data['instagram_age_m_18'] }},
                   {{ $data['instagram_age_m_25'] }},
                   {{ $data['instagram_age_m_35'] }},
                   {{ $data['instagram_age_m_45'] }},
                   {{ $data['instagram_age_m_55'] }}]
        }, {
            name: "Female (%)",
            data: [{{ $data['instagram_age_w_13'] }},
                   {{ $data['instagram_age_w_18'] }},
                   {{ $data['instagram_age_w_25'] }},
                   {{ $data['instagram_age_w_35'] }},
                   {{ $data['instagram_age_w_45'] }},
                   {{ $data['instagram_age_w_55'] }}]
        }],
        xaxis: {
            categories: ["13-17", "18-24", "25-34", "35-44", "45-54", "55-64"]
        },
        colors: ['#3B82F6', '#EC4899']
    };
    var instagramAgeMenWomenChart = new ApexCharts(document.querySelector("#instagram_ageMenWomenchart"), instagramAgeMenWomenOptions);
    instagramAgeMenWomenChart.render();
});
</script>
@endif

@if ($statistic_tiktok)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // TikTok Gender Chart
    var tiktokGenderOptions = {
        series: [{{ $data['tiktok_gender_men'] }}, {{ $data['tiktok_gender_women'] }}],
        chart: {
            
            type: 'donut',
        },
        labels: ['(%)M', '(%)F'],
        colors: ['#000000', '#FF0050'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270
            }
        },
        dataLabels: {
            enabled: false
        },
        fill: {
            type: 'gradient',
        },
        legend: {
            formatter: function(val, opts) {
                return val + " - " + opts.w.globals.series[opts.seriesIndex]
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    var tiktokGenderChart = new ApexCharts(document.querySelector("#tiktok_genderChart"), tiktokGenderOptions);
    tiktokGenderChart.render();

    // TikTok Cities Chart
    var tiktokCitiesOptions = {
        chart: {
            
            height: 220,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "(%)",
            data: [{{ $data['tiktok_nomber_followers_1'] }},
                   {{ $data['tiktok_nomber_followers_2'] }},
                   {{ $data['tiktok_nomber_followers_3'] }},
                   {{ $data['tiktok_nomber_followers_4'] }}]
        }],
        xaxis: {
            categories: ['{{$data['tiktok_city_1']}}', '{{$data['tiktok_city_2']}}', '{{$data['tiktok_city_3']}}', '{{$data['tiktok_city_4']}}']
        },
        colors: ['#000000'],
        responsive: [{
            breakpoint: 1000,
            options: {
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                }
            }
        }]
    };
    var tiktokCitiesChart = new ApexCharts(document.querySelector("#tiktok_citiesChart"), tiktokCitiesOptions);
    tiktokCitiesChart.render();

    // TikTok Age Chart (all audience)
    var tiktokAgeOptions = {
        chart: {
            
            height: 280,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "(%)",
            data: [{{ $data['tiktok_age_g_13'] }},
                   {{ $data['tiktok_age_g_18'] }},
                   {{ $data['tiktok_age_g_25'] }},
                   {{ $data['tiktok_age_g_35'] }},
                   {{ $data['tiktok_age_g_45'] }},
                   {{ $data['tiktok_age_g_55'] }}]
        }],
        xaxis: {
            categories: ["13-17", "18-24", "25-34", "35-44", "45-54", "55-64"]
        },
        colors: ['#000000']
    };
    var tiktokAgeChart = new ApexCharts(document.querySelector("#tiktok_agechart"), tiktokAgeOptions);
    tiktokAgeChart.render();

    // TikTok Age Chart (men/women)
    var tiktokAgeMenWomenOptions = {
        chart: {
            
            height: 280,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "Male (%)",
            data: [{{ $data['tiktok_age_m_13'] }},
                   {{ $data['tiktok_age_m_18'] }},
                   {{ $data['tiktok_age_m_25'] }},
                   {{ $data['tiktok_age_m_35'] }},
                   {{ $data['tiktok_age_m_45'] }},
                   {{ $data['tiktok_age_m_55'] }}]
        }, {
            name: "Female (%)",
            data: [{{ $data['tiktok_age_w_13'] }},
                   {{ $data['tiktok_age_w_18'] }},
                   {{ $data['tiktok_age_w_25'] }},
                   {{ $data['tiktok_age_w_35'] }},
                   {{ $data['tiktok_age_w_45'] }},
                   {{ $data['tiktok_age_w_55'] }}]
        }],
        xaxis: {
            categories: ["13-17", "18-24", "25-34", "35-44", "45-54", "55-64"]
        },
        colors: ['#000000', '#FF0050']
    };
    var tiktokAgeMenWomenChart = new ApexCharts(document.querySelector("#tiktok_ageMenWomenchart"), tiktokAgeMenWomenOptions);
    tiktokAgeMenWomenChart.render();
});
</script>
@endif

@if ($statistic_youtube)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // YouTube Gender Chart
    var youtubeGenderOptions = {
        series: [{{ $data['youtube_gender_men'] }}, {{ $data['youtube_gender_women'] }}],
        chart: {
            
            type: 'donut',
        },
        labels: ['(%)M', '(%)F'],
        colors: ['#FF0000', '#000000'],
        plotOptions: {
            pie: {
                startAngle: -90,
                endAngle: 270
            }
        },
        dataLabels: {
            enabled: false
        },
        fill: {
            type: 'gradient',
        },
        legend: {
            formatter: function(val, opts) {
                return val + " - " + opts.w.globals.series[opts.seriesIndex]
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
    var youtubeGenderChart = new ApexCharts(document.querySelector("#youtube_genderChart"), youtubeGenderOptions);
    youtubeGenderChart.render();

    // YouTube Cities Chart
    var youtubeCitiesOptions = {
        chart: {
            
            height: 220,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "(%)",
            data: [{{ $data['youtube_nomber_followers_1'] }},
                   {{ $data['youtube_nomber_followers_2'] }},
                   {{ $data['youtube_nomber_followers_3'] }},
                   {{ $data['youtube_nomber_followers_4'] }}]
        }],
        xaxis: {
            categories: ['{{$data['youtube_city_1']}}', '{{$data['youtube_city_2']}}', '{{$data['youtube_city_3']}}', '{{$data['youtube_city_4']}}']
        },
        colors: ['#FF0000'],
        responsive: [{
            breakpoint: 1000,
            options: {
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                }
            }
        }]
    };
    var youtubeCitiesChart = new ApexCharts(document.querySelector("#youtube_citiesChart"), youtubeCitiesOptions);
    youtubeCitiesChart.render();

    // YouTube Age Chart (all audience)
    var youtubeAgeOptions = {
        chart: {
            
            height: 280,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "(%)",
            data: [{{ $data['youtube_age_g_13'] }},
                   {{ $data['youtube_age_g_18'] }},
                   {{ $data['youtube_age_g_25'] }},
                   {{ $data['youtube_age_g_35'] }},
                   {{ $data['youtube_age_g_45'] }},
                   {{ $data['youtube_age_g_55'] }}]
        }],
        xaxis: {
            categories: ["13-17", "18-24", "25-34", "35-44", "45-54", "55-64"]
        },
        colors: ['#FF0000']
    };
    var youtubeAgeChart = new ApexCharts(document.querySelector("#youtube_agechart"), youtubeAgeOptions);
    youtubeAgeChart.render();

    // YouTube Age Chart (men/women)
    var youtubeAgeMenWomenOptions = {
        chart: {
            
            height: 280,
            type: "bar"
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        series: [{
            name: "Male (%)",
            data: [{{ $data['youtube_age_m_13'] }},
                   {{ $data['youtube_age_m_18'] }},
                   {{ $data['youtube_age_m_25'] }},
                   {{ $data['youtube_age_m_35'] }},
                   {{ $data['youtube_age_m_45'] }},
                   {{ $data['youtube_age_m_55'] }}]
        }, {
            name: "Female (%)",
            data: [{{ $data['youtube_age_w_13'] }},
                   {{ $data['youtube_age_w_18'] }},
                   {{ $data['youtube_age_w_25'] }},
                   {{ $data['youtube_age_w_35'] }},
                   {{ $data['youtube_age_w_45'] }},
                   {{ $data['youtube_age_w_55'] }}]
        }],
        xaxis: {
            categories: ["13-17", "18-24", "25-34", "35-44", "45-54", "55-64"]
        },
        colors: ['#FF0000', '#000000']
    };
    var youtubeAgeMenWomenChart = new ApexCharts(document.querySelector("#youtube_ageMenWomenchart"), youtubeAgeMenWomenOptions);
    youtubeAgeMenWomenChart.render();
});
</script>
@endif
@endpush