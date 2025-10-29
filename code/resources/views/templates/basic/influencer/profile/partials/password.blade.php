<!-- Password Section -->
<div class="space-y-6">
    <!-- Change Password -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Changer le mot de passe</h3>
            <p class="card-description">Mettez à jour votre mot de passe pour sécuriser votre compte</p>
        </div>
        <div class="card-content">
            <form method="POST" action="{{ localized_route('influencer.profile.password') }}" class="space-y-4">
                @csrf

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Mot de passe actuel *
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="current_password"
                               id="current_password"
                               class="input w-full pr-10"
                               required>
                        <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('current_password')">
                            <i data-lucide="eye" class="h-4 w-4 text-gray-400" id="current_password_icon"></i>
                        </button>
                    </div>
                    @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Nouveau mot de passe *
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password"
                               id="new_password"
                               class="input w-full pr-10"
                               required>
                        <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('new_password')">
                            <i data-lucide="eye" class="h-4 w-4 text-gray-400" id="new_password_icon"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="password-strength">
                            <div class="strength-meter">
                                <div id="strength_bar" class="strength-bar"></div>
                            </div>
                            <span id="strength_text" class="strength-text">Entrez un mot de passe</span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmer le nouveau mot de passe *
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="input w-full pr-10"
                               required>
                        <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('password_confirmation')">
                            <i data-lucide="eye" class="h-4 w-4 text-gray-400" id="password_confirmation_icon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Requirements -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Exigences du mot de passe :</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li class="flex items-center">
                            <i data-lucide="check" class="h-3 w-3 mr-2" id="req_length"></i>
                            Au moins 8 caractères
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="h-3 w-3 mr-2" id="req_uppercase"></i>
                            Au moins une lettre majuscule
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="h-3 w-3 mr-2" id="req_lowercase"></i>
                            Au moins une lettre minuscule
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="h-3 w-3 mr-2" id="req_number"></i>
                            Au moins un chiffre
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="h-3 w-3 mr-2" id="req_special"></i>
                            Au moins un caractère spécial
                        </li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary btn-default">
                    <i data-lucide="lock" class="mr-2 h-4 w-4"></i>
                    Changer le mot de passe
                </button>
            </form>
        </div>
    </div>

    <!-- Two-Factor Authentication -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Authentification à deux facteurs (2FA)</h3>
            <p class="card-description">Ajoutez une couche de sécurité supplémentaire à votre compte</p>
        </div>
        <div class="card-content">
            @if(authInfluencer()->ts)
                <!-- 2FA Enabled -->
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="shield-check" class="h-5 w-5 text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-green-900">2FA activé</p>
                            <p class="text-sm text-green-700">Votre compte est protégé par l'authentification à deux facteurs</p>
                        </div>
                    </div>
                    <button type="button"
                            class="btn btn-outline btn-sm"
                            onclick="disable2FA()">
                        Désactiver
                    </button>
                </div>
            @else
                <!-- 2FA Disabled -->
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="shield-alert" class="h-5 w-5 text-yellow-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-yellow-900">2FA désactivé</p>
                            <p class="text-sm text-yellow-700">Activez l'authentification à deux facteurs pour plus de sécurité</p>
                        </div>
                    </div>
                    <a href="{{ localized_route('influencer.twofactor') }}" class="btn btn-primary btn-sm">
                        Activer 2FA
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Security Log -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Activité de sécurité</h3>
            <p class="card-description">Historique des connexions récentes</p>
        </div>
        <div class="card-content">
            <div class="space-y-4">
                <!-- Recent Login Activity -->
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <div class="flex items-center">
                        <i data-lucide="smartphone" class="h-4 w-4 text-gray-400 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Connexion mobile</p>
                            <p class="text-xs text-gray-500">{{ request()->ip() }} • Maintenant</p>
                        </div>
                    </div>
                    <span class="badge badge-default">Actuel</span>
                </div>

                @if(authInfluencer()->last_login)
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <div class="flex items-center">
                        <i data-lucide="monitor" class="h-4 w-4 text-gray-400 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Connexion web</p>
                            <p class="text-xs text-gray-500">Dernière connexion • {{ authInfluencer()->last_login->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="badge badge-outline">Précédent</span>
                </div>
                @endif

                <div class="text-center pt-2">
                    <button type="button" class="btn btn-ghost btn-sm">
                        Voir toute l'activité
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
.password-strength {
    @apply flex items-center gap-3;
}

.strength-meter {
    @apply flex-1 h-2 bg-gray-200 rounded-full overflow-hidden;
}

.strength-bar {
    @apply h-full transition-all duration-300 ease-in-out;
    width: 0%;
}

.strength-text {
    @apply text-xs font-medium;
}

.strength-weak .strength-bar {
    @apply bg-red-500;
    width: 25%;
}

.strength-fair .strength-bar {
    @apply bg-yellow-500;
    width: 50%;
}

.strength-good .strength-bar {
    @apply bg-blue-500;
    width: 75%;
}

.strength-strong .strength-bar {
    @apply bg-green-500;
    width: 100%;
}
</style>
@endpush

@push('script')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        field.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }

    // Refresh Lucide icons
    lucide.createIcons();
}

function checkPasswordStrength(password) {
    let strength = 0;
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    // Update requirement indicators
    Object.keys(requirements).forEach(req => {
        const element = document.getElementById('req_' + req);
        if (element) {
            element.classList.toggle('text-green-600', requirements[req]);
            element.classList.toggle('text-gray-400', !requirements[req]);
        }
    });

    // Calculate strength
    strength = Object.values(requirements).filter(Boolean).length;

    const strengthMeter = document.querySelector('.password-strength');
    const strengthText = document.getElementById('strength_text');

    // Reset classes
    strengthMeter.classList.remove('strength-weak', 'strength-fair', 'strength-good', 'strength-strong');

    if (password.length === 0) {
        strengthText.textContent = 'Entrez un mot de passe';
        strengthText.className = 'strength-text text-gray-500';
    } else if (strength < 3) {
        strengthMeter.classList.add('strength-weak');
        strengthText.textContent = 'Faible';
        strengthText.className = 'strength-text text-red-600';
    } else if (strength < 4) {
        strengthMeter.classList.add('strength-fair');
        strengthText.textContent = 'Moyen';
        strengthText.className = 'strength-text text-yellow-600';
    } else if (strength < 5) {
        strengthMeter.classList.add('strength-good');
        strengthText.textContent = 'Bon';
        strengthText.className = 'strength-text text-blue-600';
    } else {
        strengthMeter.classList.add('strength-strong');
        strengthText.textContent = 'Excellent';
        strengthText.className = 'strength-text text-green-600';
    }
}

function disable2FA() {
    if (confirm('Êtes-vous sûr de vouloir désactiver l\'authentification à deux facteurs ?')) {
        // Redirect to 2FA disable page or show a modal
        window.location.href = '{{ localized_route("influencer.twofactor.disable") }}';
    }
}

// Password strength checker
document.getElementById('new_password').addEventListener('input', function() {
    checkPasswordStrength(this.value);
});

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
    const confirmation = this.value;

    if (confirmation && password !== confirmation) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endpush