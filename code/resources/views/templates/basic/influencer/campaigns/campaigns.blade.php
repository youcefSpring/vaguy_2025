@extends('templates.basic.layouts.influencer')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $pageTitle }}</h1>
            <p class="text-gray-600">Manage your advertising campaigns</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card">
        <div class="card-content p-0">
            <div class="flex space-x-0 border-b border-gray-200">
                <a href="{{ localized_route('influencer.campain.index') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.campain.index' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All
                </a>
                <a href="{{ localized_route('influencer.campain.pending') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.campain.pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending
                </a>
                <a href="{{ localized_route('influencer.campain.inprogress') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.campain.inprogress' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    In Progress
                </a>
                <a href="{{ localized_route('influencer.campain.jobDone') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.campain.jobDone' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Completed
                </a>
                <a href="{{ localized_route('influencer.campain.completed') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.campain.completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Finished
                </a>
                <a href="{{ localized_route('influencer.campain.cancelled') }}"
                   class="px-6 py-3 text-sm font-medium border-b-2 {{ request()->route()->getName() == 'influencer.campain.cancelled' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Cancelled
                </a>
            </div>
        </div>
    </div>

    <!-- Campaigns Table -->
    <div class="card">
        <div class="card-content p-0">
            @if($campains->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Campaign
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Client
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Your Offer
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
                            @foreach($campains as $campain)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $campain->title }}</div>
                                    <div class="text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($campain->description, 80) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $campain->user->username }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch($campain->status) {
                                            case 'pending':
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                $statusText = 'Pending';
                                                break;
                                            case 'inprogress':
                                                $statusClass = 'bg-blue-100 text-blue-800';
                                                $statusText = 'In Progress';
                                                break;
                                            case 'JobDone':
                                                $statusClass = 'bg-green-100 text-green-800';
                                                $statusText = 'Completed';
                                                break;
                                            case 'completed':
                                                $statusClass = 'bg-purple-100 text-purple-800';
                                                $statusText = 'Finished';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'bg-red-100 text-red-800';
                                                $statusText = 'Cancelled';
                                                break;
                                            default:
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                $statusText = ucfirst($campain->status ?? 'Unknown');
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($campain->campain_offers->isNotEmpty())
                                        @php $offer = $campain->campain_offers->first(); @endphp
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ showAmount($offer->price) }} {{ $general->cur_text ?? '' }}
                                        </div>
                                        @php
                                            $offerStatusClass = '';
                                            $offerStatusText = '';
                                            switch($offer->status) {
                                                case 'pending':
                                                    $offerStatusClass = 'bg-yellow-100 text-yellow-800';
                                                    $offerStatusText = 'Pending';
                                                    break;
                                                case 'accepted':
                                                    $offerStatusClass = 'bg-green-100 text-green-800';
                                                    $offerStatusText = 'Accepted';
                                                    break;
                                                case 'rejected':
                                                    $offerStatusClass = 'bg-red-100 text-red-800';
                                                    $offerStatusText = 'Rejected';
                                                    break;
                                                default:
                                                    $offerStatusClass = 'bg-gray-100 text-gray-800';
                                                    $offerStatusText = ucfirst($offer->status ?? 'Unknown');
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $offerStatusClass }}">
                                            {{ $offerStatusText }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">No offer</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $campain->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ localized_route('influencer.campain.detail', $campain->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        @if($campain->campain_offers->isNotEmpty())
                                            @php $offer = $campain->campain_offers->first(); @endphp
                                            @if($offer->status == 'pending')
                                                <form method="POST" action="{{ localized_route('influencer.campain.change_offer_status_influencer', [$offer->id, 'accepted']) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Accepter">
                                                        <i data-lucide="check" class="h-4 w-4"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ localized_route('influencer.campain.change_offer_status_influencer', [$offer->id, 'rejected']) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Refuser">
                                                        <i data-lucide="x" class="h-4 w-4"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <button onclick="showOfferForm({{ $campain->id }})" class="text-blue-600 hover:text-blue-900" title="Make an offer">
                                                <i data-lucide="send" class="h-4 w-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <!-- Offer form row (hidden by default) -->
                            @if($campain->campain_offers->isEmpty())
                            <tr id="offer-form-{{ $campain->id }}" class="hidden bg-gray-50">
                                <td colspan="6" class="px-6 py-4">
                                    <form method="POST" action="{{ localized_route('influencer.campain.post_offer') }}" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="campain_id" value="{{ $campain->id }}">
                                        <div class="flex-1">
                                            <input type="number"
                                                   name="offer"
                                                   placeholder="Your offer amount"
                                                   class="input w-full"
                                                   required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i data-lucide="send" class="mr-1 h-3 w-3"></i>
                                            Submit
                                        </button>
                                        <button type="button" onclick="hideOfferForm({{ $campain->id }})" class="btn btn-ghost btn-sm">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($campains->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $campains->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i data-lucide="megaphone" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No campaigns found</h3>
                    <p class="text-gray-600">There are currently no campaigns available.</p>
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

function showOfferForm(campainId) {
    const offerRow = document.getElementById('offer-form-' + campainId);
    if (offerRow) {
        offerRow.classList.remove('hidden');
    }
}

function hideOfferForm(campainId) {
    const offerRow = document.getElementById('offer-form-' + campainId);
    if (offerRow) {
        offerRow.classList.add('hidden');
    }
}
</script>
@endpush
@endsection