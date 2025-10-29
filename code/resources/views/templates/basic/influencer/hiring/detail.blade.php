@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Détails de l'embauche #{{ $hiring->hiring_no }}</p>
        </div>
        <a href="{{ localized_route('influencer.hiring.index') }}" class="btn btn-outline btn-default">
            <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Hiring Details -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="card-title">{{ $hiring->title }}</h3>
                        @php
                            $statusConfig = [
                                1 => ['badge-outline', 'En attente'],
                                2 => ['badge-secondary', 'En cours'],
                                3 => ['badge-default', 'Terminée'],
                                4 => ['badge-default', 'Complétée'],
                                5 => ['badge-destructive', 'Annulée'],
                                6 => ['badge-destructive', 'Signalée']
                            ];
                            $config = $statusConfig[$hiring->status] ?? ['badge-outline', 'Inconnu'];
                        @endphp
                        <span class="badge {{ $config[0] }}">
                            {{ $config[1] }}
                        </span>
                    </div>
                </div>
                <div class="card-content space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Description</h4>
                        <p class="text-gray-700 whitespace-pre-line">{{ $hiring->description }}</p>
                    </div>

                    @if($hiring->skills)
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Compétences requises</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($hiring->skills as $skill)
                            <span class="badge badge-outline">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($hiring->categories)
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Catégories</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($hiring->categories as $category)
                            <span class="badge badge-default">{{ $category }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions disponibles</h3>
                </div>
                <div class="card-content">
                    <div class="flex flex-wrap gap-3">
                        @if($hiring->status == 1)
                        <form method="POST" action="{{ localized_route('influencer.hiring.accept', $hiring->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-primary btn-default"
                                    onclick="return confirm('Êtes-vous sûr de vouloir accepter cette embauche ?')">
                                <i data-lucide="check" class="mr-2 h-4 w-4"></i>
                                Accepter l'embauche
                            </button>
                        </form>

                        <form method="POST" action="{{ localized_route('influencer.hiring.cancel', $hiring->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-destructive btn-default"
                                    onclick="return confirm('Êtes-vous sûr de vouloir refuser cette embauche ?')">
                                <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                                Refuser l'embauche
                            </button>
                        </form>
                        @elseif($hiring->status == 2)
                        <form method="POST" action="{{ localized_route('influencer.hiring.jobdone', $hiring->id) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-secondary btn-default"
                                    onclick="return confirm('Marquer ce travail comme terminé ?')">
                                <i data-lucide="check-circle" class="mr-2 h-4 w-4"></i>
                                Marquer comme terminé
                            </button>
                        </form>
                        @endif

                        <a href="{{ localized_route('influencer.hiring.conversation', $hiring->id) }}"
                           class="btn btn-outline btn-default">
                            <i data-lucide="message-circle" class="mr-2 h-4 w-4"></i>
                            Messages avec le client
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Client Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations client</h3>
                </div>
                <div class="card-content space-y-4">
                    <div class="flex items-center space-x-3">
                        @if($hiring->user->image)
                            <img src="{{ getImage(getFilePath('userProfile').'/'.$hiring->user->image, getFileSize('userProfile')) }}"
                                 alt="{{ $hiring->user->fullname }}"
                                 class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-lg">
                                    {{ substr($hiring->user->fullname ?? $hiring->user->username, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $hiring->user->fullname ?? $hiring->user->username }}</p>
                            <p class="text-sm text-gray-600">{{ $hiring->user->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Membre depuis</span>
                            <span class="text-sm font-medium">{{ $hiring->user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du projet</h3>
                </div>
                <div class="card-content space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Budget</span>
                        <span class="text-lg font-bold text-gray-900">{{ number_format($hiring->amount, 0, ',', ' ') }} DZD</span>
                    </div>

                    @if($hiring->working_day)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Durée</span>
                        <span class="text-sm font-medium">{{ $hiring->working_day }} jours</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Créé le</span>
                        <span class="text-sm font-medium">{{ $hiring->created_at->format('d/m/Y H:i') }}</span>
                    </div>
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