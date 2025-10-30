@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    // Detect user type
    $isInfluencer = auth()->guard('influencer')->check();
    $isClient = auth()->guard('web')->check();

    $user = $isInfluencer ? auth()->guard('influencer')->user() : auth()->user();
    $userType = $isInfluencer ? 'influencer' : 'client';
    $homeRoute = $isInfluencer ? 'influencer.home' : 'user.home';
    $profileRoute = $isInfluencer ? 'influencer.profile.setting' : 'user.profile.setting';
    $logoutRoute = $isInfluencer ? 'influencer.logout' : 'user.logout';
@endphp

<!-- Static sidebar for desktop -->
<div class="hidden lg:fixed lg:inset-y-0 ltr:lg:left-0 rtl:lg:right-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <!-- Sidebar component -->
    <div class="flex grow flex-col gap-y-5 overflow-y-auto ltr:border-r rtl:border-l border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-6 pb-4" style="scroll-behavior: smooth;">
        <div class="flex h-16 shrink-0 items-center">
            <img class="h-8 w-auto" src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ $general->site_name }}">
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ localized_route($homeRoute) }}"
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs($homeRoute) ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i data-lucide="layout-dashboard" class="h-6 w-6 shrink-0 {{ request()->routeIs($homeRoute) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                {{ __('Dashboard') }}
                            </a>
                        </li>

                        @if($isInfluencer)
                            <!-- Influencer-specific menu items -->
                            <!-- Services -->
                            <li>
                                <div x-data="{ open: {{ request()->routeIs('influencer.service.*') ? 'true' : 'false' }} }">
                                    <button type="button"
                                            x-on:click="open = !open"
                                            class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.service.*') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                                            aria-expanded="false">
                                        <i data-lucide="briefcase" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.service.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                        {{ __('Services') }}
                                        <i data-lucide="chevron-right"
                                           class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.service.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-transform"
                                           :class="{ 'rotate-90': open }"></i>
                                    </button>
                                    <ul x-show="open"
                                        x-transition
                                        class="mt-1 px-2">
                                        <li>
                                            <a href="{{ localized_route('influencer.service.all') }}"
                                               class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.all') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                                                {{ __('All Services') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ localized_route('influencer.service.create') }}"
                                               class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.create') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                                                {{ __('Create Service') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ localized_route('influencer.service.pending') }}"
                                               class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.pending') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                                                {{ __('Pending') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if($isClient)
                            <!-- Client-specific menu items -->
                            <!-- Browse Platform -->
                            <li>
                                <a href="{{ localized_route('influencers') }}"
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencers') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                    <i data-lucide="users" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencers') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                    {{ __('Influencers') }}
                                </a>
                            </li>

                            <li>
                                <a href="{{ localized_route('services') }}"
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('services') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                    <i data-lucide="briefcase" class="h-6 w-6 shrink-0 {{ request()->routeIs('services') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                    {{ __('Services') }}
                                </a>
                            </li>
                        @endif

                        <!-- Orders (Both users) -->
                        <li>
                            <div x-data="{ open: {{ request()->routeIs('*.order.*', '*.orders.*') ? 'true' : 'false' }} }">
                                <button type="button"
                                        x-on:click="open = !open"
                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('*.order.*', '*.orders.*') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                                        aria-expanded="false">
                                    <i data-lucide="shopping-cart" class="h-6 w-6 shrink-0 {{ request()->routeIs('*.order.*', '*.orders.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                    {{ __('Orders') }}
                                    <i data-lucide="chevron-right"
                                       class="ml-auto h-5 w-5 shrink-0 transition-transform"
                                       :class="{ 'rotate-90': open }"></i>
                                </button>
                                <ul x-show="open"
                                    x-transition
                                    class="mt-1 px-2">
                                    <li>
                                        <a href="{{ $isInfluencer ? localized_route('influencer.service.order.index') : localized_route('user.order.all') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ __('All Orders') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Campaigns (Both users) -->
                        <li>
                            <div x-data="{ open: {{ request()->routeIs('*.campain.*', '*.campaigns.*') ? 'true' : 'false' }} }">
                                <button type="button"
                                        x-on:click="open = !open"
                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                                        aria-expanded="false">
                                    <i data-lucide="megaphone" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                    {{ __('Campaigns') }}
                                    <i data-lucide="chevron-right"
                                       class="ml-auto h-5 w-5 shrink-0 transition-transform"
                                       :class="{ 'rotate-90': open }"></i>
                                </button>
                                <ul x-show="open"
                                    x-transition
                                    class="mt-1 px-2">
                                    <li>
                                        <a href="{{ $isInfluencer ? localized_route('influencer.campain.index') : localized_route('user_campaign') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ __('All Campaigns') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        @if($isClient)
                            <!-- Hiring (Client only) -->
                            <li>
                                <a href="{{ localized_route('user.hiring.history') }}"
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('user.hiring.*') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                    <i data-lucide="users" class="h-6 w-6 shrink-0 {{ request()->routeIs('user.hiring.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                    {{ __('Hiring') }}
                                </a>
                            </li>
                        @endif

                        <!-- Messages/Conversations -->
                        <li>
                            <a href="{{ $isInfluencer ? localized_route('influencer.conversation.index') : localized_route('user.conversation.index') }}"
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i data-lucide="message-circle" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                {{ __('Messages') }}
                            </a>
                        </li>

                        <!-- Transactions -->
                        <li>
                            <a href="{{ $isInfluencer ? localized_route('influencer.transactions') : localized_route('user.transactions') }}"
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i data-lucide="credit-card" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                {{ __('Transactions') }}
                            </a>
                        </li>

                        @if($isInfluencer)
                            <!-- Withdraw (Influencer only) -->
                            <li>
                                <div x-data="{ open: {{ request()->routeIs('influencer.withdraw*') ? 'true' : 'false' }} }">
                                    <button type="button"
                                            x-on:click="open = !open"
                                            class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <i data-lucide="banknote" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                        {{ __('Withdraw') }}
                                        <i data-lucide="chevron-right"
                                           class="ml-auto h-5 w-5 shrink-0 transition-transform"
                                           :class="{ 'rotate-90': open }"></i>
                                    </button>
                                    <ul x-show="open"
                                        x-transition
                                        class="mt-1 px-2">
                                        <li>
                                            <a href="{{ localized_route('influencer.withdraw') }}"
                                               class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ __('New Withdrawal') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ localized_route('influencer.withdraw.history') }}"
                                               class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ __('Withdrawal History') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if($isClient)
                            <!-- Deposits (Client only) -->
                            <li>
                                <a href="{{ localized_route('user.deposit.history') }}"
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <i data-lucide="wallet" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                    {{ __('Deposits') }}
                                </a>
                            </li>
                        @endif

                        <!-- Support -->
                        <li>
                            <a href="{{ $isInfluencer ? localized_route('influencer.ticket') : localized_route('ticket') }}"
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i data-lucide="help-circle" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                {{ __('Support') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-auto">
                    <a href="{{ localized_route($profileRoute) }}"
                       class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i data-lucide="settings" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                        {{ __('Settings') }}
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Mobile menu (same structure repeated for mobile) -->
<div class="lg:hidden">
    <div class="fixed inset-0 z-50" id="mobile-overlay" style="display: none;">
        <div class="fixed inset-0 bg-gray-900/80 dark:bg-black/90" aria-hidden="true" id="mobile-overlay-bg"></div>
        <div class="fixed inset-0 flex">
            <div class="relative ltr:mr-16 rtl:ml-16 flex w-full max-w-xs flex-1">
                <div class="absolute ltr:left-full rtl:right-full top-0 flex w-16 justify-center pt-5">
                    <button type="button" class="-m-2.5 p-2.5" id="close-sidebar">
                        <span class="sr-only">Close sidebar</span>
                        <i data-lucide="x" class="h-6 w-6 text-white"></i>
                    </button>
                </div>

                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white dark:bg-gray-800 px-6 pb-4">
                    <div class="flex h-16 shrink-0 items-center">
                        <img class="h-8 w-auto" src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ $general->site_name }}">
                    </div>
                    <!-- Mobile menu content (same as desktop) -->
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                            <li>
                                <ul role="list" class="-mx-2 space-y-1">
                                    <!-- Dashboard -->
                                    <li>
                                        <a href="{{ localized_route($homeRoute) }}"
                                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs($homeRoute) ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                            <i data-lucide="layout-dashboard" class="h-6 w-6 shrink-0 {{ request()->routeIs($homeRoute) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                            {{ __('Dashboard') }}
                                        </a>
                                    </li>

                                    @if($isInfluencer)
                                        <!-- Influencer-specific menu items -->
                                        <!-- Services -->
                                        <li>
                                            <div x-data="{ open: {{ request()->routeIs('influencer.service.*') ? 'true' : 'false' }} }">
                                                <button type="button"
                                                        x-on:click="open = !open"
                                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.service.*') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                                                        aria-expanded="false">
                                                    <i data-lucide="briefcase" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.service.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                                    {{ __('Services') }}
                                                    <i data-lucide="chevron-right"
                                                       class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.service.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-transform"
                                                       :class="{ 'rotate-90': open }"></i>
                                                </button>
                                                <ul x-show="open"
                                                    x-transition
                                                    class="mt-1 px-2">
                                                    <li>
                                                        <a href="{{ localized_route('influencer.service.all') }}"
                                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.all') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                                                            {{ __('All Services') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ localized_route('influencer.service.create') }}"
                                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.create') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                                                            {{ __('Create Service') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ localized_route('influencer.service.pending') }}"
                                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.pending') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400' }}">
                                                            {{ __('Pending') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif

                                    @if($isClient)
                                        <!-- Client-specific menu items -->
                                        <!-- Browse Platform -->
                                        <li>
                                            <a href="{{ localized_route('influencers') }}"
                                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencers') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                                <i data-lucide="users" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencers') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                                {{ __('Influencers') }}
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ localized_route('services') }}"
                                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('services') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                                <i data-lucide="briefcase" class="h-6 w-6 shrink-0 {{ request()->routeIs('services') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                                {{ __('Services') }}
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Orders (Both users) -->
                                    <li>
                                        <div x-data="{ open: {{ request()->routeIs('*.order.*', '*.orders.*') ? 'true' : 'false' }} }">
                                            <button type="button"
                                                    x-on:click="open = !open"
                                                    class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('*.order.*', '*.orders.*') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                                                    aria-expanded="false">
                                                <i data-lucide="shopping-cart" class="h-6 w-6 shrink-0 {{ request()->routeIs('*.order.*', '*.orders.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                                {{ __('Orders') }}
                                                <i data-lucide="chevron-right"
                                                   class="ml-auto h-5 w-5 shrink-0 transition-transform"
                                                   :class="{ 'rotate-90': open }"></i>
                                            </button>
                                            <ul x-show="open"
                                                x-transition
                                                class="mt-1 px-2">
                                                <li>
                                                    <a href="{{ $isInfluencer ? localized_route('influencer.service.order.index') : localized_route('user.order.all') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ __('All Orders') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <!-- Campaigns (Both users) -->
                                    <li>
                                        <div x-data="{ open: {{ request()->routeIs('*.campain.*', '*.campaigns.*') ? 'true' : 'false' }} }">
                                            <button type="button"
                                                    x-on:click="open = !open"
                                                    class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                                                    aria-expanded="false">
                                                <i data-lucide="megaphone" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                                {{ __('Campaigns') }}
                                                <i data-lucide="chevron-right"
                                                   class="ml-auto h-5 w-5 shrink-0 transition-transform"
                                                   :class="{ 'rotate-90': open }"></i>
                                            </button>
                                            <ul x-show="open"
                                                x-transition
                                                class="mt-1 px-2">
                                                <li>
                                                    <a href="{{ $isInfluencer ? localized_route('influencer.campain.index') : localized_route('user_campaign') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ __('All Campaigns') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    @if($isClient)
                                        <!-- Hiring (Client only) -->
                                        <li>
                                            <a href="{{ localized_route('user.hiring.history') }}"
                                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('user.hiring.*') ? 'bg-gray-50 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                                <i data-lucide="users" class="h-6 w-6 shrink-0 {{ request()->routeIs('user.hiring.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                                {{ __('Hiring') }}
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Messages/Conversations -->
                                    <li>
                                        <a href="{{ $isInfluencer ? localized_route('influencer.conversation.index') : localized_route('user.conversation.index') }}"
                                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <i data-lucide="message-circle" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                            {{ __('Messages') }}
                                        </a>
                                    </li>

                                    <!-- Transactions -->
                                    <li>
                                        <a href="{{ $isInfluencer ? localized_route('influencer.transactions') : localized_route('user.transactions') }}"
                                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <i data-lucide="credit-card" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                            {{ __('Transactions') }}
                                        </a>
                                    </li>

                                    @if($isInfluencer)
                                        <!-- Withdraw (Influencer only) -->
                                        <li>
                                            <div x-data="{ open: {{ request()->routeIs('influencer.withdraw*') ? 'true' : 'false' }} }">
                                                <button type="button"
                                                        x-on:click="open = !open"
                                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <i data-lucide="banknote" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                                    {{ __('Withdraw') }}
                                                    <i data-lucide="chevron-right"
                                                       class="ml-auto h-5 w-5 shrink-0 transition-transform"
                                                       :class="{ 'rotate-90': open }"></i>
                                                </button>
                                                <ul x-show="open"
                                                    x-transition
                                                    class="mt-1 px-2">
                                                    <li>
                                                        <a href="{{ localized_route('influencer.withdraw') }}"
                                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ __('New Withdrawal') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ localized_route('influencer.withdraw.history') }}"
                                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ __('Withdrawal History') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif

                                    @if($isClient)
                                        <!-- Deposits (Client only) -->
                                        <li>
                                            <a href="{{ localized_route('user.deposit.history') }}"
                                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <i data-lucide="wallet" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                                {{ __('Deposits') }}
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Support -->
                                    <li>
                                        <a href="{{ $isInfluencer ? localized_route('influencer.ticket') : localized_route('ticket') }}"
                                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <i data-lucide="help-circle" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                            {{ __('Support') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="mt-auto">
                                <a href="{{ localized_route($profileRoute) }}"
                                   class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <i data-lucide="settings" class="h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400"></i>
                                    {{ __('Settings') }}
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const closeSidebar = document.getElementById('close-sidebar');
        const mobileOverlayBg = document.getElementById('mobile-overlay-bg');

        if (mobileMenuButton && mobileOverlay) {
            mobileMenuButton.addEventListener('click', function() {
                mobileOverlay.style.display = 'block';
                setTimeout(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }, 50);
            });
        }

        if (closeSidebar && mobileOverlay) {
            closeSidebar.addEventListener('click', function() {
                mobileOverlay.style.display = 'none';
            });
        }

        if (mobileOverlayBg && mobileOverlay) {
            mobileOverlayBg.addEventListener('click', function() {
                mobileOverlay.style.display = 'none';
            });
        }
    });
</script>
