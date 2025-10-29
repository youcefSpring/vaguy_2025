@php
$socialIcons = getContent('social_icon.element', false, null, true);
$policyPages = getContent('policy_pages.element', false, null, true);
$contact = getContent('contact_us.content', true);
$footer = getContent('footer.content', true);
@endphp
<footer class="bg--accent footer footer_section pt-80">
    <div class="container">
        <div class="row gy-4 justify-content-between">
            <div class="col-lg-3 col-sm-6 col-md-5">
                <div class="footer-widget">
                    <a class="logo mb-4" href="{{ localized_route('home') }}"><img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="Logo"></a>
                    <p>{{ __(@$footer->data_values->description) }}</p>
                    <ul class="social-links d-flex mt-4 flex-wrap gap-3">
                        @foreach ($socialIcons as $social)
                            <li>
                                <a href="{{ @$social->data_values->url }}">
                                    @php
                                        echo @$social->data_values->social_icon;
                                    @endphp
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 col-md-5">
                <div class="footer-widget">
                    <h5 class="footer-widget__title text--base mb-sm-3 mb-2 pb-1">@lang('روابط سريعة')</h5>
                    <ul class="footer-links">
                      {{--  <li>
                            <a href="{{ localized_route('services') }}" class="{{ menuActive('services') }}">@lang('الخدمات')</a>
                        </li>
                        <li>
                            <a href="{{ localized_route('influencers') }}" class="{{ menuActive('influencers') }}">@lang('المؤثرون')</a>
                        </li> --}}
                        <li>
                            <a href="{{ localized_route('contact') }}" class="{{ menuActive('contact') }}">@lang('تواصل معنا')</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 col-md-5">
                <div class="footer-widget">
                    <h5 class="footer-widget__title text--base mb-sm-3 mb-2 pb-1">@lang('روابط مفيدة')</h5>
                    <ul class="footer-links">
                        @foreach ($policyPages as $policy)
                            <li>
                                <a href="{{ localized_route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                    {{ __(@$policy->data_values->title) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-5">
                <div class="footer-widget">
                    <h5 class="footer-widget__title text--base mb-sm-3 mb-2 pb-1">@lang('جهات الاتصال')</h5>
                    <ul class="footer-links">
                        <li><a href="mailto:{{ @$contact->data_values->email_address }}"><i class="las la-envelope-open"></i> {{ __(@$contact->data_values->email_address) }}</a></li>
                        <li><a href="tel:{{ @$contact->data_values->contact_number_one }}"><i class="las la-phone-volume"></i> {{ @$contact->data_values->contact_number_one }}</a></li>
                        <li><a href="tel:{{ @$contact->data_values->contact_number_two }}"><i class="las la-phone-volume"></i> {{ @$contact->data_values->contact_number_two }}</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <p class="text-center border-top my-4 flex-wrap pt-4">
            @lang('فاغي') &copy; @php echo date('Y') @endphp. @lang('جميع الحقوق محفوظة')
        </p>
    </div>
</footer>
<style>
.bg--accent{
background-image: url("./assets/backgroud/2em-min.png");

}
.social-links i {
	font-size: 18px;
	color: #a5a8ab;
	text-align: center;
}
</style>

