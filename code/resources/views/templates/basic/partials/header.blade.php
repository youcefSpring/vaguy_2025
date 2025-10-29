@php
$pages = App\Models\Page::where('tempname', $activeTemplate)
    ->where('is_default', 0)
    ->get();

$condition = request()->routeIs('user.*') || request()->routeIs('influencer.*') || request()->routeIs('ticket*');
@endphp
<style>
    a{
        color:black;
        /* font-size: 200px; */
    }
</style>

<div class="header @if($condition) dash-header @endif">
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-area align-items-center">
                <div class="logo">
                    <a href="@if(request()->routeIs('user.*')) {{ localized_route('user.home') }} @elseif(request()->routeIs('influencer.*')) {{ localized_route('influencer.home') }} @else {{ localized_route('home') }} @endif">
                        <img src="@if(!$condition) {{ getImage(getFilePath('logoIcon') . '/logo.png') }} @else {{ getImage(getFilePath('logoIcon') . '/logo_dark.png') }} @endif " alt="logo">
                    </a>
                </div>
                <ul class="menu">
                    <li class="d-lg-none p-0 border-0 header-close ">
                        <span class="fs--20px text-white"><i class="las la-times"></i></span>
                    </li>

                    {{-- <li>
                        <a href="{{ localized_route('home') }}" class="{{ menuActive('home') }}">@lang('الصفحة الرئيسية') </a>
                        {{-- <a href="{{ localized_route('home') }}" class="{{ menuActive('home') }}">@lang('Home')</a>
                    </li>

                    @foreach ($pages as $k => $data)
                        <li><a href="{{ localized_route('pages', [$data->slug]) }}" class="{{ menuActive('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
                    @endforeach
--}}
                    @auth
                    <li>
                        <a style="color :black;"
                            href="{{ localized_route('services') }}"
                            class="{{ menuActive('services') }}"
                            >
                            @lang('الخدمات')
                        </a>
                        {{-- <a href="{{ localized_route('services') }}" class="{{ menuActive('services') }}">@lang('Services')</a> --}}
                    </li>

                    <li>
                        <a style="color :black;"
                           href="{{ localized_route('influencers') }}"
                           class="{{ menuActive('influencers') }}"
                           >
                           @lang('المؤثرون')
                        </a>
                    </li>
                    @endauth
                    {{-- <li>
                        <a href="{{ localized_route('contact') }}" class="{{ menuActive('contact') }}">@lang('تواصل معنا')</a>
                    </li> --}}

                    <li class="d-lg-none">
                        @if (!(auth()->id() || authInfluencerId()))
                            <a href="{{ localized_route('user.login') }}" class="btn btn-md btn--base">@lang('تسجيل الدخول')</a>
                        @endif

                        @auth
                            <a href="{{ localized_route('user.home') }}" class="btn btn-md btn--base">@lang('لوحة التحكم')</a>
                        @endauth

                        @auth('influencer')
                            <a href="{{ localized_route('influencer.home') }}" class="btn btn-md btn--base">@lang('لوحة التحكم')</a>
                        @endauth
                    </li>
                </ul>
                <div class="header-trigger-wrapper d-flex align-items-center">
                    <div class="button-wrapper d-flex align-items-center flex-wrap" style="gap:8px 15px">
                        @if (!(auth()->id() || authInfluencerId()))
                            <ul class="d-flex align-items-center flex-wrap" style="gap:8px 15px">
                                <li class="me-0">
                                    <a href="{{ localized_route('user.login') }}" class="login-btn btn btn--md btn--outline-base d-none d-sm-grid text-white">@lang('تسجيل الدخول')</a>
                                </li>
                                <li class="me-0">
                                    <a href="{{ localized_route('user.register') }}" class="login-btn btn btn--md btn--outline-base d-none d-sm-grid text-white">@lang('التسجيل')</a>
                                </li>
                            </ul>
                        @endif

                        @auth
                            <ul class="d-flex align-items-center flex-wrap">
                                <li class="me-0">
                                    <a href="{{ localized_route('user.home') }}" class="login-btn btn btn--md btn--outline-base d-none d-sm-grid text-white">@lang('لوحة التحكم')</a>
                                </li>
                            </ul>
                        @endauth

                        @auth('influencer')
                            <ul class="d-flex align-items-center flex-wrap">
                                <li class="me-0">
                                    <a href="{{ localized_route('influencer.home') }}" class="login-btn btn--md btn btn--outline-base d-none d-sm-grid text-white">@lang('لوحة التحكم')</a>
                                </li>
                            </ul>
                        @endauth

                        @if($language->count())
                            <!-- Language Switcher Dropdown -->
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open"
                                        class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                                        style="min-width: 120px;">
                                    @php
                                        $currentLang = session('lang', config('app.locale'));
                                        $currentLangData = $language->firstWhere('code', $currentLang);

                                        // Flag mapping
                                        $flags = [
                                            'en' => '🇬🇧',
                                            'fr' => '🇫🇷',
                                            'ar' => '🇩🇿'
                                        ];
                                    @endphp
                                    <span class="text-2xl mr-2">{{ $flags[$currentLang] ?? '🌐' }}</span>
                                    <span class="text-sm font-medium text-gray-700">{{ $currentLangData ? __($currentLangData->name) : 'Language' }}</span>
                                    <svg class="ml-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                     style="display: none;">
                                    <div class="py-1">
                                        @foreach ($language as $item)
                                            @if(request()->routeIs('user.*') && $item->code == 'ar')
                                                {{-- Hide Arabic option in client dashboard --}}
                                                @continue
                                            @endif
                                            <a href="{{ route('lang', $item->code) }}"
                                               class="flex items-center px-4 py-3 text-sm hover:bg-gray-50 transition-colors duration-150 {{ session('lang') == $item->code ? 'bg-purple-50 text-purple-600' : 'text-gray-700' }}">
                                                <span class="text-2xl mr-3">{{ $flags[$item->code] ?? '🌐' }}</span>
                                                <span class="font-medium">{{ __($item->name) }}</span>
                                                @if(session('lang') == $item->code)
                                                    <svg class="ml-auto h-4 w-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="header-trigger d-lg-none">
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
