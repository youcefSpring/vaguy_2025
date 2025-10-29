@php
$content = getContent('category.content', true);
@endphp

<section class="pt-80 pb-80 bg--light">
    <div class="container category_section">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-header text-center">
                    <h2 class="section-header__title">{{ __($content->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
            <div class="testimonial-slider">
                <div class="single-slide travel">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">
                           <img src="{{ getImage('assets/categories/travel-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">
                                <div class="testimonial-content">
                                    <h5 class="name">@lang('سياحة و سفر')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class="single-slide travel">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">

                           <img src="{{ getImage('assets/categories/art-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">
                                <div class="testimonial-content">
                                    <h5 class="name">@lang('فنون و ثقافة')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class="single-slide travel">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">
                           <img src="{{ getImage('assets/categories/beauty-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center  justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">
                                <div class="testimonial-content">
                                    <h5 class="name">@lang('جمال و أناقة')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class="single-slide travel">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">
                           <img src="{{ getImage('assets/categories/cook-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">
                                <div class="testimonial-content">
                                    <h5 class="name">@lang('طبخ و تغذية')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class="single-slide travel">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">
                           <img src="{{ getImage('assets/categories/educ-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">
                                <div class="testimonial-content">
                                    <h5 class="name">@lang('تربية و تعليم')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class="single-slide travel">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">
                           <img src="{{ getImage('assets/categories/san-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">
                                <div class="testimonial-content">
                                    <h5 class="name">@lang('صحة و طب')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class="single-slide travel">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">
                           <img src="{{ getImage('assets/categories/scie-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">
                                <div class="testimonial-content">
                                    <h5 class="name">@lang('علوم و تكنولوجيا')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>

                <div class="single-slide">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">

                            <img src="{{ getImage('assets/categories/sport-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">

                                <div class="testimonial-content">
                                    <h5 class="name">@lang('رياضة')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>
                
                <div class="single-slide">
                    <div class="testimonial-item">
                        <div class="testimonial-item__content">
                            <img src="{{ getImage('assets/categories/developement-min.png', '100x200')}}" alt="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="testimonial-thumb-wrapper">
                                <div class="testimonial-content">
                                    <h5 class="name">@lang('تنمية بشرية')</h5>
                                </div>
                            </div>
                            <span class="icon"><i class="fas fa-quote-right"></i></span>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
<style>
.category_section{
    background-image: url("./assets/backgroud/3em-min.png");
    border-radius: 30px;

}


</style>
