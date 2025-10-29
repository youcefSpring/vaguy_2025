@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
        <p class="text-gray-600">Vos informations de vérification KYC</p>
    </div>

    <!-- KYC Status -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Statut de vérification</h3>
        </div>
        <div class="card-content">
            @if($influencer->kv == 1)
                <div class="p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-green-900">Vérification approuvée</p>
                            <p class="text-sm text-green-700">Votre identité a été vérifiée avec succès</p>
                        </div>
                    </div>
                </div>
            @elseif($influencer->kv == 2)
                <div class="p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="clock" class="h-5 w-5 text-yellow-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-yellow-900">Vérification en cours</p>
                            <p class="text-sm text-yellow-700">Votre demande est en cours d'examen (24-48h)</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="x-circle" class="h-5 w-5 text-red-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-red-900">Vérification refusée</p>
                            <p class="text-sm text-red-700">Votre demande a été refusée. Contactez le support.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- KYC Data Display -->
    @if($influencer->kyc_data)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informations soumises</h3>
        </div>
        <div class="card-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($influencer->kyc_data as $key => $value)
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if(is_array($value))
                            {{ implode(', ', $value) }}
                        @else
                            {{ $value }}
                        @endif
                    </dd>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection