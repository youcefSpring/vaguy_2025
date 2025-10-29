<!-- Education Section -->
<div class="space-y-6">
    <!-- Add Education Button -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Formation et éducation</h3>
            <p class="text-sm text-gray-600">Vos diplômes et formations</p>
        </div>
        <button type="button"
                class="btn btn-primary btn-sm"
                onclick="openEducationModal()">
            <i data-lucide="plus" class="mr-1 h-4 w-4"></i>
            Ajouter une formation
        </button>
    </div>

    <!-- Education List -->
    <div class="space-y-4">
        @if(isset($influencer->education) && count($influencer->education) > 0)
            @foreach($influencer->education as $index => $education)
            <div class="card" id="education-{{ $index }}">
                <div class="card-content">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="graduation-cap" class="h-5 w-5 text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $education['degree'] }}</h4>
                                    <p class="text-sm text-gray-600">{{ $education['institution'] }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $education['start_year'] }} - {{ $education['end_year'] ?? 'En cours' }}
                                    </p>
                                    @if(isset($education['description']) && $education['description'])
                                    <p class="text-sm text-gray-700 mt-2">{{ $education['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button type="button"
                                    class="btn btn-ghost btn-sm"
                                    onclick="editEducation({{ $index }})">
                                <i data-lucide="edit" class="h-4 w-4"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-ghost btn-sm text-red-600 hover:text-red-700"
                                    onclick="removeEducation({{ $index }})">
                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-content text-center py-12">
                    <i data-lucide="graduation-cap" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune formation ajoutée</h3>
                    <p class="text-gray-600 mb-4">
                        Ajoutez vos formations pour améliorer votre profil professionnel
                    </p>
                    <button type="button"
                            class="btn btn-primary btn-default"
                            onclick="openEducationModal()">
                        <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                        Ajouter votre première formation
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Education Modal -->
<div id="educationModal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" x-on:click="open = false"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold" id="modalTitle">Ajouter une formation</h3>
                </div>

                <form id="educationForm" method="POST" action="{{ localized_route('influencer.profile.education') }}" class="p-6">
                    @csrf
                    <input type="hidden" name="education_index" id="education_index" value="">

                    <div class="space-y-4">
                        <!-- Degree -->
                        <div>
                            <label for="degree" class="block text-sm font-medium text-gray-700 mb-2">
                                Diplôme / Formation *
                            </label>
                            <input type="text"
                                   name="degree"
                                   id="degree"
                                   placeholder="ex: Master en Marketing Digital"
                                   class="input w-full"
                                   required>
                        </div>

                        <!-- Institution -->
                        <div>
                            <label for="institution" class="block text-sm font-medium text-gray-700 mb-2">
                                Établissement *
                            </label>
                            <input type="text"
                                   name="institution"
                                   id="institution"
                                   placeholder="ex: Université d'Alger"
                                   class="input w-full"
                                   required>
                        </div>

                        <!-- Years -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_year" class="block text-sm font-medium text-gray-700 mb-2">
                                    Année de début *
                                </label>
                                <input type="number"
                                       name="start_year"
                                       id="start_year"
                                       placeholder="2020"
                                       min="1980"
                                       max="{{ date('Y') }}"
                                       class="input w-full"
                                       required>
                            </div>
                            <div>
                                <label for="end_year" class="block text-sm font-medium text-gray-700 mb-2">
                                    Année de fin
                                </label>
                                <input type="number"
                                       name="end_year"
                                       id="end_year"
                                       placeholder="2024"
                                       min="1980"
                                       max="{{ date('Y') + 10 }}"
                                       class="input w-full">
                                <p class="text-xs text-gray-500 mt-1">Laissez vide si en cours</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="education_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description (optionnel)
                            </label>
                            <textarea name="description"
                                      id="education_description"
                                      rows="3"
                                      placeholder="Spécialisation, projets importants, mentions..."
                                      class="input w-full"></textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="btn btn-primary btn-default flex-1">
                                <span id="submitText">Ajouter la formation</span>
                            </button>
                            <button type="button" class="btn btn-outline btn-default" x-on:click="open = false">
                                Annuler
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
let currentEducationIndex = null;

function openEducationModal() {
    currentEducationIndex = null;
    document.getElementById('modalTitle').textContent = 'Ajouter une formation';
    document.getElementById('submitText').textContent = 'Ajouter la formation';
    document.getElementById('educationForm').reset();
    document.getElementById('education_index').value = '';

    const modal = document.getElementById('educationModal');
    modal.classList.remove('hidden');
    modal.querySelector('[x-data]').__x.$data.open = true;
}

function editEducation(index) {
    currentEducationIndex = index;
    const educationData = @json($influencer->education ?? []);
    const education = educationData[index];

    document.getElementById('modalTitle').textContent = 'Modifier la formation';
    document.getElementById('submitText').textContent = 'Modifier la formation';
    document.getElementById('education_index').value = index;

    // Fill form with existing data
    document.getElementById('degree').value = education.degree || '';
    document.getElementById('institution').value = education.institution || '';
    document.getElementById('start_year').value = education.start_year || '';
    document.getElementById('end_year').value = education.end_year || '';
    document.getElementById('education_description').value = education.description || '';

    const modal = document.getElementById('educationModal');
    modal.classList.remove('hidden');
    modal.querySelector('[x-data]').__x.$data.open = true;
}

function removeEducation(index) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')) {
        // Create a form to submit the deletion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ localized_route("influencer.profile.education.delete") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const indexInput = document.createElement('input');
        indexInput.type = 'hidden';
        indexInput.name = 'education_index';
        indexInput.value = index;

        form.appendChild(csrfToken);
        form.appendChild(indexInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush