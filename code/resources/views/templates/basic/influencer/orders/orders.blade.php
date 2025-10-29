@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Manage your service orders</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card">
        <div class="card-content p-0">
            <div class="flex space-x-0 border-b border-gray-200">
                <a href="{{ localized_route('influencer.service.order.index') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.service.order.index' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All
                </a>
                <a href="{{ localized_route('influencer.service.order.pending') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.service.order.pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending
                    @if($pendingOrder > 0)
                        <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $pendingOrder }}</span>
                    @endif
                </a>
                <a href="{{ localized_route('influencer.service.order.inprogress') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.service.order.inprogress' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    In Progress
                </a>
                <a href="{{ localized_route('influencer.service.order.jobDone') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.service.order.jobDone' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Completed
                </a>
                <a href="{{ localized_route('influencer.service.order.completed') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.service.order.completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Finished
                </a>
                <a href="{{ localized_route('influencer.service.order.cancelled') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.service.order.cancelled' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Cancelled
                </a>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="card">
        <div class="card-content">
            <form method="GET" class="flex gap-3">
                <div class="flex-1">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by order number or username..."
                           class="input w-full">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="search" class="mr-2 h-4 w-4"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-ghost">
                        <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-content p-0">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Service
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
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_no }}</div>
                                    @if($order->review)
                                    <div class="flex items-center mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="h-3 w-3 {{ $i <= $order->review->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="ml-1 text-xs text-gray-500">({{ $order->review->rating }})</span>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ $order->service->title ?? 'Service supprimé' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->user->username }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ showAmount($order->amount) }} {{ $general->cur_text ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch($order->status) {
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
                                                $statusClass = 'bg-orange-100 text-orange-800';
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ localized_route('influencer.service.order.detail', $order->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        @if($order->status == 1)
                                            <form method="POST" action="{{ localized_route('influencer.service.order.accept.status', $order->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Accepter">
                                                    <i data-lucide="check" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ localized_route('influencer.service.order.cancel.status', $order->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                        title="Annuler"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                                    <i data-lucide="x" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($order->status == 2)
                                            <form method="POST" action="{{ localized_route('influencer.service.order.jobDone.status', $order->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-900" title="Marquer terminé">
                                                    <i data-lucide="check-circle" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ localized_route('influencer.service.order.conversation.view', $order->id) }}"
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
                @if($orders->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i data-lucide="package" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
                    <p class="text-gray-600">
                        @if(request('search'))
                            Aucune commande ne correspond à votre recherche.
                        @else
                            Vous n'avez pas encore reçu de commandes.
                        @endif
                    </p>
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