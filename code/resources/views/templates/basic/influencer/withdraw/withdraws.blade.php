@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Retraits</h1>
        <p class="text-gray-600">Gérez vos demandes de retrait d'argent</p>
    </div>

    <!-- Balance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Available Balance -->
        <div class="card">
            <div class="card-content">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="wallet" class="h-8 w-8 text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Solde disponible</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format(authInfluencer()->balance, 0, ',', ' ') }} DZD</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="card">
            <div class="card-content">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="clock" class="h-8 w-8 text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($pendingAmount, 0, ',', ' ') }} DZD</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Withdrawn -->
        <div class="card">
            <div class="card-content">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="trending-down" class="h-8 w-8 text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total retiré</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($totalWithdrawn, 0, ',', ' ') }} DZD</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Withdrawal Request -->
    @if(authInfluencer()->balance > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Nouvelle demande de retrait</h3>
            <p class="card-description">Retirez vos gains vers votre compte bancaire</p>
        </div>
        <div class="card-content">
            <form method="POST" action="{{ localized_route('influencer.withdraw.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Montant à retirer (DZD) *
                        </label>
                        <input type="number"
                               name="amount"
                               id="amount"
                               min="1000"
                               max="{{ authInfluencer()->balance }}"
                               step="100"
                               placeholder="5000"
                               class="input w-full"
                               required>
                        <p class="text-xs text-gray-500 mt-1">
                            Montant minimum: 1,000 DZD • Maximum: {{ number_format(authInfluencer()->balance, 0, ',', ' ') }} DZD
                        </p>
                        @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Withdrawal Method -->
                    <div>
                        <label for="method" class="block text-sm font-medium text-gray-700 mb-2">
                            Méthode de retrait *
                        </label>
                        <select name="method" id="method" class="input w-full" required onchange="toggleMethodFields()">
                            <option value="">Sélectionner une méthode</option>
                            <option value="bank_transfer">Virement bancaire</option>
                            <option value="ccp">Compte CCP</option>
                            <option value="baridimob">BaridiMob</option>
                        </select>
                        @error('method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bank Transfer Fields -->
                <div id="bank_fields" class="hidden space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom de la banque
                            </label>
                            <input type="text" name="bank_name" id="bank_name" class="input w-full">
                        </div>
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro de compte
                            </label>
                            <input type="text" name="account_number" id="account_number" class="input w-full">
                        </div>
                    </div>
                    <div>
                        <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du titulaire du compte
                        </label>
                        <input type="text" name="account_holder" id="account_holder" class="input w-full">
                    </div>
                </div>

                <!-- CCP Fields -->
                <div id="ccp_fields" class="hidden space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="ccp_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro CCP
                            </label>
                            <input type="text" name="ccp_number" id="ccp_number" class="input w-full">
                        </div>
                        <div>
                            <label for="ccp_key" class="block text-sm font-medium text-gray-700 mb-2">
                                Clé CCP
                            </label>
                            <input type="text" name="ccp_key" id="ccp_key" class="input w-full">
                        </div>
                    </div>
                </div>

                <!-- BaridiMob Fields -->
                <div id="baridimob_fields" class="hidden space-y-4">
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de téléphone BaridiMob
                        </label>
                        <input type="tel" name="phone_number" id="phone_number" class="input w-full">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (optionnel)
                    </label>
                    <textarea name="notes"
                              id="notes"
                              rows="3"
                              placeholder="Informations supplémentaires..."
                              class="input w-full"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary btn-default">
                        <i data-lucide="send" class="mr-2 h-4 w-4"></i>
                        Demander le retrait
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-content text-center py-8">
            <i data-lucide="wallet" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Solde insuffisant</h3>
            <p class="text-gray-600 mb-4">
                Vous devez avoir un solde minimum de 1,000 DZD pour effectuer un retrait.
            </p>
            <a href="{{ localized_route('influencer.service.create') }}" class="btn btn-primary btn-default">
                <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                Créer un service
            </a>
        </div>
    </div>
    @endif

    <!-- Withdrawal History -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="card-title">Historique des retraits</h3>
                    <p class="card-description">Toutes vos demandes de retrait</p>
                </div>
                <!-- Filter -->
                <div class="flex gap-2">
                    <a href="{{ localized_route('influencer.withdraw.index') }}"
                       class="btn {{ !request()->status ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Tous
                    </a>
                    <a href="{{ localized_route('influencer.withdraw.index', ['status' => 'pending']) }}"
                       class="btn {{ request()->status == 'pending' ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        En attente
                    </a>
                    <a href="{{ localized_route('influencer.withdraw.index', ['status' => 'approved']) }}"
                       class="btn {{ request()->status == 'approved' ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Approuvés
                    </a>
                    <a href="{{ localized_route('influencer.withdraw.index', ['status' => 'rejected']) }}"
                       class="btn {{ request()->status == 'rejected' ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Rejetés
                    </a>
                </div>
            </div>
        </div>
        <div class="card-content p-0">
            @if($withdrawals->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Référence
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Méthode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $withdrawal->trx }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($withdrawal->amount, 0, ',', ' ') }} DZD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @switch($withdrawal->method->name)
                                    @case('bank_transfer')
                                        <div class="flex items-center">
                                            <i data-lucide="building" class="h-4 w-4 mr-2 text-gray-500"></i>
                                            Virement bancaire
                                        </div>
                                        @break
                                    @case('ccp')
                                        <div class="flex items-center">
                                            <i data-lucide="credit-card" class="h-4 w-4 mr-2 text-gray-500"></i>
                                            CCP
                                        </div>
                                        @break
                                    @case('baridimob')
                                        <div class="flex items-center">
                                            <i data-lucide="smartphone" class="h-4 w-4 mr-2 text-gray-500"></i>
                                            BaridiMob
                                        </div>
                                        @break
                                    @default
                                        {{ $withdrawal->method->name }}
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        0 => ['badge-outline', 'En attente'],
                                        1 => ['badge-default', 'Approuvé'],
                                        2 => ['badge-destructive', 'Rejeté'],
                                        3 => ['badge-secondary', 'En cours']
                                    ];
                                    $config = $statusConfig[$withdrawal->status] ?? ['badge-outline', 'Inconnu'];
                                @endphp
                                <span class="badge {{ $config[0] }}">
                                    {{ $config[1] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $withdrawal->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button type="button"
                                        class="btn btn-ghost btn-sm"
                                        onclick="viewWithdrawal({{ $withdrawal->id }})">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </button>
                                @if($withdrawal->status == 0)
                                <button type="button"
                                        class="btn btn-ghost btn-sm text-red-600"
                                        onclick="cancelWithdrawal({{ $withdrawal->id }})">
                                    <i data-lucide="x" class="h-4 w-4"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($withdrawals->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $withdrawals->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-12">
                <i data-lucide="history" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun retrait</h3>
                <p class="text-gray-600">Vous n'avez pas encore effectué de demandes de retrait.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Withdrawal Details Modal -->
<div id="withdrawalModal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" x-on:click="open = false"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Détails du retrait</h3>
                </div>

                <div class="p-6" id="withdrawalDetails">
                    <!-- Details will be loaded here -->
                </div>

                <div class="px-6 py-4 border-t">
                    <button type="button" class="btn btn-outline btn-default w-full" x-on:click="open = false">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
function toggleMethodFields() {
    const method = document.getElementById('method').value;
    const bankFields = document.getElementById('bank_fields');
    const ccpFields = document.getElementById('ccp_fields');
    const baridimobFields = document.getElementById('baridimob_fields');

    // Hide all fields
    bankFields.classList.add('hidden');
    ccpFields.classList.add('hidden');
    baridimobFields.classList.add('hidden');

    // Show relevant fields
    switch(method) {
        case 'bank_transfer':
            bankFields.classList.remove('hidden');
            break;
        case 'ccp':
            ccpFields.classList.remove('hidden');
            break;
        case 'baridimob':
            baridimobFields.classList.remove('hidden');
            break;
    }
}

function viewWithdrawal(withdrawalId) {
    fetch(`/influencer/withdrawals/${withdrawalId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('withdrawalDetails').innerHTML = data.html;
            const modal = document.getElementById('withdrawalModal');
            modal.classList.remove('hidden');
            modal.querySelector('[x-data]').__x.$data.open = true;
        });
}

function cancelWithdrawal(withdrawalId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette demande de retrait ?')) {
        fetch(`/influencer/withdrawals/${withdrawalId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'annulation de la demande');
            }
        });
    }
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection