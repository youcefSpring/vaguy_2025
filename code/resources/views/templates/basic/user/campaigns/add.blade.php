@extends('layouts.dashboard')
@section('content')

<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">{{ __('Add Campaign') }}</h3>
    </div>

    <div class="p-6">
        <p class="text-gray-600 mb-4">{{ __('Create a new campaign to work with influencers.') }}</p>

        <!-- Show preselected influencer info if available -->
        @if(isset($preselectedInfluencer))
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <img src="{{ getImage(getFilePath('influencerProfile') . '/' . $preselectedInfluencer['image'], getFileSize('influencerProfile'), true) }}"
                         alt="{{ $preselectedInfluencer['name'] }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-blue-300">
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-blue-900">@lang('المؤثر المحدد'): {{ $preselectedInfluencer['name'] }}</h4>
                    <p class="text-sm text-blue-700">@lang('سيتم إنشاء الحملة مع هذا المؤثر')</p>
                </div>
                <div class="flex-shrink-0">
                    <i data-lucide="check-circle" class="h-6 w-6 text-green-500"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- Vue.js Campaign Wizard -->
        <div id="campaign-wizard-app">
            <campaign-wizard
                :preselected-influencer="{{ json_encode($preselectedInfluencer ?? null) }}"
                csrf-token="{{ csrf_token() }}">
            </campaign-wizard>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Vue.js Campaign Wizard Component
const { createApp, ref, reactive, computed, onMounted } = Vue;

// Campaign Wizard Component
const CampaignWizard = {
    props: {
        preselectedInfluencer: {
            type: Object,
            default: null
        },
        csrfToken: {
            type: String,
            required: true
        }
    },
    emits: ['close', 'campaign-created'],
    template: `
        <div class="bg-white">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">@lang('إنشاء حملة جديدة')</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Progress Steps -->
            <div class="px-6 py-4 bg-gray-50">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-medium text-gray-900">@lang('تقدم الخطوات')</h4>
                    <span class="text-sm text-gray-500">@{{ currentStep }}/5</span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                         :style="{ width: (currentStep / 5) * 100 + '%' }"></div>
                </div>

                <!-- Step Indicators -->
                <div class="grid grid-cols-5 gap-4">
                    <div v-for="step in 5" :key="step" class="flex flex-col items-center">
                        <button @click="goToStep(step)"
                                :class="[
                                    'w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors',
                                    currentStep >= step
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-gray-200 text-gray-600 hover:bg-gray-300'
                                ]">
                            @{{ step }}
                        </button>
                        <span class="text-xs text-gray-600 mt-1 text-center">
                            @lang('الخطوة') @{{ step }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                <!-- Step 1: Company Information -->
                <div v-if="currentStep === 1" class="space-y-6">
                    <h4 class="text-lg font-semibold text-gray-900">@lang('معلومات الشركة')</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @lang('شعار العلامة التجارية') <span class="text-red-500">*</span>
                            </label>
                            <input type="file"
                                   @change="handleFileUpload($event, 'company_logo')"
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                            <span v-if="errors.company_logo" class="text-red-500 text-sm">@{{ errors.company_logo }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @lang('اسم الشركة') <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   v-model="form.company_name"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   :placeholder="'@lang('أدخل اسم الشركة')'">
                            <span v-if="errors.company_name" class="text-red-500 text-sm">@{{ errors.company_name }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            @lang('وصف الشركة') <span class="text-red-500">*</span>
                        </label>
                        <textarea v-model="form.company_desc"
                                 rows="4"
                                 class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                 :placeholder="'@lang('وصف مختصر عن شركتك وأنشطتها')'"></textarea>
                        <span v-if="errors.company_desc" class="text-red-500 text-sm">@{{ errors.company_desc }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @lang('الفئة الرئيسية') <span class="text-red-500">*</span>
                            </label>
                            <select v-model="form.company_principal_category"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">@lang('أختر التصنيف الرئيسي')</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    @{{ category.name }}
                                </option>
                            </select>
                            <span v-if="errors.company_principal_category" class="text-red-500 text-sm">@{{ errors.company_principal_category }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @lang('رابط موقع الشركة')
                            </label>
                            <input type="url"
                                   v-model="form.company_web_url"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://example.com">
                            <span v-if="errors.company_web_url" class="text-red-500 text-sm">@{{ errors.company_web_url }}</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Campaign Details -->
                <div v-if="currentStep === 2" class="space-y-6">
                    <h4 class="text-lg font-semibold text-gray-900">@lang('تفاصيل الحملة')</h4>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            @lang('اسم الحملة') <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               v-model="form.campain_name"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               :placeholder="'@lang('أدخل اسم الحملة')'">
                        <span v-if="errors.campain_name" class="text-red-500 text-sm">@{{ errors.campain_name }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            @lang('هدف الحملة') <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               v-model="form.campain_objective"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               :placeholder="'@lang('هدف الحملة الإعلانية')'">
                        <span v-if="errors.campain_objective" class="text-red-500 text-sm">@{{ errors.campain_objective }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            @lang('تفاصيل الحملة') <span class="text-red-500">*</span>
                        </label>
                        <textarea v-model="form.campain_details"
                                 rows="4"
                                 class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                 :placeholder="'@lang('تفاصيل مفصلة عن الحملة')'"></textarea>
                        <span v-if="errors.campain_details" class="text-red-500 text-sm">@{{ errors.campain_details }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @lang('تاريخ البداية') <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   v-model="form.campain_start_date"
                                   :min="today"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <span v-if="errors.campain_start_date" class="text-red-500 text-sm">@{{ errors.campain_start_date }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @lang('تاريخ النهاية') <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   v-model="form.campain_end_date"
                                   :min="form.campain_start_date || today"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <span v-if="errors.campain_end_date" class="text-red-500 text-sm">@{{ errors.campain_end_date }}</span>
                        </div>
                    </div>
                </div>

                <!-- Steps 3, 4, 5 would be added here -->
                <div v-if="currentStep > 2" class="text-center py-8">
                    <p class="text-gray-500">@lang('الخطوات الإضافية قيد التطوير')</p>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between">
                <button v-if="currentStep > 1"
                        @click="previousStep"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i data-lucide="chevron-left" class="w-4 h-4 mr-2"></i>
                    @lang('السابق')
                </button>
                <div v-else></div>

                <button v-if="currentStep < 5"
                        @click="nextStep"
                        :disabled="!canProceed"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    @lang('التالي')
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-2"></i>
                </button>
                <button v-else
                        @click="submitCampaign"
                        :disabled="submitting"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 disabled:opacity-50">
                    <span v-if="submitting" class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
                    @lang('إنشاء الحملة')
                </button>
            </div>
        </div>
    `,
    setup(props, { emit }) {
        const currentStep = ref(1);
        const submitting = ref(false);
        const errors = ref({});
        const categories = ref(@json(\App\Models\Category::all() ?? []));

        const form = reactive({
            company_logo: null,
            company_name: '',
            company_desc: '',
            company_principal_category: '',
            company_web_url: '',
            campain_name: '',
            campain_objective: '',
            campain_details: '',
            campain_want: '',
            campain_social_media: '',
            campain_social_media_content: '',
            campain_start_date: '',
            campain_end_date: ''
        });

        const today = computed(() => {
            return new Date().toISOString().split('T')[0];
        });

        const canProceed = computed(() => {
            if (currentStep.value === 1) {
                return form.company_name && form.company_desc && form.company_principal_category;
            } else if (currentStep.value === 2) {
                return form.campain_name && form.campain_objective && form.campain_details &&
                       form.campain_start_date && form.campain_end_date;
            }
            return true;
        });

        const handleFileUpload = (event, field) => {
            const file = event.target.files[0];
            if (file) {
                form[field] = file;
            }
        };

        const validateStep = (step) => {
            errors.value = {};
            let isValid = true;

            if (step === 1) {
                if (!form.company_name) {
                    errors.value.company_name = '@lang("حقل اسم الشركة مطلوب")';
                    isValid = false;
                }
                if (!form.company_desc) {
                    errors.value.company_desc = '@lang("حقل وصف الشركة مطلوب")';
                    isValid = false;
                }
                if (!form.company_principal_category) {
                    errors.value.company_principal_category = '@lang("حقل الفئة الرئيسية مطلوب")';
                    isValid = false;
                }
            } else if (step === 2) {
                if (!form.campain_name) {
                    errors.value.campain_name = '@lang("حقل اسم الحملة مطلوب")';
                    isValid = false;
                }
                if (!form.campain_objective) {
                    errors.value.campain_objective = '@lang("حقل هدف الحملة مطلوب")';
                    isValid = false;
                }
                if (!form.campain_details) {
                    errors.value.campain_details = '@lang("حقل تفاصيل الحملة مطلوب")';
                    isValid = false;
                }
                if (!form.campain_start_date) {
                    errors.value.campain_start_date = '@lang("حقل تاريخ البداية مطلوب")';
                    isValid = false;
                }
                if (!form.campain_end_date) {
                    errors.value.campain_end_date = '@lang("حقل تاريخ النهاية مطلوب")';
                    isValid = false;
                }
            }

            return isValid;
        };

        const nextStep = () => {
            if (validateStep(currentStep.value) && currentStep.value < 5) {
                currentStep.value++;
            }
        };

        const previousStep = () => {
            if (currentStep.value > 1) {
                currentStep.value--;
            }
        };

        const goToStep = (step) => {
            if (step <= currentStep.value || validateStep(currentStep.value)) {
                currentStep.value = step;
            }
        };

        const submitCampaign = async () => {
            if (!validateStep(currentStep.value)) return;

            submitting.value = true;
            errors.value = {};

            try {
                const formData = new FormData();

                // Add all form fields
                Object.keys(form).forEach(key => {
                    if (form[key] !== null && form[key] !== '') {
                        formData.append(key, form[key]);
                    }
                });

                formData.append('_token', props.csrfToken);

                const response = await axios.post('/client/campaigns-vue', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    emit('campaign-created', response.data.campaign);
                    // Show success message
                    alert('@lang("تم إنشاء الحملة بنجاح!")');
                } else {
                    errors.value = response.data.errors || {};
                    alert(response.data.message || '@lang("حدث خطأ أثناء إنشاء الحملة")');
                }
            } catch (error) {
                console.error('Error creating campaign:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    errors.value = error.response.data.errors;
                }
                alert('@lang("حدث خطأ أثناء إنشاء الحملة")');
            } finally {
                submitting.value = false;
            }
        };

        onMounted(() => {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        return {
            currentStep,
            submitting,
            errors,
            categories,
            form,
            today,
            canProceed,
            handleFileUpload,
            validateStep,
            nextStep,
            previousStep,
            goToStep,
            submitCampaign
        };
    }
};

// Mount the app
createApp({
    components: {
        CampaignWizard
    }
}).mount('#campaign-wizard-app');
</script>
@endpush