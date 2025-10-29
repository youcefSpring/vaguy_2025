@php
$aboutUs = getContent('about.content', true);
@endphp
<section class="about-section pt-80 pb-80">
    <div class="container">
        <div class="row gy-4 gy-sm-5">

            <div class="col-lg-7">
                <div class="section-thumb about-thumb pb-lg-5 mb-lg-4">
                    <img src="{{ getImage('assets/images/frontend/about/' . @$aboutUs->data_values->image, '400x600') }}" alt="thumb">

                </div>
            </div>
            <div class="col-lg-5">
                <div class="section-header mb-0">
                    <h2 class="section-header__title">{{ __(@$aboutUs->data_values->title) }}</h2>
                    <p>{{ __(@$aboutUs->data_values->short_detail) }}</p>

                </div>
            </div>

            </div>
        </div>
    </div>
</section>
