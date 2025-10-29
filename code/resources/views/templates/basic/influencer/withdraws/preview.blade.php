@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ localized_route('influencer.withdraw') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i>
            Retour
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Vérifiez les détails de votre retrait</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Withdrawal Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Résumé du retrait</h3>
                </div>
                <div class="card-content">
                    <div class="space-y-4">
                        <!-- Method -->
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                            @if($withdraw->method->image)
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-white">
                                <img src="{{ getImage(getFilePath('gateway').'/'.$withdraw->method->image) }}"
                                     alt="{{ $withdraw->method->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            @else
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="credit-card" class="h-6 w-6 text-blue-600"></i>
                            </div>
                            @endif
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $withdraw->method->name }}</h4>
                                <p class="text-sm text-gray-600">Méthode de retrait sélectionnée</p>
                            </div>
                        </div>

                        <!-- Amount Breakdown -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Montant demandé</span>
                                <span class="font-medium">{{ showAmount($withdraw->amount) }} {{ $general->cur_text ?? '' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Frais de traitement</span>
                                <span class="font-medium text-red-600">-{{ showAmount($withdraw->charge) }} {{ $general->cur_text ?? '' }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Montant après frais</span>
                                    <span class="font-medium">{{ showAmount($withdraw->after_charge) }} {{ $general->cur_text ?? '' }}</span>
                                </div>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900">Vous recevrez</span>
                                    <span class="text-xl font-bold text-green-600">{{ showAmount($withdraw->final_amount) }} {{ $withdraw->currency }}</span>
                                </div>
                                @if($withdraw->rate != 1)
                                <p class="text-sm text-gray-500 mt-1">
                                    Taux de change: 1 {{ $general->cur_text ?? '' }} = {{ showAmount($withdraw->rate) }} {{ $withdraw->currency }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de paiement</h3>
                    <p class="card-description">Complétez les informations requises pour le retrait</p>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ localized_route('influencer.withdraw.submit') }}" class="space-y-6">
                        @csrf

                        @if($withdraw->method->form)
                            @php
                                $formData = $withdraw->method->form->form_data;
                            @endphp

                            @if(is_array($formData) || is_object($formData))
                                @foreach($formData as $key => $field)
                                <div>
                                    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $field->label ?? $key)) }}
                                        @if($field->validation == 'required')
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if($field->type == 'text' || $field->type == 'email' || $field->type == 'number')
                                        <input type="{{ $field->type }}"
                                               name="{{ $key }}"
                                               id="{{ $key }}"
                                               value="{{ old($key) }}"
                                               class="input w-full"
                                               placeholder="{{ $field->placeholder ?? '' }}"
                                               {{ $field->validation == 'required' ? 'required' : '' }}>
                                    @elseif($field->type == 'textarea')
                                        <textarea name="{{ $key }}"
                                                  id="{{ $key }}"
                                                  rows="3"
                                                  class="input w-full"
                                                  placeholder="{{ $field->placeholder ?? '' }}"
                                                  {{ $field->validation == 'required' ? 'required' : '' }}>{{ old($key) }}</textarea>
                                    @elseif($field->type == 'select')
                                        <select name="{{ $key }}"
                                                id="{{ $key }}"
                                                class="input w-full"
                                                {{ $field->validation == 'required' ? 'required' : '' }}>
                                            <option value="">Sélectionner...</option>
                                            @if(isset($field->options))
                                                @foreach($field->options as $option)
                                                <option value="{{ $option }}" {{ old($key) == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @endif

                                    @error($key)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endforeach
                            @endif
                        @endif

                        <!-- 2FA if enabled -->
                        @if(authInfluencer()->ts)
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <div class="flex">
                                <i data-lucide="shield" class="h-5 w-5 text-yellow-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <h4 class="font-medium text-yellow-900 mb-2">Authentification à deux facteurs</h4>
                                    <div>
                                        <label for="authenticator_code" class="block text-sm font-medium text-yellow-900 mb-2">
                                            Code de vérification *
                                        </label>
                                        <input type="text"
                                               name="authenticator_code"
                                               id="authenticator_code"
                                               maxlength="6"
                                               class="input w-full text-center text-lg tracking-wider"
                                               placeholder="123456"
                                               required>
                                        @error('authenticator_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Submit -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ localized_route('influencer.withdraw') }}" class="btn btn-ghost">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="check" class="mr-2 h-4 w-4"></i>
                                Confirmer le retrait
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Transaction Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de transaction</h3>
                </div>
                <div class="card-content space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Numéro de transaction</p>
                        <p class="font-medium font-mono">{{ $withdraw->trx }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Date de demande</p>
                        <p class="font-medium">{{ $withdraw->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Statut</p>
                        <span class="badge bg-yellow-100 text-yellow-800">En attente</span>
                    </div>
                </div>
            </div>

            <!-- Method Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de la méthode</h3>
                </div>
                <div class="card-content space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Méthode</p>
                        <p class="font-medium">{{ $withdraw->method->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Devise</p>
                        <p class="font-medium">{{ $withdraw->currency }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Délai de traitement</p>
                        <p class="font-medium">{{ $withdraw->method->delay ?? '24-48 heures' }}</p>
                    </div>

                    @if($withdraw->method->min_limit || $withdraw->method->max_limit)
                    <div>
                        <p class="text-sm text-gray-600">Limites</p>
                        <p class="font-medium">
                            {{ showAmount($withdraw->method->min_limit) }} - {{ showAmount($withdraw->method->max_limit) }} {{ $withdraw->method->currency }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Important Notes -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notes importantes</h3>
                </div>
                <div class="card-content">
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex items-start gap-2">
                            <i data-lucide="check" class="h-4 w-4 text-green-600 mt-0.5 flex-shrink-0"></i>
                            <span>Vérifiez que toutes les informations sont correctes</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i data-lucide="check" class="h-4 w-4 text-green-600 mt-0.5 flex-shrink-0"></i>
                            <span>Les retraits sont traités sous 24-48 heures</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i data-lucide="check" class="h-4 w-4 text-green-600 mt-0.5 flex-shrink-0"></i>
                            <span>Vous recevrez une confirmation par email</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i data-lucide="alert-circle" class="h-4 w-4 text-orange-600 mt-0.5 flex-shrink-0"></i>
                            <span>Cette action ne peut pas être annulée</span>
                        </div>
                    </div>
                </div>
            </div>
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