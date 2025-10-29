@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-section">
        <div class="container">
            <div class="row gy-4 gy-sm-5">
                <div class="col-lg-12">
                    <div class="dashboard-body">
                        <div class="card custom--card influencer-profile-edit d-none">
                            <div class="card-body has-select2">
                                <form action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row gy-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="profile-thumb text-center">
                                                    <div class="thumb">
                                                        <img id="upload-img" src="{{ getImage(getFilePath('influencerProfile') . '/' . $influencer->image, getFileSize('influencerProfile'), true) }}" alt="userProfile">
                                                        <label class="badge badge--icon badge--fill-base update-thumb-icon" for="update-photo"><i class="las la-pen"></i></label>
                                                    </div>
                                                    <div class="profile__info">
                                                        <input type="file" name="image" class="form-control d-none" id="update-photo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label for="firstname" class="col-form-label">@lang('الاسم')</label>
                                                    <input type="text" class="form-control form--control" id="firstname" name="firstname" value="{{ __($influencer->firstname) }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label for="lastname" class="col-form-label">@lang('اللقب')</label>
                                                    <input type="text" class="form-control form--control" id="lastname" name="lastname" value="{{ __($influencer->lastname) }}">
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label for="professional-headline" class="col-form-label">@lang('المهنة')</label>
                                                    <input type="text" class="form-control form--control" id="professional-headline" name="profession" value="{{ __($influencer->profession) }}">
                                                </div>


                                                @php
                                                    $categoryId = [];
                                                    foreach (@$influencer->categories as $category) {
                                                        $categoryId[] = $category->id;
                                                    }
                                                @endphp

                                                <div class="form-group col-sm-12">
                                                    <label for="professional-headline" class="col-form-label">@lang('الفئة')</label>
                                                    <select name="category[]" class="from--control form-control select2-multi-select form-select" multiple>

                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @if (in_array($category->id, $categoryId)) selected @endif>{{ __($category->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-sm-12">
                                                    <label for="summary" class="col-form-label">@lang('ملخص')</label>
                                                    <textarea name="summary" id="summary" class="form-control form--control">{{ br2nl($influencer->summary) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-end mt-3">
                                        <button type="button" class="btn btn--dark btn--md cancelBtn">@lang('الغاء')</button>
                                        <button type="submit" class="btn btn--base btn--md">@lang('حفظ')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="influencer-profile-wrapper influencer-profile">
                            <div class="d-flex justify-content-between flex-wrap gap-4">
                                <div class="left">
                                    <div class="profile">
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('influencerProfile') . '/' . $influencer->image, getFileSize('influencerProfile'), true) }}" alt="profile thumb">
                                        </div>
                                        <div class="content">
                                            <h5 class="fw-medium name account-status d-inline-block">{{ __($influencer->fullname) }}</h5>
                                            <h3 class="title fw-normal">{{ __($influencer->profession) }}</h3>

                                            <ul class="list d-flex flex-wrap">
                                                <li><span><i class="las la-user-alt"></i></span>{{ __($influencer->username) }}</li>
                                                <li><i class="las la-envelope"></i> {{ __($influencer->email) }}</li>
                                            </ul>

                                            <ul class="list d-flex flex-wrap">
                                                <li>@lang('عنصر منذ') {{ $influencer->created_at->format('d M Y') }}</li>
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
                                        </div>
                                    </div>
                                </div>
                                <div class="right">
                                    <button type="button" class="btn--no-border editbtn border-0"> <i class="la la-edit"></i> @lang('تعديل')</button>
                                </div>
                            </div>
                            <ul class="info d-flex justify-content-between border-top mt-4 flex-wrap gap-3 pt-4">
                                <li class="d-flex align-items-center gap-2">
                                    <h4 class="text--warning d-inline-block">{{ $data['pending_orders'] }}</h4>
                                    <span>@lang('الطلبات المعلقة')</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <h4 class="text--base d-inline-block">{{ $data['ongoing_orders'] }}</h4>
                                    <span>@lang('الطلبات الجارية')</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <h4 class="text--success d-inline-block">{{ $data['completed_orders'] }}</h4>
                                    <span>@lang('الطلبات المكتملة')</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <h4 class="text--info d-inline-block">{{ $data['total_services'] }}</h4>
                                    <span>@lang('جميع الخدمات')</span>
                                </li>
                            </ul>

                            @if ($influencer->categories)
                                @foreach (@$influencer->categories as $category)
                                    <div class="justify-content-between skill-card mt-3">
                                        <span>{{ __(@$category->name) }}</span>
                                    </div>
                                @endforeach
                            @endif
                            <p class="mt-3">
                                @if ($influencer->summary)
                                    @php
                                        echo $influencer->summary;
                                    @endphp
                                @else
                                    @lang('No summary added yet.')
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom--card skill-edit d-none mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('المهارات')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm skillBtn"> <i class="la la-plus"></i> @lang('اضافة')</button>
                </div>
                <div class="card-body">
                    <form action="{{ localized_route('influencer.skill') }}" method="POST">
                        @csrf
                        <div id="skillContainer">
                            @if ($influencer->skills)
                                @foreach ($influencer->skills as $skill)
                                    <div class="add-skill d-flex gap-2 mb-2">
                                        <input type="text" name="skills[]" class="form-control form--control" value="{{ $skill }}" required />
                                        <button class="btn btn--danger @if ($loop->first) remove-disable-btn @else remove-btn @endif" type="button">
                                            <i class="las la-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="add-skill d-flex gap-2  mb-2">
                                    <input type="text" name="skills[]" class="form-control form--control" placeholder="@lang('')" required />
                                    <button class="btn btn--danger remove-disable-btn" type="button"><i class="las la-times"></i></button>
                                </div>
                            @endif
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn--dark btn--md cancelSkillBtn">@lang('الغاء')</button>
                            <button class="btn btn--base btn--md">@lang('حفظ')</button>
                        </div>
                    </form>
                </div>
            </div>
<div>
    <form action="{{ localized_route('profile.update_stat') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="card custom--card  mt-5">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
            <h6 class="card-title">@lang('احصائيات')</h6>
        </div>

        <div class="card-body">
            <div class="input-group">
                <input type="file" class="form-control" autocomplete="off" name="stat[]" required   multiple>
                <span class="input-group-text input-group-addon"></span>
            </div>
            <br>
            <button type="submit" class="btn btn--base btn--md w-100">@lang('حفظ')</button>

        </div>
    </div>
    <div class="card custom--card  mt-5">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
            <h6 class="card-title">@lang('صور الاحصائيات')</h6>
        </div>

        <div class="card-body">
            <div class="input-group">
               @php
                    $user = authInfluencer();
                    $images=json_decode($user->stat);
               @endphp
                 @if(isset($images))
               @foreach ($images as $i )
               <div class="col-md-4">
                {{-- <img src="{{ getImage(getFilePath($i),5, true) }}" alt="profile thumb"> --}}

                <img src="{{asset($i)}}" alt="{{$i}}" width="100px" height="100px">
                </div>
               @endforeach
               @endif
            </div>
            <br>

        </div>
    </div>
    </form>
    <form action="{{ localized_route('profile.update_birth_day') }}" method="POST">
        @csrf
    <div class="card custom--card  mt-5">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
            <h6 class="card-title">@lang('تاريخ الميلاد')</h6>
        </div>

        <div class="card-body">
            <div class="input-group">
                <input type="date" class="form-control" autocomplete="off" name="birthday" required value="{{ $influencer->birth_day }}">
                <span class="input-group-text input-group-addon"></span>
            </div>
            <br>
            <button type="submit" class="btn btn--base btn--md w-100">@lang('حفظ')</button>

        </div>
    </div>
    </form>
            <div class="card custom--card influencer-skill mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('المهارات')</h6>
                    <button type="button" class="btn--no-border editSkillbtn border-0"> <i class="la la-edit"></i> @lang('تعديل')</button>
                </div>
                <div class="card-body">
                    @if ($influencer->skills)
                        @foreach (@$influencer->skills as $skill)
                            <div class="justify-content-between skill-card my-1">
                                <span>{{ __(@$skill) }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="justify-content-center noSkill">
                            <span>@lang('لا يوجد مهارات')</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('اللغة')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm languageBtn"> <i class="la la-plus"></i> @lang('اضافة')</button>
                </div>
                <div class="card-body py-0">
                    <div class="row">
                        @if ($influencer->languages)
                            @foreach (@$influencer->languages as $key => $profiencies)
                                <div class="col-12">
                                    <div class="education-content py-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2 gap-3">
                                            <h6>{{ __($key) }}</h6>
                                            <div class="d-flex gap-sm-2 gap-1">
                                                <button type="button" class="btn--no-border confirmationBtn border-0" data-action="{{ localized_route('influencer.language.remove', $key) }}" data-question="@lang('هل أنت متؤكد من حذف هذه اللغة')" data-btn_class="btn btn--base btn--md"><span class="text--danger"><i class="las la-trash"></i> @lang('حذف')</span></button>
                                            </div>

                                        </div>
                                        <div class="d-flex my-2 flex-wrap gap-2">
                                            @foreach ($profiencies as $key => $profiency)
                                                <span class="me-3 py-1">
                                                    <span class="fw-medium">{{ keyToTitle($key) }}</span>: {{ $profiency }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 py-3">
                                <div class="justify-content-center">
                                    <span>@lang('لا توجد لغات')</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            <div class="card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('روابط التواصل الاجتماعي')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm socialBtn"><i class="la la-plus"></i> @lang('اضافة')</button>
                </div>
                <div class="card-body py-0">
                    @forelse (@$influencer->socialLink as $social)
                        <div class="education-content py-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="d-flex flex-wrap">
                                    <span class="text--base me-2">@php  echo $social->social_icon @endphp</span>
                                    <span class="text-break">{{ __($social->url) }}</span>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <span>{{ __($social->followers) }}</span>
                                    <span class="ms-2">@lang('المتابعين')</span>
                                </div>
                                <div class="d-flex gap-sm-2 gap-1">
                                    <button type="button" class="btn--no-border editSocialBtn border-0" data-url="{{ $social->url }}" data-social_icon="{{ $social->social_icon }}" data-followers="{{ $social->followers }}" data-action="{{ localized_route('influencer.add.socialLink', $social->id) }}"><span class="text--base"><i class="lar la-edit"></i> @lang('تعديل')</span></button>
                                    <button type="button" class="btn--no-border confirmationBtn border-0" data-action="{{ localized_route('influencer.remove.socialLink', $social->id) }}" data-question="@lang('هل أنت متؤكد من حذف هذا الرابط؟')" data-btn_class="btn btn--base btn--md"><span class="text--danger"><i class="las la-trash"></i> @lang('حذف')</span></button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="justify-content-center py-3">
                            <span>@lang('لا توجد روابط')</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-none">
                    <h6 class="card-title">@lang('تعليم')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm educationBtn"> <i class="la la-plus"></i> @lang('اضافة')</button>
                </div>
                <div class="card-body py-0">
                    @forelse (@$influencer->education as $education)
                        <div class="education-content py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6>{{ __($education->degree) }}</h6>
                                <div class="d-flex gap-sm-2 gap-1">

                                    <button type="button" class="btn--no-border editEduBtn border-0" data-degree="{{ $education->degree }}" data-institute="{{ $education->institute }}" data-country="{{ $education->country }}" data-start_year="{{ $education->start_year }}" data-end_year="{{ $education->end_year }}" data-action="{{ localized_route('influencer.add.education', $education->id) }}"><span class="text--base"><i class="lar la-edit"></i> @lang('تعديل')</span></button>

                                    <button type="button" class="btn--no-border confirmationBtn border-0" data-question="@lang('Are you sure to remove this education?')" data-action="{{ localized_route('influencer.remove.education', $education->id) }}" data-btn_class="btn btn--base btn--md"><span class="text--danger"><i class="las la-trash"></i> @lang('حذف')</span></button>

                                </div>
                            </div>
                            <p>
                                {{ __($education->institute) }}, <span>{{ __($education->country) }}</span>
                            </p>
                            <p>{{ $education->start_year }} - {{ $education->end_year }}</p>
                        </div>
                    @empty
                        <div class="justify-content-center py-3">
                            <span>@lang('لا يوجد تعليم')</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card custom--card mt-5">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 border-none">
                    <h6 class="card-title">@lang('الكفاءات')</h6>
                    <button type="button" class="btn btn--outline-base btn--sm qualificationBtn"> <i class="la la-plus"></i> @lang('اضافة')</button>
                </div>
                <div class="card-body py-0">
                    @forelse (@$influencer->qualification as $qualification)
                        <div class="education-content py-3">
                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <h6>{{ __($qualification->certificate) }}</h6>
                                <div class="d-flex gap-sm-2 gap-1">

                                    <button type="button" class="btn--no-border editQualifyBtn border-0" data-certificate="{{ $qualification->certificate }}" data-organization="{{ $qualification->organization }}" data-year="{{ $qualification->year }}" data-summary="{{ $qualification->summary }}" data-action="{{ localized_route('influencer.add.qualification', $qualification->id) }}"><span class="text--base"><i class="lar la-edit"></i> @lang('تعديل')</span></button>

                                    <button type="button" class="btn--no-border confirmationBtn border-0" data-question="@lang('Are you sure to remove this qualification?')" data-action="{{ localized_route('influencer.remove.qualification', $qualification->id) }}" data-btn_class="btn btn--base btn--md"><span class="text--danger"><i class="las la-trash"></i> @lang('حذف')</span></button>

                                </div>
                            </div>
                            <p class="fw-medium my-2">
                                {{ __($qualification->organization) }}, <span>{{ __($qualification->year) }}</span>
                            </p>
                            <p>{{ $qualification->summary }}</p>
                        </div>
                    @empty
                        <div class="justify-content-center py-3">
                            <span>@lang('لا توجد كفاءات')</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div id="socialLinkModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('اضافة روابط التواصل الاجتماعي')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="skill" class="col-form-label">@lang('رمز الموقع')</label>
                            <div class="input-group">
                                <select name="social_icon" id="social_icon" class="form-control">
                                    <option value='<i class="fab fa-facebook"></i>'>Facebook</option>
                                    <option value='<i class="fab fa-instagram"></i>'>Instagram</option>
                                    <option value='<i class="fab fa-youtube"></i>'>Youtube</option>
                                    <option value='<i class="fab fa-tiktok"></i>'>Tiktok</option>

                                </select>
                                {{-- <input type="text" class="form-control form--control iconPicker icon" autocomplete="off" name="social_icon" required>
                                <span class="input-group-text input-group-addon" data-icon="las la-home" role="iconpicker"></span> --}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="skill" class="col-form-label">@lang('عدد المتابعين')</label>
                            <div class="input-group">
                                <input type="text" name="followers" class="form-control form--control" value="{{ old('followers') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="skill" class="col-form-label">@lang('الرابط')</label>
                            <div class="input-group">
                                <input type="text" name="url" class="form-control form--control" value="{{ old('url') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--base btn--md w-100">@lang('حفظ')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="languageModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('اضافة لغة')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">@lang('الاسم')</label>
                            <select name="name" class="form-control form--control form-select" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($languageData as $lang)
                                    <option value="{{ $lang }}">{{ __($lang) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="from-group">
                            <label class="col-form-label">@lang('السمع')</label>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="form-group custom--radio">
                                    <input type="radio" name="listening" id="basic-listening" value="Basic" required>
                                    <label for="basic-listening">@lang('قاعدي')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="medium-listening" type="radio" name="listening" value="Medium" required>
                                    <label for="medium-listening">@lang('متوسط')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="fluent-listening" type="radio" name="listening" value="Fluent" required>
                                    <label for="fluent-listening">@lang('فصيح')</label>
                                </div>
                            </div>
                        </div>
                        <div class="from-group">
                            <label class="col-form-label">@lang('التحدث')</label>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="form-group custom--radio">
                                    <input type="radio" name="speaking" id="basic-speaking" value="Basic" required>
                                    <label for="basic-speaking">@lang('قاعدي')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="medium-speaking" type="radio" name="speaking" value="Medium" required>
                                    <label for="medium-speaking">@lang('متوسط')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="fluent-speaking" type="radio" name="speaking" value="Fluent" required>
                                    <label for="fluent-speaking">@lang('فصيح')</label>
                                </div>
                            </div>
                        </div>
                        <div class="from-group">
                            <label class="col-form-label">@lang('الكتابة')</label>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="form-group custom--radio">
                                    <input type="radio" name="writing" id="basic-writing" value="Basic" required>
                                    <label for="basic-writing">@lang('قاعدي')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="medium-writing" type="radio" name="writing" value="Medium" required>
                                    <label for="medium-writing">@lang('متوسط')</label>
                                </div>
                                <div class="form-group custom--radio">
                                    <input id="fluent-writing" type="radio" name="writing" value="Fluent" required>
                                    <label for="fluent-writing">@lang('فصيح')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--base btn--md w-100">@lang('حفظ')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="educationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="form-group col-md-6">
                                <label for="skill" class="col-form-label">@lang('البلد')</label>
                                <select name="country" class="form-control form--control form-select" required>
                                    <option value="" selected disabled>@lang('اختر البلد')</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->country }}">{{ __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('الجامعة/المدرسة')</label>
                                <input type="text" name="institute" class="form-control form--control" value="{{ old('institute') }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label">@lang('المستوى')</label>
                                <input type="text" name="degree" class="form-control form--control" value="{{ old('degree') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('سنة البداية')</label>
                                <select name="start_year" class="form-control form--control form-select start-year" required></select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('سنة النهاية')</label>
                                <select name="end_year" class="form-control form--control form-select end-year" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--base btn--md w-100">@lang('حفظ')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="qualificationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('الشهادات الجامعية')</label>
                                <input type="text" name="certificate" class="form-control form--control" value="{{ old('certificate') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-form-label">@lang('منظمة المنح')</label>
                                <input type="text" name="organization" class="form-control form--control" value="{{ old('organization') }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label">@lang('الملخص')</label>
                                <textarea name="summary" class="form-control form--control">{{ old('summary') }}</textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label">@lang('السنة')</label>
                                <select name="year" class="form-control form--control form-select year" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--base btn--md w-100">@lang('حفظ')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal></x-confirmation-modal>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/fontawesome-iconpicker.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('style')
    <style>
        .badge.badge--icon {
            border-radius: 5px 0 0 0;
        }

        .select2-container--open {
            z-index: 99999;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            const inputField = document.querySelector('#update-photo'),
                uploadImg = document.querySelector('#upload-img');
            inputField.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        const result = reader.result;
                        uploadImg.src = result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            let presentYear = new Date().getFullYear();
            let options = "";
            for (var year = presentYear; year >= 1970; year--) {
                options += `<option value="${year}">${year}</option>`;
            }

            $('.start-year').html(options)
            $('.end-year').html(options)
            $('.year').html(options)

            $('.skillBtn').on('click', function() {
                $('.noSkill').addClass('d-none');
                $("#skillContainer").append(`
                    <div class="add-skill d-flex gap-2 mb-2">
                        <input type="text" name="skills[]" class="form-control form--control" placeholder="@lang('Enter your skill')" require />
                        <button class="btn btn--danger remove-btn" type="button"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.add-skill').remove();
                if ($("#skillContainer").children().length == 0) {
                    $('.noSkill').removeClass('d-none');
                }
            });

            $('.socialBtn').on('click', function() {
                var modal = $('#socialLinkModal');
                modal.find('form').attr('action', `{{ localized_route('influencer.add.socialLink') }}`);
                modal.modal('show')
            });

            $('.editSocialBtn').on('click', function() {
                var modal = $('#socialLinkModal');
                modal.find('.modal-title').text('Update Social Link');
                var action = $(this).data('action');
                modal.find('form').attr('action', `${action}`);
                modal.find('[name=social_icon]').val($(this).data('social_icon'));
                modal.find('[name=url]').val($(this).data('url'));
                modal.find('[name=followers]').val($(this).data('followers'));
                modal.modal('show')
            });

            $('.languageBtn').on('click', function() {
                var modal = $('#languageModal');
                modal.find('form').attr('action', `{{ localized_route('influencer.language.add') }}`);
                modal.modal('show')
            });

            $('.editLangBtn').on('click', function() {
                var modal = $('#languageModal');
                modal.find('.modal-title').text('Update Language');
                var action = $(this).data('action');
                modal.find('form').attr('action', `${action}`);
                modal.find('[name=name]').val($(this).data('name'));
                modal.find('select[name=label]').val($(this).data('label'));
                modal.modal('show')
            });

            $('.educationBtn').on('click', function() {
                var modal = $('#educationModal');
                modal.find('.modal-title').text('Add New Education');
                modal.find('form').attr('action', `{{ localized_route('influencer.add.education', '') }}`);
                modal.modal('show')
            });
            $('.editEduBtn').on('click', function() {
                var modal = $('#educationModal');
                modal.find('.modal-title').text('Update Education');
                var action = $(this).data('action');
                modal.find('form').attr('action', `${action}`);
                modal.find('select[name=country]').val($(this).data('country'));
                modal.find('[name=institute]').val($(this).data('institute'));
                modal.find('[name=degree]').val($(this).data('degree'));
                modal.find('select[name=start_year]').val($(this).data('start_year'));
                modal.find('select[name=end_year]').val($(this).data('end_year'));
                modal.modal('show')
            });

            $('.qualificationBtn').on('click', function() {
                var modal = $('#qualificationModal');
                modal.find('.modal-title').text('Add New Qualification');
                modal.find('form').attr('action', `{{ localized_route('influencer.add.qualification', '') }}`);
                modal.modal('show')
            });

            $('.editQualifyBtn').on('click', function() {
                var modal = $('#qualificationModal');
                modal.find('.modal-title').text('Update Qualification');
                var action = $(this).data('action');
                modal.find('form').attr('action', `${action}`);
                modal.find('[name=certificate]').val($(this).data('certificate'));
                modal.find('[name=organization]').val($(this).data('organization'));
                modal.find('[name=summary]').val($(this).data('summary'));
                modal.find('select[name=year]').val($(this).data('year'));
                modal.modal('show')
            });

            $('.editbtn').on('click', function() {
                $('.influencer-profile-edit').removeClass('d-none');
                $('.influencer-profile').addClass('d-none');
            });
            $('.cancelBtn').on('click', function() {
                $('.influencer-profile-edit').addClass('d-none');
                $('.influencer-profile').removeClass('d-none');

            });

            $('.editSkillbtn').on('click', function() {
                $('.skill-edit').removeClass('d-none');
                $('.influencer-skill').addClass('d-none');
            });

            $('.cancelSkillBtn').on('click', function() {
                $('.skill-edit').addClass('d-none');
                $('.influencer-skill').removeClass('d-none');
            });


            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });

            $('#educationModal').on('hidden.bs.modal', function() {
                $('#educationModal form')[0].reset();
            });

            $('#qualificationModal').on('hidden.bs.modal', function() {
                $('#qualificationModal form')[0].reset();
            });

            $('#socialLinkModal').on('hidden.bs.modal', function() {
                $('#socialLinkModal form')[0].reset();
            });

            $(".select2-multi-select").select2({
                dropdownParent: $('.has-select2')
            });

        })(jQuery);
    </script>
@endpush
