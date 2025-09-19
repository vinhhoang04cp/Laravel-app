@if ($results->hasPages() || $results->total() > 0)
    <div class="row align-items-center">

        {{-- Length menu --}}
        <div class="col-sm-2">
            <label>
                Show
                <select name="length" id="length" class="form-select form-select-sm d-inline-block" style="width: auto;"
                        onchange="changeLength(this.value)">
                    @foreach ([10, 25, 50, 100] as $len)
                        <option value="{{ $len }}" {{ request('length', $results->perPage()) == $len ? 'selected' : '' }}>
                            {{ $len }}
                        </option>
                    @endforeach
                </select>
                entries
            </label>
        </div>

        {{-- Info --}}
        <div class="col-sm-4">
            <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} entries
            </div>
        </div>

        {{-- Pagination --}}
        <div class="col-sm-6">
            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                <ul class="pagination justify-content-end mb-0">

                    {{-- Previous --}}
                    @if ($results->onFirstPage())
                        <li class="paginate_button previous disabled" id="dataTable_previous">
                            <a href="#" tabindex="0">Previous</a>
                        </li>
                    @else
                        <li class="paginate_button previous" id="dataTable_previous">
                            <a href="{{ $results->previousPageUrl() }}" tabindex="0">Previous</a>
                        </li>
                    @endif

                    {{-- Page numbers --}}
                    @for ($page = 1; $page <= $results->lastPage(); $page++)
                        @if ($page == $results->currentPage())
                            <li class="paginate_button active" tabindex="0">
                                <a href="#">{{ $page }}</a>
                            </li>
                        @else
                            <li class="paginate_button" tabindex="0">
                                <a href="{{ $results->url($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endfor

                    {{-- Next --}}
                    @if ($results->hasMorePages())
                        <li class="paginate_button next" id="dataTable_next">
                            <a href="{{ $results->nextPageUrl() }}" tabindex="0">Next</a>
                        </li>
                    @else
                        <li class="paginate_button next disabled" id="dataTable_next">
                            <a href="#" tabindex="0">Next</a>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>

    {{-- Script thay đổi số dòng --}}
    <script>
        function changeLength(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('length', val);
            url.searchParams.delete('page'); // reset về page 1 khi đổi length
            window.location.href = url.toString();
        }
    </script>
@endif
