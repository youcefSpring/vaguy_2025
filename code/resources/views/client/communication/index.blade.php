@extends('layouts.dashboard')

@section('title', 'Communication & Support')

@section('content')
<div class="space-y-6" x-data="communicationManager()">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Communication & Support</h1>
                <p class="mt-1 text-sm text-gray-500">Gérez vos messages, notifications et demandes de support</p>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="showNewTicketModal = true"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                    Nouveau Ticket
                </button>
                <button @click="markAllAsRead()"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i data-lucide="check-check" class="h-4 w-4 mr-2"></i>
                    Tout Marquer Lu
                </button>
            </div>
        </div>
    </div>

    <!-- Communication Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i data-lucide="inbox" class="h-5 w-5 text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Messages non lus</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.unread_messages">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <i data-lucide="help-circle" class="h-5 w-5 text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tickets Ouverts</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.open_tickets">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <i data-lucide="check-circle" class="h-5 w-5 text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tickets Résolus</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.resolved_tickets">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i data-lucide="bell" class="h-5 w-5 text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Notifications</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.total_notifications">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Communication Tabs -->
    <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button @click="activeTab = 'messages'"
                        :class="activeTab === 'messages' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i data-lucide="mail" class="h-4 w-4 mr-2 inline"></i>
                    Messages
                </button>
                <button @click="activeTab = 'tickets'"
                        :class="activeTab === 'tickets' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i data-lucide="help-circle" class="h-4 w-4 mr-2 inline"></i>
                    Tickets Support
                </button>
                <button @click="activeTab = 'notifications'"
                        :class="activeTab === 'notifications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i data-lucide="bell" class="h-4 w-4 mr-2 inline"></i>
                    Notifications
                </button>
                <button @click="activeTab = 'faq'"
                        :class="activeTab === 'faq' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i data-lucide="book-open" class="h-4 w-4 mr-2 inline"></i>
                    FAQ
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Messages Tab -->
            <div x-show="activeTab === 'messages'">
                <div class="space-y-4">
                    <!-- Search and Filters -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input type="text"
                                       x-model="messageFilters.search"
                                       @input="searchMessages"
                                       placeholder="Rechercher des messages..."
                                       class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <select x-model="messageFilters.status" @change="loadMessages()" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Tous les statuts</option>
                                <option value="unread">Non lus</option>
                                <option value="read">Lus</option>
                            </select>
                        </div>
                    </div>

                    <!-- Messages List -->
                    <div x-show="loadingMessages" class="text-center py-8">
                        <div class="inline-flex items-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600">Chargement des messages...</span>
                        </div>
                    </div>

                    <div x-show="!loadingMessages && messages.length === 0" class="text-center py-8">
                        <i data-lucide="mail" class="mx-auto h-12 w-12 text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun message</h3>
                        <p class="mt-1 text-sm text-gray-500">Vos messages apparaîtront ici.</p>
                    </div>

                    <div x-show="!loadingMessages && messages.length > 0" class="space-y-4">
                        <template x-for="message in messages" :key="message.id">
                            <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" @click="viewMessage(message)" :class="{'bg-blue-50 border-blue-200': !message.read}">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <img class="h-8 w-8 rounded-full" :src="message.sender.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(message.sender.name)" :alt="message.sender.name">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-medium text-gray-900" x-text="message.sender.name"></span>
                                                <span x-show="!message.read" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Nouveau
                                                </span>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900" x-text="message.subject"></p>
                                            <p class="text-sm text-gray-500 line-clamp-2" x-text="message.preview"></p>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500" x-text="formatDate(message.created_at)"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Tickets Tab -->
            <div x-show="activeTab === 'tickets'">
                <div class="space-y-4">
                    <!-- Search and Filters -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <input type="text"
                                       x-model="ticketFilters.search"
                                       @input="searchTickets"
                                       placeholder="Rechercher des tickets..."
                                       class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <select x-model="ticketFilters.status" @change="loadTickets()" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Tous les statuts</option>
                                <option value="open">Ouvert</option>
                                <option value="pending">En attente</option>
                                <option value="resolved">Résolu</option>
                                <option value="closed">Fermé</option>
                            </select>
                            <select x-model="ticketFilters.priority" @change="loadTickets()" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Toutes les priorités</option>
                                <option value="low">Basse</option>
                                <option value="medium">Moyenne</option>
                                <option value="high">Haute</option>
                                <option value="urgent">Urgente</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tickets Table -->
                    <div x-show="loadingTickets" class="text-center py-8">
                        <div class="inline-flex items-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600">Chargement des tickets...</span>
                        </div>
                    </div>

                    <div x-show="!loadingTickets && tickets.length === 0" class="text-center py-8">
                        <i data-lucide="help-circle" class="mx-auto h-12 w-12 text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun ticket</h3>
                        <p class="mt-1 text-sm text-gray-500">Créez votre premier ticket de support.</p>
                        <div class="mt-6">
                            <button @click="showNewTicketModal = true" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                                Nouveau Ticket
                            </button>
                        </div>
                    </div>

                    <div x-show="!loadingTickets && tickets.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière réponse</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="ticket in tickets" :key="ticket.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900" x-text="'#' + ticket.id + ' - ' + ticket.subject"></div>
                                                <div class="text-sm text-gray-500" x-text="ticket.category"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  :class="{
                                                      'bg-yellow-100 text-yellow-800': ticket.status === 'open',
                                                      'bg-blue-100 text-blue-800': ticket.status === 'pending',
                                                      'bg-green-100 text-green-800': ticket.status === 'resolved',
                                                      'bg-gray-100 text-gray-800': ticket.status === 'closed'
                                                  }"
                                                  x-text="getStatusLabel(ticket.status)">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  :class="{
                                                      'bg-gray-100 text-gray-800': ticket.priority === 'low',
                                                      'bg-yellow-100 text-yellow-800': ticket.priority === 'medium',
                                                      'bg-orange-100 text-orange-800': ticket.priority === 'high',
                                                      'bg-red-100 text-red-800': ticket.priority === 'urgent'
                                                  }"
                                                  x-text="getPriorityLabel(ticket.priority)">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span x-text="formatDate(ticket.updated_at)"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button @click="viewTicket(ticket)" class="text-blue-600 hover:text-blue-900">
                                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                                </button>
                                                <button @click="replyToTicket(ticket)" class="text-green-600 hover:text-green-900">
                                                    <i data-lucide="reply" class="h-4 w-4"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div x-show="activeTab === 'notifications'">
                <div class="space-y-4">
                    <!-- Notifications List -->
                    <div x-show="loadingNotifications" class="text-center py-8">
                        <div class="inline-flex items-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600">Chargement des notifications...</span>
                        </div>
                    </div>

                    <div x-show="!loadingNotifications && notifications.length === 0" class="text-center py-8">
                        <i data-lucide="bell" class="mx-auto h-12 w-12 text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune notification</h3>
                        <p class="mt-1 text-sm text-gray-500">Vos notifications apparaîtront ici.</p>
                    </div>

                    <div x-show="!loadingNotifications && notifications.length > 0" class="space-y-4">
                        <template x-for="notification in notifications" :key="notification.id">
                            <div class="border rounded-lg p-4 hover:bg-gray-50" :class="{'bg-blue-50 border-blue-200': !notification.read}">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                 :class="{
                                                     'bg-blue-100': notification.type === 'info',
                                                     'bg-green-100': notification.type === 'success',
                                                     'bg-yellow-100': notification.type === 'warning',
                                                     'bg-red-100': notification.type === 'error'
                                                 }">
                                                <i :data-lucide="getNotificationIcon(notification.type)"
                                                   class="h-4 w-4"
                                                   :class="{
                                                       'text-blue-600': notification.type === 'info',
                                                       'text-green-600': notification.type === 'success',
                                                       'text-yellow-600': notification.type === 'warning',
                                                       'text-red-600': notification.type === 'error'
                                                   }"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                            <p class="text-sm text-gray-500" x-text="notification.message"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500" x-text="formatDate(notification.created_at)"></span>
                                        <button @click="markNotificationAsRead(notification)" x-show="!notification.read" class="text-blue-600 hover:text-blue-900">
                                            <i data-lucide="check" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- FAQ Tab -->
            <div x-show="activeTab === 'faq'">
                <div class="space-y-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text"
                               x-model="faqSearch"
                               @input="searchFAQ"
                               placeholder="Rechercher dans la FAQ..."
                               class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in filteredFAQ" :key="index">
                            <div class="border rounded-lg">
                                <button @click="item.open = !item.open" class="w-full text-left p-4 hover:bg-gray-50 flex items-center justify-between">
                                    <span class="font-medium text-gray-900" x-text="item.question"></span>
                                    <i data-lucide="chevron-down" class="h-5 w-5 text-gray-400 transform transition-transform" :class="{'rotate-180': item.open}"></i>
                                </button>
                                <div x-show="item.open" x-collapse class="px-4 pb-4">
                                    <div class="text-sm text-gray-700" x-html="item.answer"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredFAQ.length === 0" class="text-center py-8">
                        <i data-lucide="search" class="mx-auto h-12 w-12 text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun résultat trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">Essayez de modifier votre recherche.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Ticket Modal -->
    <div x-show="showNewTicketModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Nouveau Ticket de Support</h3>
                    <button @click="showNewTicketModal = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>

                <form @submit.prevent="createTicket()">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                                <select x-model="newTicket.category" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="technical">Problème technique</option>
                                    <option value="billing">Facturation</option>
                                    <option value="account">Compte utilisateur</option>
                                    <option value="feature">Demande de fonctionnalité</option>
                                    <option value="general">Question générale</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                                <select x-model="newTicket.priority" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="low">Basse</option>
                                    <option value="medium">Moyenne</option>
                                    <option value="high">Haute</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                            <input type="text" x-model="newTicket.subject" required placeholder="Décrivez brièvement votre problème..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description détaillée *</label>
                            <textarea x-model="newTicket.description" required rows="6" placeholder="Décrivez votre problème en détail..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pièces jointes</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i data-lucide="upload" class="mx-auto h-12 w-12 text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Télécharger un fichier</span>
                                            <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple>
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF jusqu'à 10MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showNewTicketModal = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" :disabled="submitting" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50">
                            <span x-show="!submitting">Créer le ticket</span>
                            <span x-show="submitting" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Création...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function communicationManager() {
    return {
        activeTab: 'messages',
        loadingMessages: false,
        loadingTickets: false,
        loadingNotifications: false,
        submitting: false,
        showNewTicketModal: false,
        faqSearch: '',

        stats: {
            unread_messages: 5,
            open_tickets: 2,
            resolved_tickets: 8,
            total_notifications: 12
        },

        messages: [],
        tickets: [],
        notifications: [],

        messageFilters: {
            search: '',
            status: ''
        },

        ticketFilters: {
            search: '',
            status: '',
            priority: ''
        },

        newTicket: {
            category: '',
            priority: 'medium',
            subject: '',
            description: ''
        },

        faqItems: [
            {
                question: "Comment créer une nouvelle campagne ?",
                answer: "Pour créer une nouvelle campagne, rendez-vous dans la section 'Campagnes' et cliquez sur 'Nouvelle Campagne'. Suivez ensuite les étapes du formulaire pour définir vos objectifs, budget et critères.",
                open: false
            },
            {
                question: "Comment contacter un influenceur ?",
                answer: "Vous pouvez contacter un influenceur directement depuis son profil en cliquant sur 'Envoyer un message' ou en créant une offre de collaboration spécifique.",
                open: false
            },
            {
                question: "Quels sont les modes de paiement acceptés ?",
                answer: "Nous acceptons les cartes bancaires (Visa, Mastercard), PayPal, et les virements bancaires. Tous les paiements sont sécurisés et traités via nos partenaires certifiés.",
                open: false
            },
            {
                question: "Comment suivre les performances de ma campagne ?",
                answer: "Accédez au tableau de bord de votre campagne pour voir les métriques en temps réel : impressions, engagement, clics, conversions et ROI. Des rapports détaillés sont également disponibles.",
                open: false
            },
            {
                question: "Que faire si j'ai un problème technique ?",
                answer: "En cas de problème technique, créez un ticket de support en décrivant le problème rencontré. Notre équipe technique vous répondra dans les plus brefs délais.",
                open: false
            }
        ],

        filteredFAQ: [],

        init() {
            this.loadMessages();
            this.loadTickets();
            this.loadNotifications();
            this.filteredFAQ = [...this.faqItems];
        },

        async loadMessages() {
            this.loadingMessages = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                this.messages = [
                    {
                        id: 1,
                        sender: { name: 'Sarah Martin', avatar: null },
                        subject: 'Proposition de collaboration',
                        preview: 'Bonjour, je suis intéressée par votre nouvelle campagne...',
                        read: false,
                        created_at: '2024-01-15T10:30:00Z'
                    },
                    {
                        id: 2,
                        sender: { name: 'Équipe Support', avatar: null },
                        subject: 'Votre campagne a été approuvée',
                        preview: 'Félicitations ! Votre campagne "Promotion Été 2024" a été...',
                        read: true,
                        created_at: '2024-01-14T14:20:00Z'
                    }
                ];
            } catch (error) {
                console.error('Error loading messages:', error);
            }
            this.loadingMessages = false;
        },

        async loadTickets() {
            this.loadingTickets = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                this.tickets = [
                    {
                        id: 1001,
                        subject: 'Problème de connexion',
                        category: 'Technique',
                        status: 'open',
                        priority: 'high',
                        updated_at: '2024-01-15T10:30:00Z'
                    },
                    {
                        id: 1002,
                        subject: 'Question sur la facturation',
                        category: 'Facturation',
                        status: 'resolved',
                        priority: 'medium',
                        updated_at: '2024-01-14T14:20:00Z'
                    }
                ];
            } catch (error) {
                console.error('Error loading tickets:', error);
            }
            this.loadingTickets = false;
        },

        async loadNotifications() {
            this.loadingNotifications = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                this.notifications = [
                    {
                        id: 1,
                        type: 'success',
                        title: 'Campagne lancée',
                        message: 'Votre campagne "Promotion Été" a été lancée avec succès.',
                        read: false,
                        created_at: '2024-01-15T10:30:00Z'
                    },
                    {
                        id: 2,
                        type: 'info',
                        title: 'Nouveau message',
                        message: 'Vous avez reçu un nouveau message de Sarah Martin.',
                        read: true,
                        created_at: '2024-01-14T14:20:00Z'
                    }
                ];
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
            this.loadingNotifications = false;
        },

        searchMessages() {
            // Implement message search
            console.log('Searching messages:', this.messageFilters.search);
        },

        searchTickets() {
            // Implement ticket search
            console.log('Searching tickets:', this.ticketFilters.search);
        },

        searchFAQ() {
            if (!this.faqSearch) {
                this.filteredFAQ = [...this.faqItems];
                return;
            }

            this.filteredFAQ = this.faqItems.filter(item =>
                item.question.toLowerCase().includes(this.faqSearch.toLowerCase()) ||
                item.answer.toLowerCase().includes(this.faqSearch.toLowerCase())
            );
        },

        async createTicket() {
            this.submitting = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));

                this.showNewTicketModal = false;
                this.resetNewTicket();
                this.loadTickets();
            } catch (error) {
                console.error('Error creating ticket:', error);
            }
            this.submitting = false;
        },

        resetNewTicket() {
            this.newTicket = {
                category: '',
                priority: 'medium',
                subject: '',
                description: ''
            };
        },

        viewMessage(message) {
            console.log('View message:', message);
            // Mark as read
            message.read = true;
        },

        viewTicket(ticket) {
            console.log('View ticket:', ticket);
        },

        replyToTicket(ticket) {
            console.log('Reply to ticket:', ticket);
        },

        markNotificationAsRead(notification) {
            notification.read = true;
        },

        markAllAsRead() {
            this.notifications.forEach(notification => {
                notification.read = true;
            });
            this.messages.forEach(message => {
                message.read = true;
            });
        },

        getStatusLabel(status) {
            const labels = {
                'open': 'Ouvert',
                'pending': 'En attente',
                'resolved': 'Résolu',
                'closed': 'Fermé'
            };
            return labels[status] || status;
        },

        getPriorityLabel(priority) {
            const labels = {
                'low': 'Basse',
                'medium': 'Moyenne',
                'high': 'Haute',
                'urgent': 'Urgente'
            };
            return labels[priority] || priority;
        },

        getNotificationIcon(type) {
            const icons = {
                'info': 'info',
                'success': 'check-circle',
                'warning': 'alert-triangle',
                'error': 'alert-circle'
            };
            return icons[type] || 'bell';
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    };
}
</script>
@endsection