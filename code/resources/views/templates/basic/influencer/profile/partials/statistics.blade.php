<!-- Statistics Section -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Services -->
    <div class="card">
        <div class="card-content">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="briefcase" class="h-8 w-8 text-blue-600"></i>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">@lang('Total Services')</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $influencer->services->count() ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>





    <!-- Active Services -->
    <div class="card">
        <div class="card-content">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="check-circle" class="h-8 w-8 text-green-600"></i>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">@lang('Active Services')</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $influencer->services->where('status', 1)->count() ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="card">
        <div class="card-content">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="shopping-bag" class="h-8 w-8 text-purple-600"></i>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">@lang('user.total_orders')</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $influencer->orders->count() ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Earnings -->
    <div class="card">
        <div class="card-content">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="dollar-sign" class="h-8 w-8 text-yellow-600"></i>
                </div>
                <div class="ltr:ml-5 rtl:mr-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">@lang('Total Earnings')</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($influencer->balance ?? 0, 0, ',', ' ') }} DZD</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Service Performance -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Service Performance')</h3>
            <p class="card-description">@lang('Your services by status')</p>
        </div>
        <div class="card-content">
            <canvas id="serviceChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Order Trends -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Order Trends')</h3>
            <p class="card-description">@lang('Orders over the last 6 months')</p>
        </div>
        <div class="card-content">
            <canvas id="orderChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card mt-6">
    <div class="card-header">
        <h3 class="card-title">@lang('Recent Activity')</h3>
        <p class="card-description">@lang('Your latest interactions and updates')</p>
    </div>
    <div class="card-content">
        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @if($influencer->orders->take(5))
                    @foreach($influencer->orders->take(5) as $index => $order)
                    <li>
                        <div class="relative {{ $loop->last ? '' : 'pb-8' }}">
                            @if(!$loop->last)
                            <span class="absolute top-4 ltr:left-4 rtl:right-4 ltr:-ml-px rtl:-mr-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex ltr:space-x-3 rtl:space-x-reverse">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <i data-lucide="shopping-bag" class="h-4 w-4 text-white"></i>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between ltr:space-x-4 rtl:space-x-reverse">
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            @lang('New order for') <span class="font-medium text-gray-900">{{ $order->service->title ?? 'Service' }}</span>
                                        </p>
                                    </div>
                                    <div class="ltr:text-right rtl:text-left text-sm whitespace-nowrap text-gray-500">
                                        {{ $order->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                @else
                    <li class="text-center py-8">
                        <p class="text-gray-500">@lang('No recent activity')</p>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Service Performance Chart
    const serviceCtx = document.getElementById('serviceChart').getContext('2d');
    new Chart(serviceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    {{ $influencer->services->where('status', 1)->count() ?? 0 }},
                    {{ $influencer->services->where('status', 0)->count() ?? 0 }},
                    {{ $influencer->services->where('status', 2)->count() ?? 0 }}
                ],
                backgroundColor: [
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
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

    // Order Trends Chart (simplified version)
    const orderCtx = document.getElementById('orderChart').getContext('2d');
    new Chart(orderCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Orders',
                data: [12, 19, 3, 5, 2, 10], // Replace with actual data
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
