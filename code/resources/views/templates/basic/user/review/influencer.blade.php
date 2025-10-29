@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">@lang('تقييم المؤثر')</h2>
        <a href="{{ localized_route('user.review.index') }}" class="btn btn-outline">
            <i data-lucide="arrow-right" class="w-4 h-4 mr-2"></i>
            @lang('العودة للتقييمات')
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-lg font-medium text-blue-900 mb-2">
                @lang('العنوان'): {{ __(@$hiring->title) }}
            </h3>
        </div>

        <form action="{{ localized_route('user.review.influencer.add', $hiring->id) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('اسم المؤثر')</label>
                    <div class="relative">
                        <input type="text"
                               class="input w-full bg-gray-50"
                               value="{{ __(@$hiring->influencer->fullname) }}"
                               readonly>
                        <i data-lucide="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('البريد الإلكتروني')</label>
                    <div class="relative">
                        <input type="text"
                               class="input w-full bg-gray-50"
                               value="{{ __(@$hiring->influencer->email) }}"
                               readonly>
                        <i data-lucide="mail" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    @lang('التقييم') <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer star-rating" data-rating="{{ $i }}">
                            <input type="radio" name="star" value="{{ $i }}" class="sr-only"
                                   @if(@$hiring->review && @$hiring->review->star == $i) checked @endif>
                            <i data-lucide="star" class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors star-icon"></i>
                        </label>
                    @endfor
                </div>
                <p class="text-sm text-gray-600 mt-2">@lang('اختر عدد النجوم لتقييم المؤثر')</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="review-comments">@lang('التقييم')</label>
                <div class="relative">
                    <textarea name="review"
                              class="input w-full"
                              id="review-comments"
                              rows="5"
                              placeholder="@lang('اكتب تقييمك هنا...')"
                              required>@if(@$hiring->review){{ @$hiring->review->review }}@else{{ old('review') }}@endif</textarea>
                    <i data-lucide="message-square" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                    @lang('إرسال التقييم')
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-rating');
        const starIcons = document.querySelectorAll('.star-icon');

        // Set initial state
        const checkedRadio = document.querySelector('input[name="star"]:checked');
        if (checkedRadio) {
            updateStars(parseInt(checkedRadio.value));
        }

        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                updateStars(rating);
                this.querySelector('input').checked = true;
            });

            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                highlightStars(rating);
            });
        });

        // Reset on mouse leave
        const ratingContainer = document.querySelector('.flex.items-center.space-x-1');
        ratingContainer.addEventListener('mouseleave', function() {
            const checkedRadio = document.querySelector('input[name="star"]:checked');
            if (checkedRadio) {
                updateStars(parseInt(checkedRadio.value));
            } else {
                resetStars();
            }
        });

        function updateStars(rating) {
            starIcons.forEach((icon, index) => {
                if (index < rating) {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-yellow-400', 'fill-current');
                } else {
                    icon.classList.add('text-gray-300');
                    icon.classList.remove('text-yellow-400', 'fill-current');
                }
            });
        }

        function highlightStars(rating) {
            starIcons.forEach((icon, index) => {
                if (index < rating) {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-yellow-400');
                } else {
                    icon.classList.add('text-gray-300');
                    icon.classList.remove('text-yellow-400');
                }
            });
        }

        function resetStars() {
            starIcons.forEach(icon => {
                icon.classList.add('text-gray-300');
                icon.classList.remove('text-yellow-400', 'fill-current');
            });
        }
    });
</script>
@endpush
@endsection
