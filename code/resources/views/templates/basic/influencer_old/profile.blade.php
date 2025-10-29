@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="pt-80 pb-80">
    <div class="influencer-profile-area">
        <div class="container">
            <div class="influencer-profile-wrapper">
                <div class="d-flex justify-content-between flex-wrap gap-4">
                    <div class="left">
                        <div class="profile">
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('influencerProfile') . '/' . $influencer->image, getFileSize('influencerProfile'), true) }}" alt="profile thumb">
                            </div>
                            <div class="content">
                                <h5 class="fw-medium name account-status d-inline-block">{{ $influencer->fullname }}</h5>
                                <h6 class="text--base"> {{ $influencer->username }}</h6>

                                <span>@lang('Profession'): <i class="title text--small text--muted p-0 m-0">{{ $influencer->profession }}</i></span>
                                <ul class="list d-flex flex-wrap">

                                    <li>
                                        <div class="rating-wrapper">
                                            <span class="text--warning service-rating">
                                                @php
                                                echo showRatings($influencer->rating);
                                                @endphp
                                                ({{ getAmount($influencer->total_review) }})
                                            </span>
                                        </div>
                                    </li>
                                </ul>

                                @if($influencer->categories)
                                    @foreach (@$influencer->categories as $category)
                                        <div class="justify-content-between skill-card mt-3">
                                            <span>{{ __(@$category->name) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (!authInfluencerId())
                    <div class="right buttons-wrapper">
                        <a href="{{ localized_route('user.hiring.request', [slug($influencer->username), $influencer->id]) }}" class="btn btn--outline-base btn--sm radius-0"><i class="fas fa-user-check"></i>
                            @lang('Hire Me Now')</a>

                        <a href="{{ localized_route('user.conversation.create', $influencer->id) }}" class="btn btn--outline-info btn--sm radius-0"><i class="fas fa-sms"></i> @lang('Contact')</a>
                    </div>
                    @endif
                </div>

                <ul class="info d-flex justify-content-between border-top mt-4 flex-wrap gap-3 pt-4">
                    <li class="d-flex align-items-center gap-2">
                        <h4 class="text--warning d-inline-block">{{ $data['pending_job'] }}</h4>
                        <span>@lang('Pending Job')</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <h4 class="text--base d-inline-block">{{ $data['ongoing_job'] }}</h4>
                        <span>@lang('Ongoing Job')</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <h4 class="text--info d-inline-block">{{ $data['queue_job'] }}</h4>
                        <span>@lang('Queue Job')</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <h4 class="text--success d-inline-block">{{ $data['completed_job'] }}</h4>
                        <span>@lang('Completed Job')</span>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="profile-content mt-4">
                        <div class="custom--card">
                            <div class="card-body">
                                <div class="influencer-profile-sidebar">
                                    <h6 class="mb-3">@lang('Description')</h6>
                                    <p>
                                        @if ($influencer->summary)
                                        @php
                                        echo $influencer->summary;
                                        @endphp
                                        @else
                                        @lang('No summary have been added.')
                                        @endif
                                    </p>
                                </div>

                                @if ($influencer->skills)
                                <div class="influencer-profile-sidebar">
                                    <h6 class="mb-3">@lang('Skills')</h6>
                                    @foreach ($influencer->skills as $skill)

                                    <div class="justify-content-between skill-card my-1">
                                        <span>{{ __(@$skill) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                @if($influencer->languages)
                                    @foreach (@$influencer->languages as $key=>$profiencies)
                                    <div class="col-12 ">
                                        <div class="education-content py-3">
                                            <div class="d-flex justify-content-between align-items-center gap-3">
                                                <h6>{{ __($key) }}</h6>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 my-2">
                                                @foreach ($profiencies as $key=>$profiency)
                                                <span class="skill-card px-2 py-1 rounded">
                                                    {{ keyToTitle($key) }} : {{ $profiency }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                                @if ($influencer->education->count() > 0)
                                <div class="influencer-profile-sidebar">
                                    <h6 class="mb-3">@lang('Educations')</h6>
                                    @foreach ($influencer->education as $education)
                                    <div class="expertise-content">
                                        <div class="expertise-product">
                                            <div class="expertise-details">
                                                <h6 class="fs--15px mb-1 mt-3">{{ __($education->degree) }}
                                                </h6>
                                                <ul class="experties-meta fs--14px my-1">
                                                    <li class="text-dark">
                                                        <span>{{ __($education->institute) }},
                                                            {{ $education->country }}</span>
                                                    </li>
                                                </ul>
                                                <ul class="experties-meta fs--14px my-1">
                                                    <li class="text-dark">
                                                        <span>{{ __($education->start_year) }} -
                                                            {{ __($education->end_year) }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                @if ($influencer->qualification->count() > 0)
                                <div class="influencer-profile-sidebar">
                                    <h6 class="mb-3">@lang('Qualifications')</h6>
                                    @foreach ($influencer->qualification as $qualification)
                                    <div class="expertise-content">
                                        <div class="expertise-product">
                                            <div class="expertise-details">
                                                <h6 class="fs--15px mb-2">
                                                    {{ __($qualification->certificate) }}</h6>
                                                <ul class="experties-meta my-1">
                                                    <li class="text-dark">
                                                        <span>{{ __($qualification->organization) }},
                                                            {{ $qualification->year }}</span>
                                                    </li>
                                                </ul>
                                                <p>{{ __($qualification->summary) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 ">

                    <div class="profile-content mt-4 statistics" id="social_facebook">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Facebook</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Instagram</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Tiktok</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="youtube-tab" data-bs-toggle="tab" data-bs-target="#youtube" type="button" role="tab" aria-controls="youtube" aria-selected="false">Youtube</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                @if ($statistic)
                                <div class="custom--card">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <span>@lang('Followers : ')</span>
                                                    <h4 class="text--base d-inline-block">{{ $data['followers'] }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <span>@lang('Average interactions : ')</span>
                                                    <h4 class="text--info d-inline-block">{{ $data['average_interactions'] }} % </h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience gender')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="genderChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Principal cities')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="citiesChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience age (All)')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="agechart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience age (Men/Women)')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="ageMenWomenchart"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @else

                                    <div class="row gy-4 justify-content-center">
                                        <img src="{{ getImage('assets/images/frontend/empty_message/no_data.png') }}" alt="" class="w-100">
                                    </div>

                                @endif
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                @if ($statistic_instagram)
                                <div class="custom--card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <span>@lang('Followers : ')</span>
                                                    <h4 class="text--base d-inline-block">{{ $data['instagram_followers'] }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <span>@lang('Average interactions : ')</span>
                                                    <h4 class="text--info d-inline-block">{{ $data['instagram_average_interactions'] }} % </h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience gender')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="instagram_genderChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Principal cities')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="instagram_citiesChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience age (All)')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="instagram_agechart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience age (Men/Women)')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="instagram_ageMenWomenchart"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row gy-4 justify-content-center">
                                    <img src="{{ getImage('assets/images/frontend/empty_message/no_data.png') }}" alt="" class="w-100">
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                @if ($statistic_tiktok)
                                <div class="custom--card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <span>@lang('Followers : ')</span>
                                                    <h4 class="text--base d-inline-block">{{ $data['tiktok_followers'] }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <span>@lang('Average interactions : ')</span>
                                                    <h4 class="text--info d-inline-block">{{ $data['tiktok_average_interactions'] }} % </h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience gender')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="tiktok_genderChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Principal cities')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="tiktok_citiesChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience age (All)')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="tiktok_agechart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience age (Men/Women)')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="tiktok_ageMenWomenchart"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row gy-4 justify-content-center">
                                    <img src="{{ getImage('assets/images/frontend/empty_message/no_data.png') }}" alt="" class="w-100">
                                </div>
                                @endif
                            </div>


                            <div class="tab-pane fade" id="youtube" role="tabpanel" aria-labelledby="youtube-tab">
                                @if ($statistic_youtube)
                                <div class="custom--card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <span>@lang('Followers : ')</span>
                                                    <h4 class="text--base d-inline-block">{{ $data['youtube_followers'] }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <span>@lang('Average interactions : ')</span>
                                                    <h4 class="text--info d-inline-block">{{ $data['youtube_average_interactions'] }} % </h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience gender')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="youtube_genderChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Principal cities')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="youtube_citiesChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience age (All)')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="youtube_agechart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="job__completed my-4">
                                                    <div class="job__completed-header d-flex align-items-center justify-content-between">
                                                        <h5>@lang('Audience age (Men/Women)')</h5>
                                                    </div>
                                                    <div class="job__completed-body">
                                                        <div id="youtube_ageMenWomenchart"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row gy-4 justify-content-center">
                                    <img src="{{ getImage('assets/images/frontend/empty_message/no_data.png') }}" alt="" class="w-100">
                                </div>
                                @endif


                            </div>
                        </div>

                    </div>
                </div>

            </div>
            </div>
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
    .profile .thumb {
        width: 100px;
        height: 100px;
    }
    .statistics{
        background : #f8f9fa;
    }
    a.social-item{
        width: 80px;

    }
    .text--base{
        color: #fd8075;
    }

</style>
@endpush
@push('script')
@if ($statistic)
<script>
    $(document).ready(function() {

            var options = {

                chart: {
                    width: 350,
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
                name: "Men(%)",

                data: [{{ $data['age_m_13'] }},
                       {{ $data['age_m_18'] }},
                       {{ $data['age_m_25'] }},
                       {{ $data['age_m_35'] }},
                       {{ $data['age_m_45'] }},
                       {{ $data['age_m_55'] }}]
                },

                {
                name: "Women(%)",

                data: [{{ $data['age_w_13'] }},
                       {{ $data['age_w_18'] }},
                       {{ $data['age_w_25'] }},
                       {{ $data['age_w_35'] }},
                       {{ $data['age_w_45'] }},
                       {{ $data['age_w_55'] }}]
                }
                ],

                xaxis: {
                    categories: [
                        "13-17",
                        "18-24",
                        "25-34",
                        "35-44",
                        "45-54",
                        "55-64",
                    ]
                },
               legend: {
                    position: "left",
                    verticalAlign: "top",
                    containerMargin: {
                        left: 20,
                        right: 35
                    }

                },
               colors: ['#7fb6ea', '#fd8075'],
               responsive: [
                    {
                    breakpoint: 1000,
                    options: {
                    plotOptions: {
                    bar: {
                    horizontal: false
                    }
                },
                legend: {
                    position: "bottom"
                }
            }
         }
      ]
     };

             var chart = new ApexCharts(document.querySelector("#ageMenWomenchart"),options);
            chart.render();

            var options = {
            chart: {
                width: 350,
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
            series: [

            {
                name: "audience(%)",
                data: [{{ $data['age_g_13'] }},
                       {{ $data['age_g_18'] }},
                       {{ $data['age_g_25'] }},
                       {{ $data['age_g_35'] }},
                       {{ $data['age_g_45'] }},
                       {{ $data['age_g_55'] }}]
            }
            ],
            xaxis: {
            categories: [
                "13-17",
                "18-24",
                "25-34",
                "35-44",
                "45-54",
                "55-64",
            ]
            },
            legend: {
                position: "right",
                verticalAlign: "top",
                containerMargin: {
                    left: 20,
                    right: 40
                }
            },
            colors: ['#7fb6ea'],
            responsive: [
           {
            breakpoint: 1000,
            options: {
            plotOptions: {
            bar: {
            horizontal: false
            }
            },
            legend: {
             position: "bottom"
            }
            }
            }
            ]
            };

            var chart = new ApexCharts(document.querySelector("#agechart"),options);

            chart.render();

        var options = {
            chart: {
                width: 350,
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
            series: [

            {
                name: "audience(%)",
                data:     [{{ $data['nomber_followers_1'] }},
                           {{ $data['nomber_followers_2'] }},
                           {{ $data['nomber_followers_3'] }},
                           {{ $data['nomber_followers_4'] }}],

                        }],
            xaxis: {
                categories: ['{{$city_1}}', '{{$city_2}}', '{{$city_3}}', '{{$city_4}}']
            },
            legend: {
                position: "right",
                verticalAlign: "top",
                containerMargin: {
                    left: 35,
                    right: 60
                }
            },
            colors: ['#7fb6ea'],
            responsive: [
           {
            breakpoint: 1000,
            options: {
            plotOptions: {
            bar: {
            horizontal: false
            }
            },
            legend: {
             position: "bottom"
            }
            }
            }
            ]
            };

            var chart = new ApexCharts(document.querySelector("#citiesChart"),options);

            chart.render();



        var options = {
            series: [
                    {{ $data['gender_men'] }},
                    {{ $data['gender_women'] }}


                ],
            chart: {
            width: 350,
            type: 'donut',
            },
            labels: [
                    `Men (%)`,
                    `Women (%)`,


                ],
                colors: [


                    "#7fb6ea",
                    "#fd8075",

                ],
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

        var chart = new ApexCharts(document.querySelector("#genderChart"), options);
        chart.render();






        });


</script>
@endif




@if ($statistic_instagram)
<script>
    $(document).ready(function() {


        var options = {
            series: [
                    {{ $data['instagram_gender_men'] }},
                    {{ $data['instagram_gender_women'] }}


                ],
            chart: {
            width: 350,
            type: 'donut',
            },
            labels: [
                    `Men (%)`,
                    `Women (%)`,


                ],
                colors: [


                    "#7fb6ea",
                    "#fd8075",

                ],
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

        var chart = new ApexCharts(document.querySelector("#instagram_genderChart"), options);
        chart.render();

        var options = {
            chart: {
                width: 350,
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
            series: [

            {
                name: "audience(%)",
                data:     [{{ $data['instagram_nomber_followers_1'] }},
                           {{ $data['instagram_nomber_followers_2'] }},
                           {{ $data['instagram_nomber_followers_3'] }},
                           {{ $data['instagram_nomber_followers_4'] }}],

                        }],
            xaxis: {
                categories: ['{{$instagram_city_1}}', '{{$instagram_city_2}}', '{{$instagram_city_3}}', '{{$instagram_city_4}}']
            },
            legend: {
                position: "right",
                verticalAlign: "top",
                containerMargin: {
                    left: 35,
                    right: 60
                }
            },
            colors: ['#7fb6ea'],
            responsive: [
           {
            breakpoint: 1000,
            options: {
            plotOptions: {
            bar: {
            horizontal: false
            }
            },
            legend: {
             position: "bottom"
            }
            }
            }
            ]
            };

            var chart = new ApexCharts(document.querySelector("#instagram_citiesChart"),options);

            chart.render();

            var options = {

        chart: {
            width: 400,
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
       name: "Men(%)",

        data: [{{ $data['instagram_age_m_13'] }},
         {{ $data['instagram_age_m_18'] }},
         {{ $data['instagram_age_m_25'] }},
         {{ $data['instagram_age_m_35'] }},
         {{ $data['instagram_age_m_45'] }},
         {{ $data['instagram_age_m_55'] }}]
        },

        {
        name: "Women(%)",

        data: [{{ $data['instagram_age_w_13'] }},
         {{ $data['instagram_age_w_18'] }},
         {{ $data['instagram_age_w_25'] }},
         {{ $data['instagram_age_w_35'] }},
         {{ $data['instagram_age_w_45'] }},
         {{ $data['instagram_age_w_55'] }}]
        }
        ],

        xaxis: {
        categories: [
          "13-17",
          "18-24",
          "25-34",
          "35-44",
          "45-54",
          "55-64",
        ]
        },
        legend: {
            position: "left",
            verticalAlign: "top",
            containerMargin: {
            left: 20,
            right: 35
        }

        },
        colors: ['#7fb6ea', '#fd8075'],
        responsive: [
        {
        breakpoint: 1000,
        options: {
        plotOptions: {
        bar: {
        horizontal: false
        }
        },
        legend: {
            position: "bottom"
        }
        }
        }
        ]
        };

        var chart = new ApexCharts(document.querySelector("#instagram_ageMenWomenchart"),options);
        chart.render();

        var options = {
            chart: {
                width: 350,
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
                colors: ["#7fb6ea"]
            },
            series: [

            {
                name: "audience(%)",
                data: [{{ $data['instagram_age_g_13'] }},
                       {{ $data['instagram_age_g_18'] }},
                       {{ $data['instagram_age_g_25'] }},
                       {{ $data['instagram_age_g_35'] }},
                       {{ $data['instagram_age_g_45'] }},
                       {{ $data['instagram_age_g_55'] }}]
            }
            ],
            xaxis: {
            categories: [
                "13-17",
                "18-24",
                "25-34",
                "35-44",
                "45-54",
                "55-64",
            ]
            },
            legend: {
                position: "right",
                verticalAlign: "top",
                containerMargin: {
                    left: 35,
                    right: 60
                }
            },
            colors: ['#7fb6ea'],
            responsive: [
           {
            breakpoint: 1000,
            options: {
            plotOptions: {
            bar: {
            horizontal: false
            }
            },
            legend: {
             position: "bottom"
            }
            }
            }
            ]
            };

            var chart = new ApexCharts(document.querySelector("#instagram_agechart"),options);

            chart.render();







        });


</script>
@endif

@if ($statistic_tiktok)
<script>
    $(document).ready(function() {


        var options = {
            series: [
                    {{ $data['tiktok_gender_men'] }},
                    {{ $data['tiktok_gender_women'] }}


                ],
            chart: {
            width: 350,
            type: 'donut',
            },
            labels: [
                    `Men (%)`,
                    `Women (%)`,


                ],
                colors: [


                    "#7fb6ea",
                    "#fd8075",

                ],
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

        var chart = new ApexCharts(document.querySelector("#tiktok_genderChart"), options);
        chart.render();

        var options = {
            chart: {
                width: 350,
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
            series: [

            {
                name: "audience(%)",
                data:     [{{ $data['tiktok_nomber_followers_1'] }},
                           {{ $data['tiktok_nomber_followers_2'] }},
                           {{ $data['tiktok_nomber_followers_3'] }},
                           {{ $data['tiktok_nomber_followers_4'] }}],

                        }],
            xaxis: {
                categories: ['{{$tiktok_city_1}}', '{{$tiktok_city_2}}', '{{$tiktok_city_3}}', '{{$tiktok_city_4}}']
            },
            legend: {
                position: "right",
                verticalAlign: "top",
                containerMargin: {
                    left: 35,
                    right: 60
                }
            },
            colors: ['#7fb6ea'],
            responsive: [
           {
            breakpoint: 1000,
            options: {
            plotOptions: {
            bar: {
            horizontal: false
            }
            },
            legend: {
             position: "bottom"
            }
            }
            }
            ]
            };

            var chart = new ApexCharts(document.querySelector("#tiktok_citiesChart"),options);

            chart.render();

            var options = {

        chart: {
            width: 400,
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
       name: "Men(%)",

        data: [{{ $data['tiktok_age_m_13'] }},
         {{ $data['tiktok_age_m_18'] }},
         {{ $data['tiktok_age_m_25'] }},
         {{ $data['tiktok_age_m_35'] }},
         {{ $data['tiktok_age_m_45'] }},
         {{ $data['tiktok_age_m_55'] }}]
        },

        {
        name: "Women(%)",

        data: [{{ $data['tiktok_age_w_13'] }},
         {{ $data['tiktok_age_w_18'] }},
         {{ $data['tiktok_age_w_25'] }},
         {{ $data['tiktok_age_w_35'] }},
         {{ $data['tiktok_age_w_45'] }},
         {{ $data['tiktok_age_w_55'] }}]
        }
        ],

        xaxis: {
        categories: [
          "13-17",
          "18-24",
          "25-34",
          "35-44",
          "45-54",
          "55-64",
        ]
        },
        legend: {
            position: "left",
            verticalAlign: "top",
            containerMargin: {
            left: 20,
            right: 35
        }

        },
        colors: ['#7fb6ea', '#fd8075'],
        responsive: [
        {
        breakpoint: 1000,
        options: {
        plotOptions: {
        bar: {
        horizontal: false
        }
        },
        legend: {
            position: "bottom"
        }
        }
        }
        ]
        };

        var chart = new ApexCharts(document.querySelector("#tiktok_ageMenWomenchart"),options);
        chart.render();

        var options = {
            chart: {
                width: 350,
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
                colors: ["#7fb6ea"]
            },
            series: [

            {
                name: "audience(%)",
                data: [{{ $data['tiktok_age_g_13'] }},
                       {{ $data['tiktok_age_g_18'] }},
                       {{ $data['tiktok_age_g_25'] }},
                       {{ $data['tiktok_age_g_35'] }},
                       {{ $data['tiktok_age_g_45'] }},
                       {{ $data['tiktok_age_g_55'] }}]
            }
            ],
            xaxis: {
            categories: [
                "13-17",
                "18-24",
                "25-34",
                "35-44",
                "45-54",
                "55-64",
            ]
            },
            legend: {
                position: "right",
                verticalAlign: "top",
                containerMargin: {
                    left: 35,
                    right: 60
                }
            },
            colors: ['#7fb6ea'],
            responsive: [
           {
            breakpoint: 1000,
            options: {
            plotOptions: {
            bar: {
            horizontal: false
            }
            },
            legend: {
             position: "bottom"
            }
            }
            }
            ]
            };

            var chart = new ApexCharts(document.querySelector("#tiktok_agechart"),options);

            chart.render();







        });


</script>
@endif


@if ($statistic_youtube)
<script>
    $(document).ready(function() {


        var options = {
            series: [
                    {{ $data['youtube_gender_men'] }},
                    {{ $data['youtube_gender_women'] }}


                ],
            chart: {
            width: 350,
            type: 'donut',
            },
            labels: [
                    `Men (%)`,
                    `Women (%)`,


                ],
                colors: [


                    "#7fb6ea",
                    "#fd8075",

                ],
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

        var chart = new ApexCharts(document.querySelector("#youtube_genderChart"), options);
        chart.render();

        var options = {
            chart: {
                width: 350,
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
            series: [

            {
                name: "audience(%)",
                data:     [{{ $data['youtube_nomber_followers_1'] }},
                           {{ $data['youtube_nomber_followers_2'] }},
                           {{ $data['youtube_nomber_followers_3'] }},
                           {{ $data['youtube_nomber_followers_4'] }}],

                        }],
            xaxis: {
                categories: ['{{$youtube_city_1}}', '{{$youtube_city_2}}', '{{$youtube_city_3}}', '{{$youtube_city_4}}']
            },
            legend: {
                position: "right",
                verticalAlign: "top",
                containerMargin: {
                    left: 35,
                    right: 60
                }
            },
            colors: ['#7fb6ea'],
            responsive: [
           {
            breakpoint: 1000,
            options: {
            plotOptions: {
            bar: {
            horizontal: false
            }
            },
            legend: {
             position: "bottom"
            }
            }
            }
            ]
            };

            var chart = new ApexCharts(document.querySelector("#youtube_citiesChart"),options);

            chart.render();

            var options = {

        chart: {
            width: 400,
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
       name: "Men(%)",

        data: [{{ $data['youtube_age_m_13'] }},
         {{ $data['youtube_age_m_18'] }},
         {{ $data['youtube_age_m_25'] }},
         {{ $data['youtube_age_m_35'] }},
         {{ $data['youtube_age_m_45'] }},
         {{ $data['youtube_age_m_55'] }}]
        },

        {
        name: "Women(%)",

        data: [{{ $data['youtube_age_w_13'] }},
         {{ $data['youtube_age_w_18'] }},
         {{ $data['youtube_age_w_25'] }},
         {{ $data['youtube_age_w_35'] }},
         {{ $data['youtube_age_w_45'] }},
         {{ $data['youtube_age_w_55'] }}]
        }
        ],

        xaxis: {
        categories: [
          "13-17",
          "18-24",
          "25-34",
          "35-44",
          "45-54",
          "55-64",
        ]
        },
        legend: {
            position: "left",
            verticalAlign: "top",
            containerMargin: {
            left: 20,
            right: 35
        }

        },
        colors: ['#7fb6ea', '#fd8075'],
        responsive: [
        {
        breakpoint: 1000,
        options: {
        plotOptions: {
        bar: {
        horizontal: false
        }
        },
        legend: {
            position: "bottom"
        }
        }
        }
        ]
        };

        var chart = new ApexCharts(document.querySelector("#youtube_ageMenWomenchart"),options);
        chart.render();

        var options = {
            chart: {
                width: 350,
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
                colors: ["#7fb6ea"]
            },
            series: [

            {
                name: "audience(%)",
                data: [{{ $data['youtube_age_g_13'] }},
                       {{ $data['youtube_age_g_18'] }},
                       {{ $data['youtube_age_g_25'] }},
                       {{ $data['youtube_age_g_35'] }},
                       {{ $data['youtube_age_g_45'] }},
                       {{ $data['youtube_age_g_55'] }}]
            }
            ],
            xaxis: {
            categories: [
                "13-17",
                "18-24",
                "25-34",
                "35-44",
                "45-54",
                "55-64",
            ]
            },
            legend: {
                position: "right",
                verticalAlign: "top",
                containerMargin: {
                    left: 35,
                    right: 60
                }
            },
            colors: ['#7fb6ea'],
            responsive: [
           {
            breakpoint: 1000,
            options: {
            plotOptions: {
            bar: {
            horizontal: false
            }
            },
            legend: {
             position: "bottom"
            }
            }
            }
            ]
            };

            var chart = new ApexCharts(document.querySelector("#youtube_agechart"),options);

            chart.render();







        });


</script>
@endif
<script>



</script>
@endpush
