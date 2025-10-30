@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
            @lang('dashboard.welcome_message', ['name' => authInfluencer()->fullname])
        </h1>
        <p class="text-gray-600">
            @lang('dashboard.platform_activity_overview')
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Current Balance -->
        <div class="card">
            <div class="card-content pt-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-blue-500 text-white">
                            <i data-lucide="credit-card" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                @lang('dashboard.current_balance')
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ number_format($data['current_balance'], 2) }} DZD
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdraw Balance -->
        <div class="card">
            <div class="card-content pt-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-green-500 text-white">
                            <i data-lucide="banknote" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                @lang('dashboard.total_withdrawals')
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ number_format($data['withdraw_balance'], 2) }} DZD
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="card">
            <div class="card-content pt-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-purple-500 text-white">
                            <i data-lucide="trending-up" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                @lang('dashboard.total_transactions')
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $data['total_transaction'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Hiring -->
        <div class="card">
            <div class="card-content pt-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-orange-500 text-white">
                            <i data-lucide="users" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                @lang('dashboard.total_jobs')
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $data['total_hiring'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="card">
            <div class="card-content pt-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-red-500 text-white">
                            <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                @lang('dashboard.total_offers')
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $data['total_order'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Services -->
        <div class="card">
            <div class="card-content pt-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-cyan-500 text-white">
                            <i data-lucide="briefcase" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                @lang('dashboard.total_services')
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $data['total_service'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Orders Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('dashboard.orders_distribution')</h3>
                <p class="card-description">
                    @lang('dashboard.service_orders_status')
                </p>
            </div>
            <div class="card-content">
                <div class="relative" style="height: 300px;">
                    <canvas id="ordersChart" class="w-full h-full"></canvas>
                    <div id="ordersChartEmpty" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <i data-lucide="inbox" class="h-12 w-12 mx-auto text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">@lang('dashboard.no_orders_yet')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiring Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('dashboard.jobs_distribution')</h3>
                <p class="card-description">
                    @lang('dashboard.recruitment_projects_status')
                </p>
            </div>
            <div class="card-content">
                <div class="relative" style="height: 300px;">
                    <canvas id="hiringChart" class="w-full h-full"></canvas>
                    <div id="hiringChartEmpty" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <i data-lucide="inbox" class="h-12 w-12 mx-auto text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">@lang('dashboard.no_jobs_yet')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('dashboard.quick_actions')</h3>
            <p class="card-description">
                @lang('dashboard.manage_services_quickly')
            </p>
        </div>
        <div class="card-content">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ localized_route('influencer.service.create') }}"
                   class="btn btn-primary btn-default w-full">
                    <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                    @lang('dashboard.create_service')
                </a>
                <a href="{{ localized_route('influencer.service.order.index') }}"
                   class="btn btn-secondary btn-default w-full">
                    <i data-lucide="list" class="mr-2 h-4 w-4"></i>
                    @lang('dashboard.view_orders')
                </a>
                <a href="{{ localized_route('influencer.campain.index') }}"
                   class="btn btn-outline btn-default w-full">
                    <i data-lucide="megaphone" class="mr-2 h-4 w-4"></i>
                    @lang('dashboard.manage_campaigns')
                </a>
                <a href="{{ localized_route('influencer.withdraw') }}"
                   class="btn btn-ghost btn-default w-full">
                    <i data-lucide="banknote" class="mr-2 h-4 w-4"></i>
                    @lang('dashboard.request_withdrawal')
                </a>
            </div>
        </div>
    </div>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helper function to check if data has values
    function hasData(dataArray) {
        return dataArray.some(value => value > 0);
    }

    // Helper function to create chart with empty state handling
    function createChartWithEmptyState(canvasId, emptyStateId, chartConfig) {
        const canvas = document.getElementById(canvasId);
        const emptyState = document.getElementById(emptyStateId);

        if (!canvas || !emptyState) {
            console.error('Chart elements not found');
            return null;
        }

        const data = chartConfig.data.datasets[0].data;

        if (!hasData(data)) {
            // Show empty state, hide canvas
            canvas.style.display = 'none';
            emptyState.classList.remove('hidden');
            return null;
        } else {
            // Show canvas, hide empty state
            canvas.style.display = 'block';
            emptyState.classList.add('hidden');

            try {
                const ctx = canvas.getContext('2d');
                return new Chart(ctx, chartConfig);
            } catch (error) {
                console.error('Error creating chart:', error);
                return null;
            }
        }
    }

    // Orders Chart Configuration
    const ordersData = [
        {{ $data['pending_order'] }},
        {{ $data['completed_order'] }},
        {{ $data['inprogress_order'] }},
        {{ $data['cancelled_order'] }},
        {{ $data['job_done_order'] }},
        {{ $data['reported_order'] }},
        {{ $data['rejected_order'] }}
    ];

    const ordersConfig = {
        type: 'doughnut',
        data: {
            labels: [
                '@lang('dashboard.status_pending')',
                '@lang('dashboard.status_completed')',
                '@lang('dashboard.status_in_progress')',
                '@lang('dashboard.status_cancelled')',
                '@lang('dashboard.status_job_done')',
                '@lang('dashboard.status_reported')',
                '@lang('dashboard.status_rejected')'
            ],
            datasets: [{
                data: ordersData,
                backgroundColor: [
                    '#f59e0b', // amber
                    '#10b981', // emerald
                    '#3b82f6', // blue
                    '#ef4444', // red
                    '#8b5cf6', // violet
                    '#f97316', // orange
                    '#6b7280'  // gray
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: window.innerWidth < 640 ? 10 : 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    };

    // Hiring Chart Configuration
    const hiringData = [
        {{ $data['pending_hiring'] }},
        {{ $data['completed_hiring'] }},
        {{ $data['inprogress_hiring'] }},
        {{ $data['cancelled_hiring'] }},
        {{ $data['job_done_hiring'] }},
        {{ $data['reported_hiring'] }},
        {{ $data['rejected_hiring'] }}
    ];

    const hiringConfig = {
        type: 'doughnut',
        data: {
            labels: [
                '@lang('dashboard.status_pending')',
                '@lang('dashboard.status_completed')',
                '@lang('dashboard.status_in_progress')',
                '@lang('dashboard.status_cancelled')',
                '@lang('dashboard.status_job_done')',
                '@lang('dashboard.status_reported')',
                '@lang('dashboard.status_rejected')'
            ],
            datasets: [{
                data: hiringData,
                backgroundColor: [
                    '#f59e0b', // amber
                    '#10b981', // emerald
                    '#3b82f6', // blue
                    '#ef4444', // red
                    '#8b5cf6', // violet
                    '#f97316', // orange
                    '#6b7280'  // gray
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: window.innerWidth < 640 ? 10 : 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    };

    // Create charts with empty state handling
    const ordersChart = createChartWithEmptyState('ordersChart', 'ordersChartEmpty', ordersConfig);
    const hiringChart = createChartWithEmptyState('hiringChart', 'hiringChartEmpty', hiringConfig);

    // Handle responsive resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (ordersChart) ordersChart.resize();
            if (hiringChart) hiringChart.resize();
        }, 250);
    });

    // Initialize Lucide icons for empty states
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endpush
@endsection