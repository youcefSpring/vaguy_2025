@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
        <p class="text-gray-600">Vérifiez votre identité pour accéder à toutes les fonctionnalités</p>
    </div>

    <!-- KYC Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Formulaire de vérification d'identité</h3>
            <p class="card-description">Veuillez remplir toutes les informations requises</p>
        </div>
        <div class="card-content">
            <form method="POST" action="{{ localized_route('influencer.kyc.submit') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div>
                    <h4 class="font-medium text-gray-900 mb-4">Informations personnelles</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Prénom *
                            </label>
                            <input type="text"
                                   name="first_name"
                                   id="first_name"
                                   class="input w-full"
                                   required>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom *
                            </label>
                            <input type="text"
                                   name="last_name"
                                   id="last_name"
                                   class="input w-full"
                                   required>
                        </div>
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de naissance *
                            </label>
                            <input type="date"
                                   name="date_of_birth"
                                   id="date_of_birth"
                                   class="input w-full"
                                   required>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro de téléphone *
                            </label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   class="input w-full"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="space-y-4">
                    <button type="submit" class="btn btn-primary btn-default w-full">
                        <i data-lucide="check-circle" class="mr-2 h-4 w-4"></i>
                        Soumettre ma demande de vérification
                    </button>
                </div>
            </form>
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