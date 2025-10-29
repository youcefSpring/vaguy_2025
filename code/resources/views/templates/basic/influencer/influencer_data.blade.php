@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card custom--card">
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <strong> <i class="la la-info-circle"></i> @lang('تحتاج إلى إكمال ملف التعريف الخاص بك للوصول إلى لوحة التحكم الخاصة بك')</strong>
                        </div>

                        <form method="POST" action="{{ localized_route('influencer.data.submit') }}" class="form row gy-2">
                            @csrf
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('الاسم')</label>
                                <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('اللقب')</label>
                                <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('العنوان')</label>
                                <input type="text" class="form-control form--control" name="address" value="{{ @$influencer->address->address }}">
                            </div>

                            {{-- <div class="form-group col-sm-6">
                                <label class="form-label">@lang('الولاية')</label>
                                <input type="text" class="form-control form--control" name="state" value="{{ @$influencer->address->state }}">
                            </div> --}}
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('الولاية')</label>
                                {{-- <select name="state" id="state" class="form-control"> --}}
                                    {{-- @foreach ($wilayas as $w )
                                        <option value="{{$w->name}}">
                                            {{ $w->name }}
                                        </option>
                                    @endforeach --}}
                                    @livewire('singlewilaya')
                                {{-- </select> --}}
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Zip كود')</label>
                                <input type="text" class="form-control form--control" name="zip" value="{{ @$influencer->address->zip }}">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('المدينة')</label>
                                <input type="text" class="form-control form--control" name="city" value="{{ @$influencer->address->city }}">
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn--base w-100">@lang('حفظ')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
