@if ($paginator->hasPages())
    <nav class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 w-100" role="navigation" aria-label="Navigasi Halaman">
        <div class="text-muted small text-center text-md-start">
            Menampilkan
            <span class="fw-semibold text-dark">{{ $paginator->firstItem() }}</span>
            sampai
            <span class="fw-semibold text-dark">{{ $paginator->lastItem() }}</span>
            dari
            <span class="fw-semibold text-dark">{{ $paginator->total() }}</span>
            data
        </div>

        <div>
            <ul class="pagination pagination-sm mb-0 flex-wrap justify-content-center justify-content-md-end gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="Sebelumnya">
                        <span class="page-link rounded-2 px-2.5 py-1.5 d-inline-flex align-items-center gap-1" aria-hidden="true">
                            <i class="bi bi-chevron-left"></i>
                            <span class="d-none d-sm-inline">Sebelumnya</span>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link rounded-2 px-2.5 py-1.5 d-inline-flex align-items-center gap-1" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">
                            <i class="bi bi-chevron-left"></i>
                            <span class="d-none d-sm-inline">Sebelumnya</span>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link rounded-2 px-2.5 py-1.5">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link rounded-2 px-2.5 py-1.5 fw-bold">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-2 px-2.5 py-1.5" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link rounded-2 px-2.5 py-1.5 d-inline-flex align-items-center gap-1" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya">
                            <span class="d-none d-sm-inline">Berikutnya</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="Berikutnya">
                        <span class="page-link rounded-2 px-2.5 py-1.5 d-inline-flex align-items-center gap-1" aria-hidden="true">
                            <span class="d-none d-sm-inline">Berikutnya</span>
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif

