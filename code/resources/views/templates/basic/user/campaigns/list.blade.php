@extends('layouts.dashboard')
@section('content')

<!-- Vue.js Campaign Management App -->
<div id="campaign-app">
    <campaign-manager></campaign-manager>
</div>

@endsection

@push('scripts')
<script>
// Vue.js Campaign Manager Component
const { createApp, ref, reactive, onMounted, computed } = Vue;

// Campaign Manager Component
const CampaignManager = {
    template: `
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" v-text="texts.manageTitle"></h3>
                        <p class="text-sm text-gray-600" v-text="texts.manageSubtitle"></p>
                    </div>
                    <button @click="showWizard = true"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i data-lucide="plus" class="h-4 w-4 ml-2"></i>
                        <span v-text="texts.createNew"></span>
                    </button>
                </div>
            </div>

            <!-- Campaign Wizard Modal -->
            <div v-if="showWizard" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-start justify-center min-h-screen pt-8 px-4 pb-8">
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showWizard = false"></div>

                    <div class="relative bg-white rounded-lg shadow-xl transform transition-all w-full max-w-4xl mx-auto">
                        <campaign-wizard @close="showWizard = false" @campaign-created="onCampaignCreated"></campaign-wizard>
                    </div>
                </div>
            </div>

            <!-- Campaign List -->
            <div class="bg-white shadow rounded-lg">
                <div v-if="loading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-gray-500" v-text="texts.loading"></p>
                </div>

                <div v-else-if="campaigns.length === 0" class="text-center py-16">
                    <i data-lucide="megaphone" class="h-16 w-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2" v-text="texts.noCampaigns"></h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto" v-text="texts.noCampaignsDesc"></p>
                    <button @click="showWizard = true"
                            class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        <i data-lucide="plus" class="h-5 w-5 ml-2"></i>
                        <span v-text="texts.createNew"></span>
                    </button>
                </div>

                <div v-else class="p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div v-for="campaign in campaigns" :key="campaign?.id || Math.random()" v-if="campaign"
                             class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex items-start space-x-3 space-x-reverse">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i data-lucide="megaphone" class="h-5 w-5 text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">
                                            @{{ campaign?.campain_name || 'حملة #' + (campaign?.id || 'غير محدد') }}
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                            @{{ campaign?.campain_objective || 'لا يوجد هدف محدد' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500" v-text="texts.createdDate"></span>
                                        <span class="text-gray-900 font-medium">
                                            @{{ formatDate(campaign?.created_at) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-6 flex space-x-3 space-x-reverse">
                                    <button @click="viewCampaign(campaign)" :disabled="!campaign?.id"
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i data-lucide="eye" class="h-4 w-4 ml-1"></i>
                                        <span v-text="texts.view"></span>
                                    </button>
                                    <button @click="editCampaign(campaign)" :disabled="!campaign?.id"
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i data-lucide="edit" class="h-4 w-4 ml-1"></i>
                                        <span v-text="texts.edit"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    setup() {
        const campaignsData = @json($campains ?? []);
        const campaigns = ref(Array.isArray(campaignsData) ? campaignsData.filter(c => c !== null) : []);
        const loading = ref(false);
        const showWizard = ref(false);

        const texts = {
            manageTitle: @json(__('إدارة الحملات')),
            manageSubtitle: @json(__('إنشاء وإدارة حملاتك الإعلانية')),
            createNew: @json(__('إنشاء حملة جديدة')),
            loading: @json(__('جاري التحميل...')),
            noCampaigns: @json(__('لا توجد حملات')),
            noCampaignsDesc: @json(__('لم تقم بإنشاء أي حملات إعلانية بعد. ابدأ بإنشاء حملتك الأولى الآن!')),
            createdDate: @json(__('تاريخ الإنشاء')),
            view: @json(__('عرض')),
            edit: @json(__('تعديل'))
        };

        const formatDate = (dateString) => {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('ar-SA');
        };

        const loadCampaigns = async () => {
            loading.value = true;
            try {
                const response = await axios.get('/client/campaigns-data');
                const data = response.data;
                campaigns.value = Array.isArray(data) ? data.filter(c => c !== null && c !== undefined) : [];
            } catch (error) {
                console.error('Error loading campaigns:', error);
                campaigns.value = [];
            } finally {
                loading.value = false;
            }
        };

        const onCampaignCreated = (newCampaign) => {
            if (newCampaign && newCampaign.id) {
                campaigns.value.push(newCampaign);
            }
            showWizard.value = false;
        };

        const viewCampaign = (campaign) => {
            if (campaign && campaign.id) {
                window.location.href = `/client/campaigns/${campaign.id}`;
            } else {
                console.warn('Campaign ID not found');
            }
        };

        const editCampaign = (campaign) => {
            if (campaign && campaign.id) {
                window.location.href = `/client/campaigns/${campaign.id}/edit`;
            } else {
                console.warn('Campaign ID not found');
            }
        };

        onMounted(() => {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        return {
            campaigns,
            loading,
            showWizard,
            texts,
            formatDate,
            loadCampaigns,
            onCampaignCreated,
            viewCampaign,
            editCampaign
        };
    }
};

// Campaign Wizard Component (simplified for modal use)
const CampaignWizard = {
    props: {
        preselectedInfluencer: {
            type: Object,
            default: null
        },
        csrfToken: {
            type: String,
            default: () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    },
    emits: ['close', 'campaign-created'],
    template: `
        <div class="bg-white">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">إنشاء حملة جديدة</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-center text-gray-500 py-8">
                    لإنشاء حملة جديدة، يرجى استخدام النموذج المفصل.
                </p>
                <div class="text-center">
                    <a href="/client/add-campaign"
                       class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                        <i data-lucide="plus" class="h-5 w-5 ml-2"></i>
                        إنشاء حملة جديدة
                    </a>
                </div>
            </div>
        </div>
    `,
    setup(props, { emit }) {
        return {};
    }
};

// Mount the app
createApp({
    components: {
        CampaignManager,
        CampaignWizard
    }
}).mount('#campaign-app');
</script>
@endpush