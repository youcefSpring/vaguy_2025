{{--
    User Profile Dropdown Menu

    This partial provides the user profile dropdown menu for authenticated users.
    It's converted from the React ProfileMenu component to Bootstrap/Alpine.js.

    Features:
    - User avatar display
    - Quick access to profile and settings
    - Account management links
    - Logout functionality
    - User type specific links (influencer vs client)
--}}

@php
    $user = auth()->user();
    $currentPath = request()->path();
    $isInfluencer = str_starts_with($currentPath, 'influencer') || ($user && $user->user_type === 'influencer');

    // Get user avatar or default
    $userAvatar = $user->image ? asset('storage/users/' . $user->image) : asset('assets/images/default-avatar.png');

    // Profile menu items
    $profileMenuItems = [
        [
            'title' => __('profile.view_profile'),
            'url' => $isInfluencer ? '/influencer/profile' : '/client/profile',
            'icon' => 'person'
        ],
        [
            'title' => __('profile.account_settings'),
            'url' => $isInfluencer ? '/influencer/settings' : '/client/settings',
            'icon' => 'gear'
        ],
        [
            'title' => __('profile.change_password'),
            'url' => $isInfluencer ? '/influencer/password' : '/client/password',
            'icon' => 'key'
        ]
    ];

    // Additional menu items based on user type
    if ($isInfluencer) {
        $profileMenuItems[] = [
            'title' => __('profile.my_services'),
            'url' => '/influencer/service/all',
            'icon' => 'briefcase'
        ];
        $profileMenuItems[] = [
            'title' => __('profile.withdrawals'),
            'url' => '/influencer/withdraw/history',
            'icon' => 'cash-coin'
        ];
    } else {
        $profileMenuItems[] = [
            'title' => __('profile.order_reviews'),
            'url' => '/client/review/all',
            'icon' => 'star'
        ];
        $profileMenuItems[] = [
            'title' => __('profile.favorites_list'),
            'url' => '/client/favorite/all',
            'icon' => 'heart'
        ];
        $profileMenuItems[] = [
            'title' => __('profile.two_factor_security'),
            'url' => '/client/2fa',
            'icon' => 'shield-check'
        ];
    }
@endphp

{{-- Profile Dropdown --}}
<div class="dropdown">
    <button
        class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2 px-3 py-2"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        data-bs-auto-close="outside"
    >
        {{-- User Avatar --}}
        <div class="rounded-circle overflow-hidden bg-light d-flex align-items-center justify-content-center" style="width: 1.75rem; height: 1.75rem;">
            @if($user->image)
                <img
                    src="{{ $userAvatar }}"
                    alt="{{ $user->firstname ?? $user->username }}"
                    class="w-100 h-100 object-fit-cover"
                >
            @else
                <i class="bi bi-person-fill text-muted" style="font-size: 1rem;"></i>
            @endif
        </div>

        {{-- User Name (hidden on mobile) --}}
        <span class="d-none d-md-inline fw-medium">
            {{ $user->firstname ?? $user->username ?? __('User') }}
        </span>
    </button>

    {{-- Dropdown Menu --}}
    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 280px;">
        {{-- User Info Header --}}
        <li class="px-4 py-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                {{-- User Avatar (larger) --}}
                <div class="rounded-circle overflow-hidden bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width: 3rem; height: 3rem;">
                    @if($user->image)
                        <img
                            src="{{ $userAvatar }}"
                            alt="{{ $user->firstname ?? $user->username }}"
                            class="w-100 h-100 object-fit-cover"
                        >
                    @else
                        <i class="bi bi-person-fill text-muted" style="font-size: 1.5rem;"></i>
                    @endif
                </div>

                {{-- User Details --}}
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold text-truncate">
                        {{ $user->firstname && $user->lastname ? $user->firstname . ' ' . $user->lastname : ($user->username ?? __('User')) }}
                    </div>
                    <small class="text-muted text-truncate d-block">{{ $user->email }}</small>
                    @if($user->user_type)
                        <span class="badge bg-{{ $isInfluencer ? 'success' : 'primary' }} bg-opacity-10 text-{{ $isInfluencer ? 'success' : 'primary' }}" style="font-size: 0.7rem;">
                            {{ $isInfluencer ? __('Influencer') : __('Client') }}
                        </span>
                    @endif
                </div>
            </div>
        </li>

        {{-- Menu Items --}}
        @foreach($profileMenuItems as $item)
            <li>
                <a
                    href="{{ $item['url'] }}"
                    class="dropdown-item d-flex align-items-center gap-3 py-2 px-4"
                    onclick="window.showProgressBar()"
                >
                    <i class="bi bi-{{ $item['icon'] }} text-muted flex-shrink-0" style="font-size: 1rem; width: 1rem;"></i>
                    <span>{{ $item['title'] }}</span>
                </a>
            </li>
        @endforeach

        {{-- Divider --}}
        <li><hr class="dropdown-divider my-2"></li>

        {{-- Balance Display (for clients) --}}
        @if(!$isInfluencer && isset($user->balance))
            <li class="px-4 py-2">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-wallet text-success flex-shrink-0" style="font-size: 1rem; width: 1rem;"></i>
                    <div class="flex-grow-1">
                        <small class="text-muted d-block">{{ __('profile.current_balance') }}</small>
                        <span class="fw-semibold text-success">{{ number_format($user->balance, 2) }} {{ config('app.currency', 'DZD') }}</span>
                    </div>
                </div>
            </li>
            <li><hr class="dropdown-divider my-2"></li>
        @endif

        {{-- Theme Toggle (future feature) --}}
        <li class="px-4 py-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-moon text-muted flex-shrink-0" style="font-size: 1rem; width: 1rem;"></i>
                    <span>{{ __('profile.dark_mode') }}</span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="darkModeSwitch">
                </div>
            </div>
        </li>

        {{-- Divider --}}
        <li><hr class="dropdown-divider my-2"></li>

        {{-- Logout --}}
        <li>
            <form method="POST" action="{{ localized_route('user.logout') }}" class="m-0">
                @csrf
                <button
                    type="submit"
                    class="dropdown-item d-flex align-items-center gap-3 py-2 px-4 text-danger"
                    onclick="return confirm('{{ __('profile.confirm_logout') }}')"
                >
                    <i class="bi bi-box-arrow-right flex-shrink-0" style="font-size: 1rem; width: 1rem;"></i>
                    <span>{{ __('profile.logout') }}</span>
                </button>
            </form>
        </li>
    </ul>
</div>

{{-- Dark mode toggle functionality --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeSwitch = document.getElementById('darkModeSwitch');

        if (darkModeSwitch) {
            // Check current theme preference
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme === 'dark') {
                darkModeSwitch.checked = true;
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }

            // Handle theme toggle
            darkModeSwitch.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    localStorage.setItem('theme', 'light');
                }
            });
        }
    });
</script>
@endpush