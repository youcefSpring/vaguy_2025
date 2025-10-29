{{--
    Client Hiring Edit Page

    This page allows clients to edit existing hiring requests.
    It loads the existing data and provides a form to update the hiring details.

    Features:
    - Pre-populated form with existing data
    - Dynamic milestone management
    - File upload and management
    - Form validation with real-time feedback
    - Save as draft or publish
    - Responsive design
    - Multilingual support
--}}

@extends('layouts.dashboard')

@section('title', __('hirings.edit_hiring'))

@section('page-header')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ __('hirings.edit_hiring') }}</h1>
            <p class="text-gray-600 text-sm">{{ __('hirings.edit_hiring_desc') }} - #{{ $hiring->id }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ localized_route('client.hirings.show', $hiring->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('common.back') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<div x-data="hiringEditData()" x-init="init()">
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            {{-- Project Information Card --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('hirings.project_information') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Project Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('hirings.project_title') }}
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   :class="errors.title ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''"
                                   id="title"
                                   name="title"
                                   x-model="form.title"
                                   placeholder="{{ __('hirings.project_title_placeholder') }}"
                                   required>
                            <template x-if="errors.title">
                                <p class="mt-2 text-sm text-red-600" x-text="errors.title[0]"></p>
                            </template>
                        </div>

                        {{-- Project Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('hirings.project_description') }}
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      :class="errors.description ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      x-model="form.description"
                                      placeholder="{{ __('hirings.project_description_placeholder') }}"
                                      required></textarea>
                            <template x-if="errors.description">
                                <p class="mt-2 text-sm text-red-600" x-text="errors.description[0]"></p>
                            </template>
                        </div>

                        {{-- Project Category and Duration --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('hirings.project_category') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white"
                                            :class="errors.category ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''"
                                            id="category"
                                            name="category"
                                            x-model="form.category"
                                            required>
                                        <option value="">{{ __('hirings.select_category') }}</option>
                                        <option value="content_creation">🎬 {{ __('hirings.content_creation') }}</option>
                                        <option value="brand_promotion">📢 {{ __('hirings.brand_promotion') }}</option>
                                        <option value="product_review">⭐ {{ __('hirings.product_review') }}</option>
                                        <option value="event_coverage">📸 {{ __('hirings.event_coverage') }}</option>
                                        <option value="long_term_partnership">🤝 {{ __('hirings.long_term_partnership') }}</option>
                                        <option value="other">🔄 {{ __('hirings.other') }}</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <template x-if="errors.category">
                                    <p class="mt-2 text-sm text-red-600" x-text="errors.category[0]"></p>
                                </template>
                            </div>

                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('hirings.project_duration') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md appearance-none bg-white"
                                            :class="errors.duration ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''"
                                            id="duration"
                                            name="duration"
                                            x-model="form.duration"
                                            required>
                                        <option value="">{{ __('hirings.select_duration') }}</option>
                                        <option value="1_week">📅 {{ __('hirings.1_week') }}</option>
                                        <option value="2_weeks">📅 {{ __('hirings.2_weeks') }}</option>
                                        <option value="1_month">📅 {{ __('hirings.1_month') }}</option>
                                        <option value="2_months">📅 {{ __('hirings.2_months') }}</option>
                                        <option value="3_months">📅 {{ __('hirings.3_months') }}</option>
                                        <option value="6_months">📅 {{ __('hirings.6_months') }}</option>
                                        <option value="1_year">📅 {{ __('hirings.1_year') }}</option>
                                        <option value="ongoing">∞ {{ __('hirings.ongoing') }}</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <template x-if="errors.duration">
                                    <p class="mt-2 text-sm text-red-600" x-text="errors.duration[0]"></p>
                                </template>
                            </div>
                        </div>

                        {{-- Skills Required --}}
                        <div>
                            <label for="skills_required" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('hirings.skills_required') }}
                            </label>
                            <input type="text"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   id="skills_required"
                                   name="skills_required"
                                   x-model="form.skills_required"
                                   placeholder="{{ __('hirings.skills_placeholder') }}">
                            <p class="mt-1 text-sm text-gray-500">{{ __('hirings.skills_help') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Budget and Timeline Card --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        {{ __('hirings.budget_timeline') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Budget Range --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="budget_min" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('hirings.budget_range') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="flex rounded-md shadow-sm">
                                    <input type="number"
                                           class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           :class="errors.budget_min ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''"
                                           id="budget_min"
                                           name="budget_min"
                                           x-model="form.budget_min"
                                           placeholder="{{ __('hirings.min_budget') }}"
                                           min="0"
                                           step="100"
                                           required>
                                    <span class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">{{ __('hirings.to') }}</span>
                                    <input type="number"
                                           class="flex-1 min-w-0 block w-full px-3 py-2 border border-l-0 border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           :class="errors.budget_max ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''"
                                           name="budget_max"
                                           x-model="form.budget_max"
                                           placeholder="{{ __('hirings.max_budget') }}"
                                           min="0"
                                           step="100"
                                           required>
                                    <span class="inline-flex items-center px-3 py-2 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">DZD</span>
                                </div>
                                <template x-if="errors.budget_min || errors.budget_max">
                                    <div class="mt-2 text-sm text-red-600">
                                        <span x-show="errors.budget_min" x-text="errors.budget_min && errors.budget_min[0]"></span>
                                        <span x-show="errors.budget_max" x-text="errors.budget_max && errors.budget_max[0]"></span>
                                    </div>
                                </template>
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('hirings.start_date') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       :class="errors.start_date ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''"
                                       id="start_date"
                                       name="start_date"
                                       x-model="form.start_date"
                                       :min="new Date().toISOString().split('T')[0]"
                                       required>
                                <template x-if="errors.start_date">
                                    <p class="mt-2 text-sm text-red-600" x-text="errors.start_date[0]"></p>
                                </template>
                            </div>
                        </div>

                        {{-- Payment Terms --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                {{ __('hirings.payment_terms') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="relative">
                                    <input class="sr-only peer" type="radio" name="payment_terms" id="payment_upfront" value="upfront" x-model="form.payment_terms">
                                    <label class="flex items-center justify-center p-4 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500" for="payment_upfront">
                                        <div class="text-center">
                                            <div class="text-lg mb-1">💰</div>
                                            <div class="font-medium text-gray-900">{{ __('hirings.payment_upfront') }}</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="relative">
                                    <input class="sr-only peer" type="radio" name="payment_terms" id="payment_milestone" value="milestone" x-model="form.payment_terms">
                                    <label class="flex items-center justify-center p-4 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500" for="payment_milestone">
                                        <div class="text-center">
                                            <div class="text-lg mb-1">📊</div>
                                            <div class="font-medium text-gray-900">{{ __('hirings.payment_milestone') }}</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="relative">
                                    <input class="sr-only peer" type="radio" name="payment_terms" id="payment_completion" value="completion" x-model="form.payment_terms">
                                    <label class="flex items-center justify-center p-4 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-2 peer-checked:ring-blue-500 peer-checked:border-blue-500" for="payment_completion">
                                        <div class="text-center">
                                            <div class="text-lg mb-1">✅</div>
                                            <div class="font-medium text-gray-900">{{ __('hirings.payment_completion') }}</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Milestones Card --}}
            <div x-show="form.payment_terms === 'milestone'" class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            {{ __('hirings.project_milestones') }}
                        </h3>
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                @click="addMilestone()">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('hirings.add_milestone') }}
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <template x-for="(milestone, index) in form.milestones" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 mb-4 last:mb-0" x-show="!milestone.deleted">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-medium text-gray-900" x-text="`{{ __('hirings.milestone') }} ${index + 1}`"></h4>
                                <button type="button"
                                        class="inline-flex items-center px-2 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        @click="removeMilestone(index)"
                                        x-show="form.milestones.filter(m => !m.deleted).length > 1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label :for="`milestone_title_${index}`" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('hirings.milestone_title') }}
                                        </label>
                                        <input type="text"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               :id="`milestone_title_${index}`"
                                               :name="`milestones[${index}][title]`"
                                               x-model="milestone.title"
                                               placeholder="{{ __('hirings.milestone_title_placeholder') }}">
                                    </div>
                                    <div>
                                        <label :for="`milestone_amount_${index}`" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('hirings.amount') }}
                                        </label>
                                        <div class="flex rounded-md shadow-sm">
                                            <input type="number"
                                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                   :id="`milestone_amount_${index}`"
                                                   :name="`milestones[${index}][amount]`"
                                                   x-model="milestone.amount"
                                                   min="0"
                                                   step="100">
                                            <span class="inline-flex items-center px-3 py-2 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">DZD</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label :for="`milestone_due_date_${index}`" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('hirings.due_date') }}
                                        </label>
                                        <input type="date"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               :id="`milestone_due_date_${index}`"
                                               :name="`milestones[${index}][due_date]`"
                                               x-model="milestone.due_date">
                                    </div>
                                </div>
                                <div>
                                    <label :for="`milestone_description_${index}`" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('hirings.milestone_description') }}
                                    </label>
                                    <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                              :id="`milestone_description_${index}`"
                                              :name="`milestones[${index}][description]`"
                                              rows="2"
                                              x-model="milestone.description"
                                              placeholder="{{ __('hirings.milestone_description_placeholder') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="form.milestones.filter(m => !m.deleted).length === 0" class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <p class="mb-4">{{ __('hirings.no_milestones') }}</p>
                        <button type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                @click="addMilestone()">
                            {{ __('hirings.add_first_milestone') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Current Attachments --}}
            @if($hiring->attachments && count($hiring->attachments) > 0)
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('hirings.current_attachments') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($hiring->attachments as $index => $attachment)
                                <div x-data="{ deleted: false }" class="flex items-center bg-gray-50 rounded-lg p-3" x-show="!deleted">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $attachment['name'] ?? 'Document' }}</div>
                                        <div class="text-sm text-gray-500">{{ $attachment['size'] ?? 'Unknown size' }}</div>
                                    </div>
                                    <div class="flex gap-2 ml-3">
                                        <a href="{{ $attachment['url'] ?? '#' }}"
                                           class="inline-flex items-center px-2.5 py-1.5 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                           target="_blank">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                        <button type="button"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                @click="deleted = true; removeExistingAttachment({{ $index }})">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- New Attachments --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        {{ __('hirings.add_new_attachments') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label for="new_attachments" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('hirings.upload_files') }}
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="new_attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>{{ __('hirings.upload_files_label') }}</span>
                                            <input id="new_attachments" name="new_attachments[]" type="file" class="sr-only" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.zip,.rar" @change="handleFileUpload($event)">
                                        </label>
                                        <p class="pl-1">{{ __('hirings.or_drag_drop') }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ __('hirings.attachment_help') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- New File Preview --}}
                        <div x-show="newUploadedFiles.length > 0">
                            <h4 class="font-medium text-gray-900 mb-3">{{ __('hirings.new_uploaded_files') }}</h4>
                            <template x-for="(file, index) in newUploadedFiles" :key="index">
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3 mb-2">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span x-text="file.name" class="text-sm font-medium text-gray-900"></span>
                                        <span x-text="`(${formatFileSize(file.size)})`" class="text-sm text-gray-500 ml-2"></span>
                                    </div>
                                    <button type="button"
                                            class="inline-flex items-center px-2.5 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            @click="removeNewFile(index)">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <a href="{{ localized_route('client.hirings.show', $hiring->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('common.cancel') }}
                        </a>

                        <div class="flex gap-3">
                            @if($hiring->status === 'draft')
                                <button type="button"
                                        class="inline-flex items-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        @click="saveDraft()"
                                        :disabled="loading">
                                    <span x-show="loading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-blue-700">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    <svg class="w-4 h-4 mr-2" x-show="!loading" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ __('hirings.save_draft') }}
                                </button>
                            @endif

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    :disabled="loading || !isFormValid()">
                                <span x-show="loading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <svg class="w-4 h-4 mr-2" x-show="!loading" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('hirings.update_hiring') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function hiringEditData() {
        return {
            hiring: @json($hiring),
            form: {
                title: '{{ $hiring->title }}',
                description: '{{ $hiring->description }}',
                category: '{{ $hiring->category }}',
                duration: '{{ $hiring->duration }}',
                skills_required: '{{ $hiring->skills_required }}',
                budget_min: '{{ $hiring->budget_min }}',
                budget_max: '{{ $hiring->budget_max }}',
                start_date: '{{ $hiring->start_date }}',
                payment_terms: '{{ $hiring->payment_terms }}',
                milestones: @json($hiring->milestones ?? [])
            },
            errors: {},
            loading: false,
            newUploadedFiles: [],
            deletedAttachments: [],

            init() {
                // Ensure milestones array is properly formatted
                if (!Array.isArray(this.form.milestones)) {
                    this.form.milestones = [];
                }

                // Add missing properties to existing milestones
                this.form.milestones = this.form.milestones.map(milestone => ({
                    title: milestone.title || '',
                    description: milestone.description || '',
                    amount: milestone.amount || '',
                    due_date: milestone.due_date || '',
                    deleted: false,
                    ...milestone
                }));

                // Watch for payment terms changes
                this.$watch('form.payment_terms', (value) => {
                    if (value === 'milestone' && this.form.milestones.filter(m => !m.deleted).length === 0) {
                        this.addMilestone();
                    }
                });
            },

            isFormValid() {
                return this.form.title &&
                       this.form.description &&
                       this.form.category &&
                       this.form.duration &&
                       this.form.budget_min &&
                       this.form.budget_max &&
                       this.form.start_date;
            },

            addMilestone() {
                this.form.milestones.push({
                    title: '',
                    description: '',
                    amount: '',
                    due_date: '',
                    deleted: false
                });
            },

            removeMilestone(index) {
                if (this.form.milestones.filter(m => !m.deleted).length > 1) {
                    this.form.milestones[index].deleted = true;
                }
            },

            removeExistingAttachment(index) {
                this.deletedAttachments.push(index);
            },

            handleFileUpload(event) {
                const files = Array.from(event.target.files);
                this.newUploadedFiles = [...this.newUploadedFiles, ...files];
            },

            removeNewFile(index) {
                this.newUploadedFiles.splice(index, 1);
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            async submitForm() {
                this.loading = true;
                this.errors = {};

                try {
                    const formData = new FormData();

                    // Add form fields
                    Object.keys(this.form).forEach(key => {
                        if (key === 'milestones') {
                            this.form.milestones.forEach((milestone, index) => {
                                if (!milestone.deleted) {
                                    Object.keys(milestone).forEach(milestoneKey => {
                                        if (milestoneKey !== 'deleted') {
                                            formData.append(`milestones[${index}][${milestoneKey}]`, milestone[milestoneKey]);
                                        }
                                    });
                                }
                            });
                        } else {
                            formData.append(key, this.form[key]);
                        }
                    });

                    // Add new files
                    this.newUploadedFiles.forEach((file, index) => {
                        formData.append(`new_attachments[${index}]`, file);
                    });

                    // Add deleted attachments
                    this.deletedAttachments.forEach((index) => {
                        formData.append(`deleted_attachments[]`, index);
                    });

                    const response = await fetch('{{ localized_route("client.hirings.update", $hiring->id) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        window.location.href = '{{ localized_route("client.hirings.show", $hiring->id) }}';
                    } else {
                        this.errors = data.errors || {};
                        this.showNotification('error', data.message || '{{ __("hirings.error_updating") }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showNotification('error', '{{ __("common.error_occurred") }}');
                } finally {
                    this.loading = false;
                }
            },

            async saveDraft() {
                this.loading = true;

                try {
                    const formData = new FormData();
                    formData.append('status', 'draft');

                    // Add form fields
                    Object.keys(this.form).forEach(key => {
                        if (key === 'milestones') {
                            this.form.milestones.forEach((milestone, index) => {
                                if (!milestone.deleted) {
                                    Object.keys(milestone).forEach(milestoneKey => {
                                        if (milestoneKey !== 'deleted') {
                                            formData.append(`milestones[${index}][${milestoneKey}]`, milestone[milestoneKey]);
                                        }
                                    });
                                }
                            });
                        } else {
                            formData.append(key, this.form[key]);
                        }
                    });

                    const response = await fetch('{{ localized_route("client.hirings.update", $hiring->id) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.showNotification('success', '{{ __("hirings.draft_saved") }}');
                    } else {
                        this.showNotification('error', data.message || '{{ __("hirings.error_saving_draft") }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showNotification('error', '{{ __("common.error_occurred") }}');
                } finally {
                    this.loading = false;
                }
            },

            showNotification(type, message) {
                // You can implement a toast notification system here
                if (type === 'error') {
                    alert(message); // Fallback - replace with proper notification
                } else {
                    console.log(message); // Success notifications
                }
            }
        }
    }
</script>
@endpush