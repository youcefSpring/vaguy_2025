{{--
    Main Navigation Bar

    This partial provides the top navigation bar for the dashboard.
    It's converted from the React Navbar component to Bootstrap/Alpine.js.

    Features:
    - Responsive design with mobile menu
    - Language switcher (Arabic RTL, French, English)
    - User profile dropdown
    - Message and notification links
    - Logo with smart routing
    - Bootstrap dropdown components
--}}

@php
    $currentPath = request()->path();
    $isInfluencer = str_starts_with($currentPath, 'influencer') || (auth()->user() && auth()->user()->user_type === 'influencer');
    $currentLang = app()->getLocale();

    // Logo route logic
    $logoRoute = '/';
    if (auth()->check()) {
        if ($isInfluencer && !str_contains($currentPath, '/influencer/profile/')) {
            $logoRoute = '/influencer/dashboard';
        } else {
            $logoRoute = '/client/dashboard';
        }
    }

    // Navigation items for client dashboard
    $navigationItems = [
        [
            'title' => __('navbar.dashboard'),
            'url' => '/client/dashboard',
            'icon' => 'house'
        ],
        [
            'title' => __('navbar.business'),
            'icon' => 'briefcase',
            'dropdown' => [
                [
                    'title' => __('navbar.campaigns'),
                    'url' => '/client/campaign',
                    'icon' => 'megaphone',
                    'description' => __('navbar.manage_existing_campaigns')
                ],
                [
                    'title' => __('navbar.new_campaign'),
                    'url' => route('add_campaign'),
                    'icon' => 'plus-circle',
                    'description' => __('navbar.create_new_campaign')
                ],
                [
                    'title' => __('navbar.orders'),
                    'url' => '/client/order/all',
                    'icon' => 'box',
                    'description' => __('navbar.ordered_services')
                ],
                [
                    'title' => __('navbar.hiring'),
                    'url' => '/client/hiring/all',
                    'icon' => 'person-plus',
                    'description' => __('navbar.hired_influencers')
                ]
            ]
        ],
        [
            'title' => __('navbar.finance'),
            'icon' => 'cash-coin',
            'dropdown' => [
                [
                    'title' => __('navbar.deposit'),
                    'url' => '/client/deposit/manual',
                    'icon' => 'wallet',
                    'description' => __('navbar.add_funds_account')
                ],
                [
                    'title' => __('navbar.deposit_history'),
                    'url' => '/client/deposit/history',
                    'icon' => 'clock-history',
                    'description' => __('navbar.view_all_deposits')
                ],
                [
                    'title' => __('navbar.transactions'),
                    'url' => '/client/transactions',
                    'icon' => 'arrow-left-right',
                    'description' => __('navbar.transaction_history')
                ]
            ]
        ],
        [
            'title' => __('navbar.help'),
            'icon' => 'question-circle',
            'dropdown' => [
                [
                    'title' => __('navbar.new_ticket'),
                    'url' => '/ticket/new',
                    'icon' => 'ticket',
                    'description' => __('navbar.create_support_ticket')
                ],
                [
                    'title' => __('navbar.my_tickets'),
                    'url' => '/ticket',
                    'icon' => 'list-task',
                    'description' => __('navbar.track_support_requests')
                ],
                [
                    'title' => __('navbar.influencers'),
                    'url' => '/getinf',
                    'icon' => 'people',
                    'description' => __('navbar.discover_influencers')
                ],
                [
                    'title' => __('navbar.services'),
                    'url' => '/services',
                    'icon' => 'gear',
                    'description' => __('navbar.browse_services')
                ],
                [
                    'title' => __('navbar.analysis'),
                    'url' => '/scrapeProfileInstagram',
                    'icon' => 'graph-up',
                    'description' => __('navbar.analyze_profiles')
                ]
            ]
        ]
    ];
@endphp

{{-- Main Navigation Bar --}}
<nav class="navbar navbar-expand-lg navbar-fixed bg-white shadow-sm" dir="{{ $currentLang === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="container-fluid px-4">
        {{-- Logo --}}
        <div class="navbar-brand me-0">
            <a href="{{ $logoRoute }}" class="d-flex align-items-center text-decoration-none">
                <img
                    src="{{ asset('assets/logoIcon/logo_dark.png') }}"
                    alt="Vaguy"
                    class="img-fluid"
                    style="height: 2.5rem; padding: 0.25rem;"
                >
            </a>
        </div>

        {{-- Navigation Menu (Hidden on influencer dashboard pages, shown for public influencer profiles) --}}
        @if(!$isInfluencer || str_contains($currentPath, '/influencer/profile/'))
            <div class="d-none d-lg-flex flex-grow-1 justify-content-center mx-4">
                <ul class="navbar-nav gap-1">
                    @foreach($navigationItems as $item)
                        <li class="nav-item {{ isset($item['dropdown']) ? 'dropdown' : '' }}">
                            @if(isset($item['dropdown']))
                                {{-- Dropdown Menu Item --}}
                                <a
                                    class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 fw-medium"
                                    href="#"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <i class="bi bi-{{ $item['icon'] }}" style="font-size: 0.875rem;"></i>
                                    <span class="d-none d-xl-inline">{{ $item['title'] }}</span>
                                    <span class="d-xl-none">{{ substr($item['title'], 0, 3) }}</span>
                                </a>

                                {{-- Dropdown Menu --}}
                                <ul class="dropdown-menu shadow-lg border-0" style="min-width: 280px;">
                                    @foreach($item['dropdown'] as $dropdownItem)
                                        <li>
                                            <a
                                                href="{{ $dropdownItem['url'] }}"
                                                class="dropdown-item d-flex align-items-start gap-3 p-3"
                                                onclick="window.showProgressBar()"
                                            >
                                                <i class="bi bi-{{ $dropdownItem['icon'] }} text-primary mt-1 flex-shrink-0" style="font-size: 1.1rem;"></i>
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium text-dark">{{ $dropdownItem['title'] }}</div>
                                                    @if(isset($dropdownItem['description']))
                                                        <small class="text-muted">{{ $dropdownItem['description'] }}</small>
                                                    @endif
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                {{-- Single Menu Item --}}
                                <a
                                    href="{{ $item['url'] }}"
                                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 fw-medium"
                                    onclick="window.showProgressBar()"
                                >
                                    <i class="bi bi-{{ $item['icon'] }}" style="font-size: 0.875rem;"></i>
                                    <span class="d-none d-xl-inline">{{ $item['title'] }}</span>
                                    <span class="d-xl-none">{{ substr($item['title'], 0, 3) }}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Mobile Menu Button (Only for client pages) --}}
        @if(!$isInfluencer)
            <button
                class="btn btn-outline-secondary d-lg-none me-2"
                type="button"
                @click="$store.ui.toggleSidebar()"
                style="width: 2.5rem; height: 2.5rem;"
            >
                <i class="bi bi-list" style="font-size: 1.25rem;"></i>
            </button>
        @endif

        {{-- Right Side Actions --}}
        <div class="d-flex align-items-center gap-2">
            {{-- Language Selector --}}
            <div class="dropdown">
                <button
                    class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2 px-3 py-2"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="bi bi-translate" style="font-size: 0.875rem;"></i>
                    <span class="d-none d-sm-inline">{{ strtoupper($currentLang) }}</span>
                </button>

                <ul class="dropdown-menu">
                    <li>
                        <button
                            type="button"
                            class="dropdown-item {{ $currentLang === 'fr' ? 'active' : '' }}"
                            onclick="window.changeLanguage('fr')"
                        >
                            <div class="fw-medium">Français</div>
                            <small class="text-muted">Langue française</small>
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="dropdown-item {{ $currentLang === 'en' ? 'active' : '' }}"
                            onclick="window.changeLanguage('en')"
                        >
                            <div class="fw-medium">English</div>
                            <small class="text-muted">English language</small>
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="dropdown-item {{ $currentLang === 'ar' ? 'active' : '' }}"
                            onclick="window.changeLanguage('ar')"
                        >
                            <div class="fw-medium">العربية</div>
                            <small class="text-muted">اللغة العربية</small>
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Messages Link --}}
            <a
                href="{{ $isInfluencer ? '/influencer/conversation/index' : '/client/conversation/influencers' }}"
                class="btn btn-outline-secondary d-flex align-items-center justify-content-center"
                style="width: 2.5rem; height: 2.5rem;"
                data-bs-toggle="tooltip"
                data-bs-title="{{ __('navbar.messages') }}"
                onclick="window.showProgressBar()"
            >
                <i class="bi bi-chat" style="font-size: 1rem;"></i>
            </a>

            {{-- Notifications --}}
            <button
                type="button"
                class="btn btn-outline-secondary d-flex align-items-center justify-content-center position-relative"
                style="width: 2.5rem; height: 2.5rem;"
                data-bs-toggle="tooltip"
                data-bs-title="{{ __('navbar.notifications') }}"
            >
                <i class="bi bi-bell" style="font-size: 1rem;"></i>
                {{-- Notification badge (if there are unread notifications) --}}
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    3
                    <span class="visually-hidden">{{ __('navbar.unread_notifications') }}</span>
                </span>
            </button>

            {{-- User Profile Dropdown --}}
            @auth
                @include('partials.profile-menu')
            @else
                {{-- Login Button for guests --}}
                <a href="{{ localized_route('login') }}" class="btn btn-primary">
                    {{ __('navbar.login') }}
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- Initialize tooltips --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>
@endpush