<!-- Static sidebar for desktop -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
    <!-- Sidebar component -->
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4" style="scroll-behavior: smooth;">
        <div class="flex h-16 shrink-0 items-center">
            <img class="h-8 w-auto" src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ $general->site_name }}">
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ localized_route('influencer.home') }}"
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencer.home') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                <i data-lucide="layout-dashboard" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.home') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                Tableau de bord
                            </a>
                        </li>

                        <!-- Services -->
                        <li>
                            <div x-data="{ open: {{ request()->routeIs('influencer.service.*') ? 'true' : 'false' }} }">
                                <button type="button"
                                        x-on:click="open = !open"
                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.service.*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}"
                                        aria-expanded="false">
                                    <i data-lucide="briefcase" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.service.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                    Services
                                    <i data-lucide="chevron-right"
                                       class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.service.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} transition-transform"
                                       :class="{ 'rotate-90': open }"></i>
                                </button>
                                <ul x-show="open"
                                    x-transition
                                    class="mt-1 px-2">
                                    <li>
                                        <a href="{{ localized_route('influencer.service.all') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.all') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Tous les services
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.service.create') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.create') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Créer un service
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.service.pending') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.pending') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            En attente
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.service.approved') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.approved') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Approuvés
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.service.rejected') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.rejected') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Rejetés
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Orders -->
                        <li>
                            <div x-data="{ open: {{ request()->routeIs('influencer.service.order.*') ? 'true' : 'false' }} }">
                                <button type="button"
                                        x-on:click="open = !open"
                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.service.order.*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}"
                                        aria-expanded="false">
                                    <i data-lucide="shopping-cart" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.service.order.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                    Commandes
                                    <i data-lucide="chevron-right"
                                       class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.service.order.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} transition-transform"
                                       :class="{ 'rotate-90': open }"></i>
                                </button>
                                <ul x-show="open"
                                    x-transition
                                    class="mt-1 px-2">
                                    <li>
                                        <a href="{{ localized_route('influencer.service.order.index') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.order.index') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Toutes les commandes
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.service.order.pending') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.order.pending') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            En attente
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.service.order.inprogress') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.order.inprogress') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            En cours
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.service.order.completed') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.order.completed') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Terminées
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Campaigns -->
                        <li>
                            <div x-data="{ open: {{ request()->routeIs('influencer.campain.*') ? 'true' : 'false' }} }">
                                <button type="button"
                                        x-on:click="open = !open"
                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.campain.*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}"
                                        aria-expanded="false">
                                    <i data-lucide="megaphone" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.campain.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                    Campagnes
                                    <i data-lucide="chevron-right"
                                       class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.campain.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} transition-transform"
                                       :class="{ 'rotate-90': open }"></i>
                                </button>
                                <ul x-show="open"
                                    x-transition
                                    class="mt-1 px-2">
                                    <li>
                                        <a href="{{ localized_route('influencer.campain.index') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.campain.index') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Toutes les campagnes
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.campain.pending') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.campain.pending') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            En attente
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.campain.inprogress') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.campain.inprogress') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            En cours
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.campain.completed') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.campain.completed') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Terminées
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Conversations -->
                        <li>
                            <a href="{{ localized_route('influencer.conversation.index') }}"
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencer.conversation.*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                <i data-lucide="message-circle" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.conversation.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                Conversations
                            </a>
                        </li>

                        <!-- Transactions -->
                        <li>
                            <a href="{{ localized_route('influencer.transactions') }}"
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencer.transactions') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                <i data-lucide="credit-card" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.transactions') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                Transactions
                            </a>
                        </li>

                        <!-- Withdraw -->
                        <li>
                            <div x-data="{ open: {{ request()->routeIs('influencer.withdraw*') ? 'true' : 'false' }} }">
                                <button type="button"
                                        x-on:click="open = !open"
                                        class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.withdraw*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}"
                                        aria-expanded="false">
                                    <i data-lucide="banknote" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.withdraw*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                    Retraits
                                    <i data-lucide="chevron-right"
                                       class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.withdraw*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} transition-transform"
                                       :class="{ 'rotate-90': open }"></i>
                                </button>
                                <ul x-show="open"
                                    x-transition
                                    class="mt-1 px-2">
                                    <li>
                                        <a href="{{ localized_route('influencer.withdraw') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.withdraw') && !request()->routeIs('influencer.withdraw.history') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Nouveau retrait
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ localized_route('influencer.withdraw.history') }}"
                                           class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.withdraw.history') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                            Historique des retraits
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Support -->
                        <li>
                            <a href="{{ localized_route('influencer.ticket') }}"
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencer.ticket*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                <i data-lucide="help-circle" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.ticket*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                Support
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-auto">
                    <a href="{{ localized_route('influencer.profile.setting') }}"
                       class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('influencer.profile.setting') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                        <i data-lucide="settings" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.profile.setting') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                        Paramètres
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Mobile menu -->
<div class="lg:hidden">
    <div class="fixed inset-0 z-50" id="mobile-overlay" style="display: none;">
        <div class="fixed inset-0 bg-gray-900/80" aria-hidden="true" id="mobile-overlay-bg"></div>
        <div class="fixed inset-0 flex">
            <div class="relative mr-16 flex w-full max-w-xs flex-1">
                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                    <button type="button" class="-m-2.5 p-2.5" id="close-sidebar">
                        <span class="sr-only">Close sidebar</span>
                        <i data-lucide="x" class="h-6 w-6 text-white"></i>
                    </button>
                </div>

                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                    <div class="flex h-16 shrink-0 items-center">
                        <img class="h-8 w-auto" src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="{{ $general->site_name }}">
                    </div>
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                            <li>
                                <ul role="list" class="-mx-2 space-y-1">
                                    <!-- Dashboard -->
                                    <li>
                                        <a href="{{ localized_route('influencer.home') }}"
                                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencer.home') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                            <i data-lucide="layout-dashboard" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.home') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                            Tableau de bord
                                        </a>
                                    </li>

                                    <!-- Services -->
                                    <li>
                                        <div x-data="{ open: {{ request()->routeIs('influencer.service.*') ? 'true' : 'false' }} }">
                                            <button type="button"
                                                    x-on:click="open = !open"
                                                    class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.service.*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}"
                                                    aria-expanded="false">
                                                <i data-lucide="briefcase" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.service.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                                Services
                                                <i data-lucide="chevron-right"
                                                   class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.service.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} transition-transform"
                                                   :class="{ 'rotate-90': open }"></i>
                                            </button>
                                            <ul x-show="open"
                                                x-transition
                                                class="mt-1 px-2">
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.all') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.all') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Tous les services
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.create') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.create') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Créer un service
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.pending') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.pending') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        En attente
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.approved') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.approved') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Approuvés
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.rejected') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.rejected') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Rejetés
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <!-- Orders -->
                                    <li>
                                        <div x-data="{ open: {{ request()->routeIs('influencer.service.order.*') ? 'true' : 'false' }} }">
                                            <button type="button"
                                                    x-on:click="open = !open"
                                                    class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.service.order.*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}"
                                                    aria-expanded="false">
                                                <i data-lucide="shopping-cart" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.service.order.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                                Commandes
                                                <i data-lucide="chevron-right"
                                                   class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.service.order.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} transition-transform"
                                                   :class="{ 'rotate-90': open }"></i>
                                            </button>
                                            <ul x-show="open"
                                                x-transition
                                                class="mt-1 px-2">
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.order.index') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.order.index') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Toutes les commandes
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.order.pending') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.order.pending') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        En attente
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.order.inprogress') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.order.inprogress') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        En cours
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.service.order.completed') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.service.order.completed') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Terminées
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <!-- Campaigns -->
                                    <li>
                                        <div x-data="{ open: {{ request()->routeIs('influencer.campain.*') ? 'true' : 'false' }} }">
                                            <button type="button"
                                                    x-on:click="open = !open"
                                                    class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.campain.*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}"
                                                    aria-expanded="false">
                                                <i data-lucide="megaphone" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.campain.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                                Campagnes
                                                <i data-lucide="chevron-right"
                                                   class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.campain.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} transition-transform"
                                                   :class="{ 'rotate-90': open }"></i>
                                            </button>
                                            <ul x-show="open"
                                                x-transition
                                                class="mt-1 px-2">
                                                <li>
                                                    <a href="{{ localized_route('influencer.campain.index') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.campain.index') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Toutes les campagnes
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.campain.pending') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.campain.pending') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        En attente
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.campain.inprogress') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.campain.inprogress') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        En cours
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.campain.completed') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.campain.completed') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Terminées
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <!-- Conversations -->
                                    <li>
                                        <a href="{{ localized_route('influencer.conversation.index') }}"
                                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencer.conversation.*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                            <i data-lucide="message-circle" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.conversation.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                            Conversations
                                        </a>
                                    </li>

                                    <!-- Transactions -->
                                    <li>
                                        <a href="{{ localized_route('influencer.transactions') }}"
                                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencer.transactions') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                            <i data-lucide="credit-card" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.transactions') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                            Transactions
                                        </a>
                                    </li>

                                    <!-- Withdraw -->
                                    <li>
                                        <div x-data="{ open: {{ request()->routeIs('influencer.withdraw*') ? 'true' : 'false' }} }">
                                            <button type="button"
                                                    x-on:click="open = !open"
                                                    class="group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold {{ request()->routeIs('influencer.withdraw*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}"
                                                    aria-expanded="false">
                                                <i data-lucide="banknote" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.withdraw*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                                Retraits
                                                <i data-lucide="chevron-right"
                                                   class="ml-auto h-5 w-5 shrink-0 {{ request()->routeIs('influencer.withdraw*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }} transition-transform"
                                                   :class="{ 'rotate-90': open }"></i>
                                            </button>
                                            <ul x-show="open"
                                                x-transition
                                                class="mt-1 px-2">
                                                <li>
                                                    <a href="{{ localized_route('influencer.withdraw') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.withdraw') && !request()->routeIs('influencer.withdraw.history') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Nouveau retrait
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ localized_route('influencer.withdraw.history') }}"
                                                       class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('influencer.withdraw.history') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                                        Historique des retraits
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>

                                    <!-- Support -->
                                    <li>
                                        <a href="{{ localized_route('influencer.ticket') }}"
                                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('influencer.ticket*') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                            <i data-lucide="help-circle" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.ticket*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                            Support
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="mt-auto">
                                <a href="{{ localized_route('influencer.profile.setting') }}"
                                   class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('influencer.profile.setting') ? 'bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    <i data-lucide="settings" class="h-6 w-6 shrink-0 {{ request()->routeIs('influencer.profile.setting') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                                    Paramètres
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
                // Reinitialize Lucide icons for mobile menu
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

        // Close mobile menu when clicking overlay background
        if (mobileOverlayBg && mobileOverlay) {
            mobileOverlayBg.addEventListener('click', function() {
                mobileOverlay.style.display = 'none';
            });
        }

        // Close mobile menu when navigating to a new page
        const mobileLinks = mobileOverlay?.querySelectorAll('a');
        if (mobileLinks) {
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileOverlay.style.display = 'none';
                });
            });
        }
    });
</script>