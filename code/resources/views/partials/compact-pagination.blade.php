@if (isset($paginator) && $paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-10 h-10 text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">
                <i data-lucide="chevron-right" class="h-5 w-5"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="pagination-link inline-flex items-center justify-center w-10 h-10 text-gray-700 bg-white border-2 border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-500 hover:text-purple-600 transition-all duration-200 hover:shadow-md"
               rel="prev" aria-label="@lang('Previous')">
                <i data-lucide="chevron-right" class="h-5 w-5"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @php
            $start = max($paginator->currentPage() - 2, 1);
            $end = min($paginator->currentPage() + 2, $paginator->lastPage());
        @endphp

        @if($start > 1)
            <a href="{{ $paginator->url(1) }}"
               class="pagination-link hidden sm:inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-500 hover:text-purple-600 transition-all duration-200">
                1
            </a>
            @if($start > 2)
                <span class="hidden sm:inline-flex items-center text-gray-400">...</span>
            @endif
        @endif

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $paginator->currentPage())
                <span aria-current="page"
                      class="inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $paginator->url($page) }}"
                   class="pagination-link hidden sm:inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-500 hover:text-purple-600 transition-all duration-200"
                   aria-label="@lang('Go to page :page', ['page' => $page])">
                    {{ $page }}
                </a>
            @endif
        @endfor

        @if($end < $paginator->lastPage())
            @if($end < $paginator->lastPage() - 1)
                <span class="hidden sm:inline-flex items-center text-gray-400">...</span>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}"
               class="pagination-link hidden sm:inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-500 hover:text-purple-600 transition-all duration-200">
                {{ $paginator->lastPage() }}
            </a>
        @endif

        {{-- Mobile Page Info --}}
        <span class="sm:hidden inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg">
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="pagination-link inline-flex items-center justify-center w-10 h-10 text-gray-700 bg-white border-2 border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-500 hover:text-purple-600 transition-all duration-200 hover:shadow-md"
               rel="next" aria-label="@lang('Next')">
                <i data-lucide="chevron-left" class="h-5 w-5"></i>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-10 h-10 text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">
                <i data-lucide="chevron-left" class="h-5 w-5"></i>
            </span>
        @endif
    </nav>
@endif