@extends('layouts.dashboard')

@section('title', 'Créer une campagne')

@section('content')
<div class="space-y-6" x-data="campaignWizard()" x-init="init()">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Créer une nouvelle campagne</h1>
            <p class="text-gray-600">Créez une campagne marketing pour atteindre vos objectifs</p>
        </div>
        <a href="{{ localized_route('client.campaigns.index') }}" class="btn btn-outline">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Retour aux campagnes
        </a>
    </div>

    <!-- Progress Steps -->
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Progression</h3>
                <span class="badge badge-primary" x-text="`${currentStep}/4`"></span>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                     :style="`width: ${(currentStep / 4) * 100}%`"></div>
            </div>

            <!-- Step Indicators -->
            <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         :class="currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">
                        <span class="text-sm font-medium">1</span>
                    </div>
                    <span class="text-sm text-gray-600 mt-1">Informations générales</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         :class="currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">
                        <span class="text-sm font-medium">2</span>
                    </div>
                    <span class="text-sm text-gray-600 mt-1">Détails campagne</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         :class="currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">
                        <span class="text-sm font-medium">3</span>
                    </div>
                    <span class="text-sm text-gray-600 mt-1">Exigences</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         :class="currentStep >= 4 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'">
                        <span class="text-sm font-medium">4</span>
                    </div>
                    <span class="text-sm text-gray-600 mt-1">Révision</span>
                </div>
            </div>
        </div>
    </div>

    <form @submit.prevent="submitForm()">
        <!-- Step 1: General Information -->
        <div x-show="currentStep === 1" class="card">
            <div class="card-content">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations sur l'entreprise</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Nom de l'entreprise *</label>
                        <input type="text" x-model="form.company_name" class="input" placeholder="Nom de votre entreprise" required>
                    </div>
                    <div>
                        <label class="form-label">Catégorie d'entreprise *</label>
                        <div class="relative">
                            <select x-model="form.company_category"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white"
                                    required>
                                <option value="" disabled class="text-gray-400">Sélectionner une catégorie</option>
                                <option value="technology">💻 Technologie</option>
                                <option value="fashion">👗 Mode</option>
                                <option value="food">🍽️ Alimentation</option>
                                <option value="travel">✈️ Voyage</option>
                                <option value="beauty">💄 Beauté</option>
                                <option value="fitness">💪 Fitness</option>
                                <option value="education">📚 Éducation</option>
                                <option value="finance">💰 Finance</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Site web</label>
                        <input type="url" x-model="form.company_website" class="input" placeholder="https://votresite.com">
                    </div>
                    <div>
                        <label class="form-label">Email de contact *</label>
                        <input type="email" x-model="form.contact_email" class="input" placeholder="contact@entreprise.com" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description de l'entreprise *</label>
                        <textarea x-model="form.company_description" class="input" rows="4" placeholder="Décrivez brièvement votre entreprise..." required></textarea>
                    </div>
                    <div>
                        <label class="form-label">Logo de l'entreprise</label>
                        <input type="file" @change="handleFileUpload($event, 'company_logo')" class="input" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Campaign Information -->
        <div x-show="currentStep === 2" class="card">
            <div class="card-content">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Détails de la campagne</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="form-label">Nom de la campagne *</label>
                        <input type="text" x-model="form.campaign_name" class="input" placeholder="Nom de votre campagne" required>
                    </div>
                    <div>
                        <label class="form-label">Objectif de la campagne *</label>
                        <div class="relative">
                            <select x-model="form.campaign_objective"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white"
                                    required>
                                <option value="" disabled class="text-gray-400">Sélectionner un objectif</option>
                                <option value="brand_awareness">🎯 Notoriété de marque</option>
                                <option value="lead_generation">📋 Génération de leads</option>
                                <option value="sales">💰 Ventes</option>
                                <option value="engagement">👥 Engagement</option>
                                <option value="traffic">📈 Trafic web</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Plateforme cible *</label>
                        <div class="relative">
                            <select x-model="form.target_platform"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white"
                                    required>
                                <option value="" disabled class="text-gray-400">Sélectionner une plateforme</option>
                                <option value="instagram">📸 Instagram</option>
                                <option value="youtube">🎥 YouTube</option>
                                <option value="tiktok">🎵 TikTok</option>
                                <option value="facebook">👥 Facebook</option>
                                <option value="twitter">🐦 Twitter</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Date de début *</label>
                        <input type="date" x-model="form.campaign_start_date" class="input" required>
                    </div>
                    <div>
                        <label class="form-label">Date de fin *</label>
                        <input type="date" x-model="form.campaign_end_date" class="input" required>
                    </div>
                    <div>
                        <label class="form-label">Budget (DZD) *</label>
                        <input type="number" x-model="form.budget" class="input" placeholder="10000" min="1000" required>
                    </div>
                    <div>
                        <label class="form-label">Type de contenu</label>
                        <div class="relative">
                            <select x-model="form.content_type"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                                <option value="" disabled class="text-gray-400">Sélectionner le type</option>
                                <option value="post">📝 Publication</option>
                                <option value="story">📱 Story</option>
                                <option value="video">🎬 Vidéo</option>
                                <option value="reel">🎥 Reel</option>
                                <option value="live">📺 Live</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description de la campagne *</label>
                        <textarea x-model="form.campaign_description" class="input" rows="4" placeholder="Décrivez votre campagne, vos attentes..." required></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Influencer Requirements -->
        <div x-show="currentStep === 3" class="card">
            <div class="card-content">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Exigences pour les influenceurs</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Followers minimum</label>
                        <input type="number" x-model="form.min_followers" class="input" placeholder="10000" min="0">
                    </div>
                    <div>
                        <label class="form-label">Followers maximum</label>
                        <input type="number" x-model="form.max_followers" class="input" placeholder="100000" min="0">
                    </div>
                    <div>
                        <label class="form-label">Âge minimum</label>
                        <input type="number" x-model="form.min_age" class="input" placeholder="18" min="13" max="100">
                    </div>
                    <div>
                        <label class="form-label">Âge maximum</label>
                        <input type="number" x-model="form.max_age" class="input" placeholder="35" min="13" max="100">
                    </div>
                    <div>
                        <label class="form-label">Genre</label>
                        <div class="relative">
                            <select x-model="form.gender"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white">
                                <option value="" disabled class="text-gray-400">Tous les genres</option>
                                <option value="male">👨 Homme</option>
                                <option value="female">👩 Femme</option>
                                <option value="other">🧑‍🤝‍🧑 Autre</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Localisation</label>
                        <input type="text" x-model="form.location" class="input" placeholder="Alger, Algérie">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Niches/Catégories</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                            <template x-for="niche in availableNiches" :key="niche.value">
                                <label class="flex items-center">
                                    <input type="checkbox" :value="niche.value" @change="toggleNiche(niche.value)" class="mr-2">
                                    <span class="text-sm" x-text="niche.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Instructions spéciales</label>
                        <textarea x-model="form.special_instructions" class="input" rows="3" placeholder="Instructions particulières pour les influenceurs..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Review & Confirmation -->
        <div x-show="currentStep === 4" class="card">
            <div class="card-content">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Révision et confirmation</h3>

                <div class="space-y-6">
                    <!-- Company Information Summary -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Informations entreprise</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Entreprise</dt>
                                    <dd class="text-sm text-gray-900" x-text="form.company_name"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                                    <dd class="text-sm text-gray-900" x-text="form.company_category"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900" x-text="form.contact_email"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Site web</dt>
                                    <dd class="text-sm text-gray-900" x-text="form.company_website || 'Non spécifié'"></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Campaign Information Summary -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Détails campagne</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nom de la campagne</dt>
                                    <dd class="text-sm text-gray-900" x-text="form.campaign_name"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Objectif</dt>
                                    <dd class="text-sm text-gray-900" x-text="form.campaign_objective"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Plateforme</dt>
                                    <dd class="text-sm text-gray-900" x-text="form.target_platform"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Budget</dt>
                                    <dd class="text-sm text-gray-900" x-text="form.budget + ' DZD'"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Période</dt>
                                    <dd class="text-sm text-gray-900" x-text="`${form.campaign_start_date} - ${form.campaign_end_date}`"></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="border-t pt-6">
                        <label class="flex items-start">
                            <input type="checkbox" x-model="agreeToTerms" class="mt-1 mr-3">
                            <span class="text-sm text-gray-600">
                                J'accepte les <a href="#" class="text-blue-600 hover:underline">termes et conditions</a>
                                et confirme que toutes les informations fournies sont exactes.
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between">
            <button type="button"
                    @click="previousStep()"
                    x-show="currentStep > 1"
                    class="btn btn-outline">
                <i data-lucide="chevron-left" class="w-4 h-4 mr-2"></i>
                Précédent
            </button>
            <div></div>
            <button type="button"
                    @click="nextStep()"
                    x-show="currentStep < 4"
                    class="btn btn-primary">
                Suivant
                <i data-lucide="chevron-right" class="w-4 h-4 ml-2"></i>
            </button>
            <button type="submit"
                    x-show="currentStep === 4"
                    :disabled="!agreeToTerms || submitting"
                    class="btn btn-primary">
                <span x-show="submitting" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                Créer la campagne
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function campaignWizard() {
    return {
        currentStep: 1,
        submitting: false,
        agreeToTerms: false,

        form: {
            // Company Information
            company_name: '',
            company_category: '',
            company_website: '',
            contact_email: '',
            company_description: '',
            company_logo: null,

            // Campaign Information
            campaign_name: '',
            campaign_objective: '',
            target_platform: '',
            campaign_start_date: '',
            campaign_end_date: '',
            budget: '',
            content_type: '',
            campaign_description: '',

            // Influencer Requirements
            min_followers: '',
            max_followers: '',
            min_age: '',
            max_age: '',
            gender: '',
            location: '',
            niches: [],
            special_instructions: ''
        },

        availableNiches: [
            { value: 'fashion', label: 'Mode' },
            { value: 'beauty', label: 'Beauté' },
            { value: 'fitness', label: 'Fitness' },
            { value: 'food', label: 'Cuisine' },
            { value: 'travel', label: 'Voyage' },
            { value: 'technology', label: 'Tech' },
            { value: 'lifestyle', label: 'Style de vie' },
            { value: 'gaming', label: 'Gaming' }
        ],

        init() {
            // Auto-fill current user email if available
            this.form.contact_email = '{{ auth()->user()->email ?? '' }}';
        },

        nextStep() {
            if (this.validateCurrentStep()) {
                this.currentStep++;
            }
        },

        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },

        validateCurrentStep() {
            switch (this.currentStep) {
                case 1:
                    return this.form.company_name && this.form.company_category &&
                           this.form.contact_email && this.form.company_description;
                case 2:
                    return this.form.campaign_name && this.form.campaign_objective &&
                           this.form.target_platform && this.form.campaign_start_date &&
                           this.form.campaign_end_date && this.form.budget && this.form.campaign_description;
                case 3:
                    return true; // Optional step
                case 4:
                    return this.agreeToTerms;
                default:
                    return true;
            }
        },

        toggleNiche(value) {
            const index = this.form.niches.indexOf(value);
            if (index === -1) {
                this.form.niches.push(value);
            } else {
                this.form.niches.splice(index, 1);
            }
        },

        handleFileUpload(event, field) {
            const file = event.target.files[0];
            if (file) {
                this.form[field] = file;
            }
        },

        async submitForm() {
            if (!this.validateCurrentStep()) {
                window.showToast('Veuillez accepter les termes et conditions', 'error');
                return;
            }

            this.submitting = true;

            try {
                const formData = new FormData();

                // Append all form fields
                Object.keys(this.form).forEach(key => {
                    if (key === 'niches') {
                        formData.append(key, JSON.stringify(this.form[key]));
                    } else if (this.form[key] instanceof File) {
                        formData.append(key, this.form[key]);
                    } else if (this.form[key] !== null && this.form[key] !== '') {
                        formData.append(key, this.form[key]);
                    }
                });

                const response = await fetch('{{ localized_route("client.campaigns.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    window.showToast('Campagne créée avec succès!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ localized_route("client.campaigns.index") }}';
                    }, 1000);
                } else {
                    window.showToast(data.message || 'Erreur lors de la création', 'error');
                }
            } catch (error) {
                console.error('Error creating campaign:', error);
                window.showToast('Erreur lors de la création de la campagne', 'error');
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
@endpush