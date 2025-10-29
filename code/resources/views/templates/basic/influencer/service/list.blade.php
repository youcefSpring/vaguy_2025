@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Services</h1>
            <p class="text-gray-600">Manage your services and track their performance</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ localized_route('influencer.service.create') }}"
               class="btn btn-primary btn-default">
                <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                Create Service
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-content">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Status Filter -->
                <div class="flex gap-2">
                    <a href="{{ localized_route('influencer.service.all') }}"
                       class="btn {{ request()->routeIs('influencer.service.all') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        All
                    </a>
                    <a href="{{ localized_route('influencer.service.pending') }}"
                       class="btn {{ request()->routeIs('influencer.service.pending') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Pending
                    </a>
                    <a href="{{ localized_route('influencer.service.approved') }}"
                       class="btn {{ request()->routeIs('influencer.service.approved') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Approved
                    </a>
                    <a href="{{ localized_route('influencer.service.rejected') }}"
                       class="btn {{ request()->routeIs('influencer.service.rejected') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Rejected
                    </a>
                </div>

                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <form method="GET" action="{{ request()->url() }}" class="flex gap-2">
                        <input type="text"
                               name="search"
                               value="{{ request()->search }}"
                               placeholder="Search services..."
                               class="input flex-1">
                        <button type="submit" class="btn btn-outline btn-default">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Table -->
    <div class="card">
        <div class="card-content p-0">
            @if($services->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Service
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Orders
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($services as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img src="{{ getImage(getFilePath('service') . '/' . $service->image, getFileSize('service')) }}"
                                                 alt="{{ $service->title }}"
                                                 class="h-12 w-12 rounded-lg object-cover border">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $service->title }}</div>
                                            <div class="text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($service->description, 80) }}</div>
                                            @if($service->tags && $service->tags->count() > 0)
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach($service->tags->take(3) as $tag)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
                                                        #{{ $tag->name }}
                                                    </span>
                                                    @endforeach
                                                    @if($service->tags->count() > 3)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600">
                                                        +{{ $service->tags->count() - 3 }}
                                                    </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $service->category->name ?? 'Uncategorized' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ showAmount($service->price) }} {{ $general->cur_text ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch($service->status) {
                                            case 0:
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                $statusText = 'Pending';
                                                break;
                                            case 1:
                                                $statusClass = 'bg-green-100 text-green-800';
                                                $statusText = 'Approved';
                                                break;
                                            case 2:
                                                $statusClass = 'bg-red-100 text-red-800';
                                                $statusText = 'Rejected';
                                                break;
                                            case -1:
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                $statusText = 'Deleted';
                                                break;
                                            default:
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                $statusText = 'Unknown';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $service->total_order_count ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $service->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="openServiceModal({{ $service->id }})" class="text-indigo-600 hover:text-indigo-900" title="View">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </button>

                                        <a href="{{ localized_route('influencer.service.edit', $service->id) }}"
                                           class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i data-lucide="edit" class="h-4 w-4"></i>
                                        </a>

                                        @if($service->status == 1)
                                            <a href="{{ localized_route('influencer.service.orders', $service->id) }}"
                                               class="text-green-600 hover:text-green-900" title="Commandes">
                                                <i data-lucide="list" class="h-4 w-4"></i>
                                            </a>
                                        @endif

                                        <form method="POST"
                                              action="{{ localized_route('influencer.service.destroy', $service->id) }}"
                                              style="display: inline;"
                                              onsubmit="return confirm('Are you sure you want to delete this service?')">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i data-lucide="trash" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($services->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $services->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i data-lucide="briefcase" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No services found</h3>
                    <p class="text-gray-600 mb-4">
                        @if(request()->search)
                            No services match your search "{{ request()->search }}".
                        @else
                            You haven't created any services yet.
                        @endif
                    </p>
                    <a href="{{ localized_route('influencer.service.create') }}"
                       class="btn btn-primary">
                        <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                        Create your first service
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('script')
<script>
function openServiceModal(serviceId) {
    // This would typically load service details via AJAX
    console.log('Opening service modal for ID:', serviceId);
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection