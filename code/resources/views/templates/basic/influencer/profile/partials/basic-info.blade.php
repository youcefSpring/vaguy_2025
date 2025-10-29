<!-- Basic Information Section -->
<form method="POST" action="{{ localized_route('influencer.profile.setting') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations personnelles</h3>
                    <p class="card-description">Vos informations de base visibles sur votre profil</p>
                </div>
                <div class="card-content space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label for="firstname" class="block text-sm font-medium text-gray-700 mb-2">
                                Prénom *
                            </label>
                            <input type="text"
                                   name="firstname"
                                   id="firstname"
                                   value="{{ old('firstname', $influencer->firstname) }}"
                                   class="input w-full"
                                   required>
                            @error('firstname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="lastname" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom *
                            </label>
                            <input type="text"
                                   name="lastname"
                                   id="lastname"
                                   value="{{ old('lastname', $influencer->lastname) }}"
                                   class="input w-full"
                                   required>
                            @error('lastname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ $influencer->email }}"
                               class="input w-full bg-gray-50"
                               readonly>
                        <p class="text-xs text-gray-500 mt-1">L'email ne peut pas être modifié</p>
                    </div>

                    <!-- Mobile -->
                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <input type="tel"
                               name="mobile"
                               id="mobile"
                               value="{{ old('mobile', $influencer->mobile) }}"
                               class="input w-full">
                        @error('mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Profession -->
                    <div>
                        <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                            Profession
                        </label>
                        <input type="text"
                               name="profession"
                               id="profession"
                               value="{{ old('profession', $influencer->profession ?? '') }}"
                               placeholder="ex: Content Creator, Designer, Photographe"
                               class="input w-full">
                        @error('profession')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Summary/Bio -->
                    <div>
                        <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">
                            Biographie
                        </label>
                        <textarea name="summary"
                                  id="summary"
                                  rows="4"
                                  placeholder="Décrivez-vous en quelques mots..."
                                  class="input w-full">{{ old('summary', $influencer->summary ?? '') }}</textarea>
                        @error('summary')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birthday -->
                    <div>
                        <label for="birthday" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de naissance
                        </label>
                        <input type="date"
                               name="birthday"
                               id="birthday"
                               value="{{ old('birthday', $influencer->birthday ? \Carbon\Carbon::parse($influencer->birthday)->format('Y-m-d') : '') }}"
                               class="input w-full">
                        @error('birthday')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Adresse</h3>
                    <p class="card-description">Votre localisation géographique</p>
                </div>
                <div class="card-content space-y-4">
                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse
                        </label>
                        <input type="text"
                               name="address"
                               id="address"
                               value="{{ old('address', $influencer->address->address ?? '') }}"
                               class="input w-full">
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Ville
                            </label>
                            <input type="text"
                                   name="city"
                                   id="city"
                                   value="{{ old('city', $influencer->address->city ?? '') }}"
                                   class="input w-full">
                            @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State/Wilaya -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                Wilaya
                            </label>
                            <select name="state" id="state" class="input w-full">
                                <option value="">Sélectionner une wilaya</option>
                                @if(isset($wilayas))
                                    @foreach($wilayas as $wilaya)
                                    <option value="{{ $wilaya->code }}"
                                            {{ old('state', $influencer->address->state ?? '') == $wilaya->code ? 'selected' : '' }}>
                                        {{ $wilaya->name }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- ZIP Code -->
                    <div>
                        <label for="zip" class="block text-sm font-medium text-gray-700 mb-2">
                            Code postal
                        </label>
                        <input type="text"
                               name="zip"
                               id="zip"
                               value="{{ old('zip', $influencer->address->zip ?? '') }}"
                               class="input w-full">
                        @error('zip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Catégories</h3>
                    <p class="card-description">Vos domaines d'expertise</p>
                </div>
                <div class="card-content">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @if(isset($categories))
                            @foreach($categories as $category)
                            <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox"
                                       name="categories[]"
                                       value="{{ $category->id }}"
                                       {{ in_array($category->id, old('categories', $influencer->categories->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        @endif
                    </div>
                    @error('categories')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Image -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Photo de profil</h3>
                    <p class="card-description">Votre image de profil publique</p>
                </div>
                <div class="card-content space-y-4">
                    <!-- Current Image -->
                    @if($influencer->image)
                    <div class="text-center">
                        <img src="{{ getImage(getFilePath('influencerProfile').'/'.$influencer->image, getFileSize('influencerProfile')) }}"
                             alt="Current profile"
                             class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-gray-200">
                        <p class="text-xs text-gray-500 mt-2">Image actuelle</p>
                    </div>
                    @endif

                    <!-- Image Upload -->
                    <div>
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="input w-full">
                        <p class="text-xs text-gray-500 mt-1">
                            Formats acceptés: JPG, PNG. Taille recommandée: 400x400px
                        </p>
                        @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statut du compte</h3>
                </div>
                <div class="card-content space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Email vérifié</span>
                        <span class="badge {{ $influencer->ev ? 'badge-default' : 'badge-destructive' }}">
                            {{ $influencer->ev ? 'Vérifié' : 'Non vérifié' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Mobile vérifié</span>
                        <span class="badge {{ $influencer->sv ? 'badge-default' : 'badge-destructive' }}">
                            {{ $influencer->sv ? 'Vérifié' : 'Non vérifié' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">KYC</span>
                        <span class="badge
                            {{ $influencer->kv == 1 ? 'badge-default' : ($influencer->kv == 2 ? 'badge-secondary' : 'badge-destructive') }}">
                            @if($influencer->kv == 1) Vérifié
                            @elseif($influencer->kv == 2) En cours
                            @else Non vérifié
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-default w-full">
                <i data-lucide="save" class="mr-2 h-4 w-4"></i>
                Sauvegarder les modifications
            </button>
        </div>
    </div>
</form>