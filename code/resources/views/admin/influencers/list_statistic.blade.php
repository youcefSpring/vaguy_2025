@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Plateforme')</th>
                                    <th>@lang('Followers')</th>
                                    <th>@lang('Average interactions')</th>

                                    <th>@lang('Gender(men)')</th>
                                    <th>@lang('Gender(women)')</th>
                                    <th>@lang('Globale age(13-17)')</th>
                                    <th>@lang('Globale age(18-24)')</th>
                                    <th>@lang('Globale age(25-34)')</th>
                                    <th>@lang('Globale age(35-44)')</th>
                                    <th>@lang('Globale age(45-54)')</th>
                                    <th>@lang('Globale age(55-64)')</th>

                                    <th>@lang('Men age(13-17)')</th>
                                    <th>@lang('Men age(18-24)')</th>
                                    <th>@lang('Men age(25-34)')</th>
                                    <th>@lang('Men age(35-44)')</th>
                                    <th>@lang('Men age(45-54)')</th>
                                    <th>@lang('Men age(55-64)')</th>

                                    <th>@lang('Women age(13-17)')</th>
                                    <th>@lang('Women age(18-24)')</th>
                                    <th>@lang('Women age(25-34)')</th>
                                    <th>@lang('Women age(35-44)')</th>
                                    <th>@lang('Women age(45-54)')</th>
                                    <th>@lang('Women age(55-64)')</th>

                                    <th>@lang('City 1(55-64)')</th>
                                    <th>@lang('Followers 1(55-64)')</th>
                                    <th>@lang('City 2(55-64)')</th>
                                    <th>@lang('Followers 2(55-64)')</th>
                                    <th>@lang('City 3(55-64)')</th>
                                    <th>@lang('Followers 3(55-64)')</th>
                                    <th>@lang('City 4(55-64)')</th>
                                    <th>@lang('Followers 4(55-64)')</th>
                                    <th>@lang('Action')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statistics  as $statistic)
                                <tr>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->social}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->followers}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->average_interactions}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->gender_men}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->gender_women}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_g_13}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_g_18}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_g_25}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_g_35}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_g_45}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_g_55}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_m_13}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_m_18}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_m_25}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_m_35}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_m_45}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_m_55}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_w_13}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_w_18}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_w_25}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_w_35}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_w_45}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->age_w_55}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->city_1}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->nomber_followers_1}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->city_2}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->nomber_followers_2}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->city_3}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->nomber_followers_3}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->city_4}}</span>
                                    </td>
                                    <td data-label="@lang('Influencer')">
                                        <span class="fw-bold">{{@$statistic->nomber_followers_4}}</span>
                                    </td>
                                    <td data-label="@lang('Action')">
                                        {{-- <a href="" class="btn btn-sm btn-outline--primary">
                                            <i class="las fas fa-edit text--shadow"></i> @lang('Edit')
                                        </a> --}}

                                                <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#filter-infu{{$statistic->id}}">
                                                    <i class="las fas fa-edit text--shadow"></i> @lang('Edit')
                                                </button>

                                        </div>
                                        <div class="modal filter" tabindex="-1" id="filter-infu{{$statistic->id}}">
                                            <div class="filter-modal modal-dialog">
                                                <div class="modal-content">

                                                     <div class="modal-header">
                                                         <h5 class="modal-title">Edit Statistics ({{ $statistic->social}} )</h5>
                                                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                     </div>
                                                 <div class="modal-body">

                                                     <div class="content d-flex justify-content-between dash-sidebar filter-sidebar p-xl-0 flex-wrap gap-4 shadow-none">


                                                        <form method="POST" action="{{ localized_route('statisticInfluencersUpdate',$statistic->id)}}">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="row">

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="followers">@lang('Followers')</label>
                                                                        <input type="number" step="any" name="followers" value="{{ $statistic->followers }}" id="followers" value="" class="form-control form--control checkUser" required>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="average_interactions">@lang('Average interactions')</label>
                                                                        <input type="text" name="average_interactions" value="{{ $statistic->average_interactions }}" id="average_interactions" value="" class="form-control form--control checkUser" required>

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
                                                                        <input type="number" step="any" min="0" max="100" value="{{ $statistic->gender_men }}" name="gender_men" id="gender_men" value="" class="form-control form--control checkUser" placeholder="Man" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="gender_women">@lang('')</label>
                                                                        <input type="number" step="any" min="0" max="100" value="{{ $statistic->gender_women }}" name="gender_women" id="gender_women" value="" class="form-control form--control checkUser" placeholder="Woman" required>
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
                                                                        <input type="text" name="age_g_13" id="age_g_13"  value="{{ $statistic->age_g_13 }}" class="form-control form--control checkUser" placeholder="13-17" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_g_18">@lang('')</label>
                                                                        <input type="text" name="age_g_18" id="age_g_18" value="{{ $statistic->age_g_18 }}"class="form-control form--control checkUser" placeholder="18-24" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_g_25">@lang('')</label>
                                                                        <input type="text" name="age_g_25" id="age_g_25" value="{{ $statistic->age_g_25 }}"class="form-control form--control checkUser" placeholder="25-34" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_g_35">@lang('')</label>
                                                                        <input type="text" name="age_g_35" id="age_g_35" value="{{ $statistic->age_g_35 }}"class="form-control form--control checkUser" placeholder="35-44" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_g_45">@lang('')</label>
                                                                        <input type="text" name="age_g_45" id="age_g_45" value="{{ $statistic->age_g_45 }}" class="form-control form--control checkUser" placeholder="45-54" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_g_55">@lang('')</label>
                                                                        <input type="text" name="age_g_55" id="age_g_55" value="{{ $statistic->age_g_55 }}" class="form-control form--control checkUser" placeholder="55-64" required>
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
                                                                        <input type="text" name="age_m_13" id="age_m_13" value="{{ $statistic->age_m_13 }}" value="" class="form-control form--control checkUser" placeholder="13-17" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_m_18">@lang('')</label>
                                                                        <input type="text" name="age_m_18" id="age_m_18" value="{{ $statistic->age_m_18}}" class="form-control form--control checkUser" placeholder="18-24" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_m_25">@lang('')</label>
                                                                        <input type="text" name="age_m_25" id="age_m_25" value="{{ $statistic->age_m_25}}" class="form-control form--control checkUser" placeholder="25-34" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_m_35">@lang('')</label>
                                                                        <input type="text" name="age_m_35" id="age_m_35" value="{{ $statistic->age_m_35}}" class="form-control form--control checkUser" placeholder="35-44" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_m_45">@lang('')</label>
                                                                        <input type="text" name="age_m_45" id="age_m_45" value="{{ $statistic->age_m_45}}" class="form-control form--control checkUser" placeholder="45-54" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_m_55">@lang('')</label>
                                                                        <input type="text" name="age_m_55" id="age_m_55" value="{{ $statistic->age_m_55}}" class="form-control form--control checkUser" placeholder="55-64" required>
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
                                                                        <input type="text" name="age_w_13" id="age_w_13" value="{{ $statistic->age_w_13}}" class="form-control form--control checkUser" placeholder="13-17" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_w_18">@lang('')</label>
                                                                        <input type="text" name="age_w_18" id="age_w_18" value="{{ $statistic->age_w_18}}" class="form-control form--control checkUser" placeholder="18-24" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_w_25">@lang('')</label>
                                                                        <input type="text" name="age_w_25" id="age_w_25" value="{{ $statistic->age_w_25}}" class="form-control form--control checkUser" placeholder="25-34" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_w_35">@lang('')</label>
                                                                        <input type="text" name="age_w_35" id="age_w_35" value="{{ $statistic->age_w_35}}" class="form-control form--control checkUser" placeholder="35-44" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_w_45">@lang('')</label>
                                                                        <input type="text" name="age_w_45" id="age_w_45"  value="{{ $statistic->age_w_45}}" class="form-control form--control checkUser" placeholder="45-54" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="age_w_55">@lang('')</label>
                                                                        <input type="text" name="age_w_55" id="age_w_55"  value="{{ $statistic->age_w_55}}" class="form-control form--control checkUser" placeholder="55-64" required>
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
                                                                        <input type="text" name="city_1" id="city_1"  value="{{ $statistic->city_1}}" class="form-control form--control checkUser" placeholder="city 1" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="nomber_followers">@lang('')</label>
                                                                        <input type="text" name="nomber_followers_1" id="nomber_followers_1" value="{{ $statistic->nomber_followers_1}}" class="form-control form--control checkUser" placeholder="nomber followers" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="city_2">@lang('')</label>
                                                                        <input type="text" name="city_2" id="city_2" value="{{ $statistic->city_2}}" class="form-control form--control checkUser" placeholder="city 2" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="nomber_followers">@lang('')</label>
                                                                        <input type="text" name="nomber_followers_2" id="nomber_followers_2" value="{{ $statistic->nomber_followers_2}}" class="form-control form--control checkUser" placeholder="nomber followers" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="city_3">@lang('')</label>
                                                                        <input type="text" name="city_3" id="city_3" value="{{ $statistic->city_3}}" class="form-control form--control checkUser" placeholder="city 3" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="nomber_followers">@lang('')</label>
                                                                        <input type="text" name="nomber_followers_3" id="nomber_followers_3" value="{{ $statistic->nomber_followers_3}}" class="form-control form--control checkUser" placeholder="nomber followers" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="city_4">@lang('')</label>
                                                                        <input type="text" name="city_4" id="city_4" value="{{ $statistic->city_4}}" class="form-control form--control checkUser" placeholder="city 4" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="nomber_followers">@lang('')</label>
                                                                        <input type="number" step="any" step="any" name="nomber_followers_4" id="nomber_followers_4" value="{{ $statistic->nomber_followers_4}}"class="form-control form--control checkUser" placeholder="nomber followers" required>
                                                                    </div>
                                                                </div>



                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Update Statistics</button>
                                                                    </div>


                                                            </div>
                                                        </form>




                                                 </div>

                                                 </div>


                                             </div>
                                         </div>
                                         </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end">
        <form action="" method="GET" class="form-inline">
            <div class="input-group justify-content-end">
                <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Search here')" value="{{ request()->search }}">
                <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>
@endpush

