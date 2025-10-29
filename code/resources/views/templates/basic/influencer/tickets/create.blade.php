@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ localized_route('influencer.ticket') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i>
            Retour
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Créez un nouveau ticket de support</p>
        </div>
    </div>

    <!-- Create Ticket Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Nouveau ticket de support</h3>
            <p class="card-description">Décrivez votre problème en détail pour obtenir une assistance rapide</p>
        </div>
        <div class="card-content">
            <form method="POST" action="{{ localized_route('influencer.ticket.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                @if($campain_offer_id)
                    <input type="hidden" name="campain_offer_id" value="{{ $campain_offer_id }}">
                @endif

                <!-- Hidden fields for name and email (required by validation) -->
                <input type="hidden" name="name" value="{{ $user->firstname ?? '' }} {{ $user->lastname ?? '' }}">
                <input type="hidden" name="email" value="{{ $user->email }}">

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Sujet *
                    </label>
                    <input type="text"
                           name="subject"
                           id="subject"
                           value="{{ old('subject') }}"
                           class="input w-full"
                           placeholder="Résumez votre problème en quelques mots"
                           required>
                    @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Priorité
                    </label>
                    <select name="priority" id="priority" class="input w-full">
                        <option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>Basse</option>
                        <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Moyenne</option>
                        <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Haute</option>
                    </select>
                    @error('priority')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Message *
                    </label>
                    <textarea name="message"
                              id="message"
                              rows="6"
                              class="input w-full"
                              placeholder="Décrivez votre problème en détail..."
                              required>{{ old('message') }}</textarea>
                    @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachments -->
                <div>
                    <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                        Pièces jointes
                    </label>
                    <input type="file"
                           name="attachments[]"
                           id="attachments"
                           multiple
                           class="input w-full"
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt,.zip">
                    <p class="mt-1 text-sm text-gray-500">
                        Formats acceptés: JPG, PNG, PDF, DOC, DOCX, TXT, ZIP (max 5 fichiers, 2MB chacun)
                    </p>
                    @error('attachments')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('attachments.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Support Info -->
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="flex">
                        <i data-lucide="info" class="h-5 w-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div class="text-sm text-blue-800">
                            <h4 class="font-medium mb-1">Conseils pour un support efficace</h4>
                            <ul class="space-y-1 list-disc list-inside">
                                <li>Soyez aussi précis que possible dans votre description</li>
                                <li>Incluez des captures d'écran si nécessaire</li>
                                <li>Mentionnez les étapes pour reproduire le problème</li>
                                <li>Nous vous répondrons sous 24-48 heures</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-3">
                    <a href="{{ localized_route('influencer.ticket') }}" class="btn btn-ghost">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="send" class="mr-2 h-4 w-4"></i>
                        Créer le ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection