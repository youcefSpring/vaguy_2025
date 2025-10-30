<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full bg-gray-50" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Client dashboard for {{ config('app.name') }}">
    <meta name="keywords" content="dashboard, client, management">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logoIcon/favicon.png') }}" sizes="16x16">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js for dashboard charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Vue.js 3 CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <!-- Axios for HTTP requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- jQuery (required for some features) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Shadcn UI styles -->
    <style>
        :root {
            --background: 0 0% 100%;
            --foreground: 222.2 84% 4.9%;
            --card: 0 0% 100%;
            --card-foreground: 222.2 84% 4.9%;
            --popover: 0 0% 100%;
            --popover-foreground: 222.2 84% 4.9%;
            --primary: 221.2 83.2% 53.3%;
            --primary-foreground: 210 40% 98%;
            --secondary: 210 40% 96%;
            --secondary-foreground: 222.2 84% 4.9%;
            --muted: 210 40% 96%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --accent: 210 40% 96%;
            --accent-foreground: 222.2 84% 4.9%;
            --destructive: 0 84.2% 60.2%;
            --destructive-foreground: 210 40% 98%;
            --border: 214.3 31.8% 91.4%;
            --input: 214.3 31.8% 91.4%;
            --ring: 221.2 83.2% 53.3%;
            --radius: 0.75rem;
        }

        .dark {
            --background: 222.2 84% 4.9%;
            --foreground: 210 40% 98%;
            --card: 222.2 84% 4.9%;
            --card-foreground: 210 40% 98%;
            --popover: 222.2 84% 4.9%;
            --popover-foreground: 210 40% 98%;
            --primary: 217.2 91.2% 59.8%;
            --primary-foreground: 222.2 84% 4.9%;
            --secondary: 217.2 32.6% 17.5%;
            --secondary-foreground: 210 40% 98%;
            --muted: 217.2 32.6% 17.5%;
            --muted-foreground: 215 20.2% 65.1%;
            --accent: 217.2 32.6% 17.5%;
            --accent-foreground: 210 40% 98%;
            --destructive: 0 62.8% 30.6%;
            --destructive-foreground: 210 40% 98%;
            --border: 217.2 32.6% 17.5%;
            --input: 217.2 32.6% 17.5%;
            --ring: 224.3 76.3% 94.1%;
        }

        * {
            border-color: hsl(var(--border));
        }

        body {
            background-color: hsl(var(--background));
            color: hsl(var(--foreground));
        }

        /* Shadcn UI Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            border-radius: calc(var(--radius) - 2px);
            font-size: 0.875rem;
            font-weight: 500;
            transition: colors 0.2s;
            outline: none;
            border: 1px solid transparent;
            cursor: pointer;
            padding: 0.5rem 1rem;
        }

        .btn:focus-visible {
            outline: 2px solid hsl(var(--ring));
            outline-offset: 2px;
        }

        .btn:disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .btn-primary {
            background-color: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
        }

        .btn-primary:hover {
            background-color: hsl(var(--primary) / 0.9);
        }

        .btn-secondary {
            background-color: hsl(var(--secondary));
            color: hsl(var(--secondary-foreground));
        }

        .btn-secondary:hover {
            background-color: hsl(var(--secondary) / 0.8);
        }

        .btn-outline {
            border: 1px solid hsl(var(--border));
            background-color: hsl(var(--background));
            color: hsl(var(--foreground));
        }

        .btn-outline:hover {
            background-color: hsl(var(--accent));
            color: hsl(var(--accent-foreground));
        }

        /* Card styles */
        .card {
            border-radius: calc(var(--radius));
            border: 1px solid hsl(var(--border));
            background-color: hsl(var(--card));
            color: hsl(var(--card-foreground));
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .card-content {
            padding: 1.5rem;
        }

        /* Input styles */
        .input {
            display: flex;
            height: 2.5rem;
            width: 100%;
            border-radius: calc(var(--radius) - 2px);
            border: 1px solid hsl(var(--input));
            background-color: hsl(var(--background));
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: border-color 0.2s;
            outline: none;
        }

        .input:focus {
            border-color: hsl(var(--ring));
            box-shadow: 0 0 0 2px hsl(var(--ring) / 0.2);
        }

        /* Form styles */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: hsl(var(--foreground));
        }

        .form-error {
            font-size: 0.75rem;
            color: hsl(var(--destructive));
            margin-top: 0.25rem;
        }

        /* Badge styles */
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: calc(var(--radius) - 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .badge-primary {
            background-color: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
        }

        .badge-secondary {
            background-color: hsl(var(--secondary));
            color: hsl(var(--secondary-foreground));
        }

        .badge-success {
            background-color: hsl(142.1 76.2% 36.3%);
            color: hsl(355.7 100% 97.3%);
        }

        .badge-warning {
            background-color: hsl(32.2 95% 44%);
            color: hsl(355.7 100% 97.3%);
        }

        .badge-danger {
            background-color: hsl(var(--destructive));
            color: hsl(var(--destructive-foreground));
        }

        /* Dark Mode Support */
        .dark {
            background-color: #111827;
            color: #f9fafb;
        }

        .dark .bg-white {
            background-color: #1f2937;
        }

        .dark .bg-gray-50 {
            background-color: #374151;
        }

        .dark .text-gray-900 {
            color: #f9fafb;
        }

        .dark .text-gray-800 {
            color: #f3f4f6;
        }

        .dark .text-gray-700 {
            color: #e5e7eb;
        }

        .dark .text-gray-600 {
            color: #d1d5db;
        }

        .dark .text-gray-500 {
            color: #9ca3af;
        }

        .dark .border-gray-200 {
            border-color: #374151;
        }

        .dark .border-gray-100 {
            border-color: #374151;
        }

        .dark .divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #374151;
        }

        /* RTL Support */
        [dir="rtl"] .ml-auto {
            margin-left: 0;
            margin-right: auto;
        }

        [dir="rtl"] .mr-auto {
            margin-right: 0;
            margin-left: auto;
        }

        [dir="rtl"] {
            direction: rtl;
            text-align: right;
        }

        /* Custom responsive utilities */
        @media (max-width: 768px) {
            .card-content {
                padding: 1rem;
            }
        }

        /* Smooth transitions for dark mode */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
    </style>

    @stack('styles')
    @stack('style')
</head>

<body class="h-full">
    <!-- Modern Loading Screen -->
    <div id="page-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
        <div class="text-center">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('assets/images/logoIcon/favicon.png') }}" alt="Logo" class="w-16 h-16 mx-auto">
            </div>

            <!-- Animated Loader -->
            <div class="relative">
                <!-- Spinning Circle -->
                <div class="w-16 h-16 mx-auto border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>

                <!-- Pulsing Dots -->
                <div class="flex justify-center space-x-2 mt-6">
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse-dot"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse-dot"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse-dot"></div>
                </div>
            </div>

            <!-- Loading Text -->
            <p class="mt-6 text-sm text-gray-600 font-medium">{{ __('common.loading') }}...</p>
        </div>
    </div>

    <!-- AJAX Loading Overlay -->
    <div id="ajax-loader" class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-4">
                <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                <span class="text-gray-700 font-medium">{{ __('common.processing') }}...</span>
            </div>
        </div>
    </div>

    <div class="min-h-full">
        @include('partials.shared.navigation')

        <div class="lg:ltr:pl-72 lg:rtl:pr-72">
            <!-- Page header with mobile menu button -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 lg:hidden" id="mobile-menu-button">
                    <span class="sr-only">Open sidebar</span>
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="relative flex flex-1 items-center">
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                            @yield('page-title', __('Dashboard'))
                        </h1>
                    </div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications button -->
                        <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="sr-only">{{ __('View notifications') }}</span>
                            <i data-lucide="bell" class="h-6 w-6"></i>
                        </button>

                        <!-- Language Switcher -->
                        @if(is_object($language) && method_exists($language, 'count') && $language->count())
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open"
                                        class="inline-flex items-center justify-center px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200"
                                        style="min-width: 90px;">
                                    @php
                                        $currentLang = session('lang', config('app.locale'));
                                        $currentLangData = $language->firstWhere('code', $currentLang);
                                        $flags = [
                                            'en' => '🇬🇧',
                                            'fr' => '🇫🇷',
                                            'ar' => '🇩🇿'
                                        ];
                                    @endphp
                                    <span class="text-xl mr-1.5">{{ $flags[$currentLang] ?? '🌐' }}</span>
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $currentLangData ? __($currentLangData->name) : 'Language' }}</span>
                                    <svg class="ml-1 h-3 w-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-40 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50"
                                     style="display: none;">
                                    <div class="py-1">
                                        @foreach ($language as $item)
                                            <a href="{{ route('lang', $item->code) }}"
                                               class="flex items-center px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 {{ session('lang') == $item->code ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300' }}">
                                                <span class="text-xl mr-2">{{ $flags[$item->code] ?? '🌐' }}</span>
                                                <span class="font-medium text-xs">{{ __($item->name) }}</span>
                                                @if(session('lang') == $item->code)
                                                    <svg class="ml-auto h-3 w-3 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200 dark:lg:bg-gray-700" aria-hidden="true"></div>

                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" class="-m-1.5 flex items-center p-1.5"
                                    x-on:click="open = !open"
                                    aria-expanded="false"
                                    aria-haspopup="true">
                                <span class="sr-only">{{ __('Open user menu') }}</span>
                                @php
                                    $currentUser = auth()->guard('influencer')->check() ? auth()->guard('influencer')->user() : auth()->user();
                                    $userName = isset($currentUser->fullname) ? $currentUser->fullname : ($currentUser->firstname ?? 'User');
                                @endphp
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($userName, 0, 1)) }}
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900 dark:text-white" aria-hidden="true">
                                        {{ $userName }}
                                    </span>
                                    <i data-lucide="chevron-down" class="ml-2 h-5 w-5 text-gray-400"></i>
                                </span>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 x-on:click.away="open = false"
                                 class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                @if(auth()->guard('influencer')->check())
                                    <a href="{{ localized_route('influencer.profile.setting') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Your profile') }}</a>
                                    <a href="{{ localized_route('influencer.logout') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Sign out') }}</a>
                                @else
                                    <a href="{{ localized_route('user.profile.setting') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Your profile') }}</a>
                                    <a href="{{ localized_route('user.logout') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Sign out') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Toast notifications container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        // Toast notification system
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const bgColor = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            }[type] || 'bg-green-500';

            toast.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg transform transition-transform duration-300 translate-x-full`;
            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                lucide.createIcons();
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Make showToast global
        window.showToast = showToast;

        // Handle CSRF token for AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            window.axios = window.axios || {};
            if (window.axios.defaults) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
            }
        }
    </script>

    <!-- Modern Loader JavaScript -->
    <script>
        // Page Loading Management
        class ModernLoader {
            constructor() {
                this.pageLoader = document.getElementById('page-loader');
                this.ajaxLoader = document.getElementById('ajax-loader');
                this.init();
            }

            init() {
                // Hide page loader when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => {
                        this.hidePageLoader();
                    });
                } else {
                    this.hidePageLoader();
                }

                // Hide page loader when everything is fully loaded
                window.addEventListener('load', () => {
                    this.hidePageLoader();
                });

                // Setup AJAX request interceptors
                this.setupAjaxInterceptors();

                // Setup form submission interceptors
                this.setupFormInterceptors();

                // Setup Alpine.js integration
                this.setupAlpineIntegration();
            }

            hidePageLoader() {
                if (this.pageLoader) {
                    // Add fade out animation
                    this.pageLoader.style.opacity = '0';
                    this.pageLoader.style.transition = 'opacity 0.3s ease-out';

                    setTimeout(() => {
                        this.pageLoader.style.display = 'none';
                    }, 300);
                }
            }

            showAjaxLoader() {
                if (this.ajaxLoader) {
                    this.ajaxLoader.classList.remove('hidden');
                    this.ajaxLoader.classList.add('flex');
                }
            }

            hideAjaxLoader() {
                if (this.ajaxLoader) {
                    this.ajaxLoader.classList.add('hidden');
                    this.ajaxLoader.classList.remove('flex');
                }
            }

            setupAjaxInterceptors() {
                // Intercept fetch requests
                const originalFetch = window.fetch;
                window.fetch = (...args) => {
                    this.showAjaxLoader();
                    return originalFetch.apply(this, args)
                        .finally(() => {
                            setTimeout(() => this.hideAjaxLoader(), 500);
                        });
                };

                // Intercept jQuery AJAX if available
                if (window.jQuery) {
                    $(document).ajaxStart(() => this.showAjaxLoader());
                    $(document).ajaxStop(() => this.hideAjaxLoader());
                }
            }

            setupFormInterceptors() {
                // Show loader on form submissions
                document.addEventListener('submit', (e) => {
                    if (e.target.tagName === 'FORM') {
                        this.showAjaxLoader();
                    }
                });
            }

            setupAlpineIntegration() {
                // Integration with Alpine.js for manual loader control
                window.loader = {
                    show: () => this.showAjaxLoader(),
                    hide: () => this.hideAjaxLoader(),
                    showPage: () => {
                        if (this.pageLoader) {
                            this.pageLoader.style.display = 'flex';
                            this.pageLoader.style.opacity = '1';
                        }
                    },
                    hidePage: () => this.hidePageLoader()
                };
            }
        }

        // Initialize the loader
        new ModernLoader();

        // Preloader for navigation
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (link &&
                link.href.startsWith(window.location.origin) &&
                !link.hasAttribute('target') &&
                !link.hasAttribute('download') &&
                !link.href.includes('#') &&
                !link.hasAttribute('data-no-loader')) {

                const pageLoader = document.getElementById('page-loader');
                if (pageLoader) {
                    pageLoader.style.display = 'flex';
                    pageLoader.style.opacity = '1';
                    pageLoader.style.transition = 'opacity 0.3s ease-in';
                }
            }
        });

        // Ensure loader is hidden on page show (back/forward navigation)
        window.addEventListener('pageshow', function(event) {
            const pageLoader = document.getElementById('page-loader');
            if (pageLoader && event.persisted) {
                // Page was loaded from cache
                pageLoader.style.opacity = '0';
                setTimeout(() => {
                    pageLoader.style.display = 'none';
                }, 300);
            }
        });

        // Custom loading animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse-dot {
                0%, 20% { opacity: 0.3; }
                50% { opacity: 1; }
                100% { opacity: 0.3; }
            }

            .animate-pulse-dot {
                animation: pulse-dot 1.5s ease-in-out infinite;
            }

            .animate-pulse-dot:nth-child(1) { animation-delay: 0s; }
            .animate-pulse-dot:nth-child(2) { animation-delay: 0.2s; }
            .animate-pulse-dot:nth-child(3) { animation-delay: 0.4s; }

            /* Smooth transitions */
            #page-loader {
                backdrop-filter: blur(8px);
                background: rgba(255, 255, 255, 0.95);
            }

            #ajax-loader {
                backdrop-filter: blur(4px);
            }
        `;
        document.head.appendChild(style);
    </script>

    @stack('scripts')
    @stack('script')
</body>
</html>