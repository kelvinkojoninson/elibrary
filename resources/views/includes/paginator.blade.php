@if ($paginator->lastPage() > 1)
    <div class="dc-paginationvtwo">
        <nav class="dc-pagination">
            <ul>
                @if ($paginator->currentPage() > 1)
                    <li class="dc-prevpage"><a href="{{ $paginator->url($paginator->currentPage() - 1) }}"><i class="lnr lnr-chevron-left"></i></a>
                    </li>
                @endif

                @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                    <li class="{{ $paginator->currentPage() == $i ? 'dc-active' : '' }}"><a
                            href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endfor

                @if ($paginator->currentPage() < $paginator->lastPage())
                    <li class='dc-nextpage'><a href="{{ $paginator->url($paginator->currentPage() + 1) }}"><i
                                class="lnr lnr-chevron-right"></i></a></li>
                @endif
            </ul>
        </nav>
    </div>
@endif