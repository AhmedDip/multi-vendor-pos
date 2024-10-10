{{--@if ($paginator->hasPages())--}}
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination pagination-sm">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.previous')</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.next')</span>
                    </li>
                @endif
            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <p class="small text-muted">
                    {!! __('Showing') !!}
                    <span class="fw-semibold">{{ number($paginator->firstItem()) }}</span>
                    {!! __('to') !!}
                    <span class="fw-semibold">{{ number($paginator->lastItem()) }}</span>
                    {!! __('of') !!}
                    <span class="fw-semibold">{{ number($paginator->total()) }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div class="d-flex">
                <div class="per-page me-2">
                    {{html()->select('per_page', [10=>number(10), 25=>number(25), 50=>number(50), 100=>number(100)], $search['per_page'] ?? 25)->class('form-select form-select-sm')->id('per_page_input') }}
                </div>

                <ul class="pagination pagination-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->appends(request()->query())->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->appends(request()->query())->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ number($page) }}</span></li>
                                @else
                                    @php
                                    $query_string = request()->query();
                                    unset($query_string['page']);
                                        $formatted_url = $url.'&'.http_build_query($query_string);
                                    @endphp
                                    <li class="page-item"><a class="page-link" href="{{ $formatted_url }}">{{ number($page) }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->appends(request()->query())->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
{{--@endif--}}
