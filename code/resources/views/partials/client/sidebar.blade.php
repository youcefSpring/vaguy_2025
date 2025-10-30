{{--
    Client Sidebar Navigation - Structure organisée selon votre demande

    Structure finale:
    1. لوحة التحكم (non groupé)
    2. تصفح المنصة (non groupé) - المؤثرين، الخدمات
    3. الأعمال والخدمات (groupé) - عروض العمل، الطلبات، الحملات
    4. التواصل والدعم (groupé) - المحادثات، بطاقة الدعم
    5. الحساب والمعاملات (groupé) - الإيداع، المعاملات، الآراء، القائمة المفضلة
--}}

@php
    $currentPath = request()->path();
    $isRTL = app()->getLocale() === 'ar';
    $locale = app()->getLocale();

    // Structure finale - store translation KEYS, not translated strings
    $sidebarSections = [
        // 1. لوحة التحكم (non groupé)
        [
            'title_key' => 'navbar.dashboard',
            'icon' => 'home',
            'url' => "/{$locale}/client/dashboard",
            'route' => 'user.home'
        ],

        // 2. تصفح المنصة (non groupé, placé après dashboard)
        [
            'title_key' => 'navbar.influencers',
            'icon' => 'users',
            'url' => "/{$locale}/getinf",
            'route' => 'influencers'
        ],
        [
            'title_key' => 'navbar.services',
            'icon' => 'grid-3x3-gap',
            'url' => "/{$locale}/services",
            'route' => 'services'
        ],

        // 3. الأعمال والخدمات (section groupée)
        [
            'section' => true,
            'title_key' => 'navbar.business_and_services',
            'icon' => 'briefcase',
            'items' => [
                [
                    'title_key' => 'navbar.job_offers',
                    'icon' => 'user-plus',
                    'url' => "/{$locale}/client/hiring/all",
                    'route' => 'user.hiring.history'
                ],
                [
                    'title_key' => 'navbar.orders',
                    'icon' => 'package',
                    'url' => "/{$locale}/client/order/all",
                    'route' => 'user.order.all'
                ],
                [
                    'title_key' => 'navbar.campaigns',
                    'icon' => 'megaphone',
                    'url' => "/{$locale}/client/campaign",
                    'route' => 'user_campaign'
                ]
            ]
        ],

        // 4. التواصل والدعم (section groupée)
        [
            'section' => true,
            'title_key' => 'navbar.communication_and_support',
            'icon' => 'message-circle',
            'items' => [
                [
                    'title_key' => 'navbar.conversations',
                    'icon' => 'messages',
                    'url' => "/{$locale}/client/conversation/influencers",
                    'route' => 'user.conversation.index'
                ],
                [
                    'title_key' => 'navbar.support_tickets',
                    'icon' => 'headphones',
                    'url' => "/{$locale}/ticket",
                    'route' => 'ticket'
                ]
            ]
        ],

        // 5. الحساب والمعاملات (section groupée)
        [
            'section' => true,
            'title_key' => 'navbar.account_and_transactions',
            'icon' => 'wallet',
            'items' => [
                [
                    'title_key' => 'navbar.deposit',
                    'icon' => 'banknote',
                    'url' => "/{$locale}/client/deposit/history",
                    'route' => 'user.deposit.history'
                ],
                [
                    'title_key' => 'navbar.transactions',
                    'icon' => 'arrow-left-right',
                    'url' => "/{$locale}/client/transactions",
                    'route' => 'user.transactions'
                ],
                [
                    'title_key' => 'navbar.reviews',
                    'icon' => 'star',
                    'url' => "/{$locale}/client/review/order/index",
                    'route' => 'user.review.order.index'
                ],
                [
                    'title_key' => 'navbar.favorites',
                    'icon' => 'heart',
                    'url' => "/{$locale}/client/favorite/list",
                    'route' => 'user.favorite.list'
                ]
            ]
        ]
    ];

    // Function to check if a route/URL is active
    function isActive($route = null, $url = null) {
        global $currentPath;
        if ($route && request()->routeIs($route . '*')) {
            return true;
        }
        if ($url && str_starts_with('/' . $currentPath, $url)) {
            return true;
        }
        return false;
    }

    // Function to check if a section has active items
    function sectionHasActiveItem($items) {
        foreach ($items as $item) {
            if (isActive($item['route'] ?? null, $item['url'] ?? null)) {
                return true;
            }
        }
        return false;
    }
@endphp

<!-- Static sidebar for desktop -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <!-- Sidebar component -->
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center">
            <img class="h-8 w-auto" src="{{ asset('assets/images/logoIcon/logo.png') }}" alt="{{ config('app.name') }}">
        </div>

        <!-- Navigation -->
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        @foreach($sidebarSections as $section)
                            @if(isset($section['section']) && $section['section'])
                                {{-- Section groupée collapsible --}}
                                @php
                                    $sectionId = 'section-' . str_replace(' ', '-', strtolower($section['title']));
                                    $hasActiveItem = sectionHasActiveItem($section['items']);
                                @endphp

                                <li x-data="{ open: {{ $hasActiveItem ? 'true' : 'false' }} }">
                                    {{-- Section Header --}}
                                    <button
                                        @click="open = !open"
                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ $hasActiveItem ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }} transition-colors duration-200"
                                    >
                                        <i
                                            data-lucide="{{ $section['icon'] }}"
                                            class="h-6 w-6 shrink-0 {{ $hasActiveItem ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"
                                        ></i>
                                        <span class="flex-grow {{ $isRTL ? 'text-right' : 'text-left' }}">{{ __($section['title_key']) }}</span>
                                        <i
                                            data-lucide="chevron-down"
                                            class="h-4 w-4 transition-transform duration-200"
                                            :class="open ? 'rotate-180' : ''"
                                        ></i>
                                    </button>

                                    {{-- Section Items --}}
                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                                        <ul class="mt-2 space-y-1 {{ $isRTL ? 'pr-6' : 'pl-6' }}">
                                            @foreach($section['items'] as $item)
                                                @php $itemIsActive = isActive($item['route'] ?? null, $item['url'] ?? null); @endphp
                                                <li>
                                                    <a href="{{ $item['url'] }}"
                                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ $itemIsActive ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }} transition-colors duration-200"
                                                       onclick="window.showProgressBar && window.showProgressBar()"
                                                    >
                                                        <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 shrink-0 {{ $itemIsActive ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                                        {{ __($item['title_key']) }}
                                                        @if($itemIsActive)
                                                            <span class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></span>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @else
                                {{-- Item individuel (non groupé) --}}
                                @php $itemIsActive = isActive($section['route'] ?? null, $section['url'] ?? null); @endphp
                                <li>
                                    <a href="{{ $section['url'] }}"
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ $itemIsActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }} transition-colors duration-200"
                                       onclick="window.showProgressBar && window.showProgressBar()"
                                    >
                                        <i data-lucide="{{ $section['icon'] }}" class="h-6 w-6 shrink-0 {{ $itemIsActive ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                        {{ __($section['title_key']) }}
                                        @if($itemIsActive)
                                            <span class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

                {{-- Settings - toujours en bas --}}
                <li class="mt-auto">
                    <a href="/{{ $locale }}/client/profile-setting"
                       class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200"
                       onclick="window.showProgressBar && window.showProgressBar()"
                    >
                        <i data-lucide="settings" class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-blue-600"></i>
                        {{ __('navbar.account_settings') }}
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

{{-- Mobile sidebar overlay --}}
<div x-show="sidebarOpen" class="relative z-50 lg:hidden" x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state." x-ref="dialog" aria-modal="true">
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80"></div>

    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="{{ $isRTL ? 'translate-x-full' : '-translate-x-full' }}" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="{{ $isRTL ? 'translate-x-full' : '-translate-x-full' }}" class="relative {{ $isRTL ? 'ml-auto' : 'mr-16' }} flex w-full max-w-xs flex-1" @click.away="sidebarOpen = false">
            {{-- Close button --}}
            <div class="absolute {{ $isRTL ? 'left-full' : 'left-full' }} top-0 flex w-16 justify-center pt-5">
                <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="sr-only">{{ __('Close sidebar') }}</span>
                    <i data-lucide="x" class="h-6 w-6 text-white" aria-hidden="true"></i>
                </button>
            </div>

            {{-- Sidebar component, swap this element with another sidebar if you like --}}
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                {{-- Logo --}}
                <div class="flex h-16 shrink-0 items-center">
                    <img class="h-8 w-auto" src="{{ asset('assets/images/logoIcon/logo.png') }}" alt="{{ config('app.name') }}">
                </div>

                {{-- Mobile Navigation --}}
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                @foreach($sidebarSections as $section)
                                    @if(isset($section['section']) && $section['section'])
                                        {{-- Section groupée collapsible --}}
                                        @php
                                            $sectionId = 'mobile-section-' . str_replace(' ', '-', strtolower($section['title']));
                                            $hasActiveItem = sectionHasActiveItem($section['items']);
                                        @endphp

                                        <li x-data="{ open: {{ $hasActiveItem ? 'true' : 'false' }} }">
                                            {{-- Section Header --}}
                                            <button
                                                @click="open = !open"
                                                class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ $hasActiveItem ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }} transition-colors duration-200"
                                            >
                                                <i
                                                    data-lucide="{{ $section['icon'] }}"
                                                    class="h-6 w-6 shrink-0 {{ $hasActiveItem ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"
                                                ></i>
                                                <span class="flex-grow {{ $isRTL ? 'text-right' : 'text-left' }}">{{ __($section['title_key']) }}</span>
                                                <i
                                                    data-lucide="chevron-down"
                                                    class="h-4 w-4 transition-transform duration-200"
                                                    :class="open ? 'rotate-180' : ''"
                                                ></i>
                                            </button>

                                            {{-- Section Items --}}
                                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                                                <ul class="mt-2 space-y-1 {{ $isRTL ? 'pr-6' : 'pl-6' }}">
                                                    @foreach($section['items'] as $item)
                                                        @php $itemIsActive = isActive($item['route'] ?? null, $item['url'] ?? null); @endphp
                                                        <li>
                                                            <a href="{{ $item['url'] }}"
                                                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-medium {{ $itemIsActive ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }} transition-colors duration-200"
                                                               @click="sidebarOpen = false"
                                                               onclick="window.showProgressBar && window.showProgressBar()"
                                                            >
                                                                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 shrink-0 {{ $itemIsActive ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                                                {{ __($item['title_key']) }}
                                                                @if($itemIsActive)
                                                                    <span class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></span>
                                                                @endif
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                    @else
                                        {{-- Item individuel (non groupé) --}}
                                        @php $itemIsActive = isActive($section['route'] ?? null, $section['url'] ?? null); @endphp
                                        <li>
                                            <a href="{{ $section['url'] }}"
                                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ $itemIsActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }} transition-colors duration-200"
                                               @click="sidebarOpen = false"
                                               onclick="window.showProgressBar && window.showProgressBar()"
                                            >
                                                <i data-lucide="{{ $section['icon'] }}" class="h-6 w-6 shrink-0 {{ $itemIsActive ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                                {{ __($section['title_key']) }}
                                                @if($itemIsActive)
                                                    <span class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></span>
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>

                        {{-- Mobile Settings --}}
                        <li class="mt-auto">
                            <a href="/{{ $locale }}/client/profile-setting"
                               class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200"
                               @click="sidebarOpen = false"
                               onclick="window.showProgressBar && window.showProgressBar()"
                            >
                                <i data-lucide="settings" class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-blue-600"></i>
                                {{ __('navbar.account_settings') }}
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

{{-- Initialize Lucide icons after page load --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Add smooth scrolling for anchor links
        const sidebarLinks = document.querySelectorAll('a[href^="/"]');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Add subtle loading animation
                this.style.opacity = '0.7';
                setTimeout(() => {
                    this.style.opacity = '1';
                }, 300);
            });
        });
    });
</script>
@endpush