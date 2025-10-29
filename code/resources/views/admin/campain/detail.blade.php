@extends('admin.layouts.app')
@section('panel')
<div class="row setup-content" >
    <div class="col-xs-12">
        <div class="col-md-12">


            <table class="table">
                <tr>
                    <td>شعار الشركة</td>
                    <td>
                        @if($campain->company_logo )
                        @if((str_contains($campain->company_logo ,"files/")))
                        {{-- <img src="{{asset($campain->company_logo )}}" width="120" height="90"> --}}
                        <img src="{{'https://vaguy.app/youcef_test/public/'.$campain->company_logo}}" width="120" height="90">
                        @else
                            <br>
                            {{-- @if(isset($campain->company_principal_image)) --}}
                        <img src="{{$campain->company_logo->temporaryUrl()}}" width="120" height="90">
                           {{-- @endif --}}
                        @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>اسم الشركة</td>
                    <td><strong>{{$campain->company_name}}</strong></td>
                </tr>
                <tr>
                    <td>وصف الشركة</td>
                    <td><strong>{{$campain->company_desc}}</strong></td>
                </tr>
                <tr>
                    <td>Company prinicipal image</td>
                    <td>
                        @if($campain->company_principal_image)
                        @if((str_contains($campain->company_principal_image,"files/")))
                        {{-- <img src="{{asset($campain->company_principal_image)}}" width="120" height="90"> --}}
                        <img src="{{'https://vaguy.app/youcef_test/public/'.$campain->company_principal_image}}" width="120" height="90">

                        @else
                            <br>
                            {{-- @if(isset($campain->company_principal_image)) --}}
                        <img src="{{$campain->company_principal_image->temporaryUrl()}}" width="120" height="90">
                           {{-- @endif --}}
                        @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Company prinicipal category</td>
                    <td>
                        {{\App\Models\Category::find($campain->company_principal_category)->name ?? null}}
                    </td>
                </tr>
                <tr>
                    <td>Company web url</td>
                    <td>
                       <a href=" {{$campain->company_web_url}}" target="_blank">
                         {{$campain->company_web_url}}</a>
                    </td>
                </tr>
                <tr>
                    <td>Campain name</td>
                    <td>
                        {{$campain->campain_name}}
                    </td>
                </tr>
                <tr>
                    <td>Campain objective</td>
                    <td>
                        {{$campain->campain_objective}}
                    </td>
                </tr>
                <tr>
                    <td>Campain details</td>
                    <td>
                        {{$campain->campain_details}}
                    </td>
                </tr>
                <tr>
                    <td>Campain want</td>
                    <td>
                        {{$campain->campain_want}}
                    </td>
                </tr>
                <tr>
                    <td>Photos required</td>
                    <td>
                    @if ($campain->campain_photos_required)
                    @php
                        $phs=json_decode($campain->campain_photos_required,false);
                    @endphp
                        @if(str_contains($phs[0],"files/"))
                              @for ($i=0 ; $i<count($phs); $i++)
                                {{-- <img src="{{asset($phs[$i])}}" width="120" height="90"> --}}
                                <img src="{{'https://vaguy.app/youcef_test/public/'.$phs[$i]}}" width="120" height="90">

                              @endfor
                        @else
                                <br>
                              @for ($i=0 ; $i<count($phs); $i++)
                                 <img src="{{$phs[$i]->temporaryUrl()}}" width="120" height="90">

                              @endfor
                        @endif
                    @endif
                    </td>
                </tr>
                <tr>
                    <td>Campain social media</td>
                    <td>
                        {{$campain->campain_social_media}}
                    </td>
                </tr>
                <tr>
                    <td>Campain social content</td>
                    <td>
                        {{$campain->campain_social_media_content}}
                    </td>
                </tr>
                <tr>
                    <td>Campain publishing requirement</td>
                    <td>
                       @php
                         $a=json_decode($campain->campain_publishing_requirement);
                         $do_this=$a[0];
                         $dont_do_this=$a[1];
                        @endphp
                        {{$do_this}}
                        <br>
                        {{$dont_do_this}}
                    </td>
                </tr>
                <tr>
                    <td>Campain start date</td>
                    <td>
                        {{$campain->campain_start_date}}
                    </td>
                </tr>
                <tr>
                    <td>Campain end date</td>
                    <td>
                        {{$campain->campain_end_date}}
                    </td>
                </tr>
                <tr>
                    <td>influencer age range</td>
                    <td>
                        {{$campain->influencer_age_range}}
                    </td>
                </tr>
                <tr>
                    <td>influencer category</td>
                    <td>
                        {{$campain->influencer_category}}
                    </td>
                </tr>
                <tr>
                    <td>influencer gender</td>
                    <td>
                        {{$campain->influencer_gender}}
                    </td>
                </tr>
                <tr>
                    <td>influencer age</td>
                    <td>
                        {{$campain->influencer_age}}
                    </td>
                </tr>
                <tr>
                    <td>influencer wilaya</td>
                    <td>
                        {{$campain->influencer_wilaya}}
                    </td>
                </tr>
                <tr>
                    <td>influencer interests</td>
                    <td>
                        {{$campain->influencer_interests}}
                    </td>
                </tr>
                <tr>
                    <td>influencer public gender</td>
                    <td>
                        {{$campain->influencer_public_gender}}
                    </td>
                </tr>
                <tr>
                    <td>influencer public age</td>
                    <td>
                        {{$campain->influencer_public_age}}
                    </td>
                </tr>
                <tr>
                    <td>influencer public wilayas</td>
                    <td>
                        {{$campain->influencer_public_wilaya}}
                    </td>
                </tr>
            </table>


        </div>
    </div>
</div>
@endsection
