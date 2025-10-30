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
                    <a :href="createCampaignUrl"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span v-text="texts.createNew"></span>
                    </a>
                </div>
            </div>

            <!-- Campaign List -->
            <div class="bg-white shadow rounded-lg">
                <div v-if="loading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-gray-500" v-text="texts.loading"></p>
                </div>

                <div v-else-if="campaigns.length === 0" class="text-center py-16">
                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2" v-text="texts.noCampaigns"></h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto" v-text="texts.noCampaignsDesc"></p>
                    <a :href="createCampaignUrl"
                       class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span v-text="texts.createNew"></span>
                    </a>
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
                                            @{{ campaign?.campain_name || (texts.campaignNumber || '') + (campaign?.id || '') }}
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                            @{{ campaign?.campain_objective || texts.noObjective }}
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
        const locale = @json(app()->getLocale());
        const createCampaignUrl = '/' + locale + '/client/add-campaign';

        const texts = {
            manageTitle: @json(__('campaigns.manage_campaigns')),
            manageSubtitle: @json(__('campaigns.manage_campaigns_subtitle')),
            createNew: @json(__('campaigns.create_new')),
            loading: @json(__('campaigns.loading')),
            noCampaigns: @json(__('campaigns.no_campaigns')),
            noCampaignsDesc: @json(__('campaigns.no_campaigns_desc')),
            createdDate: @json(__('campaigns.created_date')),
            view: @json(__('campaigns.view')),
            edit: @json(__('campaigns.edit')),
            campaignNumber: @json(__('campaigns.campaign_number')),
            noObjective: @json(__('campaigns.no_objective'))
        };

        const formatDate = (dateString) => {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('ar-SA');
        };

        const loadCampaigns = async () => {
            loading.value = true;
            try {
                const response = await axios.get('/' + locale + '/client/campaigns-data');
                const data = response.data;
                campaigns.value = Array.isArray(data) ? data.filter(c => c !== null && c !== undefined) : [];
            } catch (error) {
                console.error('Error loading campaigns:', error);
                campaigns.value = [];
            } finally {
                loading.value = false;
            }
        };

        const viewCampaign = (campaign) => {
            if (campaign && campaign.id) {
                window.location.href = '/' + locale + '/client/show-detail/' + campaign.id;
            } else {
                console.warn('Campaign ID not found');
            }
        };

        const editCampaign = (campaign) => {
            if (campaign && campaign.id) {
                window.location.href = '/' + locale + '/client/edit-campaign/' + campaign.id;
            } else {
                console.warn('Campaign ID not found');
            }
        };

        onMounted(() => {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            console.log('CampaignManager mounted', {
                campaigns: campaigns.value,
                locale: locale
            });
        });

        return {
            campaigns,
            loading,
            locale,
            createCampaignUrl,
            texts,
            formatDate,
            loadCampaigns,
            viewCampaign,
            editCampaign
        };
    }
};

// Mount the app
createApp({
    components: {
        CampaignManager
    }
}).mount('#campaign-app');
</script>
@endpush