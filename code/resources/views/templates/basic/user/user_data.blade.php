@extends($activeTemplate . 'layouts.app')

@section('app')

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        padding: 20px 0;
    }

    .form-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 700px;
        width: 90%;
        margin: 20px auto;
        overflow: hidden;
        max-height: 95vh;
        overflow-y: auto;
    }

    .form-header {
        background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);
        padding: 30px 20px;
        text-align: center;
        color: white;
    }

    .form-header h1 {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .form-header p {
        font-size: 14px;
        opacity: 0.95;
        margin: 0;
    }

    .form-body {
        padding: 30px 30px 25px;
    }

    .alert-info {
        background: linear-gradient(135deg, #ede9fe 0%, #f3e8ff 100%);
        border: 2px solid #9b87f5;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-info i {
        font-size: 24px;
        color: #9b87f5;
    }

    .alert-info strong {
        color: #5b21b6;
        font-size: 14px;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #9b87f5;
        box-shadow: 0 0 0 3px rgba(155, 135, 245, 0.1);
    }

    .submit-btn {
        width: 100%;
        padding: 12px 24px;
        background: linear-gradient(135deg, #9b87f5 0%, #d6bcfa 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(155, 135, 245, 0.4);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-container {
            width: 95%;
            margin: 10px auto;
        }

        .form-header h1 {
            font-size: 20px;
        }

        .form-header p {
            font-size: 13px;
        }

        .form-body {
            padding: 20px 20px 15px;
        }
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1>@lang('Complete Your Profile')</h1>
        <p>@lang('We need a few more details to complete your registration')</p>
    </div>

    <div class="form-body">
        <div class="alert-info">
            <i class="la la-info-circle"></i>
            <strong>@lang('You need to complete your profile to access your dashboard')</strong>
        </div>

        <form method="POST" action="{{ localized_route('user.data.submit') }}" class="row gy-2">
            @csrf

            <div class="form-group col-sm-6">
                <label class="form-label">@lang('First Name')</label>
                <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>
            </div>

            <div class="form-group col-sm-6">
                <label class="form-label">@lang('Last Name')</label>
                <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>
            </div>

            <div class="form-group col-sm-6">
                <label class="form-label">@lang('Address')</label>
                <input type="text" class="form-control" name="address" value="{{ old('address') }}">
            </div>

            <div class="form-group col-sm-6">
                <label class="form-label">@lang('Wilaya')</label>
                @livewire('singlewilaya')
            </div>

            <div class="form-group col-sm-6">
                <label class="form-label">@lang('Zip Code')</label>
                <input type="text" class="form-control" name="zip" value="{{ old('zip') }}">
            </div>

            <div class="form-group col-sm-6">
                <label class="form-label">@lang('City')</label>
                <input type="text" class="form-control" name="city" value="{{ old('city') }}">
            </div>

            <div class="col-sm-12">
                <button type="submit" class="submit-btn">@lang('Complete Registration')</button>
            </div>
        </form>
    </div>
</div>

@endsection
