@extends('layouts.dashboard')
@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">@lang('إعدادات الملف الشخصي')</h3>
    </div>
    <div class="p-6">
        <form action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="firstname" class="form-label">@lang('الصورة')</label>
                            <div class="flex flex-col items-center space-y-4">
                                <div class="relative">
                                    <img id="upload-img" src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile'), true) }}" alt="userProfile" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                    <label class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700" for="update-photo">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </label>
                                </div>
                                <input type="file" name="image" class="hidden" id="update-photo">
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="user" class="w-5 h-5 text-gray-500"></i>
                                <span class="text-sm text-gray-900">{{ $user->username }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-500"></i>
                                <span class="text-sm text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i data-lucide="phone" class="w-5 h-5 text-gray-500"></i>
                                <span class="text-sm text-gray-900">{{ $user->mobile }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i data-lucide="globe" class="w-5 h-5 text-gray-500"></i>
                                <span class="text-sm text-gray-900">{{ @$user->address->country }}</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="firstname" class="form-label">@lang('الاسم')</label>
                                <input type="text" class="input" id="firstname" name="firstname" value="{{ $user->firstname }}" required>
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="form-label">@lang('اللقب')</label>
                                <input type="text" class="input" id="lastname" name="lastname" value="{{ $user->lastname }}" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="state" class="form-label">@lang('الولاية')</label>
                                <input type="text" class="input" id="state" name="state" placeholder="@lang('الولاية')" value="{{ @$user->address->state }}">
                            </div>
                            <div class="form-group">
                                <label for="zip" class="form-label">@lang('Zip كود')</label>
                                <input type="text" class="input" id="zip" name="zip" placeholder="@lang('Zip كود')" value="{{ @$user->address->zip }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="form-label">@lang('المدينة')</label>
                            <input type="text" class="input" id="city" name="city" placeholder="@lang('المدينة')" value="{{ @$user->address->city }}">
                        </div>
                        <div class="form-group">
                            <label for="address" class="form-label">@lang('العنوان')</label>
                            <input type="text" class="input" id="address" name="address" placeholder="@lang('العنوان')" value="{{ @$user->address->address }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-6">
                <button type="submit" class="btn btn-primary px-6">@lang('تعديل الحساب')</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('styles')
    <style>
        .profile-thumb {
            transition: all 0.3s ease;
        }
        .profile-thumb:hover {
            transform: scale(1.02);
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
        })(jQuery);
    </script>
@endpush
