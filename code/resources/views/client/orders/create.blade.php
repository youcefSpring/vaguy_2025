@extends('layouts.dashboard')

@section('title', 'Créer une Commande')

@section('content')
<div class="max-w-4xl mx-auto" x-data="orderCreate()">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Créer une Commande</h1>
                <p class="mt-1 text-sm text-gray-500">Créez une nouvelle commande pour vos services d'influence</p>
            </div>
            <a href="{{ localized_route('client.orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
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
                    <li :class="index < steps.length - 1 ? 'relative flex-1' : ''" class="flex items-center">
                        <div class="flex items-center">
                            <div :class="{
                                'bg-blue-600 text-white': index < currentStep,
                                'bg-blue-600 text-white': index === currentStep,
                                'bg-gray-200 text-gray-400': index > currentStep
                            }" class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-medium">
                                <span x-text="index + 1"></span>
                            </div>
                            <span :class="{
                                'text-blue-600 font-medium': index <= currentStep,
                                'text-gray-500': index > currentStep
                            }" class="ml-3 text-sm" x-text="step.title"></span>
                        </div>
                        <div x-show="index < steps.length - 1" class="ml-4 flex-1 h-0.5 bg-gray-200">
                            <div :class="index < currentStep ? 'bg-blue-600' : 'bg-gray-200'" class="h-full transition-all duration-300" :style="index < currentStep ? 'width: 100%' : 'width: 0%'"></div>
                        </div>
                    </li>
                </template>
            </ol>
        </nav>
    </div>

    <!-- Form Container -->
    <div class="bg-white shadow rounded-lg">
        <form @submit.prevent="submitForm()">
            <!-- Step 1: Service Information -->
            <div x-show="currentStep === 0" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du Service</h3>

                <div class="space-y-6">
                    <!-- Service Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de Service *</label>
                        <div class="relative">
                            <select x-model="formData.service_type"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white"
                                    required>
                                <option value="" disabled class="text-gray-400">Sélectionner un type de service</option>
                                <option value="instagram_post">📸 Publication Instagram</option>
                                <option value="instagram_story">📱 Story Instagram</option>
                                <option value="instagram_reel">🎬 Reel Instagram</option>
                                <option value="youtube_video">🎥 Vidéo YouTube</option>
                                <option value="tiktok_video">🎵 Vidéo TikTok</option>
                                <option value="facebook_post">👥 Publication Facebook</option>
                                <option value="twitter_post">🐦 Tweet</option>
                                <option value="linkedin_post">💼 Publication LinkedIn</option>
                                <option value="blog_article">📝 Article de Blog</option>
                                <option value="podcast_mention">🎙️ Mention Podcast</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Service Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titre du Service *</label>
                        <input type="text"
                               x-model="formData.service_title"
                               placeholder="Ex: Publication Instagram pour notre nouveau produit"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               required>
                    </div>

                    <!-- Service Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description du Service *</label>
                        <textarea x-model="formData.service_description"
                                  rows="4"
                                  placeholder="Décrivez en détail le service que vous souhaitez commander..."
                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  required></textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            <span x-text="formData.service_description.length"></span>/1000 caractères
                        </p>
                    </div>

                    <!-- Budget Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget Minimum *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                                <input type="number"
                                       x-model="formData.minimum_budget"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00"
                                       class="block w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget Maximum *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                                <input type="number"
                                       x-model="formData.maximum_budget"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00"
                                       class="block w-full pl-8 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Limite *</label>
                        <input type="date"
                               x-model="formData.deadline"
                               :min="new Date().toISOString().split('T')[0]"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               required>
                    </div>
                </div>
            </div>

            <!-- Step 2: Requirements -->
            <div x-show="currentStep === 1" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Exigences Spécifiques</h3>

                <div class="space-y-6">
                    <!-- Additional Requirements -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Exigences Additionnelles</label>
                        <textarea x-model="formData.additional_requirements"
                                  rows="6"
                                  placeholder="Détaillez vos exigences spécifiques, ton de communication, éléments à inclure, restrictions..."
                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            <span x-text="formData.additional_requirements.length"></span>/2000 caractères
                        </p>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pièces Jointes</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i data-lucide="upload" class="mx-auto h-12 w-12 text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Télécharger des fichiers</span>
                                        <input type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip" class="sr-only">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, PDF, DOC jusqu'à 10MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Influencer Criteria -->
            <div x-show="currentStep === 2" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Critères d'Influenceur</h3>

                <div class="space-y-6">
                    <!-- Follower Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Minimum d'Abonnés *</label>
                            <input type="number"
                                   x-model="formData.minimum_followers"
                                   min="0"
                                   placeholder="1000"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Maximum d'Abonnés</label>
                            <input type="number"
                                   x-model="formData.maximum_followers"
                                   min="0"
                                   placeholder="Pas de limite"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Engagement Rate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Taux d'Engagement Minimum</label>
                        <div class="relative">
                            <input type="number"
                                   x-model="formData.minimum_engagement_rate"
                                   min="0"
                                   max="100"
                                   step="0.1"
                                   placeholder="2.5"
                                   class="block w-full pr-8 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catégories *</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <template x-for="category in categories" :key="category.value">
                                <label class="flex items-center space-x-2 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox"
                                           :value="category.value"
                                           x-model="formData.selected_categories"
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm text-gray-700" x-text="category.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Gender Preference -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Préférence de Genre</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" x-model="formData.gender_preference" value="any" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Aucune préférence</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" x-model="formData.gender_preference" value="male" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Homme</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" x-model="formData.gender_preference" value="female" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Femme</span>
                            </label>
                        </div>
                    </div>

                    <!-- Age Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Âge Minimum</label>
                            <input type="number"
                                   x-model="formData.minimum_age"
                                   min="18"
                                   max="100"
                                   placeholder="18"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Âge Maximum</label>
                            <input type="number"
                                   x-model="formData.maximum_age"
                                   min="18"
                                   max="100"
                                   placeholder="65"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Preferred Locations -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Localisations Préférées</label>
                        <input type="text"
                               x-model="formData.preferred_locations"
                               placeholder="Paris, Lyon, Marseille..."
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Séparez les localisations par des virgules</p>
                    </div>
                </div>
            </div>

            <!-- Step 4: Review -->
            <div x-show="currentStep === 3" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Récapitulatif de la Commande</h3>

                <div class="space-y-6">
                    <!-- Service Summary -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Informations du Service</h4>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type de Service</dt>
                                <dd class="text-sm text-gray-900" x-text="getServiceTypeLabel(formData.service_type)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Budget</dt>
                                <dd class="text-sm text-gray-900" x-text="formatBudgetRange(formData.minimum_budget, formData.maximum_budget)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date Limite</dt>
                                <dd class="text-sm text-gray-900" x-text="formatDate(formData.deadline)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Catégories</dt>
                                <dd class="text-sm text-gray-900" x-text="getSelectedCategoriesText()"></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Order Summary -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Résumé de la Commande</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Type de Service:</span>
                                <span class="text-gray-900" x-text="getServiceTypeLabel(formData.service_type)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Fourchette de Budget:</span>
                                <span class="text-gray-900" x-text="formatBudgetRange(formData.minimum_budget, formData.maximum_budget)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Date Limite:</span>
                                <span class="text-gray-900" x-text="formatDate(formData.deadline)"></span>
                            </div>
                            <div class="flex justify-between text-sm pt-2 border-t border-gray-200">
                                <span class="font-medium text-gray-900">Statut:</span>
                                <span class="text-green-600 font-medium">Prêt à Publier</span>
                            </div>
                        </div>
                    </div>

                    <!-- Helpful Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900 mb-2">Conseils Utiles</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Soyez clair et précis dans votre description</li>
                            <li>• Fixez un budget réaliste pour attirer les meilleurs influenceurs</li>
                            <li>• Vérifiez vos critères d'influenceur pour optimiser les candidatures</li>
                            <li>• Prévoyez une date limite raisonnable pour la qualité du travail</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex items-center justify-between">
                <button type="button"
                        @click="previousStep()"
                        x-show="currentStep > 0"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i data-lucide="arrow-left" class="h-4 w-4 mr-2"></i>
                    Précédent
                </button>

                <div></div>

                <div class="flex space-x-3">
                    <button type="button"
                            @click="saveDraft()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i data-lucide="save" class="h-4 w-4 mr-2"></i>
                        Sauvegarder le Brouillon
                    </button>

                    <button type="button"
                            @click="nextStep()"
                            x-show="currentStep < steps.length - 1"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Suivant
                        <i data-lucide="arrow-right" class="h-4 w-4 ml-2"></i>
                    </button>

                    <button type="submit"
                            x-show="currentStep === steps.length - 1"
                            :disabled="submitting"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 disabled:opacity-50">
                        <span x-show="!submitting">Créer la Commande</span>
                        <span x-show="submitting" class="flex items-center">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                            Création...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function orderCreate() {
    return {
        currentStep: 0,
        submitting: false,

        steps: [
            { title: 'Service', description: 'Informations du service' },
            { title: 'Exigences', description: 'Exigences spécifiques' },
            { title: 'Critères', description: 'Critères d\'influenceur' },
            { title: 'Récapitulatif', description: 'Vérification finale' }
        ],

        formData: {
            service_type: '',
            service_title: '',
            service_description: '',
            minimum_budget: '',
            maximum_budget: '',
            deadline: '',
            additional_requirements: '',
            minimum_followers: '',
            maximum_followers: '',
            minimum_engagement_rate: '',
            selected_categories: [],
            gender_preference: 'any',
            minimum_age: '',
            maximum_age: '',
            preferred_locations: ''
        },

        categories: [
            { value: 'fashion', label: 'Mode' },
            { value: 'beauty', label: 'Beauté' },
            { value: 'lifestyle', label: 'Style de Vie' },
            { value: 'travel', label: 'Voyage' },
            { value: 'food', label: 'Gastronomie' },
            { value: 'fitness', label: 'Fitness' },
            { value: 'tech', label: 'Technologie' },
            { value: 'gaming', label: 'Gaming' },
            { value: 'music', label: 'Musique' },
            { value: 'sports', label: 'Sports' },
            { value: 'health', label: 'Santé' },
            { value: 'business', label: 'Business' }
        ],

        init() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[type="date"]').setAttribute('min', today);
        },

        nextStep() {
            if (this.validateCurrentStep() && this.currentStep < this.steps.length - 1) {
                this.currentStep++;
            }
        },

        previousStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },

        validateCurrentStep() {
            // Add validation logic here
            return true;
        },

        async submitForm() {
            this.submitting = true;

            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));

                // Redirect to orders list with success message
                window.location.href = '{{ localized_route("client.orders.index") }}';
            } catch (error) {
                console.error('Error creating order:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            }

            this.submitting = false;
        },

        saveDraft() {
            // Save draft functionality
            alert('Brouillon sauvegardé avec succès');
        },

        getServiceTypeLabel(type) {
            const types = {
                'instagram_post': 'Publication Instagram',
                'instagram_story': 'Story Instagram',
                'instagram_reel': 'Reel Instagram',
                'youtube_video': 'Vidéo YouTube',
                'tiktok_video': 'Vidéo TikTok',
                'facebook_post': 'Publication Facebook',
                'twitter_post': 'Tweet',
                'linkedin_post': 'Publication LinkedIn',
                'blog_article': 'Article de Blog',
                'podcast_mention': 'Mention Podcast'
            };
            return types[type] || type;
        },

        formatBudgetRange(min, max) {
            if (!min && !max) return 'Non spécifié';
            if (!max) return `À partir de ${min}€`;
            return `${min}€ - ${max}€`;
        },

        formatDate(date) {
            if (!date) return 'Non spécifiée';
            return new Date(date).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },

        getSelectedCategoriesText() {
            if (this.formData.selected_categories.length === 0) return 'Aucune catégorie sélectionnée';

            const selectedLabels = this.formData.selected_categories.map(value => {
                const category = this.categories.find(cat => cat.value === value);
                return category ? category.label : value;
            });

            return selectedLabels.join(', ');
        }
    };
}
</script>
@endsection