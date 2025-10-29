{{-- Order Details View --}}
@extends('layouts.dashboard')

@section('title', $order->order_number ?? __('messages.order_details'))

@section('page-title', $order->order_number ?? __('messages.order_details'))

@section('content')
<div x-data="orderShow(@json($order ?? null), @json($proposals ?? []), @json($timeline ?? []))" x-init="init()">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ localized_route('client.orders.index') }}">{{ __('messages.orders') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" x-text="order?.order_number">
                        {{ $order->order_number ?? __('messages.order_details') }}
                    </li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ localized_route('client.orders.edit', $order->id ?? 1) }}"
               x-show="order?.status === 'pending'"
               class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>
                {{ __('messages.edit') }}
            </a>
            <button class="btn btn-outline-danger"
                    x-show="order?.status === 'pending'"
                    @click="confirmDelete()">
                <i class="fas fa-trash me-2"></i>
                {{ __('messages.delete') }}
            </button>
        </div>
    </div>

    {{-- Order Status Banner --}}
    <div class="alert"
         :class="{
             'alert-warning': order?.status === 'pending',
             'alert-info': order?.status === 'in_progress',
             'alert-success': order?.status === 'completed',
             'alert-danger': order?.status === 'cancelled'
         }"
         role="alert">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="alert-heading mb-1">
                    <i :class="{
                           'fas fa-clock': order?.status === 'pending',
                           'fas fa-spinner fa-spin': order?.status === 'in_progress',
                           'fas fa-check-circle': order?.status === 'completed',
                           'fas fa-times-circle': order?.status === 'cancelled'
                       }" class="me-2"></i>
                    <span x-text="translateStatus(order?.status)"></span>
                </h6>
                <p class="mb-0" x-text="getStatusDescription(order?.status)"></p>
            </div>
            <div x-show="order?.status === 'in_progress'">
                <button class="btn btn-success btn-sm" @click="showCompletionModal()">
                    <i class="fas fa-flag-checkered me-2"></i>
                    {{ __('messages.mark_completed') }}
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Order Information --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.order_information') }}</h5>
                </div>
                <div class="card-body">
                    {{-- Service Details --}}
                    <div class="mb-4">
                        <h6>{{ __('messages.service_details') }}</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>{{ __('messages.service_type') }}:</strong>
                                <div class="text-muted">
                                    <span class="badge bg-primary" x-text="translateServiceType(order?.service_type)">
                                        {{ $order->service_type ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>{{ __('messages.budget_range') }}:</strong>
                                <div class="text-muted" x-text="'$' + formatNumber(order?.budget_min) + ' - $' + formatNumber(order?.budget_max)">
                                    ${{ $order->budget_min ?? 0 }} - ${{ $order->budget_max ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('messages.service_title') }}:</strong>
                            <p x-text="order?.service_title" class="text-muted mb-2">
                                {{ $order->service_title ?? '' }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('messages.service_description') }}:</strong>
                            <p x-text="order?.service_description" class="text-muted mb-2">
                                {{ $order->service_description ?? '' }}
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>{{ __('messages.deadline') }}:</strong>
                                <div class="text-muted" x-text="formatDate(order?.deadline)">
                                    {{ $order->deadline ?? '' }}
                                </div>
                                <div :class="{
                                        'text-danger': isOverdue(order?.deadline),
                                        'text-warning': isUpcoming(order?.deadline)
                                     }">
                                    <small x-text="getDeadlineStatus(order?.deadline)"></small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>{{ __('messages.created_date') }}:</strong>
                                <div class="text-muted" x-text="formatDate(order?.created_at)">
                                    {{ $order->created_at ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Requirements --}}
                    <div x-show="order?.requirements" class="mb-4">
                        <h6>{{ __('messages.additional_requirements') }}</h6>
                        <div class="alert alert-light">
                            <p x-text="order?.requirements" class="mb-0">{{ $order->requirements ?? '' }}</p>
                        </div>
                    </div>

                    {{-- Attachments --}}
                    <div x-show="attachments.length > 0" class="mb-4">
                        <h6>{{ __('messages.attachments') }}</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <template x-for="attachment in attachments" :key="attachment.path">
                                <a :href="'/storage/' + attachment.path"
                                   target="_blank"
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-file me-2"></i>
                                    <span x-text="attachment.original_name"></span>
                                </a>
                            </template>
                        </div>
                    </div>

                    {{-- Influencer Criteria --}}
                    <div class="mb-4">
                        <h6>{{ __('messages.influencer_criteria') }}</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>{{ __('messages.follower_range') }}:</strong>
                                <div class="text-muted">
                                    <span x-text="formatNumber(criteria?.follower_count_min || 0) + '+'"></span>
                                    <span x-show="criteria?.follower_count_max"
                                          x-text="' - ' + formatNumber(criteria?.follower_count_max)"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" x-show="criteria?.engagement_rate_min">
                                <strong>{{ __('messages.minimum_engagement') }}:</strong>
                                <div class="text-muted" x-text="criteria?.engagement_rate_min + '%'"></div>
                            </div>
                        </div>
                        <div class="mb-3" x-show="criteria?.categories?.length > 0">
                            <strong>{{ __('messages.categories') }}:</strong>
                            <div class="mt-1">
                                <template x-for="category in criteria?.categories" :key="category">
                                    <span class="badge bg-light text-dark me-1" x-text="translateCategory(category)"></span>
                                </template>
                            </div>
                        </div>
                        <div class="mb-3" x-show="criteria?.gender && criteria?.gender !== 'any'">
                            <strong>{{ __('messages.gender_preference') }}:</strong>
                            <span class="text-muted" x-text="translateGender(criteria?.gender)"></span>
                        </div>
                        <div class="mb-3" x-show="criteria?.locations?.length > 0">
                            <strong>{{ __('messages.preferred_locations') }}:</strong>
                            <div class="mt-1">
                                <template x-for="location in criteria?.locations" :key="location">
                                    <span class="badge bg-info text-white me-1" x-text="location"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Proposals Section --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        {{ __('messages.proposals') }}
                        <span class="badge bg-secondary ms-2" x-text="proposals.length">{{ count($proposals) }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div x-show="proposals.length === 0" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h6>{{ __('messages.no_proposals_yet') }}</h6>
                        <p class="text-muted">{{ __('messages.proposals_will_appear_here') }}</p>
                    </div>

                    <div x-show="proposals.length > 0">
                        <template x-for="proposal in proposals" :key="proposal.id">
                            <div class="border rounded p-3 mb-3"
                                 :class="{
                                     'border-success bg-light': proposal.status === 'accepted',
                                     'border-danger': proposal.status === 'rejected'
                                 }">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <img :src="proposal.influencer_avatar || '/images/default-avatar.png'"
                                             :alt="proposal.influencer_name"
                                             class="rounded-circle me-3"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1" x-text="proposal.influencer_name"></h6>
                                            <small class="text-muted" x-text="'@' + proposal.influencer_username"></small>
                                            <div class="d-flex align-items-center mt-1">
                                                <small class="text-muted me-3">
                                                    <i class="fas fa-users me-1"></i>
                                                    <span x-text="formatNumber(proposal.followers_count)"></span>
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-chart-line me-1"></i>
                                                    <span x-text="proposal.engagement_rate + '%'"></span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="h5 mb-0 text-success" x-text="'$' + formatNumber(proposal.proposal_amount)"></div>
                                        <small class="text-muted" x-text="proposal.delivery_time + ' ' + translate('days')"></small>
                                    </div>
                                </div>

                                <p x-text="proposal.proposal_text" class="mb-3"></p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge"
                                              :class="{
                                                  'bg-warning': proposal.status === 'pending',
                                                  'bg-success': proposal.status === 'accepted',
                                                  'bg-danger': proposal.status === 'rejected'
                                              }"
                                              x-text="translateProposalStatus(proposal.status)">
                                        </span>
                                        <small class="text-muted ms-2" x-text="formatDate(proposal.created_at)"></small>
                                    </div>
                                    <div x-show="proposal.status === 'pending' && order?.status === 'pending'">
                                        <button class="btn btn-success btn-sm me-2"
                                                @click="acceptProposal(proposal.id)">
                                            <i class="fas fa-check me-1"></i>
                                            {{ __('messages.accept') }}
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm"
                                                @click="rejectProposal(proposal.id)">
                                            <i class="fas fa-times me-1"></i>
                                            {{ __('messages.reject') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Order Timeline --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.order_timeline') }}</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <template x-for="event in timeline" :key="event.id">
                            <div class="timeline-item">
                                <div class="timeline-marker"
                                     :class="{
                                         'bg-primary': event.type === 'created',
                                         'bg-info': event.type === 'proposal',
                                         'bg-success': event.type === 'accepted',
                                         'bg-warning': event.type === 'in_progress',
                                         'bg-danger': event.type === 'cancelled'
                                     }">
                                    <i :class="{
                                           'fas fa-plus': event.type === 'created',
                                           'fas fa-user-plus': event.type === 'proposal',
                                           'fas fa-check': event.type === 'accepted',
                                           'fas fa-play': event.type === 'in_progress',
                                           'fas fa-times': event.type === 'cancelled'
                                       }"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 x-text="event.event" class="mb-1"></h6>
                                    <p x-text="event.description" class="text-muted mb-1"></p>
                                    <small x-text="formatDate(event.created_at)" class="text-muted"></small>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ localized_route('client.orders.edit', $order->id ?? 1) }}"
                           x-show="order?.status === 'pending'"
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            {{ __('messages.edit_order') }}
                        </a>
                        <button class="btn btn-outline-success"
                                x-show="order?.status === 'in_progress'"
                                @click="showCompletionModal()">
                            <i class="fas fa-flag-checkered me-2"></i>
                            {{ __('messages.mark_completed') }}
                        </button>
                        <button class="btn btn-outline-info">
                            <i class="fas fa-download me-2"></i>
                            {{ __('messages.download_summary') }}
                        </button>
                        <button class="btn btn-outline-warning"
                                x-show="order?.status === 'pending'"
                                @click="toggleStatus()">
                            <i class="fas fa-pause me-2"></i>
                            {{ __('messages.cancel_order') }}
                        </button>
                        <button class="btn btn-outline-danger"
                                x-show="order?.status === 'pending'"
                                @click="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>
                            {{ __('messages.delete_order') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Completion Modal --}}
    <div class="modal fade" id="completionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.complete_order') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('messages.complete_order_confirmation') }}</p>

                    {{-- Rating --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.rating') }} <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <template x-for="star in 5" :key="star">
                                <button type="button"
                                        class="btn btn-outline-warning btn-sm"
                                        :class="{ 'btn-warning': completionData.rating >= star }"
                                        @click="completionData.rating = star">
                                    <i class="fas fa-star"></i>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Review --}}
                    <div class="mb-3">
                        <label for="review" class="form-label">{{ __('messages.review') }}</label>
                        <textarea class="form-control"
                                  id="review"
                                  rows="3"
                                  x-model="completionData.review"
                                  maxlength="1000"
                                  placeholder="{{ __('messages.review_placeholder') }}"></textarea>
                        <div class="form-text">
                            <span x-text="completionData.review?.length || 0">0</span>/1000 {{ __('messages.characters') }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="button"
                            class="btn btn-success"
                            @click="completeOrder()"
                            :disabled="completing || !completionData.rating">
                        <span x-show="completing" class="spinner-border spinner-border-sm me-2"></span>
                        {{ __('messages.complete_order') }}
                    </button>
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
                    <p>{{ __('messages.delete_order_confirmation') }}</p>
                    <div class="alert alert-warning">
                        <strong x-text="order?.order_number"></strong>
                        <br>
                        <small x-text="order?.service_title"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="button"
                            class="btn btn-danger"
                            @click="deleteOrder()"
                            :disabled="deleting">
                        <span x-show="deleting" class="spinner-border spinner-border-sm me-2"></span>
                        {{ __('messages.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 60px;
}

.timeline-marker {
    position: absolute;
    left: 8px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    border-left: 4px solid #007bff;
}
</style>

<script>
function orderShow(orderData, proposalsData, timelineData) {
    return {
        order: orderData,
        proposals: proposalsData || [],
        timeline: timelineData || [],
        criteria: null,
        attachments: [],
        deleting: false,
        completing: false,
        completionData: {
            rating: 0,
            review: ''
        },

        init() {
            // Parse criteria and attachments if they exist
            if (this.order?.influencer_criteria) {
                try {
                    this.criteria = typeof this.order.influencer_criteria === 'string'
                        ? JSON.parse(this.order.influencer_criteria)
                        : this.order.influencer_criteria;
                } catch (e) {
                    this.criteria = {};
                }
            }

            if (this.order?.attachments) {
                try {
                    this.attachments = typeof this.order.attachments === 'string'
                        ? JSON.parse(this.order.attachments)
                        : this.order.attachments;
                } catch (e) {
                    this.attachments = [];
                }
            }

            // Initialize tooltips
            this.$nextTick(() => {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        },

        async acceptProposal(proposalId) {
            try {
                const response = await fetch(`/client/orders/${this.order.id}/proposals/${proposalId}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showAlert('success', data.message);
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error accepting proposal:', error);
                this.showAlert('error', this.translate('error_accepting_proposal'));
            }
        },

        showCompletionModal() {
            const modal = new bootstrap.Modal(document.getElementById('completionModal'));
            modal.show();
        },

        async completeOrder() {
            this.completing = true;

            try {
                const response = await fetch(`/client/orders/${this.order.id}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.completionData)
                });

                const data = await response.json();

                if (data.success) {
                    this.showAlert('success', data.message);

                    // Close modal and reload page
                    const modal = bootstrap.Modal.getInstance(document.getElementById('completionModal'));
                    modal.hide();

                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error completing order:', error);
                this.showAlert('error', this.translate('error_completing_order'));
            } finally {
                this.completing = false;
            }
        },

        confirmDelete() {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        },

        async deleteOrder() {
            if (!this.order) return;

            this.deleting = true;

            try {
                const response = await fetch(`/client/orders/${this.order.id}`, {
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

                    // Redirect to orders list after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/client/orders';
                    }, 2000);
                } else {
                    this.showAlert('error', data.message);
                }
            } catch (error) {
                console.error('Error deleting order:', error);
                this.showAlert('error', this.translate('error_deleting_order'));
            } finally {
                this.deleting = false;
            }
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

        isOverdue(deadline) {
            return new Date(deadline) < new Date();
        },

        isUpcoming(deadline) {
            const now = new Date();
            const deadlineDate = new Date(deadline);
            const diffDays = Math.ceil((deadlineDate - now) / (1000 * 60 * 60 * 24));
            return diffDays <= 3 && diffDays > 0;
        },

        getDeadlineStatus(deadline) {
            if (this.isOverdue(deadline)) {
                return this.translate('overdue');
            } else if (this.isUpcoming(deadline)) {
                return this.translate('due_soon');
            }
            return '';
        },

        getStatusDescription(status) {
            const descriptions = {
                'pending': this.translate('order_awaiting_proposals'),
                'in_progress': this.translate('order_work_in_progress'),
                'completed': this.translate('order_successfully_completed'),
                'cancelled': this.translate('order_was_cancelled')
            };
            return descriptions[status] || '';
        },

        translateStatus(status) {
            const statusMap = {
                'pending': this.translate('pending'),
                'in_progress': this.translate('in_progress'),
                'completed': this.translate('completed'),
                'cancelled': this.translate('cancelled')
            };
            return statusMap[status] || status;
        },

        translateProposalStatus(status) {
            const statusMap = {
                'pending': this.translate('pending'),
                'accepted': this.translate('accepted'),
                'rejected': this.translate('rejected')
            };
            return statusMap[status] || status;
        },

        translateServiceType(type) {
            const typeMap = {
                'social_media_post': this.translate('social_media_post'),
                'product_review': this.translate('product_review'),
                'brand_collaboration': this.translate('brand_collaboration'),
                'video_content': this.translate('video_content'),
                'live_stream': this.translate('live_stream'),
                'story_mention': this.translate('story_mention'),
                'blog_post': this.translate('blog_post'),
                'email_marketing': this.translate('email_marketing'),
                'custom_content': this.translate('custom_content')
            };
            return typeMap[type] || type;
        },

        translateCategory(category) {
            const categoryMap = {
                'lifestyle': this.translate('lifestyle'),
                'fashion': this.translate('fashion'),
                'beauty': this.translate('beauty'),
                'fitness': this.translate('fitness'),
                'food': this.translate('food'),
                'travel': this.translate('travel'),
                'technology': this.translate('technology'),
                'gaming': this.translate('gaming'),
                'music': this.translate('music'),
                'sports': this.translate('sports'),
                'business': this.translate('business'),
                'education': this.translate('education')
            };
            return categoryMap[category] || category;
        },

        translateGender(gender) {
            const genderMap = {
                'male': this.translate('male'),
                'female': this.translate('female'),
                'any': this.translate('any')
            };
            return genderMap[gender] || gender;
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