{{-- Campaign Details View --}}
@extends('layouts.dashboard')

@section('title', $campaign->campaign_name ?? __('messages.campaign_details'))

@section('page-title', $campaign->campaign_name ?? __('messages.campaign_details'))

@section('content')
<div x-data="campaignShow(@json($campaign ?? null), @json($applications ?? []), @json($analytics ?? []))" x-init="init()">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ localized_route('client.campaigns.index') }}">{{ __('messages.campaigns') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" x-text="campaign?.campaign_name">
                        {{ $campaign->campaign_name ?? __('messages.campaign_details') }}
                    </li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ localized_route('client.campaigns.edit', $campaign->id ?? 1) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>
                {{ __('messages.edit') }}
            </a>
            <button class="btn btn-outline-danger" @click="confirmDelete()">
                <i class="fas fa-trash me-2"></i>
                {{ __('messages.delete') }}
            </button>
        </div>
    </div>

    {{-- Campaign Overview Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                    <h5 class="card-title" x-text="analytics.views">{{ $analytics['views'] ?? 0 }}</h5>
                    <p class="card-text text-muted">{{ __('messages.views') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                    <h5 class="card-title" x-text="analytics.applications">{{ $analytics['applications'] ?? 0 }}</h5>
                    <p class="card-text text-muted">{{ __('messages.applications') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                    <h5 class="card-title" x-text="analytics.accepted">{{ $analytics['accepted'] ?? 0 }}</h5>
                    <p class="card-text text-muted">{{ __('messages.accepted') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-percentage fa-2x text-warning mb-2"></i>
                    <h5 class="card-title" x-text="analytics.completion_rate + '%'">{{ $analytics['completion_rate'] ?? 0 }}%</h5>
                    <p class="card-text text-muted">{{ __('messages.completion_rate') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Campaign Information --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.campaign_information') }}</h5>
                </div>
                <div class="card-body">
                    {{-- Campaign Status --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">{{ __('messages.status') }}</h6>
                        <span class="badge fs-6"
                              :class="{
                                  'bg-secondary': campaign?.status === 'draft',
                                  'bg-success': campaign?.status === 'active',
                                  'bg-warning': campaign?.status === 'paused',
                                  'bg-info': campaign?.status === 'completed',
                                  'bg-danger': campaign?.status === 'cancelled'
                              }"
                              x-text="translateStatus(campaign?.status)">
                        </span>
                    </div>

                    {{-- Campaign Dates --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('messages.start_date') }}:</strong>
                            <div x-text="formatDate(campaign?.campaign_start_date)" class="text-muted">
                                {{ $campaign->campaign_start_date ?? '' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('messages.end_date') }}:</strong>
                            <div x-text="formatDate(campaign?.campaign_end_date)" class="text-muted">
                                {{ $campaign->campaign_end_date ?? '' }}
                            </div>
                        </div>
                    </div>

                    {{-- Campaign Details --}}
                    <div class="mb-3">
                        <strong>{{ __('messages.objective') }}:</strong>
                        <p x-text="campaign?.campaign_objective" class="text-muted mb-2">
                            {{ $campaign->campaign_objective ?? '' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>{{ __('messages.details') }}:</strong>
                        <p x-text="campaign?.campaign_details" class="text-muted mb-2">
                            {{ $campaign->campaign_details ?? '' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>{{ __('messages.what_we_want') }}:</strong>
                        <p x-text="campaign?.campaign_want" class="text-muted mb-2">
                            {{ $campaign->campaign_want ?? '' }}
                        </p>
                    </div>

                    {{-- Platform --}}
                    <div class="mb-3">
                        <strong>{{ __('messages.target_platform') }}:</strong>
                        <span class="badge bg-primary ms-2" x-text="campaign?.target_platform">
                            {{ $campaign->target_platform ?? '' }}
                        </span>
                    </div>

                    {{-- Guidelines --}}
                    <div x-show="campaign?.do_this" class="mb-3">
                        <strong>{{ __('messages.do_this') }}:</strong>
                        <div class="alert alert-success mt-2">
                            <p x-text="campaign?.do_this" class="mb-0">{{ $campaign->do_this ?? '' }}</p>
                        </div>
                    </div>

                    <div x-show="campaign?.dont_do_this" class="mb-3">
                        <strong>{{ __('messages.dont_do_this') }}:</strong>
                        <div class="alert alert-danger mt-2">
                            <p x-text="campaign?.dont_do_this" class="mb-0">{{ $campaign->dont_do_this ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Targeting Information --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.targeting_criteria') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>{{ __('messages.influencer_criteria') }}</h6>
                            <ul class="list-unstyled">
                                <li><strong>{{ __('messages.age_range') }}:</strong>
                                    <span x-text="`${campaign?.influencer_age_min} - ${campaign?.influencer_age_max}`">
                                        {{ ($campaign->influencer_age_min ?? 0) . ' - ' . ($campaign->influencer_age_max ?? 0) }}
                                    </span>
                                </li>
                                <li><strong>{{ __('messages.gender') }}:</strong>
                                    <span x-text="translateGender(campaign?.influencer_gender)">
                                        {{ $campaign->influencer_gender ?? '' }}
                                    </span>
                                </li>
                                <li><strong>{{ __('messages.category') }}:</strong>
                                    <span x-text="campaign?.influencer_category">
                                        {{ $campaign->influencer_category ?? '' }}
                                    </span>
                                </li>
                                <li x-show="campaign?.influencer_location">
                                    <strong>{{ __('messages.location') }}:</strong>
                                    <span x-text="campaign?.influencer_location">
                                        {{ $campaign->influencer_location ?? '' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __('messages.audience_criteria') }}</h6>
                            <ul class="list-unstyled">
                                <li><strong>{{ __('messages.age_range') }}:</strong>
                                    <span x-text="`${campaign?.audience_age_min} - ${campaign?.audience_age_max}`">
                                        {{ ($campaign->audience_age_min ?? 0) . ' - ' . ($campaign->audience_age_max ?? 0) }}
                                    </span>
                                </li>
                                <li><strong>{{ __('messages.gender') }}:</strong>
                                    <span x-text="translateGender(campaign?.audience_gender)">
                                        {{ $campaign->audience_gender ?? '' }}
                                    </span>
                                </li>
                                <li x-show="campaign?.audience_location">
                                    <strong>{{ __('messages.location') }}:</strong>
                                    <span x-text="campaign?.audience_location">
                                        {{ $campaign->audience_location ?? '' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Company Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.company_information') }}</h5>
                </div>
                <div class="card-body text-center">
                    <div x-show="campaign?.company_logo" class="mb-3">
                        <img :src="'/storage/' + campaign?.company_logo"
                             :alt="campaign?.company_name"
                             class="img-fluid rounded"
                             style="max-height: 150px;">
                    </div>
                    <div x-show="!campaign?.company_logo" class="mb-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="fas fa-building fa-3x text-muted"></i>
                        </div>
                    </div>
                    <h5 x-text="campaign?.company_name" class="card-title">{{ $campaign->company_name ?? '' }}</h5>
                    <p x-text="campaign?.company_category" class="text-muted">{{ $campaign->company_category ?? '' }}</p>
                    <p x-text="campaign?.company_description" class="small">{{ $campaign->company_description ?? '' }}</p>
                    <div x-show="campaign?.company_website">
                        <a :href="campaign?.company_website" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>
                            {{ __('messages.visit_website') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Main Campaign Image --}}
            <div x-show="campaign?.company_main_image" class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.campaign_image') }}</h5>
                </div>
                <div class="card-body p-0">
                    <img :src="'/storage/' + campaign?.company_main_image"
                         :alt="campaign?.campaign_name"
                         class="img-fluid w-100">
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ localized_route('client.campaigns.edit', $campaign->id ?? 1) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            {{ __('messages.edit_campaign') }}
                        </a>
                        <button class="btn btn-outline-success" @click="toggleStatus()">
                            <i class="fas fa-play me-2" x-show="campaign?.status !== 'active'"></i>
                            <i class="fas fa-pause me-2" x-show="campaign?.status === 'active'"></i>
                            <span x-text="campaign?.status === 'active' ? translate('pause_campaign') : translate('activate_campaign')">
                                {{ __('messages.activate_campaign') }}
                            </span>
                        </button>
                        <button class="btn btn-outline-info">
                            <i class="fas fa-download me-2"></i>
                            {{ __('messages.export_data') }}
                        </button>
                        <button class="btn btn-outline-danger" @click="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>
                            {{ __('messages.delete_campaign') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Applications Section --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {{ __('messages.applications') }}
                <span class="badge bg-secondary ms-2" x-text="applications.length">{{ count($applications) }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div x-show="applications.length === 0" class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h6>{{ __('messages.no_applications_yet') }}</h6>
                <p class="text-muted">{{ __('messages.applications_will_appear_here') }}</p>
            </div>

            <div x-show="applications.length > 0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('messages.influencer') }}</th>
                                <th>{{ __('messages.followers') }}</th>
                                <th>{{ __('messages.engagement') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.applied_date') }}</th>
                                <th class="text-center">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="application in applications" :key="application.id">
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img :src="application.influencer_avatar || '/images/default-avatar.png'"
                                                 :alt="application.influencer_name"
                                                 class="rounded-circle me-3"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0" x-text="application.influencer_name"></h6>
                                                <small class="text-muted" x-text="'@' + application.influencer_username"></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td x-text="formatNumber(application.followers_count)">0</td>
                                    <td x-text="application.engagement_rate + '%'">0%</td>
                                    <td>
                                        <span class="badge"
                                              :class="{
                                                  'bg-warning': application.status === 'pending',
                                                  'bg-success': application.status === 'accepted',
                                                  'bg-danger': application.status === 'rejected'
                                              }"
                                              x-text="translateApplicationStatus(application.status)">
                                        </span>
                                    </td>
                                    <td x-text="formatDate(application.created_at)"></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary"
                                                    @click="viewApplication(application)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success"
                                                    x-show="application.status === 'pending'"
                                                    @click="updateApplicationStatus(application.id, 'accepted')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger"
                                                    x-show="application.status === 'pending'"
                                                    @click="updateApplicationStatus(application.id, 'rejected')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.confirm_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('messages.delete_campaign_confirmation') }}</p>
                    <div class="alert alert-warning">
                        <strong x-text="campaign?.campaign_name"></strong>
                        <br>
                        <small x-text="campaign?.company_name"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="button"
                            class="btn btn-danger"
                            @click="deleteCampaign()"
                            :disabled="deleting">
                        <span x-show="deleting" class="spinner-border spinner-border-sm me-2"></span>
                        {{ __('messages.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function campaignShow(campaignData, applicationsData, analyticsData) {
    return {
        campaign: campaignData,
        applications: applicationsData || [],
        analytics: analyticsData || {},
        deleting: false,

        init() {
            // Initialize tooltips and other components
            this.$nextTick(() => {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        },

        async toggleStatus() {
            if (!this.campaign) return;

            const newStatus = this.campaign.status === 'active' ? 'paused' : 'active';

            try {
                const response = await fetch(`/client/campaigns/${this.campaign.id}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.campaign.status = newStatus;
                    this.showAlert('success', data.message);
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error updating campaign status:', error);
                this.showAlert('error', this.translate('error_updating_status'));
            }
        },

        confirmDelete() {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        },

        async deleteCampaign() {
            if (!this.campaign) return;

            this.deleting = true;

            try {
                const response = await fetch(`/client/campaigns/${this.campaign.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showAlert('success', data.message);

                    // Redirect to campaigns list after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/client/campaigns';
                    }, 2000);
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error deleting campaign:', error);
                this.showAlert('error', this.translate('error_deleting_campaign'));
            } finally {
                this.deleting = false;
            }
        },

        async updateApplicationStatus(applicationId, status) {
            try {
                const response = await fetch(`/client/campaigns/applications/${applicationId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status })
                });

                const data = await response.json();

                if (data.success) {
                    // Update application status in local data
                    const application = this.applications.find(app => app.id === applicationId);
                    if (application) {
                        application.status = status;
                    }
                    this.showAlert('success', data.message);
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error updating application status:', error);
                this.showAlert('error', this.translate('error_updating_application'));
            }
        },

        viewApplication(application) {
            // This could open a modal or navigate to application details
            console.log('View application:', application);
        },

        // Utility functions
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString(this.$store.language.current, {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },

        formatNumber(number) {
            if (!number) return '0';
            return new Intl.NumberFormat(this.$store.language.current).format(number);
        },

        translateStatus(status) {
            const statusMap = {
                'draft': this.translate('draft'),
                'active': this.translate('active'),
                'paused': this.translate('paused'),
                'completed': this.translate('completed'),
                'cancelled': this.translate('cancelled')
            };
            return statusMap[status] || status;
        },

        translateGender(gender) {
            const genderMap = {
                'male': this.translate('male'),
                'female': this.translate('female'),
                'any': this.translate('any')
            };
            return genderMap[gender] || gender;
        },

        translateApplicationStatus(status) {
            const statusMap = {
                'pending': this.translate('pending'),
                'accepted': this.translate('accepted'),
                'rejected': this.translate('rejected')
            };
            return statusMap[status] || status;
        },

        translate(key) {
            return this.$store.language.translate(key);
        },

        showAlert(type, message) {
            // Create and show alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    };
}
</script>
@endsection