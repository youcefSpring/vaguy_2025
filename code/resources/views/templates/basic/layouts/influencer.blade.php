<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $general->getPageTitle(__( isset($pageTitle) ? $pageTitle : 'Dashboard')) }}</title>

    @include('partials.seo')
    <link rel="icon" type="image/png" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png', '?' . time()) }}" sizes="16x16">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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
        }

        .btn-outline:hover {
            background-color: hsl(var(--accent));
            color: hsl(var(--accent-foreground));
        }

        .btn-ghost {
            background-color: transparent;
        }

        .btn-ghost:hover {
            background-color: hsl(var(--accent));
            color: hsl(var(--accent-foreground));
        }

        .btn-destructive {
            background-color: hsl(var(--destructive));
            color: hsl(var(--destructive-foreground));
        }

        .btn-destructive:hover {
            background-color: hsl(var(--destructive) / 0.9);
        }

        /* Size variants */
        .btn-sm {
            height: 2.25rem;
            border-radius: calc(var(--radius) - 4px);
            padding: 0 0.75rem;
        }

        .btn-default {
            height: 2.5rem;
            padding: 0 1rem;
        }

        .btn-lg {
            height: 2.75rem;
            border-radius: var(--radius);
            padding: 0 2rem;
        }

        .btn-icon {
            height: 2.5rem;
            width: 2.5rem;
            padding: 0;
        }

        /* Card Styles */
        .card {
            border-radius: calc(var(--radius) + 2px);
            border: 1px solid hsl(var(--border));
            background-color: hsl(var(--card));
            color: hsl(var(--card-foreground));
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }

        .card-header {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1;
            letter-spacing: -0.025em;
        }

        .card-description {
            font-size: 0.875rem;
            color: hsl(var(--muted-foreground));
        }

        .card-content {
            padding: 1.5rem;
            padding-top: 0;
        }

        .card-footer {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            padding-top: 0;
        }

        /* Input Styles */
        .input {
            display: flex;
            height: 2.5rem;
            width: 100%;
            border-radius: calc(var(--radius) - 2px);
            border: 1px solid hsl(var(--border));
            background-color: hsl(var(--background));
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .input:focus {
            border-color: hsl(var(--ring));
            box-shadow: 0 0 0 2px hsl(var(--ring) / 0.2);
        }

        .input:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Badge Styles */
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: calc(var(--radius) - 2px);
            padding: 0.125rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
            transition: colors 0.2s;
            border: 1px solid transparent;
        }

        .badge-default {
            background-color: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
        }

        .badge-secondary {
            background-color: hsl(var(--secondary));
            color: hsl(var(--secondary-foreground));
        }

        .badge-destructive {
            background-color: hsl(var(--destructive));
            color: hsl(var(--destructive-foreground));
        }

        .badge-outline {
            color: hsl(var(--foreground));
            border: 1px solid hsl(var(--border));
        }

        /* Separator */
        .separator {
            flex-shrink: 0;
            background-color: hsl(var(--border));
        }

        .separator-horizontal {
            height: 1px;
            width: 100%;
        }

        .separator-vertical {
            height: 100%;
            width: 1px;
        }

        /* Progress */
        .progress {
            position: relative;
            height: 1rem;
            width: 100%;
            overflow: hidden;
            border-radius: calc(var(--radius) - 2px);
            background-color: hsl(var(--secondary));
        }

        .progress-indicator {
            height: 100%;
            width: 100%;
            flex: 1 1 0%;
            background-color: hsl(var(--primary));
            transition: all 0.2s;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: hsl(var(--secondary));
            border-radius: var(--radius);
        }

        ::-webkit-scrollbar-thumb {
            background: hsl(var(--muted-foreground));
            border-radius: var(--radius);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: hsl(var(--foreground));
        }
    </style>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Modern Loader Styles -->
    <style>
        /* Page Loader */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .page-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        /* Spinner Animation */
        .loader-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid hsl(var(--border));
            border-top: 4px solid hsl(var(--primary));
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Progress Bar */
        .loading-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, hsl(var(--primary)), hsl(var(--primary)) 50%, transparent 50%);
            z-index: 10000;
            transition: width 0.3s ease;
        }

        /* Skeleton Loader */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* Button Loading State */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Table Loading Overlay */
        .table-loading {
            position: relative;
            overflow: hidden;
        }

        .table-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    @stack('style')
</head>

<body class="h-full">
    <!-- Page Loader -->
    <div id="page-loader" class="page-loader">
        <div class="text-center">
            <div class="loader-spinner mx-auto mb-4"></div>
            <p class="text-sm text-gray-600">Loading...</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div id="loading-progress" class="loading-progress"></div>
    <div class="min-h-full">
        @include('templates.basic.influencer.partials.navigation')

        <div class="lg:pl-72">
            <!-- Page header -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" id="mobile-menu-button">
                    <span class="sr-only">Open sidebar</span>
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="relative flex flex-1 items-center">
                        <h1 class="text-xl font-semibold text-gray-900">
                            {{ isset($pageTitle) ? __($pageTitle) : 'Dashboard' }}
                        </h1>
                    </div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications button -->
                        <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                            <span class="sr-only">View notifications</span>
                            <i data-lucide="bell" class="h-6 w-6"></i>
                        </button>

                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" class="-m-1.5 flex items-center p-1.5"
                                    x-on:click="open = !open"
                                    aria-expanded="false"
                                    aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full bg-gray-50"
                                     src="{{ getImage(getFilePath('influencerProfile').'/'.authInfluencer()->image,getFileSize('influencerProfile')) }}"
                                     alt="{{ authInfluencer()->fullname }}">
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">
                                        {{ authInfluencer()->fullname }}
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
                                 class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                <a href="{{ localized_route('influencer.profile.setting') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">Your profile</a>
                                <a href="{{ localized_route('influencer.logout') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">Sign out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @include('partials.notify')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Loading Management System
        class LoadingManager {
            constructor() {
                this.pageLoader = document.getElementById('page-loader');
                this.progressBar = document.getElementById('loading-progress');
                this.isLoading = false;
                this.progress = 0;
                this.domReady = false;

                // Setup DOM ready detection
                this.setupDOMReadyDetection();

                // Setup navigation loading
                this.setupNavigationLoading();

                // Setup form loading
                this.setupFormLoading();
            }

            setupDOMReadyDetection() {
                // Hide loader when DOM is fully ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => {
                        this.domReady = true;
                        this.hidePageLoader();
                    });
                } else {
                    // DOM is already ready
                    this.domReady = true;
                    this.hidePageLoader();
                }

                // Also hide when window is fully loaded (including images, stylesheets, etc.)
                if (document.readyState !== 'complete') {
                    window.addEventListener('load', () => {
                        this.hidePageLoader();
                    });
                } else {
                    // Window is already loaded
                    this.hidePageLoader();
                }
            }

            hidePageLoader() {
                if (this.pageLoader && this.domReady) {
                    // Small delay to ensure smooth visual transition
                    setTimeout(() => {
                        this.pageLoader.classList.add('hidden');
                        setTimeout(() => {
                            this.pageLoader.style.display = 'none';
                        }, 300);
                    }, 200); // Quick transition when DOM is ready
                }
            }

            forceHideLoader() {
                if (this.pageLoader) {
                    this.pageLoader.classList.add('hidden');
                    setTimeout(() => {
                        this.pageLoader.style.display = 'none';
                    }, 300);
                }
                this.hideProgress();
            }

            showPageLoader() {
                if (this.pageLoader) {
                    this.pageLoader.style.display = 'flex';
                    this.pageLoader.classList.remove('hidden');
                }
            }

            updateProgress(percentage) {
                this.progress = Math.min(100, Math.max(0, percentage));
                if (this.progressBar) {
                    this.progressBar.style.width = this.progress + '%';
                }
            }

            showProgress() {
                if (this.progressBar) {
                    this.progressBar.style.display = 'block';
                    this.updateProgress(0);
                }
            }

            hideProgress() {
                if (this.progressBar) {
                    this.updateProgress(100);
                    setTimeout(() => {
                        this.progressBar.style.display = 'none';
                        this.updateProgress(0);
                    }, 300);
                }
            }

            setupNavigationLoading() {
                // Handle navigation clicks
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('a[href]');
                    if (link && !link.hasAttribute('target') && !link.href.includes('#')) {
                        const href = link.getAttribute('href');
                        if (href && !href.startsWith('javascript:') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
                            this.startNavigation();
                        }
                    }
                });

                // Listen for page visibility changes to handle back/forward navigation
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        this.startNavigation();
                    } else {
                        this.stopNavigation();
                    }
                });
            }

            setupFormLoading() {
                // Handle form submissions
                document.addEventListener('submit', (e) => {
                    const form = e.target;
                    if (form.tagName === 'FORM') {
                        this.startFormLoading(form);
                    }
                });
            }

            startNavigation() {
                this.isLoading = true;
                this.showProgress();

                // Simulate progress
                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 20;
                    if (progress >= 85) {
                        clearInterval(interval);
                        this.updateProgress(85);

                        // Complete progress when DOM is ready on new page
                        const checkDOMReady = () => {
                            if (document.readyState === 'complete') {
                                this.updateProgress(100);
                                setTimeout(() => this.stopNavigation(), 200);
                            } else {
                                setTimeout(checkDOMReady, 50);
                            }
                        };
                        checkDOMReady();
                    } else {
                        this.updateProgress(progress);
                    }
                }, 80);

                // Fallback: Auto-stop navigation loading after 5 seconds
                setTimeout(() => {
                    if (this.isLoading) {
                        this.stopNavigation();
                    }
                }, 5000);
            }

            stopNavigation() {
                this.isLoading = false;
                this.hideProgress();
            }

            startFormLoading(form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.classList.add('btn-loading');
                    submitBtn.disabled = true;
                }
                this.showProgress();

                // Listen for page navigation (form response) or beforeunload
                const handleFormComplete = () => {
                    this.hideProgress();
                    if (submitBtn) {
                        submitBtn.classList.remove('btn-loading');
                        submitBtn.disabled = false;
                    }
                };

                // Form completion detection
                const unloadHandler = () => {
                    handleFormComplete();
                    window.removeEventListener('beforeunload', unloadHandler);
                };

                // Listen for navigation away from page (form submission response)
                window.addEventListener('beforeunload', unloadHandler);

                // Fallback: Auto-stop form loading after 8 seconds
                setTimeout(() => {
                    handleFormComplete();
                    window.removeEventListener('beforeunload', unloadHandler);
                }, 8000);
            }

            addTableLoading(tableSelector) {
                const table = document.querySelector(tableSelector);
                if (table) {
                    table.classList.add('table-loading');
                }
            }

            removeTableLoading(tableSelector) {
                const table = document.querySelector(tableSelector);
                if (table) {
                    table.classList.remove('table-loading');
                }
            }
        }

        // Initialize loading manager
        const loadingManager = new LoadingManager();

        // Expose globally for use in pages
        window.LoadingManager = loadingManager;

        // Mobile menu toggle is handled in navigation.blade.php

        // Auto-hide notifications after 5 seconds
        setTimeout(function() {
            const notifications = document.querySelectorAll('.alert');
            notifications.forEach(function(notification) {
                notification.style.transition = 'opacity 0.5s';
                notification.style.opacity = '0';
                setTimeout(function() {
                    notification.remove();
                }, 500);
            });
        }, 5000);

        // Enhanced form handling with loading states
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to all forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('btn-loading')) {
                        submitBtn.classList.add('btn-loading');
                        submitBtn.disabled = true;

                        // Listen for page unload (form response)
                        const handleUnload = () => {
                            submitBtn.classList.remove('btn-loading');
                            submitBtn.disabled = false;
                            window.removeEventListener('beforeunload', handleUnload);
                        };

                        window.addEventListener('beforeunload', handleUnload);

                        // Fallback: Re-enable after 8 seconds
                        setTimeout(() => {
                            submitBtn.classList.remove('btn-loading');
                            submitBtn.disabled = false;
                            window.removeEventListener('beforeunload', handleUnload);
                        }, 8000);
                    }
                });
            });

            // Add skeleton loading for tables on initial load
            const tables = document.querySelectorAll('table');
            if (tables.length === 0) {
                // If no tables yet, show skeleton
                const cardContent = document.querySelector('.card-content');
                if (cardContent && cardContent.children.length === 0) {
                    cardContent.innerHTML = `
                        <div class="space-y-4 p-4">
                            <div class="skeleton h-4 w-3/4 rounded"></div>
                            <div class="skeleton h-4 w-1/2 rounded"></div>
                            <div class="skeleton h-4 w-2/3 rounded"></div>
                            <div class="skeleton h-32 w-full rounded"></div>
                        </div>
                    `;
                }
            }
        });

        // Utility functions for manual loading control
        window.showLoader = () => loadingManager.showPageLoader();
        window.hideLoader = () => loadingManager.hidePageLoader();
        window.showProgress = () => loadingManager.showProgress();
        window.hideProgress = () => loadingManager.hideProgress();
        window.updateProgress = (percentage) => loadingManager.updateProgress(percentage);
    </script>

    @stack('script')
</body>
</html>
