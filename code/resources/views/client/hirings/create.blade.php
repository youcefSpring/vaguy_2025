@extends('layouts.dashboard')

@section('title', 'Nouvelle Embauche')

@section('content')
<div class="max-w-4xl mx-auto" x-data="hiringCreate()">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nouvelle Embauche</h1>
                <p class="mt-1 text-sm text-gray-500">Créez une nouvelle demande d'embauche pour vos campagnes</p>
            </div>
            <a href="{{ localized_route('client.hirings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i data-lucide="arrow-left" class="h-4 w-4 mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <nav aria-label="Progress">
            <ol class="flex items-center">
                <template x-for="(step, index) in steps" :key="index">
                    <li :class="index !== steps.length - 1 ? 'flex-1' : ''" class="relative">
                        <div class="flex items-center">
                            <div class="relative flex items-center justify-center w-8 h-8 rounded-full border-2"
                                 :class="currentStep > index ? 'bg-blue-600 border-blue-600' : currentStep === index ? 'border-blue-600 bg-white' : 'border-gray-300 bg-white'">
                                <span class="text-sm font-medium"
                                      :class="currentStep > index ? 'text-white' : currentStep === index ? 'text-blue-600' : 'text-gray-500'"
                                      x-text="index + 1">
                                </span>
                            </div>
                            <div class="ml-4 min-w-0 flex-1">
                                <p class="text-sm font-medium"
                                   :class="currentStep >= index ? 'text-gray-900' : 'text-gray-500'"
                                   x-text="step.title">
                                </p>
                                <p class="text-sm text-gray-500" x-text="step.description"></p>
                            </div>
                        </div>
                        <div x-show="index !== steps.length - 1" class="absolute top-4 left-4 -ml-px mt-0.5 h-full w-0.5 bg-gray-300"></div>
                    </li>
                </template>
            </ol>
        </nav>
    </div>

    <!-- Form Container -->
    <div class="bg-white shadow rounded-lg">
        <form @submit.prevent="submitForm()">
            <!-- Step 1: Influencer Selection -->
            <div x-show="currentStep === 0" class="p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Sélection de l'influenceur</h3>
                        <p class="text-sm text-gray-500 mb-6">Choisissez l'influenceur avec qui vous souhaitez collaborer.</p>
                    </div>

                    <!-- Search Influencers -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher un influenceur</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input type="text"
                                   x-model="influencerSearch"
                                   @input="searchInfluencers"
                                   placeholder="Rechercher par nom, email ou catégorie..."
                                   class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Influencer Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="influencer in filteredInfluencers" :key="influencer.id">
                            <div class="relative border rounded-lg p-4 cursor-pointer hover:bg-gray-50"
                                 :class="formData.influencer_id === influencer.id ? 'border-blue-500 bg-blue-50' : 'border-gray-300'"
                                 @click="selectInfluencer(influencer)">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-full object-cover"
                                             :src="influencer.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(influencer.name)"
                                             :alt="influencer.name">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900" x-text="influencer.name"></p>
                                        <p class="text-sm text-gray-500" x-text="influencer.category"></p>
                                        <div class="flex items-center mt-1">
                                            <span class="text-xs text-gray-500">Followers:</span>
                                            <span class="ml-1 text-xs font-medium text-gray-900" x-text="formatNumber(influencer.followers)"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-2 right-2">
                                    <div x-show="formData.influencer_id === influencer.id" class="w-4 h-4 bg-blue-600 rounded-full flex items-center justify-center">
                                        <i data-lucide="check" class="h-3 w-3 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredInfluencers.length === 0" class="text-center py-8">
                        <i data-lucide="users" class="mx-auto h-12 w-12 text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun influenceur trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Collaboration Details -->
            <div x-show="currentStep === 1" class="p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Détails de la collaboration</h3>
                        <p class="text-sm text-gray-500 mb-6">Définissez les paramètres de votre collaboration.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type de collaboration *</label>
                            <div class="relative">
                                <select x-model="formData.type" required
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                                    <option value="" disabled class="text-gray-400">Sélectionner un type</option>
                                    <option value="collaboration">🤝 Collaboration ponctuelle</option>
                                    <option value="sponsorship">🎆 Partenariat à long terme</option>
                                    <option value="review">⭐ Test et avis produit</option>
                                    <option value="event">🎉 Événement spécial</option>
                                    <option value="ambassador">👑 Programme d'ambassadeur</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                            <div class="relative">
                                <select x-model="formData.priority"
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                                    <option value="normal">🟢 Normale</option>
                                    <option value="high">🟠 Haute</option>
                                    <option value="urgent">🔴 Urgente</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titre de la collaboration *</label>
                        <input type="text"
                               x-model="formData.title"
                               required
                               placeholder="Ex: Promotion de notre nouvelle collection..."
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description détaillée *</label>
                        <textarea x-model="formData.description"
                                  required
                                  rows="6"
                                  placeholder="Décrivez en détail votre demande de collaboration, vos objectifs, le message à transmettre..."
                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget proposé (€) *</label>
                            <input type="number"
                                   x-model="formData.budget"
                                   required
                                   min="0"
                                   step="0.01"
                                   placeholder="1000.00"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                            <input type="date"
                                   x-model="formData.start_date"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date limite *</label>
                            <input type="date"
                                   x-model="formData.deadline"
                                   required
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Requirements & Deliverables -->
            <div x-show="currentStep === 2" class="p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Exigences et livrables</h3>
                        <p class="text-sm text-gray-500 mb-6">Spécifiez vos attentes et les livrables souhaités.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Exigences spécifiques</label>
                        <textarea x-model="formData.requirements"
                                  rows="4"
                                  placeholder="Ex: Mention obligatoire de la marque, utilisation de hashtags spécifiques, respect de la charte graphique..."
                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Plateformes concernées</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <template x-for="platform in platforms" :key="platform.name">
                                <div class="relative">
                                    <input type="checkbox"
                                           :id="'platform-' + platform.name"
                                           :value="platform.name"
                                           x-model="formData.platforms"
                                           class="peer sr-only">
                                    <label :for="'platform-' + platform.name"
                                           class="flex flex-col items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <i :data-lucide="platform.icon" class="h-6 w-6 text-gray-600 peer-checked:text-blue-600"></i>
                                        <span class="mt-2 text-sm font-medium text-gray-900" x-text="platform.label"></span>
                                    </label>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Types de contenu attendus</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <template x-for="content in contentTypes" :key="content.name">
                                <div class="relative">
                                    <input type="checkbox"
                                           :id="'content-' + content.name"
                                           :value="content.name"
                                           x-model="formData.content_types"
                                           class="peer sr-only">
                                    <label :for="'content-' + content.name"
                                           class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <i :data-lucide="content.icon" class="h-5 w-5 text-gray-600 peer-checked:text-blue-600 mr-3"></i>
                                        <span class="text-sm font-medium text-gray-900" x-text="content.label"></span>
                                    </label>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Objectifs de performance</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Vues minimum</label>
                                <input type="number"
                                       x-model="formData.min_views"
                                       min="0"
                                       placeholder="10000"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Engagement minimum (%)</label>
                                <input type="number"
                                       x-model="formData.min_engagement"
                                       min="0"
                                       max="100"
                                       step="0.1"
                                       placeholder="3.0"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Reach minimum</label>
                                <input type="number"
                                       x-model="formData.min_reach"
                                       min="0"
                                       placeholder="50000"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Review & Submit -->
            <div x-show="currentStep === 3" class="p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Récapitulatif</h3>
                        <p class="text-sm text-gray-500 mb-6">Vérifiez les informations avant de soumettre votre demande.</p>
                    </div>

                    <!-- Review Summary -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Influenceur sélectionné</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="getSelectedInfluencer()?.name || 'Non sélectionné'"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type de collaboration</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="getTypeLabel(formData.type)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Titre</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="formData.title"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Budget</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="formatPrice(formData.budget)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date limite</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="formatDate(formData.deadline)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Plateformes</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="formData.platforms.join(', ') || 'Aucune'"></dd>
                            </div>
                        </dl>

                        <div class="mt-4">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900" x-text="formData.description"></dd>
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox"
                                   x-model="formData.terms_accepted"
                                   required
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label class="font-medium text-gray-700">
                                J'accepte les conditions générales
                            </label>
                            <p class="text-gray-500">
                                En soumettant cette demande, vous acceptez nos
                                <a href="#" class="text-blue-600 hover:text-blue-500">conditions d'utilisation</a>
                                et notre
                                <a href="#" class="text-blue-600 hover:text-blue-500">politique de confidentialité</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between">
                <button type="button"
                        @click="previousStep()"
                        x-show="currentStep > 0"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i data-lucide="arrow-left" class="h-4 w-4 mr-2"></i>
                    Précédent
                </button>

                <div class="flex space-x-3">
                    <button type="button"
                            @click="nextStep()"
                            x-show="currentStep < steps.length - 1"
                            :disabled="!canProceedToNext()"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Suivant
                        <i data-lucide="arrow-right" class="h-4 w-4 ml-2"></i>
                    </button>

                    <button type="submit"
                            x-show="currentStep === steps.length - 1"
                            :disabled="submitting || !formData.terms_accepted"
                            class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!submitting">Soumettre la demande</span>
                        <span x-show="submitting" class="flex items-center">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                            Soumission...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function hiringCreate() {
    return {
        currentStep: 0,
        submitting: false,
        influencerSearch: '',
        filteredInfluencers: [],
        allInfluencers: [],

        steps: [
            {
                title: 'Influenceur',
                description: 'Sélection de l\'influenceur'
            },
            {
                title: 'Collaboration',
                description: 'Détails de la collaboration'
            },
            {
                title: 'Exigences',
                description: 'Exigences et livrables'
            },
            {
                title: 'Récapitulatif',
                description: 'Vérification et soumission'
            }
        ],

        platforms: [
            { name: 'instagram', label: 'Instagram', icon: 'instagram' },
            { name: 'tiktok', label: 'TikTok', icon: 'music' },
            { name: 'youtube', label: 'YouTube', icon: 'youtube' },
            { name: 'facebook', label: 'Facebook', icon: 'facebook' },
            { name: 'twitter', label: 'Twitter', icon: 'twitter' },
            { name: 'linkedin', label: 'LinkedIn', icon: 'linkedin' },
            { name: 'snapchat', label: 'Snapchat', icon: 'camera' },
            { name: 'twitch', label: 'Twitch', icon: 'tv' }
        ],

        contentTypes: [
            { name: 'post', label: 'Post', icon: 'image' },
            { name: 'story', label: 'Story', icon: 'circle' },
            { name: 'video', label: 'Vidéo', icon: 'video' },
            { name: 'reel', label: 'Reel', icon: 'film' },
            { name: 'live', label: 'Live', icon: 'radio' },
            { name: 'article', label: 'Article', icon: 'file-text' }
        ],

        formData: {
            influencer_id: '',
            type: '',
            priority: 'normal',
            title: '',
            description: '',
            budget: '',
            start_date: '',
            deadline: '',
            requirements: '',
            platforms: [],
            content_types: [],
            min_views: '',
            min_engagement: '',
            min_reach: '',
            terms_accepted: false
        },

        init() {
            this.loadInfluencers();
        },

        async loadInfluencers() {
            // Mock data
            this.allInfluencers = [
                {
                    id: 1,
                    name: 'Sarah Martin',
                    category: 'Mode & Beauté',
                    followers: 125000,
                    avatar: null
                },
                {
                    id: 2,
                    name: 'Thomas Dubois',
                    category: 'Tech & Gaming',
                    followers: 85000,
                    avatar: null
                },
                {
                    id: 3,
                    name: 'Lisa Rodriguez',
                    category: 'Lifestyle',
                    followers: 230000,
                    avatar: null
                },
                {
                    id: 4,
                    name: 'Marc Durand',
                    category: 'Sport & Fitness',
                    followers: 95000,
                    avatar: null
                }
            ];
            this.filteredInfluencers = [...this.allInfluencers];
        },

        searchInfluencers() {
            if (!this.influencerSearch) {
                this.filteredInfluencers = [...this.allInfluencers];
                return;
            }

            this.filteredInfluencers = this.allInfluencers.filter(influencer =>
                influencer.name.toLowerCase().includes(this.influencerSearch.toLowerCase()) ||
                influencer.category.toLowerCase().includes(this.influencerSearch.toLowerCase())
            );
        },

        selectInfluencer(influencer) {
            this.formData.influencer_id = influencer.id;
        },

        getSelectedInfluencer() {
            return this.allInfluencers.find(inf => inf.id === this.formData.influencer_id);
        },

        canProceedToNext() {
            switch (this.currentStep) {
                case 0:
                    return this.formData.influencer_id !== '';
                case 1:
                    return this.formData.type && this.formData.title && this.formData.description && this.formData.budget && this.formData.deadline;
                case 2:
                    return true; // Optional step
                default:
                    return true;
            }
        },

        nextStep() {
            if (this.canProceedToNext() && this.currentStep < this.steps.length - 1) {
                this.currentStep++;
            }
        },

        previousStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },

        async submitForm() {
            if (!this.formData.terms_accepted) {
                alert('Vous devez accepter les conditions générales.');
                return;
            }

            this.submitting = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));

                // Redirect to hiring index with success message
                window.location.href = '{{ localized_route("client.hirings.index") }}';
            } catch (error) {
                console.error('Error submitting hiring:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            }
            this.submitting = false;
        },

        getTypeLabel(type) {
            const labels = {
                'collaboration': 'Collaboration ponctuelle',
                'sponsorship': 'Partenariat à long terme',
                'review': 'Test et avis produit',
                'event': 'Événement spécial',
                'ambassador': 'Programme d\'ambassadeur'
            };
            return labels[type] || type;
        },

        formatPrice(price) {
            if (!price) return '';
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(price);
        },

        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleDateString('fr-FR');
        },

        formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }
    };
}
</script>
@endsection