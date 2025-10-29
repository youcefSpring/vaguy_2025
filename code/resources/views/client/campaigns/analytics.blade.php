{{-- Campaign Analytics and Reporting --}}
@extends('layouts.dashboard')

@section('title', __('messages.campaign_analytics'))

@section('page-title', __('messages.campaign_analytics'))

@section('content')
<div x-data="campaignAnalytics(@json($campaign ?? null))" x-init="init()">
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
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.analytics') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-calendar me-2"></i>
                    <span x-text="dateRangeText">{{ __('messages.last_30_days') }}</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" @click="changeDateRange('7')">{{ __('messages.last_7_days') }}</a></li>
                    <li><a class="dropdown-item" href="#" @click="changeDateRange('30')">{{ __('messages.last_30_days') }}</a></li>
                    <li><a class="dropdown-item" href="#" @click="changeDateRange('90')">{{ __('messages.last_90_days') }}</a></li>
                    <li><a class="dropdown-item" href="#" @click="changeDateRange('all')">{{ __('messages.all_time') }}</a></li>
                </ul>
            </div>
            <button class="btn btn-outline-primary" @click="exportData()">
                <i class="fas fa-download me-2"></i>
                {{ __('messages.export') }}
            </button>
        </div>
    </div>

    {{-- Loading State --}}
    <div x-show="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">{{ __('messages.loading') }}</span>
        </div>
        <p class="mt-2 text-muted">{{ __('messages.loading_analytics') }}</p>
    </div>

    {{-- Analytics Content --}}
    <div x-show="!loading">
        {{-- Key Metrics Cards --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-0" x-text="analytics.impressions || 0">0</h2>
                                <p class="mb-0">{{ __('messages.impressions') }}</p>
                                <small class="opacity-75" x-show="analytics.impressions_change">
                                    <i :class="analytics.impressions_change >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    <span x-text="Math.abs(analytics.impressions_change || 0) + '%'"></span>
                                </small>
                            </div>
                            <i class="fas fa-eye fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-0" x-text="analytics.applications || 0">0</h2>
                                <p class="mb-0">{{ __('messages.applications') }}</p>
                                <small class="opacity-75" x-show="analytics.applications_change">
                                    <i :class="analytics.applications_change >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    <span x-text="Math.abs(analytics.applications_change || 0) + '%'"></span>
                                </small>
                            </div>
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-0" x-text="analytics.accepted || 0">0</h2>
                                <p class="mb-0">{{ __('messages.accepted') }}</p>
                                <small class="opacity-75" x-show="analytics.accepted_change">
                                    <i :class="analytics.accepted_change >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    <span x-text="Math.abs(analytics.accepted_change || 0) + '%'"></span>
                                </small>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-0" x-text="(analytics.conversion_rate || 0) + '%'">0%</h2>
                                <p class="mb-0">{{ __('messages.conversion_rate') }}</p>
                                <small class="opacity-75" x-show="analytics.conversion_change">
                                    <i :class="analytics.conversion_change >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    <span x-text="Math.abs(analytics.conversion_change || 0) + '%'"></span>
                                </small>
                            </div>
                            <i class="fas fa-percentage fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Performance Chart --}}
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">{{ __('messages.performance_overview') }}</h5>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="chartType" id="impressions" autocomplete="off" checked @change="changeChartType('impressions')">
                                <label class="btn btn-outline-primary" for="impressions">{{ __('messages.impressions') }}</label>

                                <input type="radio" class="btn-check" name="chartType" id="applications" autocomplete="off" @change="changeChartType('applications')">
                                <label class="btn btn-outline-primary" for="applications">{{ __('messages.applications') }}</label>

                                <input type="radio" class="btn-check" name="chartType" id="conversions" autocomplete="off" @change="changeChartType('conversions')">
                                <label class="btn btn-outline-primary" for="conversions">{{ __('messages.conversions') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="performanceChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            {{-- Demographics --}}
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.audience_demographics') }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- Gender Distribution --}}
                        <div class="mb-4">
                            <h6>{{ __('messages.gender_distribution') }}</h6>
                            <canvas id="genderChart" width="200" height="200"></canvas>
                        </div>

                        {{-- Age Distribution --}}
                        <div class="mb-4">
                            <h6>{{ __('messages.age_distribution') }}</h6>
                            <div class="mt-2">
                                <template x-for="(value, age) in analytics.age_groups" :key="age">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span x-text="age"></span>
                                        <div class="flex-grow-1 mx-2">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-primary"
                                                     :style="`width: ${(value / Math.max(...Object.values(analytics.age_groups || {}))) * 100}%`">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-muted" x-text="value + '%'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Top Locations --}}
                        <div>
                            <h6>{{ __('messages.top_locations') }}</h6>
                            <div class="mt-2">
                                <template x-for="(value, location) in analytics.top_locations" :key="location">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span x-text="location"></span>
                                        <span class="text-muted" x-text="value + '%'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Application Timeline --}}
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.application_timeline') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <template x-for="event in analytics.timeline || []" :key="event.id">
                                <div class="timeline-item">
                                    <div class="timeline-marker"
                                         :class="{
                                             'bg-success': event.type === 'accepted',
                                             'bg-warning': event.type === 'applied',
                                             'bg-danger': event.type === 'rejected',
                                             'bg-info': event.type === 'completed'
                                         }">
                                        <i :class="{
                                               'fas fa-check': event.type === 'accepted',
                                               'fas fa-user-plus': event.type === 'applied',
                                               'fas fa-times': event.type === 'rejected',
                                               'fas fa-flag-checkered': event.type === 'completed'
                                           }"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 x-text="event.title" class="mb-1"></h6>
                                        <p x-text="event.description" class="text-muted mb-1"></p>
                                        <small x-text="formatDate(event.created_at)" class="text-muted"></small>
                                    </div>
                                </div>
                            </template>
                            <div x-show="!analytics.timeline || analytics.timeline.length === 0" class="text-center text-muted py-4">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <p>{{ __('messages.no_activity_yet') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Performing Content --}}
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('messages.influencer_performance') }}</h5>
                    </div>
                    <div class="card-body">
                        <div x-show="analytics.top_influencers && analytics.top_influencers.length > 0">
                            <template x-for="influencer in analytics.top_influencers" :key="influencer.id">
                                <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                    <img :src="influencer.avatar || '/images/default-avatar.png'"
                                         :alt="influencer.name"
                                         class="rounded-circle me-3"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1" x-text="influencer.name"></h6>
                                        <div class="d-flex justify-content-between text-muted small">
                                            <span x-text="formatNumber(influencer.followers) + ' followers'"></span>
                                            <span x-text="influencer.engagement_rate + '% engagement'"></span>
                                        </div>
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar bg-success"
                                                 :style="`width: ${influencer.performance_score}%`">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        <small class="text-muted">{{ __('messages.performance') }}</small>
                                        <div class="h6 mb-0" x-text="influencer.performance_score + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div x-show="!analytics.top_influencers || analytics.top_influencers.length === 0" class="text-center text-muted py-4">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>{{ __('messages.no_influencer_data') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detailed Analytics Table --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('messages.detailed_analytics') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.impressions') }}</th>
                                <th>{{ __('messages.applications') }}</th>
                                <th>{{ __('messages.accepted') }}</th>
                                <th>{{ __('messages.conversion_rate') }}</th>
                                <th>{{ __('messages.engagement') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="row in analytics.daily_data || []" :key="row.date">
                                <tr>
                                    <td x-text="formatDate(row.date)"></td>
                                    <td x-text="formatNumber(row.impressions)"></td>
                                    <td x-text="formatNumber(row.applications)"></td>
                                    <td x-text="formatNumber(row.accepted)"></td>
                                    <td x-text="row.conversion_rate + '%'"></td>
                                    <td x-text="row.engagement_rate + '%'"></td>
                                </tr>
                            </template>
                            <tr x-show="!analytics.daily_data || analytics.daily_data.length === 0">
                                <td colspan="6" class="text-center text-muted py-4">
                                    {{ __('messages.no_data_available') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
function campaignAnalytics(campaignData) {
    return {
        campaign: campaignData,
        analytics: {},
        loading: true,
        dateRange: '30',
        dateRangeText: 'Last 30 Days',
        currentChartType: 'impressions',
        performanceChart: null,
        genderChart: null,

        async init() {
            await this.loadAnalytics();
            this.initializeCharts();
        },

        async loadAnalytics() {
            this.loading = true;

            try {
                const response = await fetch(`/client/campaigns/${this.campaign.id}/analytics?range=${this.dateRange}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.analytics = data;

                    // Update charts if they exist
                    this.updateCharts();
                } else {
                    this.showAlert('error', this.translate('error_loading_analytics'));
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
                this.showAlert('error', this.translate('error_loading_analytics'));

                // Fallback demo data
                this.analytics = this.getDemoAnalytics();
            } finally {
                this.loading = false;
            }
        },

        getDemoAnalytics() {
            return {
                impressions: 15420,
                impressions_change: 12.5,
                applications: 87,
                applications_change: 8.3,
                accepted: 23,
                accepted_change: 15.7,
                conversion_rate: 26.4,
                conversion_change: 3.2,

                age_groups: {
                    '18-24': 35,
                    '25-34': 45,
                    '35-44': 15,
                    '45+': 5
                },

                top_locations: {
                    'United States': 40,
                    'United Kingdom': 20,
                    'Canada': 15,
                    'Australia': 10,
                    'Other': 15
                },

                timeline: [
                    {
                        id: 1,
                        type: 'applied',
                        title: 'New Application',
                        description: '@fashionista_jane applied to your campaign',
                        created_at: new Date().toISOString()
                    },
                    {
                        id: 2,
                        type: 'accepted',
                        title: 'Application Accepted',
                        description: 'You accepted @lifestyle_blogger\'s application',
                        created_at: new Date(Date.now() - 86400000).toISOString()
                    }
                ],

                top_influencers: [
                    {
                        id: 1,
                        name: 'Fashion Influencer',
                        avatar: '/images/avatar1.jpg',
                        followers: 125000,
                        engagement_rate: 4.2,
                        performance_score: 87
                    },
                    {
                        id: 2,
                        name: 'Lifestyle Blogger',
                        avatar: '/images/avatar2.jpg',
                        followers: 89000,
                        engagement_rate: 5.1,
                        performance_score: 92
                    }
                ],

                daily_data: this.generateDailyData()
            };
        },

        generateDailyData() {
            const data = [];
            for (let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);

                data.push({
                    date: date.toISOString(),
                    impressions: Math.floor(Math.random() * 1000) + 200,
                    applications: Math.floor(Math.random() * 10) + 1,
                    accepted: Math.floor(Math.random() * 3),
                    conversion_rate: (Math.random() * 10 + 10).toFixed(1),
                    engagement_rate: (Math.random() * 5 + 2).toFixed(1)
                });
            }
            return data;
        },

        async changeDateRange(range) {
            this.dateRange = range;

            const rangeMap = {
                '7': 'Last 7 Days',
                '30': 'Last 30 Days',
                '90': 'Last 90 Days',
                'all': 'All Time'
            };

            this.dateRangeText = this.translate(rangeMap[range].toLowerCase().replace(/\s+/g, '_'));
            await this.loadAnalytics();
        },

        initializeCharts() {
            this.$nextTick(() => {
                this.initPerformanceChart();
                this.initGenderChart();
            });
        },

        initPerformanceChart() {
            const ctx = document.getElementById('performanceChart');
            if (!ctx) return;

            const labels = this.analytics.daily_data?.map(d => this.formatDate(d.date)) || [];
            const data = this.analytics.daily_data?.map(d => d[this.currentChartType]) || [];

            this.performanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: this.translate(this.currentChartType),
                        data: data,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },

        initGenderChart() {
            const ctx = document.getElementById('genderChart');
            if (!ctx) return;

            // Demo gender data
            const genderData = {
                'Male': 40,
                'Female': 55,
                'Other': 5
            };

            this.genderChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(genderData),
                    datasets: [{
                        data: Object.values(genderData),
                        backgroundColor: [
                            '#007bff',
                            '#28a745',
                            '#ffc107'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },

        updateCharts() {
            if (this.performanceChart) {
                const labels = this.analytics.daily_data?.map(d => this.formatDate(d.date)) || [];
                const data = this.analytics.daily_data?.map(d => d[this.currentChartType]) || [];

                this.performanceChart.data.labels = labels;
                this.performanceChart.data.datasets[0].data = data;
                this.performanceChart.data.datasets[0].label = this.translate(this.currentChartType);
                this.performanceChart.update();
            }
        },

        changeChartType(type) {
            this.currentChartType = type;
            this.updateCharts();
        },

        async exportData() {
            try {
                const response = await fetch(`/client/campaigns/${this.campaign.id}/analytics/export?range=${this.dateRange}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `campaign-analytics-${this.campaign.id}-${Date.now()}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    a.remove();

                    this.showAlert('success', this.translate('export_successful'));
                } else {
                    this.showAlert('error', this.translate('export_failed'));
                }
            } catch (error) {
                console.error('Error exporting data:', error);
                this.showAlert('error', this.translate('export_failed'));
            }
        },

        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString(this.$store.language.current, {
                month: 'short',
                day: 'numeric'
            });
        },

        formatNumber(number) {
            if (!number) return '0';
            return new Intl.NumberFormat(this.$store.language.current).format(number);
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