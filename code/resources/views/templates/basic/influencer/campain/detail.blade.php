@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="row setup-content" >
    <div class="col-xs-12">
        <div class="col-md-12">


            <table class="table">
                <tr>
                    <td>@lang('شعار الشركة')</td>
                    <td>
                        @if($campain->company_logo )
                        @if((str_contains($campain->company_logo ,"files/")))
                        {{-- <img src="{{asset($campain->company_logo )}}" width="120" height="90"> --}}
                        <img src="{{env('APP_URL').'/youcef_test/public/'.$campain->company_logo}}" width="120" height="90">
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
                    <td>@lang('اسم الشركة')</td>
                    <td><strong>{{$campain->company_name}}</strong></td>
                </tr>
                <tr>
                    <td>@lang('وصف الشركة')</td>
                    <td><strong>{{$campain->company_desc}}</strong></td>
                </tr>
                <tr>
                    <td>@lang('الصورة الرئيسية الشركة')</td>
                    <td>
                        @if($campain->company_principal_image)
                        @if((str_contains($campain->company_principal_image,"files/")))
                        {{-- <img src="{{asset($campain->company_principal_image)}}" width="120" height="90"> --}}
                        <img src="{{env('APP_URL').'/youcef_test/public/'.$campain->company_principal_image}}" width="120" height="90">

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
                    <td>@lang('فئة الشركة الرئيسية')</td>
                    <td>
                        {{\App\Models\Category::find($campain->company_principal_category)->name ?? null}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('موقع الشركة')</td>
                    <td>
                       <a href=" {{$campain->company_web_url}}" target="_blank">
                         {{$campain->company_web_url}}</a>
                    </td>
                </tr>
                <tr>
                    <td>@lang('اسم الحملة')</td>
                    <td>
                        {{$campain->campain_name}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('هدف الحملة')</td>
                    <td>
                        {{$campain->campain_objective}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('تفاصيل الحملة')</td>
                    <td>
                        {{$campain->campain_details}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('أهداف الحملة')</td>
                    <td>
                        {{$campain->campain_want}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('الصور المطلوبة')</td>
                    <td>
                    @if ($campain->campain_photos_required)
                    @php
                        $phs=json_decode($campain->campain_photos_required,false);
                    @endphp
                        @if(str_contains($phs[0],"files/"))
                              @for ($i=0 ; $i<count($phs); $i++)
                                {{-- <img src="{{asset($phs[$i])}}" width="120" height="90"> --}}
                                <img src="{{env('APP_URL').'/youcef_test/public/'.$phs[$i]}}" width="120" height="90">

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
                    <td> @lang('وسائل التواصل الاجتماعي')</td>
                    <td>
                        {{$campain->campain_social_media}}
                    </td>
                </tr>
                <tr>
                    <td> @lang('محتوى وسائل التواصل الاجتماعي')</td>
                    <td>
                        {{$campain->campain_social_media_content}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('متطلبات نشر الحملة')</td>
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
                    <td>@lang('تاريخ بدء الحملة')</td>
                    <td>
                        {{$campain->campain_start_date}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('تاريخ نهاية الحملة')</td>
                    <td>
                        {{$campain->campain_end_date}}
                    </td>
                </tr>

            </table>


        </div>
    </div>
</div>

<br><br>
@php
    $off=\App\Models\CampainInfluencerOffer::where('campain_id',$campain->id )
                                           ->where('influencer_id',authInfluencerId())
                                           ->first();
    $som= isset($off) ? $off->price : 0;

@endphp
@if(isset($off->price))
<br>
<b>@lang('اقتراحك لهذه الحملة هو'): {{$off->price }}  @lang('دج')</b>
@endif

@if(!isset($off))
<div class="card custom--card">
    <div class="card-body">
        <form action="{{ localized_route('influencer.campain.post_offer') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="campain_id" value="{{ $campain->id }}">
            {{-- <input type="hidden" name="influencer_id" value="{{ $camapain->id }}"> --}}
            <div class="row">
                <label for="offer">@lang('اقترح عرضك')</label>
                <div class="col-md-7">

                <input
                    type="number"
                    name="offer"
                    id="offer"
                    min="0"
                    required
                    class="form-control"
                    >
                </div>
                <div class="col-md-4">
                <button type="submit" class="btn btn-primary">@lang('اقترح')</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@if(isset($off) && $off->status == 1)
<div class="card custom--card">
    <div class="card-body">

        <div class="row">
            <div class="col-md-7">
                <span>
                   @lang('تم قبول عرضك، يرجى تأكيد العرض')
                </span>
            </div>
            <div class="col-md-4">
                <form action="{{ localized_route('influencer.campain.confirm_offer') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="campain_id" value="{{ $campain->id }}">
                    <input type="hidden" name="status" value="3">

                    {{-- <input type="hidden" name="influencer_id" value="{{ $camapain->id }}"> --}}
                    <div class="row">

                        <button type="submit" class="btn btn-success">@lang('تأكيد')</button>
                        </div>
                    </div>
                </form>
        </div>
        </div>

    </div>
</div>
<br> <br>
@endif
@if(isset($off) && $off->status == 3)
<div class="card custom--card">
    <div class="card-body">

        <div class="row">
            <div class="col-md-7">
                <span>
                    @lang('تم قبول عرضك، يرجى تأكيد العرض')
                </span>
            </div>
            <div class="col-md-4">
                <form action="{{ localized_route('influencer.campain.confirm_offer') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="campain_id" value="{{ $campain->id }}">
                    <input type="hidden" name="status" value="4">

                    {{-- <input type="hidden" name="influencer_id" value="{{ $camapain->id }}"> --}}
                    <div class="row">

                        <button type="submit" class="btn btn-success">@lang('Job Done')</button>
                        </div>
                    </div>
                </form>
        </div>
        </div>

    </div>
</div>
@endif
@if(isset($off) && $off->status == 4)
<div class="card custom--card">
    <div class="card-body">

        <div class="row">
            <div class="col-md-7">
                <span>
                    @lang('تحميل النتائج')
                </span>
            </div>
            <div class="col-md-4">
                <form action="{{ localized_route('influencer.campain.upload_result') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="file" name="campain_result_files[]" multiple required>
						<input type="hidden" name="campain_id" value="{{ $campain->id }}">

                    <input type="hidden" name="status" value="4" required>
                    <small class="text--warning">@lang('Supported files'): @lang('jpeg'), @lang('jpg'), @lang('png').</small>

                    </div>
                    {{-- <input type="hidden" name="influencer_id" value="{{ $camapain->id }}"> --}}
                    <div class="row">

                        <button type="submit" class="btn btn-info">@lang('تحميل')</button>
                        </div>
                    </div>
                </form>
        </div>
        </div>

    </div>
@endif

@endsection
