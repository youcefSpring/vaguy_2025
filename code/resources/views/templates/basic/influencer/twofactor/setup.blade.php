@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
        <p class="text-gray-600">Sécurisez votre compte avec l'authentification à deux facteurs</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Setup Instructions -->
        <div class="space-y-6">
            @if(!$influencer->ts)
            <!-- Enable 2FA -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activer l'authentification à deux facteurs</h3>
                    <p class="card-description">Ajoutez une couche de sécurité supplémentaire à votre compte</p>
                </div>
                <div class="card-content space-y-4">
                    <!-- Step 1: Download App -->
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Étape 1: Téléchargez une application d'authentification</h4>
                        <p class="text-sm text-blue-800 mb-3">
                            Installez une application comme Google Authenticator, Authy, ou Microsoft Authenticator sur votre téléphone.
                        </p>
                        <div class="flex gap-2 flex-wrap">
                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                               target="_blank" class="btn btn-outline btn-sm">
                                <i data-lucide="smartphone" class="mr-1 h-3 w-3"></i>
                                Google Play
                            </a>
                            <a href="https://apps.apple.com/app/google-authenticator/id388497605"
                               target="_blank" class="btn btn-outline btn-sm">
                                <i data-lucide="smartphone" class="mr-1 h-3 w-3"></i>
                                App Store
                            </a>
                        </div>
                    </div>

                    <!-- Step 2: Scan QR Code -->
                    <div class="p-4 bg-green-50 rounded-lg">
                        <h4 class="font-medium text-green-900 mb-2">Étape 2: Scannez le code QR</h4>
                        <p class="text-sm text-green-800">
                            Ouvrez votre application d'authentification et scannez le code QR ci-contre.
                        </p>
                    </div>

                    <!-- Step 3: Enter Code -->
                    <div class="p-4 bg-yellow-50 rounded-lg">
                        <h4 class="font-medium text-yellow-900 mb-2">Étape 3: Entrez le code de vérification</h4>
                        <p class="text-sm text-yellow-800">
                            Entrez le code à 6 chiffres généré par votre application dans le formulaire ci-dessous.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Enable Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Finaliser l'activation</h3>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ localized_route('influencer.twofactor.enable') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="key" value="{{ $secret }}">

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Code de vérification *
                            </label>
                            <input type="text"
                                   name="code"
                                   id="code"
                                   placeholder="123456"
                                   maxlength="6"
                                   class="input w-full text-center text-lg tracking-wider"
                                   required>
                            @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-default w-full">
                            <i data-lucide="shield-check" class="mr-2 h-4 w-4"></i>
                            Activer la 2FA
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- 2FA Enabled -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">2FA Activée</h3>
                    <p class="card-description">Votre compte est protégé par l'authentification à deux facteurs</p>
                </div>
                <div class="card-content">
                    <div class="p-4 bg-green-50 rounded-lg mb-4">
                        <div class="flex items-center">
                            <i data-lucide="shield-check" class="h-5 w-5 text-green-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-green-900">Authentification à deux facteurs activée</p>
                                <p class="text-sm text-green-700">Votre compte est maintenant sécurisé</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ localized_route('influencer.twofactor.disable') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="disable_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Code de vérification pour désactiver *
                            </label>
                            <input type="text"
                                   name="code"
                                   id="disable_code"
                                   placeholder="123456"
                                   maxlength="6"
                                   class="input w-full text-center text-lg tracking-wider"
                                   required>
                            @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="btn btn-destructive btn-default w-full"
                                onclick="return confirm('Êtes-vous sûr de vouloir désactiver la 2FA ?')">
                            <i data-lucide="shield-off" class="mr-2 h-4 w-4"></i>
                            Désactiver la 2FA
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- QR Code and Backup -->
        <div class="space-y-6">
            @if(!$influencer->ts)
            <!-- QR Code -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Code QR</h3>
                    <p class="card-description">Scannez ce code avec votre application d'authentification</p>
                </div>
                <div class="card-content text-center">
                    <div class="inline-block p-4 bg-white rounded-lg border-2 border-gray-200">
                        <img src="{{ $qrCodeUrl }}" alt="QR Code" class="w-48 h-48">
                    </div>

                    <!-- Manual Entry -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Si vous ne pouvez pas scanner le code QR, entrez cette clé manuellement :</p>
                        <div class="bg-white p-3 rounded border font-mono text-sm break-all">
                            {{ $secret }}
                        </div>
                        <button onclick="copyToClipboard('{{ $secret }}')" class="btn btn-ghost btn-sm mt-2">
                            <i data-lucide="copy" class="mr-1 h-3 w-3"></i>
                            Copier la clé
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Security Tips -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Conseils de sécurité</h3>
                </div>
                <div class="card-content">
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex items-start">
                            <i data-lucide="check" class="h-4 w-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Sauvegardez votre clé secrète dans un endroit sûr</span>
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check" class="h-4 w-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Utilisez une application d'authentification fiable</span>
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check" class="h-4 w-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Ne partagez jamais vos codes de vérification</span>
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check" class="h-4 w-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Gardez votre téléphone en sécurité</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Backup Codes Info -->
            @if($influencer->ts)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Codes de récupération</h3>
                    <p class="card-description">En cas de perte de votre téléphone</p>
                </div>
                <div class="card-content">
                    <div class="p-4 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <strong>Important :</strong> Si vous perdez l'accès à votre application d'authentification,
                            contactez le support avec une pièce d'identité pour récupérer votre compte.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('script')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i data-lucide="check" class="mr-1 h-3 w-3"></i>Copié !';
        button.classList.add('text-green-600');

        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('text-green-600');
            lucide.createIcons();
        }, 2000);
    });
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection