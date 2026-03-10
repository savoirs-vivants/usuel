@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">

            <div>
                <p class="text-sm text-gray-500 font-medium">
                    Affichage de
                    <span class="font-bold text-sv-blue">{{ $paginator->firstItem() }}</span>
                    à
                    <span class="font-bold text-sv-blue">{{ $paginator->lastItem() }}</span>
                    sur
                    <span class="font-bold text-sv-blue">{{ $paginator->total() }}</span>
                    résultats
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-xl">

                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Précédent">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-l-xl" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-bold text-gray-500 bg-white border border-gray-200 rounded-l-xl hover:bg-gray-50 hover:text-sv-blue transition-colors" aria-label="Précédent">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed">{{ $element }}</span>
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-sv-blue bg-sv-blue/10 border border-sv-blue/20 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:text-sv-blue transition-colors" aria-label="Page {{ $page }}">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-bold text-gray-500 bg-white border border-gray-200 rounded-r-xl hover:bg-gray-50 hover:text-sv-blue transition-colors" aria-label="Suivant">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Suivant">
                            <span class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-r-xl" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
