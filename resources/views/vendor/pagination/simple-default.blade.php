@if ($paginator->hasPages())
    @vite('resources/css/components/pagination.css')
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="nav-btn disabled" aria-disabled="true"><a href="#">Previous</a></li>
            @else
                <li class="nav-btn"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a></li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="nav-btn"><a href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
            @else
                <li class="nav-btn disabled" aria-disabled="true"><a href="#">Next</a></li>
            @endif
        </ul>
    </nav>
@endif
