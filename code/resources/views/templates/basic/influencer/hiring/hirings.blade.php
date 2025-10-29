@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
        <p class="text-gray-600">Manage your hiring projects and collaborations</p>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-content">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Status Filter -->
                <div class="flex gap-2">
                    <a href="{{ localized_route('influencer.hiring.index') }}"
                       class="btn {{ request()->routeIs('influencer.hiring.index') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        All
                    </a>
                    <a href="{{ localized_route('influencer.hiring.pending') }}"
                       class="btn {{ request()->routeIs('influencer.hiring.pending') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Pending
                        @if($pendingHiring > 0)
                            <span class="badge badge-destructive ml-1">{{ $pendingHiring }}</span>
                        @endif
                    </a>
                    <a href="{{ localized_route('influencer.hiring.inprogress') }}"
                       class="btn {{ request()->routeIs('influencer.hiring.inprogress') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        In Progress
                    </a>
                    <a href="{{ localized_route('influencer.hiring.jobdone') }}"
                       class="btn {{ request()->routeIs('influencer.hiring.jobdone') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Completed
                    </a>
                    <a href="{{ localized_route('influencer.hiring.completed') }}"
                       class="btn {{ request()->routeIs('influencer.hiring.completed') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Finished
                    </a>
                    <a href="{{ localized_route('influencer.hiring.cancelled') }}"
                       class="btn {{ request()->routeIs('influencer.hiring.cancelled') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        Cancelled
                    </a>
                </div>

                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <form method="GET" action="{{ request()->url() }}" class="flex gap-2">
                        <input type="text"
                               name="search"
                               value="{{ request()->search }}"
                               placeholder="Search by hiring number..."
                               class="input flex-1">
                        <button type="submit" class="btn btn-outline btn-default">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hirings Table -->
    <div class="card">
        <div class="card-content p-0">
            @if($hirings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hiring
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Client
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
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
                            @foreach($hirings as $hiring)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $hiring->hiring_no }}</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $hiring->title }}</div>
                                    <div class="text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($hiring->description, 80) }}</div>
                                    @if($hiring->working_day)
                                        <div class="text-xs text-gray-500 mt-1">{{ $hiring->working_day }} days</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $hiring->user->fullname ?? $hiring->user->username }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ showAmount($hiring->amount) }} {{ $general->cur_text ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $hiring->type == 'hourly' ? 'Hourly' : 'Fixed Project' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch($hiring->status) {
                                            case 1:
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                $statusText = 'Pending';
                                                break;
                                            case 2:
                                                $statusClass = 'bg-blue-100 text-blue-800';
                                                $statusText = 'In Progress';
                                                break;
                                            case 3:
                                                $statusClass = 'bg-green-100 text-green-800';
                                                $statusText = 'Completed';
                                                break;
                                            case 4:
                                                $statusClass = 'bg-purple-100 text-purple-800';
                                                $statusText = 'Finished';
                                                break;
                                            case 5:
                                                $statusClass = 'bg-red-100 text-red-800';
                                                $statusText = 'Cancelled';
                                                break;
                                            case 6:
                                                $statusClass = 'bg-red-100 text-red-800';
                                                $statusText = 'Reported';
                                                break;
                                            default:
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                $statusText = 'Unknown';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                    @if(in_array($hiring->status, [2, 3]))
                                        <div class="mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300"
                                                     style="width: {{ $hiring->status == 3 ? '100' : '50' }}%"></div>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $hiring->status == 3 ? '100' : '50' }}%</div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $hiring->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ localized_route('influencer.hiring.detail', $hiring->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        @if($hiring->status == 1)
                                            <form method="POST" action="{{ localized_route('influencer.hiring.accept.status', $hiring->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Accept"
                                                        onclick="return confirm('Are you sure you want to accept this hiring?')">
                                                    <i data-lucide="check" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ localized_route('influencer.hiring.cancel.status', $hiring->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Reject"
                                                        onclick="return confirm('Are you sure you want to cancel this hiring?')">
                                                    <i data-lucide="x" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($hiring->status == 2)
                                            <form method="POST" action="{{ localized_route('influencer.hiring.jobDone.status', $hiring->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-900" title="Mark as completed"
                                                        onclick="return confirm('Mark this work as completed?')">
                                                    <i data-lucide="check-circle" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ localized_route('influencer.hiring.conversation.view', $hiring->id) }}"
                                           class="text-gray-600 hover:text-gray-900"
                                           title="Messages">
                                            <i data-lucide="message-circle" class="h-4 w-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($hirings->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $hirings->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i data-lucide="briefcase" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hirings found</h3>
                    <p class="text-gray-600 mb-4">
                        @if(request()->search)
                            No hirings match your search "{{ request()->search }}".
                        @else
                            You don't have any hirings yet.
                        @endif
                    </p>
                    @if(!request()->search)
                    <a href="{{ localized_route('influencer.service.create') }}" class="btn btn-primary">
                        <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                        Create a service
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('script')
<script>
// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection