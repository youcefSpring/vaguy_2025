<!-- Skills Section -->
<div class="space-y-6">
    <!-- Add Skill Button -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Compétences</h3>
            <p class="text-sm text-gray-600">Vos compétences et expertises</p>
        </div>
        <button type="button"
                class="btn btn-primary btn-sm"
                onclick="openSkillModal()">
            <i data-lucide="plus" class="mr-1 h-4 w-4"></i>
            Ajouter une compétence
        </button>
    </div>

    <!-- Skills Categories -->
    <div class="space-y-6">
        <!-- Technical Skills -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Compétences techniques</h4>
                <p class="card-description">Logiciels, outils et technologies</p>
            </div>
            <div class="card-content">
                <div class="flex flex-wrap gap-2">
                    @if(isset($influencer->skills['technical']) && count($influencer->skills['technical']) > 0)
                        @foreach($influencer->skills['technical'] as $index => $skill)
                        <div class="skill-badge" data-category="technical" data-index="{{ $index }}">
                            <span>{{ $skill['name'] }}</span>
                            <div class="skill-level">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="level-dot {{ $i <= $skill['level'] ? 'active' : '' }}"></div>
                                @endfor
                            </div>
                            <button type="button" class="skill-remove" onclick="removeSkill('technical', {{ $index }})">
                                <i data-lucide="x" class="h-3 w-3"></i>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">Aucune compétence technique ajoutée</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Creative Skills -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Compétences créatives</h4>
                <p class="card-description">Design, contenu et création</p>
            </div>
            <div class="card-content">
                <div class="flex flex-wrap gap-2">
                    @if(isset($influencer->skills['creative']) && count($influencer->skills['creative']) > 0)
                        @foreach($influencer->skills['creative'] as $index => $skill)
                        <div class="skill-badge" data-category="creative" data-index="{{ $index }}">
                            <span>{{ $skill['name'] }}</span>
                            <div class="skill-level">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="level-dot {{ $i <= $skill['level'] ? 'active' : '' }}"></div>
                                @endfor
                            </div>
                            <button type="button" class="skill-remove" onclick="removeSkill('creative', {{ $index }})">
                                <i data-lucide="x" class="h-3 w-3"></i>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">Aucune compétence créative ajoutée</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Marketing Skills -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Marketing et communication</h4>
                <p class="card-description">Stratégies marketing et communication</p>
            </div>
            <div class="card-content">
                <div class="flex flex-wrap gap-2">
                    @if(isset($influencer->skills['marketing']) && count($influencer->skills['marketing']) > 0)
                        @foreach($influencer->skills['marketing'] as $index => $skill)
                        <div class="skill-badge" data-category="marketing" data-index="{{ $index }}">
                            <span>{{ $skill['name'] }}</span>
                            <div class="skill-level">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="level-dot {{ $i <= $skill['level'] ? 'active' : '' }}"></div>
                                @endfor
                            </div>
                            <button type="button" class="skill-remove" onclick="removeSkill('marketing', {{ $index }})">
                                <i data-lucide="x" class="h-3 w-3"></i>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">Aucune compétence marketing ajoutée</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Language Skills -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Langues</h4>
                <p class="card-description">Vos compétences linguistiques</p>
            </div>
            <div class="card-content">
                <div class="flex flex-wrap gap-2">
                    @if(isset($influencer->skills['languages']) && count($influencer->skills['languages']) > 0)
                        @foreach($influencer->skills['languages'] as $index => $skill)
                        <div class="skill-badge" data-category="languages" data-index="{{ $index }}">
                            <span>{{ $skill['name'] }}</span>
                            <div class="skill-level">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="level-dot {{ $i <= $skill['level'] ? 'active' : '' }}"></div>
                                @endfor
                            </div>
                            <button type="button" class="skill-remove" onclick="removeSkill('languages', {{ $index }})">
                                <i data-lucide="x" class="h-3 w-3"></i>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">Aucune langue ajoutée</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skill Modal -->
<div id="skillModal" class="fixed inset-0 z-50 hidden" x-data="{ open: false }" x-show="open">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" x-on:click="open = false"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Ajouter une compétence</h3>
                </div>

                <form id="skillForm" method="POST" action="{{ localized_route('influencer.profile.skills') }}" class="p-6">
                    @csrf

                    <div class="space-y-4">
                        <!-- Category -->
                        <div>
                            <label for="skill_category" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie *
                            </label>
                            <select name="category" id="skill_category" class="input w-full" required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="technical">Compétences techniques</option>
                                <option value="creative">Compétences créatives</option>
                                <option value="marketing">Marketing et communication</option>
                                <option value="languages">Langues</option>
                            </select>
                        </div>

                        <!-- Skill Name -->
                        <div>
                            <label for="skill_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom de la compétence *
                            </label>
                            <input type="text"
                                   name="name"
                                   id="skill_name"
                                   placeholder="ex: Photoshop, Content Marketing, Français..."
                                   class="input w-full"
                                   required>
                        </div>

                        <!-- Skill Level -->
                        <div>
                            <label for="skill_level" class="block text-sm font-medium text-gray-700 mb-2">
                                Niveau de maîtrise *
                            </label>
                            <div class="space-y-2">
                                <input type="range"
                                       name="level"
                                       id="skill_level"
                                       min="1"
                                       max="5"
                                       value="3"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Débutant</span>
                                    <span>Intermédiaire</span>
                                    <span>Avancé</span>
                                    <span>Expert</span>
                                    <span>Maître</span>
                                </div>
                                <div class="text-center">
                                    <span id="skill_level_text" class="text-sm font-medium text-gray-700">Intermédiaire</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="btn btn-primary btn-default flex-1">
                                Ajouter la compétence
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

@push('style')
<style>
.skill-badge {
    @apply inline-flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm;
}

.skill-level {
    @apply flex gap-1;
}

.level-dot {
    @apply w-2 h-2 rounded-full bg-gray-300;
}

.level-dot.active {
    @apply bg-blue-500;
}

.skill-remove {
    @apply ml-1 text-gray-400 hover:text-red-500 transition-colors;
}
</style>
@endpush

@push('script')
<script>
function openSkillModal() {
    document.getElementById('skillForm').reset();
    document.getElementById('skill_level').value = 3;
    updateSkillLevelText(3);

    const modal = document.getElementById('skillModal');
    modal.classList.remove('hidden');
    modal.querySelector('[x-data]').__x.$data.open = true;
}

function removeSkill(category, index) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette compétence ?')) {
        // Create a form to submit the deletion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ localized_route("influencer.profile.skills.delete") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const categoryInput = document.createElement('input');
        categoryInput.type = 'hidden';
        categoryInput.name = 'category';
        categoryInput.value = category;

        const indexInput = document.createElement('input');
        indexInput.type = 'hidden';
        indexInput.name = 'skill_index';
        indexInput.value = index;

        form.appendChild(csrfToken);
        form.appendChild(categoryInput);
        form.appendChild(indexInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function updateSkillLevelText(level) {
    const levels = ['', 'Débutant', 'Intermédiaire', 'Intermédiaire', 'Avancé', 'Expert'];
    document.getElementById('skill_level_text').textContent = levels[level] || 'Intermédiaire';
}

// Update skill level text when slider changes
document.getElementById('skill_level').addEventListener('input', function() {
    updateSkillLevelText(parseInt(this.value));
});
</script>
@endpush