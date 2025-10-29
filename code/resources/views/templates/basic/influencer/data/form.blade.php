@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
        <p class="text-gray-600">Complétez votre profil d'influenceur</p>
    </div>

    <!-- Welcome Message -->
    <div class="card">
        <div class="card-content">
            <div class="text-center py-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="user-plus" class="h-8 w-8 text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Bienvenue sur notre plateforme !</h2>
                <p class="text-gray-600">
                    Pour finaliser votre inscription, veuillez compléter les informations ci-dessous.
                </p>
            </div>
        </div>
    </div>

    <!-- Data Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informations personnelles</h3>
            <p class="card-description">Ces informations nous aideront à personnaliser votre expérience</p>
        </div>
        <div class="card-content">
            <form method="POST" action="{{ localized_route('influencer.data.submit') }}" class="space-y-6">
                @csrf

                <!-- Personal Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="firstname" class="block text-sm font-medium text-gray-700 mb-2">
                            Prénom *
                        </label>
                        <input type="text"
                               name="firstname"
                               id="firstname"
                               value="{{ old('firstname', $influencer->firstname) }}"
                               class="input w-full"
                               required>
                        @error('firstname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom *
                        </label>
                        <input type="text"
                               name="lastname"
                               id="lastname"
                               value="{{ old('lastname', $influencer->lastname) }}"
                               class="input w-full"
                               required>
                        @error('lastname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address Section -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Adresse</h4>
                    <div class="space-y-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse
                            </label>
                            <input type="text"
                                   name="address"
                                   id="address"
                                   value="{{ old('address', $influencer->address->address ?? '') }}"
                                   class="input w-full">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ville
                                </label>
                                <input type="text"
                                       name="city"
                                       id="city"
                                       value="{{ old('city', $influencer->address->city ?? '') }}"
                                       class="input w-full">
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                    Wilaya
                                </label>
                                <select name="state" id="state" class="input w-full">
                                    <option value="">Sélectionner une wilaya</option>
                                    @foreach($wilayas as $wilaya)
                                    <option value="{{ $wilaya->code }}"
                                            {{ old('state', $influencer->address->state ?? '') == $wilaya->code ? 'selected' : '' }}>
                                        {{ $wilaya->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="zip" class="block text-sm font-medium text-gray-700 mb-2">
                                    Code postal
                                </label>
                                <input type="text"
                                       name="zip"
                                       id="zip"
                                       value="{{ old('zip', $influencer->address->zip ?? '') }}"
                                       class="input w-full">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary btn-default">
                        <i data-lucide="check" class="mr-2 h-4 w-4"></i>
                        Finaliser l'inscription
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Prochaines étapes</h3>
        </div>
        <div class="card-content">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="user-check" class="h-6 w-6 text-green-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">1. Compléter le profil</h4>
                    <p class="text-sm text-gray-600">Ajoutez vos compétences et expériences</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="shield-check" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">2. Vérification KYC</h4>
                    <p class="text-sm text-gray-600">Vérifiez votre identité</p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="briefcase" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-2">3. Créer des services</h4>
                    <p class="text-sm text-gray-600">Proposez vos services aux clients</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection