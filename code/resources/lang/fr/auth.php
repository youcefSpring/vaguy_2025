<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Ces informations d\'identification ne correspondent pas à nos enregistrements.',
    'password' => 'Le mot de passe fourni est incorrect.',
    'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',

    // Page titles
    'create_account' => 'Créer votre compte',
    'create_influencer_account' => 'Créer un compte influenceur',
    'login_account' => 'Connectez-vous à votre compte',
    'login_influencer_account' => 'Connexion au compte influenceur',
    'complete_profile' => 'Complétez votre profil',
    'join_our_community' => 'Rejoignez notre communauté aujourd\'hui',
    'welcome_back' => 'Bienvenue! Veuillez vous connecter pour continuer',

    // Account types
    'client' => 'Client',
    'influencer' => 'Influenceur',
    'select_account_type' => 'Sélectionner le type de compte',

    // Form labels
    'username' => 'Nom d\'utilisateur',
    'username_or_email' => 'Nom d\'utilisateur ou e-mail',
    'email' => 'Adresse e-mail',
    'password' => 'Mot de passe',
    'confirm_password' => 'Confirmer le mot de passe',
    'gender' => 'Genre',
    'phone' => 'Numéro de téléphone',
    'country' => 'Pays',
    'mobile' => 'Mobile',
    'remember_me' => 'Se souvenir de moi',

    // Gender options
    'male' => 'Homme',
    'female' => 'Femme',
    'non_binary' => 'Non-binaire',
    'prefer_not_to_say' => 'Préfère ne pas dire',

    // Placeholders
    'username_placeholder' => 'jean123',
    'email_placeholder' => 'jean@example.com',
    'password_placeholder' => '••••••••',
    'phone_placeholder' => '+33 1 23 45 67 89',
    'select_gender' => 'Sélectionner le genre',

    // Buttons
    'register' => 'S\'inscrire',
    'login' => 'Se connecter',
    'register_with_google' => 'S\'inscrire avec Google',
    'login_with_google' => 'Se connecter avec Google',
    'submit' => 'Soumettre',
    'save_profile' => 'Enregistrer le profil',

    // Links
    'have_account' => 'Vous avez déjà un compte?',
    'already_have_account' => 'Vous avez déjà un compte?',
    'login_here' => 'Connectez-vous ici',
    'no_account' => 'Vous n\'avez pas de compte?',
    'dont_have_account' => 'Vous n\'avez pas de compte?',
    'register_here' => 'Inscrivez-vous ici',
    'forgot_password' => 'Mot de passe oublié?',
    'close' => 'Fermer',
    'are_you_with_us' => 'Déjà avec nous?',
    'you_have_account_please_login' => 'Vous avez déjà un compte. Veuillez vous connecter.',

    // Terms
    'agree_to' => 'J\'accepte les',
    'i_agree_to' => 'J\'accepte',
    'terms_conditions' => 'Conditions générales',
    'privacy_policy' => 'Politique de confidentialité',
    'and' => 'et',
    'or' => 'Ou',

    // Security note
    'security_note' => 'Vos informations sont en sécurité avec nous.',

    // Validation messages
    'username_required' => 'Le nom d\'utilisateur est requis',
    'username_min' => 'Le nom d\'utilisateur doit contenir au moins 6 caractères',
    'email_required' => 'L\'e-mail est requis',
    'email_invalid' => 'Veuillez entrer un e-mail valide',
    'gender_required' => 'Veuillez sélectionner votre genre',
    'phone_required' => 'Le numéro de téléphone est requis',
    'password_required' => 'Le mot de passe est requis',
    'password_min' => 'Le mot de passe doit contenir au moins 6 caractères',
    'password_mismatch' => 'Les mots de passe ne correspondent pas',
    'terms_required' => 'Vous devez accepter les conditions',
    'username_exists' => 'Le nom d\'utilisateur existe déjà',
    'email_exists' => 'L\'e-mail existe déjà',
    'mobile_exists' => 'Le numéro de mobile existe déjà',

    // Password requirements
    'password_requirements' => 'Exigences du mot de passe:',
    'require_lowercase' => 'Au moins une lettre minuscule',
    'password_lowercase' => 'Au moins une lettre minuscule',
    'require_uppercase' => 'Au moins une lettre majuscule',
    'password_uppercase' => 'Au moins une lettre majuscule',
    'require_number' => 'Au moins un chiffre',
    'password_number' => 'Au moins un chiffre',
    'require_special' => 'Au moins un caractère spécial',
    'password_special' => 'Au moins un caractère spécial',
    'require_minimum' => 'Minimum 6 caractères',
    'password_min_length' => 'Minimum 6 caractères',
    'username_min_length' => 'Le nom d\'utilisateur doit contenir au moins 6 caractères',
    'already_exists' => 'existe déjà',

    // Verification
    'verify_email' => 'Vérifiez votre e-mail',
    'verification_sent' => 'E-mail de vérification envoyé à votre adresse e-mail',
    'check_email' => 'Veuillez vérifier votre e-mail et cliquer sur le lien de vérification',
    'resend_verification' => 'Renvoyer l\'e-mail de vérification',

    // Success messages
    'registration_success' => 'Inscription réussie! Veuillez vérifier votre e-mail',
    'login_success' => 'Connexion réussie',
    'profile_updated' => 'Profil mis à jour avec succès',

    // Error messages
    'registration_failed' => 'L\'inscription a échoué. Veuillez réessayer',
    'login_failed' => 'Informations d\'identification invalides',
    'invalid_credentials' => 'L\'e-mail ou le mot de passe est incorrect',

    // Social auth
    'or_continue_with' => 'Ou continuer avec',
    'google_auth_failed' => 'L\'authentification Google a échoué',
    'account_created_via_google' => 'Compte créé via Google',

    // Profile completion
    'complete_profile_message' => 'Veuillez compléter votre profil pour continuer',
    'additional_information' => 'Informations supplémentaires',
    'profile_completion_required' => 'La complétion du profil est requise',
];
