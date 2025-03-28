@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="paginate_button page-item previous disabled" id="example2_previous">
                    <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                </li>
                {{-- <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true">&lsaquo;</span>
                </li> --}}
            @else
                <li class="paginate_button page-item previous disabled" id="example2_previous">
                    <a href="{{ $paginator->previousPageUrl() }}" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    {{-- <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li> --}}

                    <li class="paginate_button page-item disabled" aria-current="page">
                        <a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link"> {{ $element }} </a>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                        <li class="paginate_button page-item active" aria-current="page">
                            <a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link"> {{ $page}} </a>
                        </li>

                            {{-- <li class="active" aria-current="page"><span>{{ $page }}</span></li> --}}
                        @else
                            <li class="paginate_button page-item ">
                                <a href="{{ $url }}" aria-controls="example2" data-dt-idx="2" tabindex="0" class="page-link">{{ $page }}</a>
                            </li>
                            {{-- <li><a href="{{ $url }}">{{ $page }}</a></li> --}}
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                {{-- <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li> --}}

                <li class="paginate_button page-item next" id="example2_next">
                    <a href="{{ $paginator->nextPageUrl() }}" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                </li>
            @else
                <li class="paginate_button page-item next disabled" id="example2_next">
                    <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                </li>
                {{-- <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true">&rsaquo;</span>
                </li> --}}
            @endif
        </ul>
    </nav>
@endif
