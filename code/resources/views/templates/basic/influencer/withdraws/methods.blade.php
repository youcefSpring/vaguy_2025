@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ localized_route('influencer.withdraw.history') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" class="h-4 w-4 mr-1"></i>
            Historique
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Choisissez votre méthode de retrait</p>
        </div>
    </div>

    <!-- Balance Info -->
    <div class="card">
        <div class="card-content">
            <div class="text-center py-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="wallet" class="h-8 w-8 text-green-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Solde disponible</h3>
                <p class="text-3xl font-bold text-green-600">{{ showAmount(authInfluencer()->balance) }} {{ $general->cur_text ?? '' }}</p>
            </div>
        </div>
    </div>

    <!-- Withdraw Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Demande de retrait</h3>
            <p class="card-description">Remplissez le formulaire ci-dessous pour effectuer un retrait</p>
        </div>
        <div class="card-content">
            <form method="POST" action="{{ localized_route('influencer.withdraw.money') }}" class="space-y-6">
                @csrf

                <!-- Withdrawal Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Méthode de retrait *
                    </label>
                    @if($withdrawMethod->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($withdrawMethod as $method)
                            <div class="relative">
                                <input type="radio"
                                       name="gateway"
                                       id="method_{{ $method->id }}"
                                       value="{{ $method->id }}"
                                       class="peer sr-only"
                                       required>
                                <label for="method_{{ $method->id }}"
                                       class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300">
                                    @if($method->image)
                                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <img src="{{ getImage(getFilePath('gateway').'/'.$method->image) }}"
                                             alt="{{ $method->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    @else
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="credit-card" class="h-6 w-6 text-blue-600"></i>
                                    </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $method->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            Limites: {{ showAmount($method->min_limit) }} - {{ showAmount($method->max_limit) }} {{ $method->currency }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Frais: {{ showAmount($method->fixed_charge) }} + {{ $method->percent_charge }}%
                                        </p>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="credit-card" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune méthode disponible</h3>
                            <p class="text-gray-600">Il n'y a actuellement aucune méthode de retrait disponible.</p>
                        </div>
                    @endif
                    @error('gateway')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                @if($withdrawMethod->count() > 0)
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Montant à retirer *
                    </label>
                    <div class="relative">
                        <input type="number"
                               name="amount"
                               id="amount"
                               step="0.01"
                               min="1"
                               max="{{ authInfluencer()->balance }}"
                               value="{{ old('amount') }}"
                               class="input w-full pr-20"
                               placeholder="Entrez le montant"
                               required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-500 text-sm">{{ $general->cur_text ?? '' }}</span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Solde disponible: {{ showAmount(authInfluencer()->balance) }} {{ $general->cur_text ?? '' }}
                    </p>
                    @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Withdrawal Info -->
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="flex">
                        <i data-lucide="info" class="h-5 w-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div class="text-sm text-blue-800">
                            <h4 class="font-medium mb-1">Informations importantes</h4>
                            <ul class="space-y-1 list-disc list-inside">
                                <li>Les retraits sont traités sous 24-48 heures</li>
                                <li>Des frais peuvent s'appliquer selon la méthode choisie</li>
                                <li>Vérifiez que vos informations de paiement sont correctes</li>
                                <li>Le montant minimum et maximum varie selon la méthode</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-3">
                    <a href="{{ localized_route('influencer.withdraw.history') }}" class="btn btn-ghost">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="arrow-down" class="mr-2 h-4 w-4"></i>
                        Continuer
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Recent Withdrawals -->
    @if(authInfluencer()->withdrawals && authInfluencer()->withdrawals->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Retraits récents</h3>
        </div>
        <div class="card-content">
            <div class="space-y-3">
                @foreach(authInfluencer()->withdrawals->take(3) as $withdrawal)
                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-b-0">
                    <div>
                        <p class="font-medium">{{ showAmount($withdrawal->amount) }} {{ $general->cur_text ?? '' }}</p>
                        <p class="text-sm text-gray-600">{{ $withdrawal->method->name ?? 'Méthode supprimée' }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusClass = '';
                            $statusText = '';
                            switch($withdrawal->status) {
                                case 1:
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusText = 'Approuvé';
                                    break;
                                case 2:
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    $statusText = 'En attente';
                                    break;
                                case 3:
                                    $statusClass = 'bg-red-100 text-red-800';
                                    $statusText = 'Rejeté';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusText = 'Inconnu';
                            }
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        <p class="text-sm text-gray-500 mt-1">{{ $withdrawal->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ localized_route('influencer.withdraw.history') }}" class="btn btn-outline w-full">
                    Voir tout l'historique
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    // Update amount limits based on selected method
    const methods = @json($withdrawMethod);
    const amountInput = document.getElementById('amount');
    const methodInputs = document.querySelectorAll('input[name="gateway"]');

    methodInputs.forEach(input => {
        input.addEventListener('change', function() {
            const methodId = parseInt(this.value);
            const method = methods.find(m => m.id === methodId);

            if (method && amountInput) {
                amountInput.min = method.min_limit;
                amountInput.max = method.max_limit;
                amountInput.placeholder = `Min: ${method.min_limit} - Max: ${method.max_limit}`;
            }
        });
    });
});
</script>
@endpush
@endsection