{{--
    Dashboard Sidebar Navigation

    This partial provides the fixed sidebar navigation for the dashboard.
    It's converted from the React InfSidebar component to Bootstrap/Alpine.js.

    Features:
    - Fixed position sidebar matching the original design
    - Tooltip integration for navigation items
    - Active state highlighting
    - Responsive behavior
    - Multilingual support
    - Bootstrap Icons for consistent iconography
--}}

{{-- Sidebar navigation data - Nouvelle structure organisée --}}
@php
    $currentRoute = request()->route()->getName();
    $currentPath = request()->path();

    // Determine if user is an influencer based on URL or user type
    $isInfluencer = str_starts_with($currentPath, 'influencer') || (auth()->user() && auth()->user()->user_type === 'influencer');

    // Structure finale pour les clients selon votre demande
    $clientSidebarSections = [
        // 1. لوحة التحكم (non groupée)
        [
            'title' => __('لوحة التحكم'),
            'icon' => 'house',
            'url' => '/client/dashboard',
            'route' => 'user.home'
        ],

        // 2. تصفح المنصة (non groupée, placée après dashboard)
        [
            'title' => __('المؤثرين'),
            'icon' => 'people',
            'url' => '/influencers',
            'route' => 'influencers'
        ],
        [
            'title' => __('الخدمات'),
            'icon' => 'grid-3x3-gap',
            'url' => '/services',
            'route' => 'services'
        ],

        // 3. الأعمال والخدمات (section groupée)
        [
            'section' => true,
            'title' => __('الأعمال والخدمات'),
            'icon' => 'briefcase',
            'items' => [
                [
                    'title' => __('عروض العمل'),
                    'icon' => 'person-plus',
                    'url' => '/client/hiring/all',
                    'route' => 'user.hiring.history'
                ],
                [
                    'title' => __('الطلبات'),
                    'icon' => 'box',
                    'url' => '/client/order/all',
                    'route' => 'user.order.all'
                ],
                [
                    'title' => __('الحملات'),
                    'icon' => 'megaphone',
                    'url' => '/client/campaign',
                    'route' => 'user_campaign'
                ]
            ]
        ],

        // 4. التواصل والدعم (section groupée)
        [
            'section' => true,
            'title' => __('التواصل والدعم'),
            'icon' => 'chat-dots',
            'items' => [
                [
                    'title' => __('المحادثات'),
                    'icon' => 'chat',
                    'url' => '/client/conversation/influencers',
                    'route' => 'user.conversation.index'
                ],
                [
                    'title' => __('بطاقة الدعم'),
                    'icon' => 'ticket',
                    'url' => '/ticket',
                    'route' => 'ticket'
                ]
            ]
        ],

        // 5. الحساب والمعاملات (section groupée)
        [
            'section' => true,
            'title' => __('الحساب والمعاملات'),
            'icon' => 'wallet2',
            'items' => [
                [
                    'title' => __('الإيداع'),
                    'icon' => 'cash-stack',
                    'url' => '/client/deposit/history',
                    'route' => 'user.deposit.history'
                ],
                [
                    'title' => __('المعاملات'),
                    'icon' => 'arrow-left-right',
                    'url' => '/client/transactions',
                    'route' => 'user.transactions'
                ],
                [
                    'title' => __('الآراء'),
                    'icon' => 'star',
                    'url' => '/client/review/order/index',
                    'route' => 'user.review.order.index'
                ],
                [
                    'title' => __('القائمة المفضلة'),
                    'icon' => 'heart',
                    'url' => '/client/favorite/list',
                    'route' => 'user.favorite.list'
                ]
            ]
        ]
    ];

    // Structure pour les influenceurs (garde la structure existante)
    $influencerSidebarSections = [
        [
            'title' => __('dashboard.home'),
            'icon' => 'house',
            'route' => 'influencer.dashboard',
            'url' => '/influencer/dashboard'
        ],
        [
            'title' => __('dashboard.withdraws'),
            'icon' => 'cash-coin',
            'route' => 'influencer.withdraw.history',
            'url' => '/influencer/withdraw/history'
        ],
        [
            'title' => __('dashboard.services'),
            'icon' => 'briefcase',
            'route' => 'influencer.service.all',
            'url' => '/influencer/service/all'
        ],
        [
            'title' => __('dashboard.support_tickets'),
            'icon' => 'ticket',
            'route' => 'influencer.ticket.all',
            'url' => '/influencer/ticket/all'
        ],
        [
            'title' => __('dashboard.campaigns'),
            'icon' => 'megaphone',
            'route' => 'influencer.campaigns',
            'url' => '/influencer/campains'
        ],
        [
            'title' => __('dashboard.job_offers'),
            'icon' => 'person-plus',
            'route' => 'influencer.hirings',
            'url' => '/influencer/hirings'
        ],
        [
            'title' => __('dashboard.transactions'),
            'icon' => 'arrow-left-right',
            'route' => 'influencer.transactions',
            'url' => '/influencer/transactions'
        ],
    ];

    $sidebarSections = $isInfluencer ? $influencerSidebarSections : $clientSidebarSections;
@endphp

{{-- Fixed Sidebar with new organized structure --}}
<aside
    class="sidebar-fixed {{ request()->routeIs('conversation.*') ? '' : 'd-lg-none' }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    style="width: 260px;"
>
    {{-- Navigation List --}}
    <nav class="h-100 d-flex flex-column p-3 pt-4" style="overflow-y: auto;">
        @if($isInfluencer)
            {{-- Mode simple pour influenceurs --}}
            @foreach($sidebarSections as $item)
                @php
                    $isActive = false;
                    if (isset($item['route']) && request()->routeIs($item['route'] . '*')) {
                        $isActive = true;
                    } elseif (isset($item['url']) && str_starts_with('/' . $currentPath, $item['url'])) {
                        $isActive = true;
                    }
                @endphp

                <div class="mb-2">
                    <a
                        href="{{ $item['url'] }}"
                        class="btn {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }} w-100 d-flex align-items-center text-start"
                        onclick="window.showProgressBar()"
                    >
                        <i class="bi bi-{{ $item['icon'] }} me-3" style="font-size: 1rem;"></i>
                        <span>{{ $item['title'] }}</span>
                        @if($isActive)
                            <i class="bi bi-circle-fill ms-auto text-light" style="font-size: 0.5rem;"></i>
                        @endif
                    </a>
                </div>
            @endforeach
        @else
            {{-- Mode organisé pour clients --}}
            @foreach($sidebarSections as $section)
                @if(isset($section['section']) && $section['section'])
                    {{-- Section groupée collapsible --}}
                    @php
                        $sectionId = 'section-' . slug($section['title']);
                        $hasActiveItem = false;
                        foreach($section['items'] as $item) {
                            if ((isset($item['route']) && request()->routeIs($item['route'] . '*')) ||
                                (isset($item['url']) && str_starts_with('/' . $currentPath, $item['url']))) {
                                $hasActiveItem = true;
                                break;
                            }
                        }
                    @endphp

                    <div class="mb-3">
                        {{-- Section Header --}}
                        <button
                            class="btn {{ $hasActiveItem ? 'btn-primary' : 'btn-outline-secondary' }} w-100 d-flex align-items-center text-start"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#{{ $sectionId }}"
                            aria-expanded="{{ $hasActiveItem ? 'true' : 'false' }}"
                        >
                            <i class="bi bi-{{ $section['icon'] }} me-3" style="font-size: 1rem;"></i>
                            <span class="flex-grow-1">{{ $section['title'] }}</span>
                            <i class="bi bi-chevron-down transition-transform" style="font-size: 0.8rem;"></i>
                        </button>

                        {{-- Section Items --}}
                        <div class="collapse {{ $hasActiveItem ? 'show' : '' }}" id="{{ $sectionId }}">
                            <div class="mt-2 ps-3">
                                @foreach($section['items'] as $item)
                                    @php
                                        $isActive = false;
                                        if (isset($item['route']) && request()->routeIs($item['route'] . '*')) {
                                            $isActive = true;
                                        } elseif (isset($item['url']) && str_starts_with('/' . $currentPath, $item['url'])) {
                                            $isActive = true;
                                        }
                                    @endphp

                                    <div class="mb-1">
                                        <a
                                            href="{{ $item['url'] }}"
                                            class="btn {{ $isActive ? 'btn-sm btn-primary' : 'btn-sm btn-outline-light' }} w-100 d-flex align-items-center text-start"
                                            onclick="window.showProgressBar()"
                                        >
                                            <i class="bi bi-{{ $item['icon'] }} me-2" style="font-size: 0.9rem;"></i>
                                            <span style="font-size: 0.9rem;">{{ $item['title'] }}</span>
                                            @if($isActive)
                                                <i class="bi bi-circle-fill ms-auto text-light" style="font-size: 0.4rem;"></i>
                                            @endif
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Item individuel (non groupé) --}}
                    @php
                        $isActive = false;
                        if (isset($section['route']) && request()->routeIs($section['route'] . '*')) {
                            $isActive = true;
                        } elseif (isset($section['url']) && str_starts_with('/' . $currentPath, $section['url'])) {
                            $isActive = true;
                        }
                    @endphp

                    <div class="mb-2">
                        <a
                            href="{{ $section['url'] }}"
                            class="btn {{ $isActive ? 'btn-primary' : 'btn-outline-secondary' }} w-100 d-flex align-items-center text-start"
                            onclick="window.showProgressBar()"
                        >
                            <i class="bi bi-{{ $section['icon'] }} me-3" style="font-size: 1rem;"></i>
                            <span>{{ $section['title'] }}</span>
                            @if($isActive)
                                <i class="bi bi-circle-fill ms-auto text-light" style="font-size: 0.5rem;"></i>
                            @endif
                        </a>
                    </div>
                @endif
            @endforeach
        @endif

        {{-- Spacer to push settings to bottom --}}
        <div class="flex-grow-1"></div>

        {{-- Settings Link --}}
        <div class="mt-auto border-top pt-3">
            <a
                href="{{ $isInfluencer ? '/influencer/profile-setting' : '/client/profile-setting' }}"
                class="btn btn-outline-secondary w-100 d-flex align-items-center text-start"
                onclick="window.showProgressBar()"
            >
                <i class="bi bi-gear me-3" style="font-size: 1rem;"></i>
                <span>{{ __('إعدادات الحساب') }}</span>
            </a>
        </div>
    </nav>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-lg-none"
    style="z-index: 1040;"
    @click="sidebarOpen = false"
></div>

{{-- Mobile Sidebar --}}
<aside
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-200 transform"
    x-transition:enter-start="{{ app()->getLocale() === 'ar' ? 'translate-x-100' : '-translate-x-100' }}"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="{{ app()->getLocale() === 'ar' ? 'translate-x-100' : '-translate-x-100' }}"
    class="position-fixed {{ app()->getLocale() === 'ar' ? 'end-0' : 'start-0' }} top-0 h-100 bg-white shadow-lg d-lg-none"
    style="width: 280px; z-index: 1050; top: 70px;"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
>
    {{-- Mobile Navigation Header --}}
    <div class="p-3 border-bottom">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold">{{ __('dashboard.navigation') }}</h6>
            <button
                type="button"
                class="btn-close"
                @click="sidebarOpen = false"
                aria-label="{{ __('Close') }}"
            ></button>
        </div>
    </div>

    {{-- Mobile Navigation List --}}
    <nav class="p-3" style="overflow-y: auto;">
        <ul class="list-unstyled mb-0">
            @if($isInfluencer)
                {{-- Mode simple pour influenceurs --}}
                @foreach($sidebarSections as $item)
                    @php
                        $isActive = false;
                        if (isset($item['route']) && request()->routeIs($item['route'] . '*')) {
                            $isActive = true;
                        } elseif (isset($item['url']) && str_starts_with('/' . $currentPath, $item['url'])) {
                            $isActive = true;
                        }
                    @endphp

                    <li class="mb-2">
                        <a
                            href="{{ $item['url'] }}"
                            class="btn {{ $isActive ? 'btn-primary' : 'btn-outline-light' }} w-100 d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'text-end' : 'text-start' }}"
                            @click="sidebarOpen = false"
                            onclick="window.showProgressBar()"
                        >
                            <i class="bi bi-{{ $item['icon'] }} {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}" style="font-size: 1.1rem;"></i>
                            <span>{{ $item['title'] }}</span>
                            @if($isActive)
                                <i class="bi bi-circle-fill {{ app()->getLocale() === 'ar' ? 'me-auto' : 'ms-auto' }} text-primary" style="font-size: 0.5rem;"></i>
                            @endif
                        </a>
                    </li>
                @endforeach
            @else
                {{-- Mode organisé pour clients --}}
                @foreach($sidebarSections as $section)
                    @if(isset($section['section']) && $section['section'])
                        {{-- Section groupée collapsible --}}
                        @php
                            $sectionId = 'mobile-section-' . slug($section['title']);
                            $hasActiveItem = false;
                            foreach($section['items'] as $item) {
                                if ((isset($item['route']) && request()->routeIs($item['route'] . '*')) ||
                                    (isset($item['url']) && str_starts_with('/' . $currentPath, $item['url']))) {
                                    $hasActiveItem = true;
                                    break;
                                }
                            }
                        @endphp

                        <li class="mb-3">
                            {{-- Section Header --}}
                            <button
                                class="btn {{ $hasActiveItem ? 'btn-primary' : 'btn-outline-secondary' }} w-100 d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'text-end' : 'text-start' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $sectionId }}"
                                aria-expanded="{{ $hasActiveItem ? 'true' : 'false' }}"
                            >
                                <i class="bi bi-{{ $section['icon'] }} {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}" style="font-size: 1.1rem;"></i>
                                <span class="flex-grow-1">{{ $section['title'] }}</span>
                                <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                            </button>

                            {{-- Section Items --}}
                            <div class="collapse {{ $hasActiveItem ? 'show' : '' }}" id="{{ $sectionId }}">
                                <ul class="list-unstyled mt-2 {{ app()->getLocale() === 'ar' ? 'pe-3' : 'ps-3' }}">
                                    @foreach($section['items'] as $item)
                                        @php
                                            $isActive = false;
                                            if (isset($item['route']) && request()->routeIs($item['route'] . '*')) {
                                                $isActive = true;
                                            } elseif (isset($item['url']) && str_starts_with('/' . $currentPath, $item['url'])) {
                                                $isActive = true;
                                            }
                                        @endphp

                                        <li class="mb-1">
                                            <a
                                                href="{{ $item['url'] }}"
                                                class="btn {{ $isActive ? 'btn-sm btn-primary' : 'btn-sm btn-outline-light' }} w-100 d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'text-end' : 'text-start' }}"
                                                @click="sidebarOpen = false"
                                                onclick="window.showProgressBar()"
                                            >
                                                <i class="bi bi-{{ $item['icon'] }} {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}" style="font-size: 0.9rem;"></i>
                                                <span style="font-size: 0.9rem;">{{ $item['title'] }}</span>
                                                @if($isActive)
                                                    <i class="bi bi-circle-fill {{ app()->getLocale() === 'ar' ? 'me-auto' : 'ms-auto' }} text-light" style="font-size: 0.4rem;"></i>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @else
                        {{-- Item individuel (non groupé) --}}
                        @php
                            $isActive = false;
                            if (isset($section['route']) && request()->routeIs($section['route'] . '*')) {
                                $isActive = true;
                            } elseif (isset($section['url']) && str_starts_with('/' . $currentPath, $section['url'])) {
                                $isActive = true;
                            }
                        @endphp

                        <li class="mb-2">
                            <a
                                href="{{ $section['url'] }}"
                                class="btn {{ $isActive ? 'btn-primary' : 'btn-outline-light' }} w-100 d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'text-end' : 'text-start' }}"
                                @click="sidebarOpen = false"
                                onclick="window.showProgressBar()"
                            >
                                <i class="bi bi-{{ $section['icon'] }} {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}" style="font-size: 1.1rem;"></i>
                                <span>{{ $section['title'] }}</span>
                                @if($isActive)
                                    <i class="bi bi-circle-fill {{ app()->getLocale() === 'ar' ? 'me-auto' : 'ms-auto' }} text-primary" style="font-size: 0.5rem;"></i>
                                @endif
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif

            {{-- Mobile Settings Link --}}
            <li class="mt-4 pt-3 border-top">
                <a
                    href="{{ $isInfluencer ? '/influencer/profile-setting' : '/client/profile-setting' }}"
                    class="btn btn-outline-secondary w-100 d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'text-end' : 'text-start' }}"
                    @click="sidebarOpen = false"
                    onclick="window.showProgressBar()"
                >
                    <i class="bi bi-gear {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}" style="font-size: 1.1rem;"></i>
                    <span>{{ __('إعدادات الحساب') }}</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

{{-- Custom styles for collapsible sections --}}
@push('styles')
<style>
    .sidebar-fixed {
        position: fixed;
        top: 70px;
        left: 0;
        height: calc(100vh - 70px);
        background-color: #ffffff;
        border-right: 1px solid #e9ecef;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        z-index: 1020;
    }

    /* RTL Support */
    [dir="rtl"] .sidebar-fixed {
        left: auto;
        right: 0;
        border-right: none;
        border-left: 1px solid #e9ecef;
        box-shadow: -2px 0 10px rgba(0,0,0,0.1);
    }

    /* Collapse animation improvements */
    .sidebar-fixed .collapse {
        transition: height 0.35s ease;
    }

    /* Chevron rotation animation */
    .sidebar-fixed .bi-chevron-down {
        transition: transform 0.35s ease;
    }

    .sidebar-fixed [aria-expanded="true"] .bi-chevron-down {
        transform: rotate(180deg);
    }

    /* Hover effects for section headers */
    .sidebar-fixed .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    /* Active section highlighting */
    .sidebar-fixed .btn-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        border: none;
    }

    /* Sub-item styling */
    .sidebar-fixed .btn-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }

    /* Responsive width adjustment */
    @media (max-width: 991.98px) {
        .sidebar-fixed {
            display: none !important;
        }
    }

    /* Scroll styling */
    .sidebar-fixed nav::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-fixed nav::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .sidebar-fixed nav::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }

    .sidebar-fixed nav::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

{{-- Initialize sidebar functionality --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle smooth collapse animations
        const collapseElements = document.querySelectorAll('.sidebar-fixed .collapse');

        collapseElements.forEach(collapse => {
            collapse.addEventListener('show.bs.collapse', function() {
                // Rotate chevron when opening
                const button = document.querySelector(`[data-bs-target="#${this.id}"] .bi-chevron-down`);
                if (button) {
                    button.style.transform = 'rotate(180deg)';
                }
            });

            collapse.addEventListener('hide.bs.collapse', function() {
                // Rotate chevron when closing
                const button = document.querySelector(`[data-bs-target="#${this.id}"] .bi-chevron-down`);
                if (button) {
                    button.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Initialize tooltips if needed for small icons
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // Handle tooltip cleanup on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth < 992) {
                tooltipList.forEach(tooltip => tooltip.dispose());
            }
        });

        // Add ripple effect on button clicks
        const sidebarButtons = document.querySelectorAll('.sidebar-fixed .btn');
        sidebarButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Create ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255,255,255,0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.pointerEvents = 'none';

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    });

    // CSS animation for ripple effect
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush