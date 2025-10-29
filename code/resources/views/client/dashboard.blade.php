@extends('layouts.dashboard')

@section('title', __('Dashboard'))

@section('content')
<div class="space-y-6" x-data="dashboardData()" x-init="init()">
    <!-- RTL Toggle Button -->
    <button id="rtlToggle" class="fixed bottom-20 right-4 z-50 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-all">
        <i data-lucide="languages" class="w-5 h-5"></i>
    </button>

    <!-- Dark Mode Toggle -->
    <button id="darkModeToggle" class="fixed bottom-4 right-4 z-50 bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-900 transition-all">
        <i data-lucide="moon" class="w-5 h-5"></i>
    </button>

    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ __('Welcome') }}, {{ auth()->user()->firstname }}!
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            {{ __('Here is an overview of your activity on the platform') }}
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Current Balance -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Current Balance') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($dashboardData['current_balance'] ?? 0, 2) }} DZD</h3>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <i data-lucide="wallet" class="text-blue-500 dark:text-blue-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-500">
                <i data-lucide="trending-up" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Available for withdrawal') }}</span>
            </div>
        </div>

        <!-- Total Withdrawal Amount -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Total Spent') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($dashboardData['total_spent'] ?? 0, 2) }} DZD</h3>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <i data-lucide="banknote" class="text-green-500 dark:text-green-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-500">
                <i data-lucide="arrow-up" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Total withdrawals') }}</span>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Total Transactions') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $dashboardData['total_transactions'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                    <i data-lucide="arrow-left-right" class="text-indigo-500 dark:text-indigo-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-500">
                <i data-lucide="activity" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('All time') }}</span>
            </div>
        </div>

        <!-- Active Campaigns -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Active Campaigns') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $dashboardData['active_campaigns'] ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <i data-lucide="megaphone" class="text-purple-500 dark:text-purple-400 w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-purple-500">
                <i data-lucide="zap" class="w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1"></i>
                <span>{{ __('Currently running') }}</span>
            </div>
        </div>
    </div>

    <!-- Engagement & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Engagement Rate Gauge -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Engagement Rate') }}</h3>
            <div class="relative w-48 h-48 mx-auto">
                <canvas id="engagementGauge"></canvas>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format(($dashboardData['engagement_rate'] ?? 25.68), 2) }}%</p>
                <p class="text-gray-600 dark:text-gray-400">{{ __('Above average') }}</p>
            </div>
        </div>

        <!-- Orders Distribution Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Orders Distribution') }}</h3>
            <div class="relative h-64">
                <canvas id="ordersChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                <div class="bg-yellow-50 dark:bg-yellow-900/30 p-2 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Pending') }}</p>
                    <p class="font-bold text-gray-900 dark:text-white">30%</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/30 p-2 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('In Progress') }}</p>
                    <p class="font-bold text-gray-900 dark:text-white">45%</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/30 p-2 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Completed') }}</p>
                    <p class="font-bold text-gray-900 dark:text-white">25%</p>
                </div>
            </div>
        </div>

        <!-- Hiring Distribution Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Hiring Distribution') }}</h3>
            <div class="relative h-64">
                <canvas id="hiringChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Pending') }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">20%</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('In Progress') }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">35%</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Completed') }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">45%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Over Time -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Performance Over Time') }}</h3>
            <div class="relative h-80">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Recent Activity') }}</h3>
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4 rtl:ml-0 rtl:mr-4 flex-1">
                        <p class="font-medium text-gray-900 dark:text-white">{{ __('New Order Received') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('2 hours ago') }}</p>
                    </div>
                </div>
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded flex items-center justify-center text-green-600 dark:text-green-400">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4 rtl:ml-0 rtl:mr-4 flex-1">
                        <p class="font-medium text-gray-900 dark:text-white">{{ __('Campaign Completed') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('5 hours ago') }}</p>
                    </div>
                </div>
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4 rtl:ml-0 rtl:mr-4 flex-1">
                        <p class="font-medium text-gray-900 dark:text-white">{{ __('New Hiring Request') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('1 day ago') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Quick Actions') }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Quick access to main functions') }}</p>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ localized_route('client.orders.create') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md transition-all bg-gray-50 dark:bg-gray-700/50">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <i data-lucide="plus-circle" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Create Service') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('New service') }}</p>
                </div>
            </a>

            <a href="{{ localized_route('client.orders.index') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-md transition-all bg-gray-50 dark:bg-gray-700/50">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-600">
                    <i data-lucide="package" class="h-6 w-6 text-gray-600 dark:text-gray-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('View Orders') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Manage orders') }}</p>
                </div>
            </a>

            <a href="{{ localized_route('client.campaigns.index') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-600 hover:shadow-md transition-all bg-gray-50 dark:bg-gray-700/50">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30">
                    <i data-lucide="megaphone" class="h-6 w-6 text-purple-600 dark:text-purple-400"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Manage Campaigns') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Active campaigns') }}</p>
                </div>
            </a>

            <a href="{{ localized_route('financial.deposits') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-300 dark:hover:border-green-600 hover:shadow-md transition-all bg-gray-50 dark:bg-gray-700/50">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30">
                    <i data-lucide="banknote" class="h-6 w-6 text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Request Withdrawal') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Financial management') }}</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Recent Transactions') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Latest financial activities') }}</p>
            </div>
            <a href="{{ localized_route('financial.transactions') }}" class="btn btn-outline text-sm">
                {{ __('View all') }}
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentTransactions ?? [] as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">
                                {{ $transaction->trx ?? '#' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $transaction->trx_type === '+' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' }}">
                                    <i data-lucide="{{ $transaction->trx_type === '+' ? 'arrow-down-circle' : 'arrow-up-circle' }}" class="w-3 h-3 mr-1"></i>
                                    {{ $transaction->trx_type === '+' ? __('Credit') : __('Debit') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $transaction->trx_type === '+' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->trx_type }}{{ number_format($transaction->amount ?? 0, 2) }} DZD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    {{ $transaction->status ?? __('Completed') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ isset($transaction->created_at) ? \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="inbox" class="h-8 w-8 text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('No recent transactions') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Your transactions will appear here') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function dashboardData() {
        return {
            data: @json($dashboardData ?? []),
            recentTransactions: @json($recentTransactions ?? []),
            engagementGauge: null,
            ordersChart: null,
            hiringChart: null,
            performanceChart: null,

            init() {
                this.$nextTick(() => {
                    this.initializeCharts();
                    this.initializeToggles();
                    lucide.createIcons();
                });
            },

            initializeCharts() {
                // Engagement Gauge Chart
                const gaugeCtx = document.getElementById('engagementGauge');
                if (gaugeCtx) {
                    const engagementRate = {{ $dashboardData['engagement_rate'] ?? 25.68 }};
                    this.engagementGauge = new Chart(gaugeCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['{{ __("Engagement") }}', '{{ __("Remaining") }}'],
                            datasets: [{
                                data: [engagementRate, 100 - engagementRate],
                                backgroundColor: ['#3b82f6', '#f3f4f6'],
                                borderWidth: 0,
                                circumference: 180,
                                rotation: 270
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            cutout: '70%',
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: false }
                            }
                        }
                    });
                }

                // Orders Distribution Chart
                const ordersCtx = document.getElementById('ordersChart');
                if (ordersCtx) {
                    this.ordersChart = new Chart(ordersCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['{{ __("Pending") }}', '{{ __("In Progress") }}', '{{ __("Completed") }}'],
                            datasets: [{
                                data: [30, 45, 25],
                                backgroundColor: ['#fbbf24', '#3b82f6', '#10b981'],
                                borderWidth: 2,
                                borderColor: '#ffffff'
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
                                        font: { size: 11 }
                                    }
                                }
                            },
                            cutout: '60%'
                        }
                    });
                }

                // Hiring Distribution Chart
                const hiringCtx = document.getElementById('hiringChart');
                if (hiringCtx) {
                    this.hiringChart = new Chart(hiringCtx, {
                        type: 'bar',
                        data: {
                            labels: ['{{ __("Pending") }}', '{{ __("In Progress") }}', '{{ __("Completed") }}'],
                            datasets: [{
                                label: '{{ __("Percentage") }}',
                                data: [20, 35, 45],
                                backgroundColor: ['#f87171', '#8b5cf6', '#06d6a0'],
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { drawBorder: false },
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                },
                                x: { grid: { display: false } }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                }

                // Performance Over Time Chart
                const performanceCtx = document.getElementById('performanceChart');
                if (performanceCtx) {
                    this.performanceChart = new Chart(performanceCtx, {
                        type: 'line',
                        data: {
                            labels: ['{{ __("Jan") }}', '{{ __("Feb") }}', '{{ __("Mar") }}', '{{ __("Apr") }}', '{{ __("May") }}', '{{ __("Jun") }}', '{{ __("Jul") }}', '{{ __("Aug") }}', '{{ __("Sep") }}', '{{ __("Oct") }}', '{{ __("Nov") }}', '{{ __("Dec") }}'],
                            datasets: [
                                {
                                    label: '{{ __("Orders") }}',
                                    data: [30, 45, 35, 55, 45, 65, 60, 75, 65, 80, 70, 85],
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4
                                },
                                {
                                    label: '{{ __("Campaigns") }}',
                                    data: [15, 25, 20, 30, 25, 35, 30, 40, 35, 45, 40, 50],
                                    borderColor: '#8b5cf6',
                                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                x: { grid: { display: false } },
                                y: {
                                    beginAtZero: true,
                                    grid: { drawBorder: false }
                                }
                            }
                        }
                    });
                }
            },

            initializeToggles() {
                // RTL Toggle
                const rtlToggle = document.getElementById('rtlToggle');
                if (rtlToggle) {
                    rtlToggle.addEventListener('click', () => {
                        const currentDir = document.documentElement.getAttribute('dir');
                        const newDir = currentDir === 'ltr' ? 'rtl' : 'ltr';
                        document.documentElement.setAttribute('dir', newDir);
                        document.body.setAttribute('dir', newDir);

                        // Update button icon
                        const icon = rtlToggle.querySelector('i');
                        if (icon) {
                            icon.setAttribute('data-lucide', newDir === 'rtl' ? 'align-right' : 'align-left');
                            lucide.createIcons();
                        }
                    });
                }

                // Dark Mode Toggle
                const darkModeToggle = document.getElementById('darkModeToggle');
                if (darkModeToggle) {
                    // Check for saved preference
                    const isDark = localStorage.getItem('darkMode') === 'true';
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                        document.body.classList.add('dark');
                        this.updateDarkModeIcon(darkModeToggle, true);
                    }

                    darkModeToggle.addEventListener('click', () => {
                        const isDark = document.documentElement.classList.toggle('dark');
                        document.body.classList.toggle('dark');
                        localStorage.setItem('darkMode', isDark);
                        this.updateDarkModeIcon(darkModeToggle, isDark);
                    });
                }
            },

            updateDarkModeIcon(button, isDark) {
                const icon = button.querySelector('i');
                if (icon) {
                    icon.setAttribute('data-lucide', isDark ? 'sun' : 'moon');
                    lucide.createIcons();
                }
            },

            async refreshData() {
                try {
                    window.loader?.show();
                    const response = await fetch('{{ localized_route("user.home") }}');
                    const result = await response.json();

                    if (result.success) {
                        this.data = result.data;
                        this.recentTransactions = result.transactions;
                        window.showToast('{{ __("Data refreshed successfully") }}', 'success');
                    }
                } catch (error) {
                    window.showToast('{{ __("Error refreshing data") }}', 'error');
                } finally {
                    window.loader?.hide();
                }
            }
        }
    }

    // Initialize Lucide icons after page load
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

<style>
    /* Dark mode styles */
    .dark {
        background-color: #111827;
        color: #f9fafb;
    }

    /* RTL Support */
    [dir="rtl"] {
        direction: rtl;
        text-align: right;
    }

    [dir="rtl"] .ml-1 {
        margin-left: 0;
        margin-right: 0.25rem;
    }

    [dir="rtl"] .ml-3 {
        margin-left: 0;
        margin-right: 0.75rem;
    }

    [dir="rtl"] .ml-4 {
        margin-left: 0;
        margin-right: 1rem;
    }

    [dir="rtl"] .mr-1 {
        margin-right: 0;
        margin-left: 0.25rem;
    }

    [dir="rtl"] .mr-3 {
        margin-right: 0;
        margin-left: 0.75rem;
    }

    [dir="rtl"] .mr-4 {
        margin-right: 0;
        margin-left: 1rem;
    }

    /* Smooth transitions */
    * {
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }

    /* Mobile responsiveness */
    @media (max-width: 640px) {
        #rtlToggle,
        #darkModeToggle {
            bottom: 1rem;
            right: 1rem;
        }

        #rtlToggle {
            bottom: 5rem;
        }
    }
</style>
@endpush