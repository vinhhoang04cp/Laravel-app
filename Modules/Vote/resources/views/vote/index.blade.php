@extends('voyager::master')

@section('page_title', 'Danh sách phiếu bầu')

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="row">
            <!-- Khối lọc -->
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="voyager-filter"></i> Bộ lọc
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('vote.ui.votes.index') }}">
                            <div class="form-row">
                                <!-- Kỳ đại hội -->
                                <div class="form-group col-md-4">
                                    <label for="congress_id" class="font-weight-bold">Kỳ Đại hội</label>
                                    <select name="congress_id" id="congress_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">-- Chọn kỳ đại hội --</option>
                                        @foreach($congresses as $congress)
                                            <option value="{{ $congress->id }}" {{ request('congress_id') == $congress->id ? 'selected' : '' }}>
                                                {{ $congress->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Phiên bỏ phiếu -->
                                <div class="form-group col-md-4">
                                    <label for="session_id" class="font-weight-bold">Phiên bỏ phiếu</label>
                                    <select name="session_id" id="session_id" class="form-control">
                                        <option value="">-- Chọn phiên bỏ phiếu --</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                                                {{ $session->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nút hành động -->
                                <div class="form-group col-md-4 d-flex align-items-end">
                                    <div>
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="voyager-search"></i> Lọc
                                        </button>
                                        <a href="{{ route('vote.ui.votes.index') }}" class="btn btn-outline-secondary">
                                            <i class="voyager-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bảng phiếu bầu -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Danh sách phiếu bầu</h3>
                    </div>
                    <div class="card-body">
                        @if(request()->filled('congress_id') && request()->filled('session_id'))
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Cổ đông</th>
                                        <th>Lựa chọn</th>
                                        <th>Số cổ phần</th>
                                        <th>Kỳ Đại hội</th>
                                        <th>Phiên bỏ phiếu</th>
                                        <th>Ngày tạo</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($votes as $vote)
                                        <tr>
                                            <td>{{ $vote->id }}</td>
                                            <td>{{ $vote->shareholder->full_name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $vote->choice === 'yes' ? 'success' : ($vote->choice === 'no' ? 'danger' : 'secondary') }}">
                                                    {{ ucfirst($vote->choice) }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($vote->shares) }}</td>
                                            <td>{{ $vote->session->congress->name ?? 'N/A' }}</td>
                                            <td>{{ $vote->session->title ?? 'N/A' }}</td>
                                            <td>{{ $vote->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                Không có dữ liệu
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $votes->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="voyager-info-circled"></i> Vui lòng chọn <b>Kỳ Đại hội</b> và <b>Phiên bỏ phiếu</b> để xem danh sách phiếu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
