{{-- Campaign Edit Form --}}
@extends('layouts.dashboard')

@section('title', __('messages.edit_campaign'))

@section('page-title', __('messages.edit_campaign'))

@section('content')
<div x-data="campaignEdit(@json($campaign ?? null))" x-init="init()">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ localized_route('client.campaigns.index') }}">{{ __('messages.campaigns') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ localized_route('client.campaigns.show', $campaign->id ?? 1) }}" x-text="campaign?.campaign_name">
                            {{ $campaign->campaign_name ?? __('messages.campaign') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ localized_route('client.campaigns.show', $campaign->id ?? 1) }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>
                {{ __('messages.cancel') }}
            </a>
        </div>
    </div>

    {{-- Campaign Edit Form --}}
    <form @submit.prevent="updateCampaign()" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8">
                {{-- Campaign Information --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.campaign_information') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- Campaign Name --}}
                        <div class="mb-3">
                            <label for="campaign_name" class="form-label">
                                {{ __('messages.campaign_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="campaign_name"
                                   x-model="formData.campaign_name"
                                   :class="{ 'is-invalid': errors.campaign_name }"
                                   required>
                            <div class="invalid-feedback" x-show="errors.campaign_name" x-text="errors.campaign_name"></div>
                        </div>

                        {{-- Campaign Objective --}}
                        <div class="mb-3">
                            <label for="campaign_objective" class="form-label">
                                {{ __('messages.campaign_objective') }} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control"
                                      id="campaign_objective"
                                      rows="3"
                                      x-model="formData.campaign_objective"
                                      :class="{ 'is-invalid': errors.campaign_objective }"
                                      maxlength="500"
                                      required></textarea>
                            <div class="form-text">
                                <span x-text="formData.campaign_objective?.length || 0">0</span>/500 {{ __('messages.characters') }}
                            </div>
                            <div class="invalid-feedback" x-show="errors.campaign_objective" x-text="errors.campaign_objective"></div>
                        </div>

                        {{-- Campaign Details --}}
                        <div class="mb-3">
                            <label for="campaign_details" class="form-label">
                                {{ __('messages.campaign_details') }} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control"
                                      id="campaign_details"
                                      rows="4"
                                      x-model="formData.campaign_details"
                                      :class="{ 'is-invalid': errors.campaign_details }"
                                      maxlength="1000"
                                      required></textarea>
                            <div class="form-text">
                                <span x-text="formData.campaign_details?.length || 0">0</span>/1000 {{ __('messages.characters') }}
                            </div>
                            <div class="invalid-feedback" x-show="errors.campaign_details" x-text="errors.campaign_details"></div>
                        </div>

                        {{-- What We Want --}}
                        <div class="mb-3">
                            <label for="campaign_want" class="form-label">
                                {{ __('messages.what_we_want') }} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control"
                                      id="campaign_want"
                                      rows="3"
                                      x-model="formData.campaign_want"
                                      :class="{ 'is-invalid': errors.campaign_want }"
                                      maxlength="500"
                                      required></textarea>
                            <div class="form-text">
                                <span x-text="formData.campaign_want?.length || 0">0</span>/500 {{ __('messages.characters') }}
                            </div>
                            <div class="invalid-feedback" x-show="errors.campaign_want" x-text="errors.campaign_want"></div>
                        </div>

                        {{-- Platform and Dates Row --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="target_platform" class="form-label">
                                    {{ __('messages.target_platform') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select"
                                        id="target_platform"
                                        x-model="formData.target_platform"
                                        :class="{ 'is-invalid': errors.target_platform }"
                                        required>
                                    <option value="">{{ __('messages.select_platform') }}</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="tiktok">TikTok</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="linkedin">LinkedIn</option>
                                </select>
                                <div class="invalid-feedback" x-show="errors.target_platform" x-text="errors.target_platform"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="campaign_start_date" class="form-label">
                                    {{ __('messages.start_date') }} <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       class="form-control"
                                       id="campaign_start_date"
                                       x-model="formData.campaign_start_date"
                                       :class="{ 'is-invalid': errors.campaign_start_date }"
                                       required>
                                <div class="invalid-feedback" x-show="errors.campaign_start_date" x-text="errors.campaign_start_date"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="campaign_end_date" class="form-label">
                                    {{ __('messages.end_date') }} <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       class="form-control"
                                       id="campaign_end_date"
                                       x-model="formData.campaign_end_date"
                                       :class="{ 'is-invalid': errors.campaign_end_date }"
                                       required>
                                <div class="invalid-feedback" x-show="errors.campaign_end_date" x-text="errors.campaign_end_date"></div>
                            </div>
                        </div>

                        {{-- Campaign Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                {{ __('messages.campaign_status') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select"
                                    id="status"
                                    x-model="formData.status"
                                    :class="{ 'is-invalid': errors.status }"
                                    required>
                                <option value="draft">{{ __('messages.draft') }}</option>
                                <option value="active">{{ __('messages.active') }}</option>
                                <option value="paused">{{ __('messages.paused') }}</option>
                                <option value="completed">{{ __('messages.completed') }}</option>
                                <option value="cancelled">{{ __('messages.cancelled') }}</option>
                            </select>
                            <div class="invalid-feedback" x-show="errors.status" x-text="errors.status"></div>
                        </div>
                    </div>
                </div>

                {{-- Guidelines --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.campaign_guidelines') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- Do This --}}
                        <div class="mb-3">
                            <label for="do_this" class="form-label">{{ __('messages.do_this') }}</label>
                            <textarea class="form-control"
                                      id="do_this"
                                      rows="3"
                                      x-model="formData.do_this"
                                      :class="{ 'is-invalid': errors.do_this }"
                                      maxlength="1000"
                                      placeholder="{{ __('messages.do_this_placeholder') }}"></textarea>
                            <div class="form-text">
                                <span x-text="formData.do_this?.length || 0">0</span>/1000 {{ __('messages.characters') }}
                            </div>
                            <div class="invalid-feedback" x-show="errors.do_this" x-text="errors.do_this"></div>
                        </div>

                        {{-- Don't Do This --}}
                        <div class="mb-3">
                            <label for="dont_do_this" class="form-label">{{ __('messages.dont_do_this') }}</label>
                            <textarea class="form-control"
                                      id="dont_do_this"
                                      rows="3"
                                      x-model="formData.dont_do_this"
                                      :class="{ 'is-invalid': errors.dont_do_this }"
                                      maxlength="1000"
                                      placeholder="{{ __('messages.dont_do_this_placeholder') }}"></textarea>
                            <div class="form-text">
                                <span x-text="formData.dont_do_this?.length || 0">0</span>/1000 {{ __('messages.characters') }}
                            </div>
                            <div class="invalid-feedback" x-show="errors.dont_do_this" x-text="errors.dont_do_this"></div>
                        </div>
                    </div>
                </div>

                {{-- Targeting Criteria --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.targeting_criteria') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Influencer Criteria --}}
                            <div class="col-md-6">
                                <h6 class="mb-3">{{ __('messages.influencer_criteria') }}</h6>

                                {{-- Influencer Age Range --}}
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label for="influencer_age_min" class="form-label">
                                            {{ __('messages.min_age') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               class="form-control"
                                               id="influencer_age_min"
                                               x-model="formData.influencer_age_min"
                                               :class="{ 'is-invalid': errors.influencer_age_min }"
                                               min="13"
                                               max="100"
                                               required>
                                        <div class="invalid-feedback" x-show="errors.influencer_age_min" x-text="errors.influencer_age_min"></div>
                                    </div>
                                    <div class="col-6">
                                        <label for="influencer_age_max" class="form-label">
                                            {{ __('messages.max_age') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               class="form-control"
                                               id="influencer_age_max"
                                               x-model="formData.influencer_age_max"
                                               :class="{ 'is-invalid': errors.influencer_age_max }"
                                               min="13"
                                               max="100"
                                               required>
                                        <div class="invalid-feedback" x-show="errors.influencer_age_max" x-text="errors.influencer_age_max"></div>
                                    </div>
                                </div>

                                {{-- Influencer Gender --}}
                                <div class="mb-3">
                                    <label for="influencer_gender" class="form-label">
                                        {{ __('messages.gender') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select"
                                            id="influencer_gender"
                                            x-model="formData.influencer_gender"
                                            :class="{ 'is-invalid': errors.influencer_gender }"
                                            required>
                                        <option value="">{{ __('messages.select_gender') }}</option>
                                        <option value="male">{{ __('messages.male') }}</option>
                                        <option value="female">{{ __('messages.female') }}</option>
                                        <option value="any">{{ __('messages.any') }}</option>
                                    </select>
                                    <div class="invalid-feedback" x-show="errors.influencer_gender" x-text="errors.influencer_gender"></div>
                                </div>

                                {{-- Influencer Category --}}
                                <div class="mb-3">
                                    <label for="influencer_category" class="form-label">
                                        {{ __('messages.category') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select"
                                            id="influencer_category"
                                            x-model="formData.influencer_category"
                                            :class="{ 'is-invalid': errors.influencer_category }"
                                            required>
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        <option value="lifestyle">{{ __('messages.lifestyle') }}</option>
                                        <option value="fashion">{{ __('messages.fashion') }}</option>
                                        <option value="beauty">{{ __('messages.beauty') }}</option>
                                        <option value="fitness">{{ __('messages.fitness') }}</option>
                                        <option value="food">{{ __('messages.food') }}</option>
                                        <option value="travel">{{ __('messages.travel') }}</option>
                                        <option value="technology">{{ __('messages.technology') }}</option>
                                        <option value="gaming">{{ __('messages.gaming') }}</option>
                                        <option value="music">{{ __('messages.music') }}</option>
                                        <option value="sports">{{ __('messages.sports') }}</option>
                                        <option value="business">{{ __('messages.business') }}</option>
                                        <option value="education">{{ __('messages.education') }}</option>
                                    </select>
                                    <div class="invalid-feedback" x-show="errors.influencer_category" x-text="errors.influencer_category"></div>
                                </div>

                                {{-- Influencer Location --}}
                                <div class="mb-3">
                                    <label for="influencer_location" class="form-label">{{ __('messages.location') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           id="influencer_location"
                                           x-model="formData.influencer_location"
                                           :class="{ 'is-invalid': errors.influencer_location }"
                                           placeholder="{{ __('messages.location_placeholder') }}">
                                    <div class="invalid-feedback" x-show="errors.influencer_location" x-text="errors.influencer_location"></div>
                                </div>
                            </div>

                            {{-- Audience Criteria --}}
                            <div class="col-md-6">
                                <h6 class="mb-3">{{ __('messages.audience_criteria') }}</h6>

                                {{-- Audience Age Range --}}
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label for="audience_age_min" class="form-label">
                                            {{ __('messages.min_age') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               class="form-control"
                                               id="audience_age_min"
                                               x-model="formData.audience_age_min"
                                               :class="{ 'is-invalid': errors.audience_age_min }"
                                               min="13"
                                               max="100"
                                               required>
                                        <div class="invalid-feedback" x-show="errors.audience_age_min" x-text="errors.audience_age_min"></div>
                                    </div>
                                    <div class="col-6">
                                        <label for="audience_age_max" class="form-label">
                                            {{ __('messages.max_age') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               class="form-control"
                                               id="audience_age_max"
                                               x-model="formData.audience_age_max"
                                               :class="{ 'is-invalid': errors.audience_age_max }"
                                               min="13"
                                               max="100"
                                               required>
                                        <div class="invalid-feedback" x-show="errors.audience_age_max" x-text="errors.audience_age_max"></div>
                                    </div>
                                </div>

                                {{-- Audience Gender --}}
                                <div class="mb-3">
                                    <label for="audience_gender" class="form-label">
                                        {{ __('messages.gender') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select"
                                            id="audience_gender"
                                            x-model="formData.audience_gender"
                                            :class="{ 'is-invalid': errors.audience_gender }"
                                            required>
                                        <option value="">{{ __('messages.select_gender') }}</option>
                                        <option value="male">{{ __('messages.male') }}</option>
                                        <option value="female">{{ __('messages.female') }}</option>
                                        <option value="any">{{ __('messages.any') }}</option>
                                    </select>
                                    <div class="invalid-feedback" x-show="errors.audience_gender" x-text="errors.audience_gender"></div>
                                </div>

                                {{-- Audience Location --}}
                                <div class="mb-3">
                                    <label for="audience_location" class="form-label">{{ __('messages.location') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           id="audience_location"
                                           x-model="formData.audience_location"
                                           :class="{ 'is-invalid': errors.audience_location }"
                                           placeholder="{{ __('messages.location_placeholder') }}">
                                    <div class="invalid-feedback" x-show="errors.audience_location" x-text="errors.audience_location"></div>
                                </div>
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
                    <div class="card-body">
                        {{-- Company Name --}}
                        <div class="mb-3">
                            <label for="company_name" class="form-label">
                                {{ __('messages.company_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="company_name"
                                   x-model="formData.company_name"
                                   :class="{ 'is-invalid': errors.company_name }"
                                   required>
                            <div class="invalid-feedback" x-show="errors.company_name" x-text="errors.company_name"></div>
                        </div>

                        {{-- Company Category --}}
                        <div class="mb-3">
                            <label for="company_category" class="form-label">
                                {{ __('messages.company_category') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="company_category"
                                   x-model="formData.company_category"
                                   :class="{ 'is-invalid': errors.company_category }"
                                   required>
                            <div class="invalid-feedback" x-show="errors.company_category" x-text="errors.company_category"></div>
                        </div>

                        {{-- Company Description --}}
                        <div class="mb-3">
                            <label for="company_description" class="form-label">
                                {{ __('messages.company_description') }} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control"
                                      id="company_description"
                                      rows="3"
                                      x-model="formData.company_description"
                                      :class="{ 'is-invalid': errors.company_description }"
                                      maxlength="500"
                                      required></textarea>
                            <div class="form-text">
                                <span x-text="formData.company_description?.length || 0">0</span>/500 {{ __('messages.characters') }}
                            </div>
                            <div class="invalid-feedback" x-show="errors.company_description" x-text="errors.company_description"></div>
                        </div>

                        {{-- Company Website --}}
                        <div class="mb-3">
                            <label for="company_website" class="form-label">{{ __('messages.company_website') }}</label>
                            <input type="url"
                                   class="form-control"
                                   id="company_website"
                                   x-model="formData.company_website"
                                   :class="{ 'is-invalid': errors.company_website }"
                                   placeholder="https://example.com">
                            <div class="invalid-feedback" x-show="errors.company_website" x-text="errors.company_website"></div>
                        </div>
                    </div>
                </div>

                {{-- Current Images --}}
                <div class="card mb-4" x-show="campaign?.company_logo || campaign?.company_main_image">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.current_images') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- Current Logo --}}
                        <div x-show="campaign?.company_logo" class="mb-3">
                            <label class="form-label">{{ __('messages.current_logo') }}</label>
                            <div class="text-center">
                                <img :src="'/storage/' + campaign?.company_logo"
                                     :alt="campaign?.company_name"
                                     class="img-fluid rounded"
                                     style="max-height: 100px;">
                            </div>
                        </div>

                        {{-- Current Main Image --}}
                        <div x-show="campaign?.company_main_image" class="mb-3">
                            <label class="form-label">{{ __('messages.current_main_image') }}</label>
                            <div class="text-center">
                                <img :src="'/storage/' + campaign?.company_main_image"
                                     :alt="campaign?.campaign_name"
                                     class="img-fluid rounded"
                                     style="max-height: 150px;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Update Images --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.update_images') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- New Company Logo --}}
                        <div class="mb-3">
                            <label for="company_logo" class="form-label">{{ __('messages.new_company_logo') }}</label>
                            <input type="file"
                                   class="form-control"
                                   id="company_logo"
                                   @change="handleFileChange('company_logo', $event)"
                                   :class="{ 'is-invalid': errors.company_logo }"
                                   accept="image/*">
                            <div class="form-text">{{ __('messages.logo_requirements') }}</div>
                            <div class="invalid-feedback" x-show="errors.company_logo" x-text="errors.company_logo"></div>
                        </div>

                        {{-- New Main Image --}}
                        <div class="mb-3">
                            <label for="company_main_image" class="form-label">{{ __('messages.new_main_image') }}</label>
                            <input type="file"
                                   class="form-control"
                                   id="company_main_image"
                                   @change="handleFileChange('company_main_image', $event)"
                                   :class="{ 'is-invalid': errors.company_main_image }"
                                   accept="image/*">
                            <div class="form-text">{{ __('messages.main_image_requirements') }}</div>
                            <div class="invalid-feedback" x-show="errors.company_main_image" x-text="errors.company_main_image"></div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit"
                                    class="btn btn-primary"
                                    :disabled="loading">
                                <span x-show="loading" class="spinner-border spinner-border-sm me-2"></span>
                                <i x-show="!loading" class="fas fa-save me-2"></i>
                                {{ __('messages.update_campaign') }}
                            </button>
                            <a href="{{ localized_route('client.campaigns.show', $campaign->id ?? 1) }}" class="btn btn-outline-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function campaignEdit(campaignData) {
    return {
        campaign: campaignData,
        loading: false,
        errors: {},

        // Form data
        formData: {
            campaign_name: campaignData?.campaign_name || '',
            campaign_objective: campaignData?.campaign_objective || '',
            campaign_details: campaignData?.campaign_details || '',
            campaign_want: campaignData?.campaign_want || '',
            target_platform: campaignData?.target_platform || '',
            campaign_start_date: campaignData?.campaign_start_date || '',
            campaign_end_date: campaignData?.campaign_end_date || '',
            do_this: campaignData?.do_this || '',
            dont_do_this: campaignData?.dont_do_this || '',
            status: campaignData?.status || 'draft',

            company_name: campaignData?.company_name || '',
            company_category: campaignData?.company_category || '',
            company_description: campaignData?.company_description || '',
            company_website: campaignData?.company_website || '',

            influencer_age_min: campaignData?.influencer_age_min || 18,
            influencer_age_max: campaignData?.influencer_age_max || 35,
            influencer_gender: campaignData?.influencer_gender || '',
            influencer_category: campaignData?.influencer_category || '',
            influencer_location: campaignData?.influencer_location || '',

            audience_age_min: campaignData?.audience_age_min || 18,
            audience_age_max: campaignData?.audience_age_max || 35,
            audience_gender: campaignData?.audience_gender || '',
            audience_location: campaignData?.audience_location || ''
        },

        // File inputs
        files: {
            company_logo: null,
            company_main_image: null
        },

        init() {
            // Initialize any components if needed
        },

        handleFileChange(fieldName, event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    this.errors[fieldName] = this.translate('file_too_large');
                    event.target.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    this.errors[fieldName] = this.translate('invalid_file_type');
                    event.target.value = '';
                    return;
                }

                this.files[fieldName] = file;

                // Clear any previous errors
                if (this.errors[fieldName]) {
                    delete this.errors[fieldName];
                }
            } else {
                this.files[fieldName] = null;
            }
        },

        async updateCampaign() {
            this.loading = true;
            this.errors = {};

            try {
                // Create FormData for file uploads
                const formData = new FormData();

                // Add all form fields
                Object.keys(this.formData).forEach(key => {
                    if (this.formData[key] !== null && this.formData[key] !== '') {
                        formData.append(key, this.formData[key]);
                    }
                });

                // Add files if selected
                if (this.files.company_logo) {
                    formData.append('company_logo', this.files.company_logo);
                }
                if (this.files.company_main_image) {
                    formData.append('company_main_image', this.files.company_main_image);
                }

                // Add method override for PUT request
                formData.append('_method', 'PUT');

                const response = await fetch(`/client/campaigns/${this.campaign.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.showAlert('success', data.message);

                    // Redirect to campaign details after 2 seconds
                    setTimeout(() => {
                        window.location.href = `/client/campaigns/${this.campaign.id}`;
                    }, 2000);
                } else {
                    if (data.errors) {
                        this.errors = data.errors;
                    } else {
                        this.showAlert('error', data.message);
                    }
                }
            } catch (error) {
                console.error('Error updating campaign:', error);
                this.showAlert('error', this.translate('error_updating_campaign'));
            } finally {
                this.loading = false;
            }
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