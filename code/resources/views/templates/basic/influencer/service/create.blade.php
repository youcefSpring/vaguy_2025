@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ localized_route('influencer.service.all') }}"
               class="btn btn-ghost btn-icon">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                {{ isset($service) ? 'Modifier le Service' : 'Créer un Service' }}
            </h1>
        </div>
        <p class="text-gray-600">
            {{ isset($service) ? 'Modifiez les détails de votre service' : 'Créez un nouveau service pour vos clients' }}
        </p>
    </div>

    <!-- Service Form -->
    <form method="POST"
          action="{{ localized_route('influencer.service.store', isset($service) ? $service->id : 0) }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informations de base</h3>
                        <p class="card-description">
                            Informations principales de votre service
                        </p>
                    </div>
                    <div class="card-content space-y-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre du service *
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   value="{{ old('title', $service->title ?? '') }}"
                                   placeholder="ex: Je vais créer un logo professionnel pour votre entreprise"
                                   class="input w-full"
                                   required>
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie
                            </label>
                            <select name="category_id" id="category_id" class="input w-full">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id', $service->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description détaillée *
                            </label>
                            <textarea name="description"
                                      id="description"
                                      rows="6"
                                      placeholder="Décrivez en détail votre service, ce que vous allez livrer, vos compétences..."
                                      class="input w-full"
                                      required>{{ old('description', $service->description ?? '') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Prix (DZD) *
                            </label>
                            <div class="relative">
                                <input type="number"
                                       name="price"
                                       id="price"
                                       value="{{ old('price', $service->price ?? '') }}"
                                       placeholder="5000"
                                       min="0"
                                       step="0.01"
                                       class="input w-full pr-12"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 text-sm">DZD</span>
                                </div>
                            </div>
                            @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Key Points -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Points clés</h3>
                        <p class="card-description">
                            Listez les avantages et caractéristiques de votre service
                        </p>
                    </div>
                    <div class="card-content">
                        <div id="keyPointsContainer" class="space-y-3">
                            @if(isset($service) && $service->key_points)
                                @foreach($service->key_points as $index => $point)
                                <div class="key-point-item flex gap-2">
                                    <input type="text"
                                           name="key_points[]"
                                           value="{{ $point }}"
                                           placeholder="ex: Livraison en 24h"
                                           class="input flex-1">
                                    <button type="button"
                                            onclick="removeKeyPoint(this)"
                                            class="btn btn-destructive btn-icon btn-sm">
                                        <i data-lucide="x" class="h-4 w-4"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="key-point-item flex gap-2">
                                    <input type="text"
                                           name="key_points[]"
                                           placeholder="ex: Livraison en 24h"
                                           class="input flex-1">
                                    <button type="button"
                                            onclick="removeKeyPoint(this)"
                                            class="btn btn-destructive btn-icon btn-sm">
                                        <i data-lucide="x" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button"
                                onclick="addKeyPoint()"
                                class="btn btn-outline btn-sm mt-3">
                            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                            Ajouter un point clé
                        </button>
                        @error('key_points')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tags -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tags</h3>
                        <p class="card-description">
                            Ajoutez des mots-clés pour aider les clients à trouver votre service
                        </p>
                    </div>
                    <div class="card-content">
                        <div id="tagsContainer" class="space-y-3">
                            @if(isset($service) && $service->tags)
                                @foreach($service->tags as $tag)
                                <div class="tag-item flex gap-2">
                                    <input type="text"
                                           name="tags[]"
                                           value="{{ $tag->name }}"
                                           placeholder="ex: logo, design, graphisme"
                                           class="input flex-1">
                                    <button type="button"
                                            onclick="removeTag(this)"
                                            class="btn btn-destructive btn-icon btn-sm">
                                        <i data-lucide="x" class="h-4 w-4"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="tag-item flex gap-2">
                                    <input type="text"
                                           name="tags[]"
                                           placeholder="ex: logo, design, graphisme"
                                           class="input flex-1">
                                    <button type="button"
                                            onclick="removeTag(this)"
                                            class="btn btn-destructive btn-icon btn-sm">
                                        <i data-lucide="x" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button"
                                onclick="addTag()"
                                class="btn btn-outline btn-sm mt-3">
                            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                            Ajouter un tag
                        </button>
                        @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Main Image -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Image principale</h3>
                        <p class="card-description">
                            Image de couverture de votre service
                        </p>
                    </div>
                    <div class="card-content">
                        <div class="space-y-4">
                            @if(isset($service) && $service->image)
                            <div class="current-image">
                                <img src="{{ getImage(getFilePath('service') . '/' . $service->image, getFileSize('service')) }}"
                                     alt="Current image"
                                     class="w-full h-40 object-cover rounded-lg border">
                                <p class="text-xs text-gray-500 mt-1">Image actuelle</p>
                            </div>
                            @endif

                            <div>
                                <input type="file"
                                       name="image"
                                       id="image"
                                       accept="image/*"
                                       class="input w-full"
                                       {{ !isset($service) ? 'required' : '' }}>
                                <p class="text-xs text-gray-500 mt-1">
                                    Formats acceptés: JPG, PNG. Taille recommandée: 800x600px
                                </p>
                                @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Galerie d'images</h3>
                        <p class="card-description">
                            Images supplémentaires (optionnel)
                        </p>
                    </div>
                    <div class="card-content">
                        @if(isset($service) && isset($images) && count($images) > 0)
                        <div class="current-gallery grid grid-cols-2 gap-2 mb-4">
                            @foreach($images as $image)
                            <div class="relative">
                                <img src="{{ $image['src'] }}"
                                     alt="Gallery image"
                                     class="w-full h-20 object-cover rounded border">
                                <input type="hidden" name="old[]" value="{{ $image['id'] }}">
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <input type="file"
                               name="images[]"
                               multiple
                               accept="image/*"
                               class="input w-full">
                        <p class="text-xs text-gray-500 mt-1">
                            Vous pouvez sélectionner plusieurs images
                        </p>
                        @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Actions -->
                <div class="card">
                    <div class="card-content">
                        <div class="space-y-3">
                            <button type="submit" class="btn btn-primary btn-default w-full">
                                <i data-lucide="save" class="mr-2 h-4 w-4"></i>
                                {{ isset($service) ? 'Mettre à jour' : 'Créer le service' }}
                            </button>
                            <a href="{{ localized_route('influencer.service.all') }}"
                               class="btn btn-outline btn-default w-full">
                                <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                                Annuler
                            </a>
                        </div>

                        @if(!isset($service))
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-800">
                                <i data-lucide="info" class="inline h-4 w-4 mr-1"></i>
                                Votre service sera soumis pour approbation avant d'être publié.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('script')
<script>
function addKeyPoint() {
    const container = document.getElementById('keyPointsContainer');
    const div = document.createElement('div');
    div.className = 'key-point-item flex gap-2';
    div.innerHTML = `
        <input type="text"
               name="key_points[]"
               placeholder="ex: Livraison en 24h"
               class="input flex-1">
        <button type="button"
                onclick="removeKeyPoint(this)"
                class="btn btn-destructive btn-icon btn-sm">
            <i data-lucide="x" class="h-4 w-4"></i>
        </button>
    `;
    container.appendChild(div);
    lucide.createIcons();
}

function removeKeyPoint(button) {
    button.closest('.key-point-item').remove();
}

function addTag() {
    const container = document.getElementById('tagsContainer');
    const div = document.createElement('div');
    div.className = 'tag-item flex gap-2';
    div.innerHTML = `
        <input type="text"
               name="tags[]"
               placeholder="ex: logo, design, graphisme"
               class="input flex-1">
        <button type="button"
                onclick="removeTag(this)"
                class="btn btn-destructive btn-icon btn-sm">
            <i data-lucide="x" class="h-4 w-4"></i>
        </button>
    `;
    container.appendChild(div);
    lucide.createIcons();
}

function removeTag(button) {
    button.closest('.tag-item').remove();
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection