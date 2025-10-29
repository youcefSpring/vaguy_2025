@php
$banner = getContent('banner.content', true);
@endphp
<section class="banner-section bg_img overflow-hidden mb-0 ">
    <div class="container">
        <div class="d-flex justify-content-lg-between flex-wrap">
            <div class="banner-content">
                <h1 class="title">{{ __(@$banner->data_values->heading) }}</h1>
                <p>{{ __(@$banner->data_values->subheading) }}</p>
                <form action="{{ localized_route('influencers') }}" class="search-form" method="GET">
                    <div class="form--group">
                        <div class="icon"><i class="fas fa-search"></i></div>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form--control" placeholder="@lang('.ابحث بالاسم, المهنة أو الدولة')">
                            <button class="btn btn--base btn--round btn--md">@lang('البحث')</button>
                        </div>
                    </div>
                </form>
                @if($tags->count())
                <ul class="tags mt-5">
                    <li class="text-light">@lang('Trending') :</li>
                    @foreach ($tags as $tag)
                        <li>
                            <a class="btn btn--sm btn--outline-light" href="{{ localized_route('service.tag', [$tag->id, slug($tag->name)]) }}">
                            {{ __($tag->name) }}</a>
                        </li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="banner-thumb ps-xl-5 ps-lg-4 d-lg-block d-none">
                <img src="{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->image, '500x735') }}" alt="images">
            </div>
        </div>
    </div>
</section>


@php
    $partnerContent = getContent('partner.content', true);
    $partnerElement = getContent('partner.element', false, null, true);
@endphp

{{-- <section class="brand-section bg--light pt-30 pb-30 overflow-hidden">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-md-3 col-lg-2">
                <h5 class="text-center text-md-start">{{ __(@$partnerContent->data_values->heading) }}</h5>
            </div>

            <div class="col-md-9 col-lg-10">
                <div class="testimonial-slider">
                    @foreach ($partnerElement as $partner)
                    <div class="single-slide">
                            <img src="{{ getImage('assets/images/frontend/partner/' . @$partner->data_values->image, '130x65') }}" alt="brands">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section> --}}

<section class="brand-section bg--light pt-30 pb-30 overflow-hidden">
    <div class="container brand_backgroud">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-header text-center">
                    <h2 class="section-header__title">@lang('تزيد العلامات التجارية من مرات الظهور')</h2>
                    <p>@lang('تبحث الشركات من جميع الأحجام عن منشئي محتوى ناجحين في إشراك الجماهير المثالية تحقق من بعض العلامات التجارية التي تستخدم منصتنا للحصول على رؤى مهمة حول أداء المؤثر')</p>
                </div>
            </div>
        </div>
        <div class="row gy-4 align-items-center">
 <div class="col-md-1">

 </div>
            <div class="col-md-9 col-lg-10">
                <div class="brands-slider">
                    @foreach ($partnerElement as $partner)
                    <div class="single-slide">
                        <div class="brand-item">
                            <img src="{{ getImage('assets/images/frontend/partner/' . @$partner->data_values->image, '130x65') }}" alt="brands">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-1">

            </div>
        </div>
    </div>
</section>

<style>
/* .brand_backgroud{
    background-image: url("./assets/backgroud/2em-min.png");
    /* border-radius: 20px;

    width: 1600px;
} */

</style>
