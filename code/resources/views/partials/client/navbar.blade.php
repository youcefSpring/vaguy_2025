<!-- Navbar -->
<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <!-- Mobile menu button -->
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" id="mobile-menu-button">
        <span class="sr-only">Open sidebar</span>
        <i data-lucide="menu" class="h-6 w-6"></i>
    </button>

    <!-- Separator -->
    <div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <!-- Search bar -->
        <form class="relative flex flex-1" action="#" method="GET">
            <label for="search-field" class="sr-only">Rechercher</label>
            <i data-lucide="search" class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400 pl-3 flex items-center"></i>
            <input id="search-field"
                   class="block h-full w-full border-0 py-0 pl-10 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm bg-transparent"
                   placeholder="Rechercher..."
                   type="search"
                   name="search">
        </form>

        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <!-- Notifications button -->
            <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500" x-data @click="$refs.notificationDropdown.classList.toggle('hidden')">
                <span class="sr-only">View notifications</span>
                <div class="relative">
                    <i data-lucide="bell" class="h-6 w-6"></i>
                    <!-- Notification badge -->
                    @if((auth()->user()->unreadNotifications ?? collect())->count() > 0)
                        <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 text-xs font-medium text-white flex items-center justify-center">
                            {{ min((auth()->user()->unreadNotifications ?? collect())->count(), 9) }}
                        </span>
                    @endif
                </div>
            </button>

            <!-- Notifications dropdown -->
            <div x-ref="notificationDropdown" class="hidden absolute right-0 top-16 z-10 mt-2 w-80 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="px-4 py-2 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    @forelse((auth()->user()->notifications ?? collect())->take(5) as $notification)
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i data-lucide="info" class="h-4 w-4 text-blue-500"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <i data-lucide="bell" class="mx-auto h-8 w-8 text-gray-400"></i>
                            <p class="mt-2 text-sm text-gray-500">Aucune notification</p>
                        </div>
                    @endforelse
                </div>
                @if((auth()->user()->notifications ?? collect())->count() > 0)
                    <div class="border-t border-gray-200 px-4 py-2">
                        <a href="{{ localized_route('user.home') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            عرض جميع الإشعارات
                        </a>
                    </div>
                @endif
            </div>

            <!-- Enhanced Language Switcher -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="flex items-center gap-x-2 px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200" aria-expanded="false">
                    @php
                        $currentLang = app()->getLocale();
                        $languages = [
                            'en' => ['icon' => '🇬🇧', 'name' => 'English', 'code' => 'EN'],
                            'fr' => ['icon' => '🇫🇷', 'name' => 'Français', 'code' => 'FR'],
                            'ar' => ['icon' => '🇩🇿', 'name' => 'العربية', 'code' => 'AR']
                        ];
                    @endphp
                    <span class="text-xl">{{ $languages[$currentLang]['icon'] ?? '🌐' }}</span>
                    <span class="font-bold uppercase">{{ $languages[$currentLang]['code'] ?? strtoupper($currentLang) }}</span>
                    <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open"
                     x-cloak
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-xl bg-white py-2 shadow-xl ring-1 ring-gray-900/10 border border-gray-200">
                    <div class="px-3 py-2 border-b border-gray-200">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">@lang('Select Language')</p>
                    </div>
                    @foreach($languages as $code => $lang)
                        <a href="{{ localized_route('lang', $code) }}"
                           class="flex items-center gap-x-3 px-3 py-2.5 text-sm leading-6 text-gray-900 hover:bg-blue-50 transition-colors duration-150 {{ $code === $currentLang ? 'bg-blue-50 font-bold' : '' }}"
                           @click="open = false">
                            <span class="text-2xl">{{ $lang['icon'] }}</span>
                            <span class="flex-1">{{ $lang['name'] }}</span>
                            @if($code === $currentLang)
                                <i data-lucide="check" class="h-4 w-4 text-blue-600"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Separator -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true"></div>

            <!-- Profile dropdown -->
            @if(auth()->check() && auth()->user())
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="-m-1.5 flex items-center p-1.5" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="sr-only">Open user menu</span>
                    <div class="h-8 w-8 rounded-full bg-gray-50 flex items-center justify-center">
                        @if(auth()->check() && auth()->user() && auth()->user()->image)
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('assets/images/user/profile/' . auth()->user()->image) }}" alt="{{ auth()->user()->firstname }}">
                        @elseif(auth()->check() && auth()->user())
                            <span class="text-sm font-medium text-gray-700">
                                {{ substr(auth()->user()->firstname, 0, 1) }}{{ substr(auth()->user()->lastname, 0, 1) }}
                            </span>
                        @else
                            <span class="text-sm font-medium text-gray-700">
                                G
                            </span>
                        @endif
                    </div>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">
                            {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}
                        </span>
                        <i data-lucide="chevron-down" class="ml-2 h-4 w-4 text-gray-400" :class="{ 'rotate-180': open }"></i>
                    </span>
                </button>

                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 z-10 mt-2.5 w-56 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5">

                    <!-- Profile info -->
                    <div class="px-3 py-2 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    </div>

                    <!-- Menu items -->
                    <a href="{{ localized_route('user.profile.setting') }}" class="flex items-center gap-x-3 px-3 py-2 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                        <i data-lucide="user" class="h-4 w-4 text-gray-400"></i>
                        @lang('dashboard.profile_settings')
                    </a>
                    <a href="{{ localized_route('user.change.password') }}" class="flex items-center gap-x-3 px-3 py-2 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                        <i data-lucide="lock" class="h-4 w-4 text-gray-400"></i>
                        @lang('dashboard.change_password')
                    </a>
                    <a href="{{ localized_route('user.deposit') }}" class="flex items-center gap-x-3 px-3 py-2 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                        <i data-lucide="credit-card" class="h-4 w-4 text-gray-400"></i>
                        @lang('dashboard.deposit')
                    </a>
                    <a href="{{ localized_route('user.transactions') }}" class="flex items-center gap-x-3 px-3 py-2 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                        <i data-lucide="arrow-right-left" class="h-4 w-4 text-gray-400"></i>
                        @lang('dashboard.transactions')
                    </a>
                    <a href="{{ localized_route('ticket') }}" class="flex items-center gap-x-3 px-3 py-2 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                        <i data-lucide="help-circle" class="h-4 w-4 text-gray-400"></i>
                        @lang('dashboard.support_ticket')
                    </a>

                    <div class="border-t border-gray-200 my-2"></div>

                    <form action="{{ localized_route('user.logout') }}" method="GET">
                        <button type="submit" class="flex w-full items-center gap-x-3 px-3 py-2 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                            <i data-lucide="log-out" class="h-4 w-4 text-gray-400"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
