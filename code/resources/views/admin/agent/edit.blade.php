@extends('admin.layouts.app')
@section('panel')
<form class="form-horizontal" method="post" action="{{s*localized_route('admin.agent.update',$agent->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body row">
        <div class="form-group col-md-6">
            <label>@lang('Name')</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" value="{{ $agent->name }}" name="name" required>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>@lang('Email')</label>
            <div class="col-sm-12">
                <input type="email" class="form-control" value="{{ $agent->email }}" name="email" required>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>@lang('username')</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" value="{{ $agent->username }}" name="username" required>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>@lang('password')</label>
            <div class="col-sm-12">
                <input type="password" class="form-control" value="{{ old('password') }}" name="password" required>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save" value="add">@lang('Submit')</button>
    </div>
</form>
@endsection
