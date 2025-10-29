
<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->getPageTitle($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/bootstrap-toggle.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}">


    @stack('style')
</head>

<body>

                <div class="modal2" >

                    <div class="modal-wrapper js-modal-stop ">
                        <h1 id="titlemodal">Statistics</h1>
                        @if(session()->has('error'))
                                                <div class="alert alert-danger" role="alert">
                                                {{ session()->get('error') }}
                                                    </div>

                                                @endif
                        <div class="account-content ">
                            <div class="d-flex justify-content-between flex-wrap gap-3 pb-5">
                            </div>

                            <form method="POST" action="{{ localized_route('admin.statisticsInf',[$influencer->id])}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="social">@lang('Social Links')</label>
                                            <select name="social" class="form-select form--control" required>
                                                <option value="facebook">
                                                    <label class="form-check-label" for="facebook">
                                                        @lang('Facebook')
                                                    </label>
                                                   <i style="color: #3b5998;" class="fab fa-facebook-f fa-lg"></i>
                                                </option>
                                                <option value="instagram">@lang('Instagram')</option>
                                                <option value="tiktok">@lang('TikTok')</option>
                                                <option value="youtube">@lang('YouTube')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="followers">@lang('Followers')</label>
                                            <input type="text" name="followers" id="followers" value="" class="form-control form--control checkUser" required>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="average_interactions">@lang('Average interactions')</label>
                                            <input type="text" name="average_interactions" id="average_interactions" value="" class="form-control form--control checkUser" required>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="gender">@lang('Gender')</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="gender_men">@lang('')</label>
                                            <input type="" min="0" max="100" name="gender_men" id="gender_men" value="" class="form-control form--control checkUser" placeholder="Man" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="gender_women">@lang('')</label>
                                            <input type="" min="0" max="100" name="gender_women" id="gender_women" value="" class="form-control form--control checkUser" placeholder="Woman" required>
                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="gender">@lang('Global Age')</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="">@lang('')</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_g_13">@lang('')</label>
                                            <input type="text" name="age_g_13" id="age_g_13" value="" class="form-control form--control checkUser" placeholder="13-17" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_g_18">@lang('')</label>
                                            <input type="text" name="age_g_18" id="age_g_18" value="" class="form-control form--control checkUser" placeholder="18-24" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_g_25">@lang('')</label>
                                            <input type="text" name="age_g_25" id="age_g_25" value="" class="form-control form--control checkUser" placeholder="25-34" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_g_35">@lang('')</label>
                                            <input type="text" name="age_g_35" id="age_g_35" value="" class="form-control form--control checkUser" placeholder="35-44" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_g_45">@lang('')</label>
                                            <input type="text" name="age_g_45" id="age_g_45" value="" class="form-control form--control checkUser" placeholder="45-54" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_g_55">@lang('')</label>
                                            <input type="text" name="age_g_55" id="age_g_55" value="" class="form-control form--control checkUser" placeholder="55-64" required>
                                        </div>

                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="">@lang('Men Age')</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="">@lang('')</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_m_13">@lang('')</label>
                                            <input type="text" name="age_m_13" id="age_m_13" value="" class="form-control form--control checkUser" placeholder="13-17" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_m_18">@lang('')</label>
                                            <input type="text" name="age_m_18" id="age_m_18" value="" class="form-control form--control checkUser" placeholder="18-24" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_m_25">@lang('')</label>
                                            <input type="text" name="age_m_25" id="age_m_25" value="" class="form-control form--control checkUser" placeholder="25-34" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_m_35">@lang('')</label>
                                            <input type="text" name="age_m_35" id="age_m_35" value="" class="form-control form--control checkUser" placeholder="35-44" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_m_45">@lang('')</label>
                                            <input type="text" name="age_m_45" id="age_m_45" value="" class="form-control form--control checkUser" placeholder="45-54" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_m_55">@lang('')</label>
                                            <input type="text" name="age_m_55" id="age_m_55" value="" class="form-control form--control checkUser" placeholder="55-64" required>
                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="">@lang('Women Age')</label>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="">@lang('')</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_w_13">@lang('')</label>
                                            <input type="text" name="age_w_13" id="age_w_13" value="" class="form-control form--control checkUser" placeholder="13-17" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_w_18">@lang('')</label>
                                            <input type="text" name="age_w_18" id="age_w_18" value="" class="form-control form--control checkUser" placeholder="18-24" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_w_25">@lang('')</label>
                                            <input type="text" name="age_w_25" id="age_w_25" value="" class="form-control form--control checkUser" placeholder="25-34" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_w_35">@lang('')</label>
                                            <input type="text" name="age_w_35" id="age_w_35" value="" class="form-control form--control checkUser" placeholder="35-44" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_w_45">@lang('')</label>
                                            <input type="text" name="age_w_45" id="age_w_45" value="" class="form-control form--control checkUser" placeholder="45-54" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="age_w_55">@lang('')</label>
                                            <input type="text" name="age_w_55" id="age_w_55" value="" class="form-control form--control checkUser" placeholder="55-64" required>
                                        </div>

                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="cities">@lang('Principal cities')</label>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="">@lang('')</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="city_1">@lang('')</label>
                                            {{-- <input type="text" name="city_1" id="city_1" value="" class="form-control form--control checkUser" placeholder="city 1" required> --}}
                                            <select data-live-search="true"  class="form-control" name="city_1" >
                                                {{-- <option value="">@lang('Wilaya')</option> --}}
                                                @foreach ($wilayas as $w)
                                                                                    <option value="{{ $w->name }}">
                                                                                        {!! htmlspecialchars($w->name)!!}

                                                                                    </option>
                                                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="nomber_followers">@lang('')</label>
                                            <input type="text" name="nomber_followers_1" id="nomber_followers_1" value="" class="form-control form--control checkUser" placeholder="nomber followers" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- <div class="form-group">
                                            <label class="form-label" for="city_2">@lang('')</label>
                                            <input type="text" name="city_2" id="city_2" value="" class="form-control form--control checkUser" placeholder="city 2" required>
                                        </div> --}}
                                        <div class="form-group">
                                            <label class="form-label" for="city_2">@lang('')</label>
                                            {{-- <input type="text" name="city_1" id="city_1" value="" class="form-control form--control checkUser" placeholder="city 1" required> --}}
                                            <select data-live-search="true"  class="form-control" name="city_2" >
                                                {{-- <option value="">@lang('Wilaya')</option> --}}
                                                @foreach ($wilayas as $w)
                                                                                    <option value="{{ $w->name }}">
                                                                                        {!! htmlspecialchars($w->name)!!}

                                                                                    </option>
                                                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="nomber_followers">@lang('')</label>
                                            <input type="text" name="nomber_followers_2" id="nomber_followers_2" value="" class="form-control form--control checkUser" placeholder="nomber followers" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- <div class="form-group">
                                            <label class="form-label" for="city_3">@lang('')</label>
                                            <input type="text" name="city_3" id="city_3" value="" class="form-control form--control checkUser" placeholder="city 3" required>
                                        </div> --}}
                                        <div class="form-group">
                                            <label class="form-label" for="city_3">@lang('')</label>
                                            {{-- <input type="text" name="city_1" id="city_1" value="" class="form-control form--control checkUser" placeholder="city 1" required> --}}
                                            <select data-live-search="true"  class="form-control" name="city_3" >
                                                {{-- <option value="">@lang('Wilaya')</option> --}}
                                                @foreach ($wilayas as $w)
                                                                                    <option value="{{ $w->name }}">
                                                                                        {!! htmlspecialchars($w->name)!!}

                                                                                    </option>
                                                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="nomber_followers">@lang('')</label>
                                            <input type="text" name="nomber_followers_3" id="nomber_followers_3" value="" class="form-control form--control checkUser" placeholder="nomber followers" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- <div class="form-group">
                                            <label class="form-label" for="city_4">@lang('')</label>
                                            <input type="text" name="city_4" id="city_4" value="" class="form-control form--control checkUser" placeholder="city 4" required>
                                        </div> --}}
                                        <div class="form-group">
                                            <label class="form-label" for="city_4">@lang('')</label>
                                            {{-- <input type="text" name="city_1" id="city_1" value="" class="form-control form--control checkUser" placeholder="city 1" required> --}}
                                            <select data-live-search="true"  class="form-control" name="city_4" >
                                                {{-- <option value="">@lang('Wilaya')</option> --}}
                                                @foreach ($wilayas as $w)
                                                                                    <option value="{{ $w->name }}">
                                                                                        {!! htmlspecialchars($w->name)!!}

                                                                                    </option>
                                                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="nomber_followers">@lang('')</label>
                                            <input type="number" step="any" name="nomber_followers_4" id="nomber_followers_4" value="" class="form-control form--control checkUser" placeholder="nomber followers" required>
                                        </div>
                                    </div>


                                        <a href=""><button type="submit" class="btn btn-primary">Submit</button></a>


                                </div>
                            </form>
                                <div class="col text-center">
                                    <a href="{{ localized_route('admin.influencers.active')}}"><button class="btn btn-primary">Close</button></a>
                                </div>
                        </div>

                    </div>
                </div>




    <style>
        *{
            box-sizing: border-box;
        }
       .modal2{
        position: fixed;
        display: flex;
        align-items: center;
        justify-content:center;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);

       }
       .modal-wrapper{
        overflow:auto;
        width: 600px;
        height : 600px;
        max-width: calc(100vw - 20px);
        max-height: calc(100vw - 20px);
        padding: 20px;
        background-color: #FFF;


       }

    </style>



</body>

</html>
