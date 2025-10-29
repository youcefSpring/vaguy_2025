@extends('layouts.dashboard')

@section('title', 'Paramètres du Profil')

@section('content')
<div class="space-y-6" x-data="profileManager()">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Profile Settings') }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('Manage your personal information and account settings') }}</p>
            </div>
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <button @click="toggleEditMode()"
                        :class="editMode ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide shadow-sm transition-all duration-150">
                    <i :data-lucide="editMode ? 'save' : 'edit'" class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2"></i>
                    <span x-text="editMode ? '{{ __('Save') }}' : '{{ __('Edit') }}'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Tabs -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8 rtl:space-x-reverse px-6" aria-label="Tabs">
                <button @click="activeTab = 'general'"
                        :class="activeTab === 'general' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i data-lucide="user" class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2 inline"></i>
                    {{ __('General Information') }}
                </button>
                <button @click="activeTab = 'company'"
                        :class="activeTab === 'company' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i data-lucide="building" class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2 inline"></i>
                    {{ __('Company') }}
                </button>
                <button @click="activeTab = 'security'"
                        :class="activeTab === 'security' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i data-lucide="shield" class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2 inline"></i>
                    {{ __('Security') }}
                </button>
                <button @click="activeTab = 'preferences'"
                        :class="activeTab === 'preferences' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i data-lucide="settings" class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2 inline"></i>
                    {{ __('Preferences') }}
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- General Information Tab -->
            <div x-show="activeTab === 'general'">
                <form @submit.prevent="saveProfile()" action="{{ localized_route('client.profile.update') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Profile Picture -->
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <img class="h-20 w-20 rounded-full object-cover" :src="profile.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(profile.firstName + ' ' + profile.lastName)" :alt="profile.firstName + ' ' + profile.lastName">
                            </div>
                            <div class="flex-1">
                                <div x-show="editMode">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
                                    <div class="flex items-center space-x-4">
                                        <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <button type="button" class="text-red-600 hover:text-red-900 text-sm">Supprimer</button>
                                    </div>
                                </div>
                                <div x-show="!editMode" class="text-sm text-gray-500">
                                    Photo de profil actuelle
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                                <input type="text"
                                       name="firstname"
                                       x-model="profile.firstName"
                                       :readonly="!editMode"
                                       :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                       value="{{ old('firstname', (string)($user->firstname ?? '')) }}"
                                       required
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                                <input type="text"
                                       name="lastname"
                                       x-model="profile.lastName"
                                       :readonly="!editMode"
                                       :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                       value="{{ old('lastname', (string)($user->lastname ?? '')) }}"
                                       required
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email"
                                       name="email"
                                       x-model="profile.email"
                                       :readonly="!editMode"
                                       :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                       value="{{ old('email', (string)($user->email ?? '')) }}"
                                       required
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                <input type="tel"
                                       name="mobile"
                                       x-model="profile.phone"
                                       :readonly="!editMode"
                                       :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                       value="{{ old('mobile', (string)($user->mobile ?? '')) }}"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                                <input type="text"
                                       name="city"
                                       x-model="profile.city"
                                       :readonly="!editMode"
                                       :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                       value="{{ old('city', (string)($user->city ?? '')) }}"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                                <select name="country"
                                        x-model="profile.country"
                                        :disabled="!editMode"
                                        :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Sélectionner un pays</option>
                                    <option value="FR" {{ old('country', (string)($user->country ?? '') ?? '') === 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="DZ" {{ old('country', (string)($user->country ?? '') ?? '') === 'DZ' ? 'selected' : '' }}>Algérie</option>
                                    <option value="MA" {{ old('country', (string)($user->country ?? '') ?? '') === 'MA' ? 'selected' : '' }}>Maroc</option>
                                    <option value="TN" {{ old('country', (string)($user->country ?? '') ?? '') === 'TN' ? 'selected' : '' }}>Tunisie</option>
                                    <option value="BE" {{ old('country', (string)($user->country ?? '') ?? '') === 'BE' ? 'selected' : '' }}>Belgique</option>
                                    <option value="CH" {{ old('country', (string)($user->country ?? '') ?? '') === 'CH' ? 'selected' : '' }}>Suisse</option>
                                    <option value="CA" {{ old('country', (string)($user->country ?? '') ?? '') === 'CA' ? 'selected' : '' }}>Canada</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Adresse</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Adresse complète</label>
                                    <textarea name="address"
                                              x-model="profile.address"
                                              :readonly="!editMode"
                                              :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                              rows="3"
                                              placeholder="Adresse complète"
                                              class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('address', (string)($user->address ?? '')) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">État/Région</label>
                                    <input type="text"
                                           name="state"
                                           x-model="profile.region"
                                           :readonly="!editMode"
                                           :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                           value="{{ old('state', (string)($user->state ?? '')) }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                                    <input type="text"
                                           name="zip"
                                           x-model="profile.postalCode"
                                           :readonly="!editMode"
                                           :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                           value="{{ old('zip', (string)($user->zip ?? '')) }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Company Information Tab -->
            <div x-show="activeTab === 'company'">
                <form @submit.prevent="saveCompany()" action="{{ localized_route('client.profile.update') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise</label>
                                <input type="text"
                                       name="company_name"
                                       x-model="company.name"
                                       :readonly="!editMode"
                                       :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Secteur d'activité</label>
                                <select x-model="company.industry"
                                        name="industry"
                                        :disabled="!editMode"
                                        :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Sélectionner un secteur</option>
                                    <option value="fashion">Mode et Beauté</option>
                                    <option value="tech">Technologie</option>
                                    <option value="food">Alimentation</option>
                                    <option value="travel">Voyage</option>
                                    <option value="health">Santé et Bien-être</option>
                                    <option value="automotive">Automobile</option>
                                    <option value="finance">Finance</option>
                                    <option value="education">Éducation</option>
                                    <option value="entertainment">Divertissement</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site web</label>
                                <input type="url"
                                       name="website"
                                       x-model="company.website"
                                       :readonly="!editMode"
                                       :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                       placeholder="https://example.com"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Taille de l'entreprise</label>
                                <select x-model="company.size"
                                        name="company_size"
                                        :disabled="!editMode"
                                        :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Sélectionner la taille</option>
                                    <option value="1-10">1-10 employés</option>
                                    <option value="11-50">11-50 employés</option>
                                    <option value="51-200">51-200 employés</option>
                                    <option value="201-500">201-500 employés</option>
                                    <option value="500+">500+ employés</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description de l'entreprise</label>
                            <textarea x-model="company.description"
                                      name="company_description"
                                      :readonly="!editMode"
                                      :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                      rows="4"
                                      placeholder="Décrivez votre entreprise, ses activités et ses valeurs..."
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div x-show="activeTab === 'security'">
                <div class="space-y-6">
                    <!-- Change Password -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Change Password') }}</h3>
                        <form @submit.prevent="changePassword()" action="{{ localized_route('client.profile.password.update') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                                    <input type="password"
                                           name="current_password"
                                           x-model="passwordForm.currentPassword"
                                           required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                                    <input type="password"
                                           name="password"
                                           x-model="passwordForm.newPassword"
                                           required
                                           minlength="8"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500">Au moins 8 caractères avec majuscules, minuscules et chiffres</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           x-model="passwordForm.confirmPassword"
                                           required
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                            :disabled="passwordForm.newPassword !== passwordForm.confirmPassword || submitting"
                                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Changer le mot de passe
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Two-Factor Authentication -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Two-Factor Authentication') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Secure your account with two-factor authentication') }}</p>
                            </div>
                            <button @click="toggle2FA()"
                                    :class="security.twoFactorEnabled ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-200 hover:bg-gray-300'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span :class="security.twoFactorEnabled ? 'translate-x-5' : 'translate-x-0'"
                                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preferences Tab -->
            <div x-show="activeTab === 'preferences'">
                <form @submit.prevent="savePreferences()" action="{{ localized_route('client.profile.settings.update') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Language and Region -->
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Language and Region') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Langue de l'interface</label>
                                    <select x-model="preferences.language"
                                            name="language"
                                            :disabled="!editMode"
                                            :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="fr">Français</option>
                                        <option value="en">English</option>
                                        <option value="ar">العربية</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fuseau horaire</label>
                                    <select x-model="preferences.timezone"
                                            name="timezone"
                                            :disabled="!editMode"
                                            :class="editMode ? 'bg-white' : 'bg-gray-50'"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Europe/Paris">Europe/Paris (UTC+1)</option>
                                        <option value="Europe/London">Europe/London (UTC+0)</option>
                                        <option value="America/New_York">America/New_York (UTC-5)</option>
                                        <option value="Asia/Tokyo">Asia/Tokyo (UTC+9)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Notification Preferences') }}</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900">Notifications par email</label>
                                        <p class="text-sm text-gray-500">Recevoir des notifications importantes par email</p>
                                    </div>
                                    <button @click="preferences.emailNotifications = !preferences.emailNotifications"
                                            :class="preferences.emailNotifications ? 'bg-blue-600' : 'bg-gray-200'"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <span :class="preferences.emailNotifications ? 'translate-x-5' : 'translate-x-0'"
                                              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                    </button>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900">Notifications push</label>
                                        <p class="text-sm text-gray-500">Recevoir des notifications push dans le navigateur</p>
                                    </div>
                                    <button @click="preferences.pushNotifications = !preferences.pushNotifications"
                                            :class="preferences.pushNotifications ? 'bg-blue-600' : 'bg-gray-200'"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <span :class="preferences.pushNotifications ? 'translate-x-5' : 'translate-x-0'"
                                              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Account Actions -->
                        <div class="bg-white dark:bg-gray-800 border border-red-200 dark:border-red-900/50 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-red-900 dark:text-red-400 mb-4">{{ __('Account Actions') }}</h3>
                            <div class="space-y-4">
                                <a href="{{ localized_route('client.profile.export') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i data-lucide="download" class="h-4 w-4 mr-2"></i>
                                    Exporter mes données
                                </a>

                                <button @click="showDeleteAccountModal = true" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                    <i data-lucide="trash-2" class="h-4 w-4 mr-2"></i>
                                    Supprimer mon compte
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Account Overview Sidebar -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Account Overview') }}</h3>

        <div class="space-y-4">
            <div class="flex items-center">
                <i data-lucide="calendar" class="h-5 w-5 text-gray-400 mr-3"></i>
                <div>
                    <small class="text-gray-500">Membre depuis</small>
                    <div class="font-medium">{{ ($user ?? null)?->created_at?->format('M Y') ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="flex items-center">
                <i data-lucide="shield-check" class="h-5 w-5 text-green-500 mr-3"></i>
                <div>
                    <small class="text-gray-500">Statut du compte</small>
                    <div class="font-medium text-green-600">Actif</div>
                </div>
            </div>

            <div class="flex items-center">
                <i data-lucide="globe" class="h-5 w-5 text-blue-500 mr-3"></i>
                <div>
                    <small class="text-gray-500">Langue</small>
                    <div class="font-medium">Français</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Actions rapides</h4>
            <div class="space-y-2">
                <a href="{{ localized_route('client.profile.password') }}" class="flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <i data-lucide="key" class="h-4 w-4 mr-2"></i>
                    Changer le mot de passe
                </a>
                <a href="{{ localized_route('client.profile.settings') }}" class="flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <i data-lucide="settings" class="h-4 w-4 mr-2"></i>
                    Paramètres du compte
                </a>
                <a href="{{ localized_route('client.profile.two-factor') }}" class="flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <i data-lucide="shield" class="h-4 w-4 mr-2"></i>
                    Authentification 2FA
                </a>
                <a href="{{ localized_route('client.profile.export') }}" class="flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <i data-lucide="download" class="h-4 w-4 mr-2"></i>
                    Exporter les données
                </a>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div x-show="showDeleteAccountModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-red-900">Supprimer le compte</h3>
                    <button @click="showDeleteAccountModal = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>

                <div class="mb-6">
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Attention</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Cette action est irréversible. Toutes vos données seront définitivement supprimées.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="deleteAccount()" action="{{ localized_route('client.profile.deactivate') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tapez "SUPPRIMER" pour confirmer
                        </label>
                        <input type="text"
                               name="confirmation"
                               x-model="deleteConfirmation"
                               placeholder="SUPPRIMER"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showDeleteAccountModal = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                                :disabled="deleteConfirmation !== 'SUPPRIMER' || submitting"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 disabled:opacity-50">
                            <span x-show="!submitting">Supprimer définitivement</span>
                            <span x-show="submitting" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Suppression...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function profileManager() {
    return {
        activeTab: 'general',
        editMode: false,
        submitting: false,
        showDeleteAccountModal: false,
        deleteConfirmation: '',

        profile: {
            firstName: '{{ ($user ?? null)?->firstname ?? "Casey" }}',
            lastName: '{{ ($user ?? null)?->lastname ?? "Reynolds" }}',
            email: '{{ ($user ?? null)?->email ?? "casey@example.com" }}',
            phone: '{{ ($user ?? null)?->mobile ?? "" }}',
            city: '{{ ($user ?? null)?->city ?? "" }}',
            country: '{{ (string)($user->country ?? '') ?? "" }}',
            region: '{{ ($user ?? null)?->state ?? "" }}',
            postalCode: '{{ ($user ?? null)?->zip ?? "" }}',
            address: '{{ ($user ?? null)?->address ?? "" }}',
            avatar: null
        },

        company: {
            name: 'TechCorp France',
            industry: 'tech',
            website: 'https://techcorp.fr',
            size: '11-50',
            description: 'Entreprise spécialisée dans le développement de solutions technologiques innovantes.'
        },

        security: {
            twoFactorEnabled: {{ ($user ?? null)?->two_factor_enabled ? 'true' : 'false' }}
        },

        preferences: {
            language: '{{ ($user ?? null)?->language ?? "fr" }}',
            timezone: 'Europe/Paris',
            emailNotifications: true,
            pushNotifications: true
        },

        passwordForm: {
            currentPassword: '',
            newPassword: '',
            confirmPassword: ''
        },

        init() {
            // Initialize component
        },

        toggleEditMode() {
            if (this.editMode) {
                this.saveProfile();
            } else {
                this.editMode = true;
            }
        },

        async saveProfile() {
            this.submitting = true;
            try {
                await new Promise(resolve => setTimeout(resolve, 1000));
                this.editMode = false;
            } catch (error) {
                console.error('Error saving profile:', error);
            }
            this.submitting = false;
        },

        async saveCompany() {
            this.submitting = true;
            try {
                await new Promise(resolve => setTimeout(resolve, 1000));
            } catch (error) {
                console.error('Error saving company:', error);
            }
            this.submitting = false;
        },

        async savePreferences() {
            this.submitting = true;
            try {
                await new Promise(resolve => setTimeout(resolve, 1000));
            } catch (error) {
                console.error('Error saving preferences:', error);
            }
            this.submitting = false;
        },

        async changePassword() {
            if (this.passwordForm.newPassword !== this.passwordForm.confirmPassword) {
                alert('Les mots de passe ne correspondent pas');
                return;
            }

            this.submitting = true;
            try {
                await new Promise(resolve => setTimeout(resolve, 1000));
                this.passwordForm = {
                    currentPassword: '',
                    newPassword: '',
                    confirmPassword: ''
                };
                alert('Mot de passe changé avec succès');
            } catch (error) {
                console.error('Error changing password:', error);
            }
            this.submitting = false;
        },

        toggle2FA() {
            this.security.twoFactorEnabled = !this.security.twoFactorEnabled;
        },

        async deleteAccount() {
            if (this.deleteConfirmation !== 'SUPPRIMER') {
                return;
            }

            this.submitting = true;
            try {
                await new Promise(resolve => setTimeout(resolve, 2000));
                window.location.href = '/';
            } catch (error) {
                console.error('Error deleting account:', error);
            }
            this.submitting = false;
        }
    };
}
</script>
@endsection