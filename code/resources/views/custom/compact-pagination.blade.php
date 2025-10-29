@if ($paginator->hasPages())
    <div class="flex items-center justify-between space-x-3 text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                <i data-lucide="chevron-left" class="h-4 w-4"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-3 py-1 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                <i data-lucide="chevron-left" class="h-4 w-4"></i>
            </a>
        @endif

        {{-- Page Info --}}
        <span class="text-gray-600 whitespace-nowrap">
            {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
        </span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-3 py-1 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
            </a>
        @else
            <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
            </span>
        @endif
    </div>
@endif