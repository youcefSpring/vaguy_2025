@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Manage your support tickets</p>
        </div>
        <a href="{{ localized_route('influencer.ticket.open') }}" class="btn btn-primary">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            Nouveau ticket
        </a>
    </div>

    <!-- Tickets Table -->
    <div class="card">
        <div class="card-content p-0">
            @if($supports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ticket
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priority
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
                            @foreach($supports as $support)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 font-mono">{{ $support->ticket }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $support->subject }}</div>
                                    @if($support->messages && $support->messages->first())
                                    <div class="text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($support->messages->first()->message, 80) }}</div>
                                    @endif
                                    @if($support->last_reply)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Last reply: {{ \Carbon\Carbon::parse($support->last_reply)->format('d/m/Y H:i') }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch($support->status) {
                                            case 0:
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                $statusText = 'Open';
                                                break;
                                            case 1:
                                                $statusClass = 'bg-blue-100 text-blue-800';
                                                $statusText = 'Client Reply';
                                                break;
                                            case 2:
                                                $statusClass = 'bg-green-100 text-green-800';
                                                $statusText = 'Admin Reply';
                                                break;
                                            case 3:
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                $statusText = 'Closed';
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($support->priority)
                                        @php
                                            $priorityClass = '';
                                            $priorityText = '';
                                            switch($support->priority) {
                                                case 1:
                                                    $priorityClass = 'bg-red-100 text-red-800';
                                                    $priorityText = 'High';
                                                    break;
                                                case 2:
                                                    $priorityClass = 'bg-orange-100 text-orange-800';
                                                    $priorityText = 'Medium';
                                                    break;
                                                case 3:
                                                    $priorityClass = 'bg-green-100 text-green-800';
                                                    $priorityText = 'Low';
                                                    break;
                                                default:
                                                    $priorityClass = 'bg-gray-100 text-gray-800';
                                                    $priorityText = 'Normal';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityClass }}">
                                            {{ $priorityText }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">Normal</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $support->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ localized_route('influencer.ticket.view', $support->ticket) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        @if($support->status != 3)
                                        <form method="POST" action="{{ localized_route('influencer.ticket.close', $support->ticket) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Close"
                                                    onclick="return confirm('Are you sure you want to close this ticket?')">
                                                <i data-lucide="x" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($supports->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $supports->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i data-lucide="headphones" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tickets found</h3>
                    <p class="text-gray-600 mb-4">You haven't created any support tickets yet.</p>
                    <a href="{{ localized_route('influencer.ticket.open') }}" class="btn btn-primary">
                        <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                        Create ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection