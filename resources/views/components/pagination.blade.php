@if ($paginator->hasPages())
    <nav class="sc-pagination-nav" role="navigation" aria-label="Paginacao">
        <ul class="sc-pagination-list">
            @if ($paginator->onFirstPage())
                <li class="sc-page-item disabled" aria-disabled="true">
                    <span class="sc-page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="sc-page-item">
                    <a class="sc-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Pagina anterior">&laquo;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="sc-page-item disabled" aria-disabled="true">
                        <span class="sc-page-link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="sc-page-item active" aria-current="page">
                                <span class="sc-page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="sc-page-item">
                                <a class="sc-page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="sc-page-item">
                    <a class="sc-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Proxima pagina">&raquo;</a>
                </li>
            @else
                <li class="sc-page-item disabled" aria-disabled="true">
                    <span class="sc-page-link" aria-hidden="true">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
