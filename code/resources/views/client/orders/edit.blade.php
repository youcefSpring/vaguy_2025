{{-- Order Edit Form --}}
@extends('layouts.dashboard')

@section('title', __('messages.edit_order'))

@section('page-title', __('messages.edit_order'))

@section('content')
<div x-data="orderEdit(@json($order ?? null))" x-init="init()">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ localized_route('client.orders.index') }}">{{ __('messages.orders') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ localized_route('client.orders.show', $order->id ?? 1) }}" x-text="order?.order_number">
                            {{ $order->order_number ?? __('messages.order') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ localized_route('client.orders.show', $order->id ?? 1) }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>
                {{ __('messages.cancel') }}
            </a>
        </div>
    </div>

    {{-- Edit Restrictions Notice --}}
    <div x-show="order?.status !== 'pending'" class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ __('messages.order_edit_restrictions') }}
    </div>

    {{-- Order Edit Form --}}
    <form @submit.prevent="updateOrder()" x-show="order?.status === 'pending'">
        <div class="row">
            <div class="col-lg-8">
                {{-- Service Information --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.service_information') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- Service Title --}}
                        <div class="mb-3">
                            <label for="service_title" class="form-label">
                                {{ __('messages.service_title') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="service_title"
                                   x-model="formData.service_title"
                                   :class="{ 'is-invalid': errors.service_title }"
                                   maxlength="255"
                                   required>
                            <div class="invalid-feedback" x-show="errors.service_title" x-text="errors.service_title"></div>
                        </div>

                        {{-- Service Description --}}
                        <div class="mb-3">
                            <label for="service_description" class="form-label">
                                {{ __('messages.service_description') }} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control"
                                      id="service_description"
                                      rows="4"
                                      x-model="formData.service_description"
                                      :class="{ 'is-invalid': errors.service_description }"
                                      maxlength="1000"
                                      required></textarea>
                            <div class="form-text">
                                <span x-text="formData.service_description?.length || 0">0</span>/1000 {{ __('messages.characters') }}
                            </div>
                            <div class="invalid-feedback" x-show="errors.service_description" x-text="errors.service_description"></div>
                        </div>

                        {{-- Budget Range --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="budget_min" class="form-label">
                                    {{ __('messages.minimum_budget') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number"
                                           class="form-control"
                                           id="budget_min"
                                           x-model="formData.budget_min"
                                           :class="{ 'is-invalid': errors.budget_min }"
                                           min="0"
                                           step="0.01"
                                           required>
                                    <div class="invalid-feedback" x-show="errors.budget_min" x-text="errors.budget_min"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="budget_max" class="form-label">
                                    {{ __('messages.maximum_budget') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number"
                                           class="form-control"
                                           id="budget_max"
                                           x-model="formData.budget_max"
                                           :class="{ 'is-invalid': errors.budget_max }"
                                           min="0"
                                           step="0.01"
                                           required>
                                    <div class="invalid-feedback" x-show="errors.budget_max" x-text="errors.budget_max"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Deadline --}}
                        <div class="mb-3">
                            <label for="deadline" class="form-label">
                                {{ __('messages.deadline') }} <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control"
                                   id="deadline"
                                   x-model="formData.deadline"
                                   :class="{ 'is-invalid': errors.deadline }"
                                   :min="new Date().toISOString().split('T')[0]"
                                   required>
                            <div class="invalid-feedback" x-show="errors.deadline" x-text="errors.deadline"></div>
                        </div>

                        {{-- Requirements --}}
                        <div class="mb-3">
                            <label for="requirements" class="form-label">{{ __('messages.additional_requirements') }}</label>
                            <textarea class="form-control"
                                      id="requirements"
                                      rows="3"
                                      x-model="formData.requirements"
                                      :class="{ 'is-invalid': errors.requirements }"
                                      maxlength="2000"></textarea>
                            <div class="form-text">
                                <span x-text="formData.requirements?.length || 0">0</span>/2000 {{ __('messages.characters') }}
                            </div>
                            <div class="invalid-feedback" x-show="errors.requirements" x-text="errors.requirements"></div>
                        </div>

                        {{-- Order Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                {{ __('messages.order_status') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select"
                                    id="status"
                                    x-model="formData.status"
                                    :class="{ 'is-invalid': errors.status }"
                                    required>
                                <option value="pending">{{ __('messages.pending') }}</option>
                                <option value="cancelled">{{ __('messages.cancelled') }}</option>
                            </select>
                            <div class="form-text">{{ __('messages.status_change_info') }}</div>
                            <div class="invalid-feedback" x-show="errors.status" x-text="errors.status"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Current Order Info --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.current_order_info') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>{{ __('messages.order_number') }}:</strong>
                            <div class="text-muted" x-text="order?.order_number">{{ $order->order_number ?? '' }}</div>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('messages.service_type') }}:</strong>
                            <div class="text-muted">
                                <span class="badge bg-primary" x-text="translateServiceType(order?.service_type)">
                                    {{ $order->service_type ?? '' }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('messages.created_date') }}:</strong>
                            <div class="text-muted" x-text="formatDate(order?.created_at)">{{ $order->created_at ?? '' }}</div>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('messages.current_status') }}:</strong>
                            <div class="text-muted">
                                <span class="badge"
                                      :class="{
                                          'bg-warning': order?.status === 'pending',
                                          'bg-info': order?.status === 'in_progress',
                                          'bg-success': order?.status === 'completed',
                                          'bg-danger': order?.status === 'cancelled'
                                      }"
                                      x-text="translateStatus(order?.status)">
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('messages.proposals_received') }}:</strong>
                            <div class="text-muted">
                                <span class="badge bg-secondary" x-text="(order?.proposals_count || 0) + ' proposals'">
                                    0 {{ __('messages.proposals') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Influencer Criteria (Read-only) --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.influencer_criteria') }}</h5>
                        <small class="text-muted">{{ __('messages.criteria_cannot_be_changed') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>{{ __('messages.follower_range') }}:</strong>
                            <div class="text-muted">
                                <span x-text="formatNumber(criteria?.follower_count_min || 0) + '+'"></span>
                                <span x-show="criteria?.follower_count_max"
                                      x-text="' - ' + formatNumber(criteria?.follower_count_max)"></span>
                            </div>
                        </div>
                        <div class="mb-3" x-show="criteria?.engagement_rate_min">
                            <strong>{{ __('messages.minimum_engagement') }}:</strong>
                            <div class="text-muted" x-text="criteria?.engagement_rate_min + '%'"></div>
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

                {{-- Current Attachments (Read-only) --}}
                <div x-show="attachments.length > 0" class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.current_attachments') }}</h5>
                        <small class="text-muted">{{ __('messages.attachments_cannot_be_changed') }}</small>
                    </div>
                    <div class="card-body">
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
                </div>

                {{-- Action Buttons --}}
                <div class="card" x-show="order?.status === 'pending'">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit"
                                    class="btn btn-primary"
                                    :disabled="loading">
                                <span x-show="loading" class="spinner-border spinner-border-sm me-2"></span>
                                <i x-show="!loading" class="fas fa-save me-2"></i>
                                {{ __('messages.update_order') }}
                            </button>
                            <a href="{{ localized_route('client.orders.show', $order->id ?? 1) }}" class="btn btn-outline-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Read-only Notice --}}
                <div class="card" x-show="order?.status !== 'pending'">
                    <div class="card-body text-center">
                        <i class="fas fa-lock fa-2x text-muted mb-3"></i>
                        <h6>{{ __('messages.order_locked') }}</h6>
                        <p class="text-muted small">{{ __('messages.order_locked_description') }}</p>
                        <a href="{{ localized_route('client.orders.show', $order->id ?? 1) }}" class="btn btn-outline-primary">
                            {{ __('messages.view_order_details') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function orderEdit(orderData) {
    return {
        order: orderData,
        loading: false,
        errors: {},
        criteria: null,
        attachments: [],

        // Form data
        formData: {
            service_title: orderData?.service_title || '',
            service_description: orderData?.service_description || '',
            budget_min: orderData?.budget_min || '',
            budget_max: orderData?.budget_max || '',
            deadline: orderData?.deadline || '',
            requirements: orderData?.requirements || '',
            status: orderData?.status || 'pending'
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

            // Ensure deadline is properly formatted for date input
            if (this.formData.deadline) {
                this.formData.deadline = this.formData.deadline.split(' ')[0]; // Remove time part if present
            }
        },

        async updateOrder() {
            this.loading = true;
            this.errors = {};

            try {
                const response = await fetch(`/client/orders/${this.order.id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (data.success) {
                    this.showAlert('success', data.message);

                    // Redirect to order details after 2 seconds
                    setTimeout(() => {
                        window.location.href = `/client/orders/${this.order.id}`;
                    }, 2000);
                } else {
                    if (data.errors) {
                        this.errors = data.errors;
                    } else {
                        this.showAlert('error', data.message);
                    }
                }
            } catch (error) {
                console.error('Error updating order:', error);
                this.showAlert('error', this.translate('error_updating_order'));
            } finally {
                this.loading = false;
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

        translateStatus(status) {
            const statusMap = {
                'pending': this.translate('pending'),
                'in_progress': this.translate('in_progress'),
                'completed': this.translate('completed'),
                'cancelled': this.translate('cancelled')
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