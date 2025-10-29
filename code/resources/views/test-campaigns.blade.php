@extends('layouts.dashboard')

@section('title', 'Test Campaigns')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">✅ Test des Campagnes</h1>

        <div class="space-y-4">
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <h3 class="font-semibold text-green-800">Livewire Wizard Modernisé :</h3>
                <ul class="mt-2 text-sm text-green-700 list-disc list-inside">
                    <li>Design moderne avec Tailwind CSS</li>
                    <li>Icônes Lucide intégrées</li>
                    <li>Barre de progression moderne</li>
                    <li>Boutons interactifs avec hover states</li>
                    <li>Layout cohérent avec le reste de l'application</li>
                </ul>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-800">Routes de Campaign :</h3>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                    <li><strong>/client/campaign</strong> - Liste des campagnes (Livewire)</li>
                    <li><strong>/client/add-campaign</strong> - Ajouter campagne (Livewire Wizard)</li>
                    <li><strong>/client/campaigns</strong> - Campagnes modernes (Resource Controller)</li>
                    <li><strong>/client/campaigns/create</strong> - Créer campagne moderne</li>
                </ul>
            </div>

            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                <h3 class="font-semibold text-purple-800">Actions de Test :</h3>
                <div class="mt-4 space-x-4">
                    <a href="{{ localized_route('user_campaign') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i data-lucide="list" class="w-4 h-4 mr-2"></i>
                        Liste Campagnes (Livewire)
                    </a>

                    <a href="{{ localized_route('add_campaign') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Ajouter Campagne (Wizard)
                    </a>

                    <a href="{{ localized_route('campaigns.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <i data-lucide="grid-3x3" class="w-4 h-4 mr-2"></i>
                        Campagnes Modernes
                    </a>
                </div>
            </div>

            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <h3 class="font-semibold text-green-800">Migration vers Vue.js Complétée :</h3>
                <ul class="mt-2 text-sm text-green-700 list-disc list-inside">
                    <li>✅ Livewire complètement supprimé</li>
                    <li>✅ Vue.js 3 CDN ajouté avec Axios</li>
                    <li>✅ Composant CampaignManager créé</li>
                    <li>✅ Composant CampaignWizard créé</li>
                    <li>✅ Routes API pour Vue.js ajoutées</li>
                    <li>✅ Méthodes controller storeVue/getCampaignsData</li>
                    <li>✅ Validation et gestion d'erreurs Vue.js</li>
                    <li>✅ Interface utilisateur moderne et réactive</li>
                </ul>
            </div>
        </div>

        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-yellow-800 text-sm">
                <strong>Note :</strong> Si vous rencontrez des problèmes de clics, vérifiez :
                <br>1. Que JavaScript est activé
                <br>2. Que Livewire fonctionne correctement
                <br>3. Qu'il n'y a pas d'erreurs dans la console du navigateur
            </p>
        </div>
    </div>
</div>
@endsection