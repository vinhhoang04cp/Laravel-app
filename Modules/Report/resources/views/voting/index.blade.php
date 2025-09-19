@extends('voyager::master')

@section('css')
    <style>
        [class^="voyager-"], [class*=" voyager-"] {
            position: relative;
            top: 2px;
        }
    </style>
@endsection

@section('content')
    @if(session('toastr'))
        <div class="toastr-message"
             data-type="{{ session('toastr.type') }}"
             data-message="{{ session('toastr.message') }}">
        </div>
    @endif

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Báo cáo biểu quyết</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="display: flex;align-items: center;">
                        <div class="col-sm-6">
                            <label for="congress_id" class="form-label">Kỳ đại hội</label>
                            <select id="congress_id" class="form-control">
                                <option value="">-- Chọn kỳ đại hội --</option>
                                @foreach($congresses as $congress)
                                    <option value="{{ $congress->id }}">{{ $congress->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6" style="margin-bottom: 0">
                            <button class="btn btn-primary" id="btn-search">
                                <i class="voyager-search"></i> <span>Tìm kiếm</span>
                            </button>
                            <button class="btn btn-success" id="btn-export">
                                <i class="voyager-download"></i> <span>Xuất báo cáo</span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><strong>Số CĐ tham dự:</strong> <span id="attendee_count">0</span></div>
                        <div class="col-md-3"><strong>Tổng số CP của CĐ tham dự:</strong> <span
                                id="attended_shares">0</span></div>
                        <div class="col-md-3"><strong>Tổng CP vốn:</strong> <span id="total_shares">0</span></div>
                        <div class="col-md-3"><strong>Tỷ lệ:</strong> <span id="percent_shares">0%</span></div>
                    </div>
                    <table class="table table-bordered text-center" id="vote-report-table">
                        <thead>
                        <tr>
                            <th rowspan="2">STT</th>
                            <th rowspan="2">Nội dung biểu quyết</th>
                            <th colspan="3">Tán thành</th>
                            <th colspan="3">Không tán thành</th>
                            <th colspan="3">Không ý kiến</th>
                        </tr>
                        <tr>
                            <th>Tổng SL</th>
                            <th>Tổng CP</th>
                            <th>Tỷ lệ (%)</th>
                            <th>Tổng SL</th>
                            <th>Tổng CP</th>
                            <th>Tỷ lệ (%)</th>
                            <th>Tổng SL</th>
                            <th>Tổng CP</th>
                            <th>Tỷ lệ (%)</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('javascript')
    <script>
        $(function () {
            $('#btn-search').on('click', function () {
                let congressId = $('#congress_id').val();
                if (!congressId) {
                    alert('Vui lòng chọn kỳ đại hội');
                    return;
                }

                $.ajax({
                    url: '{{ route("reports.voting.load") }}',
                    data: {congress_id: congressId},
                    success: function (res) {
                        if (!res.success) {
                            alert('Không lấy được dữ liệu');
                            return;
                        }

                        const data = res.data;

                        // Cập nhật tổng quan
                        $('#attendee_count').text(data.attendee_count);
                        $('#attended_shares').text(data.attended_shares);
                        $('#total_shares').text(data.total_shares);
                        let percentShares = data.total_shares > 0
                            ? (data.attended_shares / data.total_shares * 100).toFixed(2)
                            : 0;
                        $('#percent_shares').text(percentShares + '%');

                        // Cập nhật bảng vote report
                        const tbody = $('#vote-report-table tbody');
                        tbody.empty();

                        data.sessions.forEach((session, index) => {
                            const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${session.vote_session_name ?? 'Chưa đặt tên'}</td>
                            <td>${session.total_vote_yes}</td>
                            <td>${session.total_shares_yes}</td>
                            <td>${session.vote_yes_ratio.toFixed(2)}%</td>
                            <td>${session.total_vote_no}</td>
                            <td>${session.total_shares_no}</td>
                            <td>${session.vote_no_ratio.toFixed(2)}%</td>
                            <td>${session.total_vote_abstain}</td>
                            <td>${session.total_shares_abstain}</td>
                            <td>${session.vote_abstain_ratio.toFixed(2)}%</td>
                        </tr>
                    `;
                            tbody.append(row);
                        });
                    },
                    error: function () {
                        alert('Lỗi khi tải dữ liệu');
                    }
                });
            });

            $('#btn-export').on('click', function () {
                let congressId = $('#congress_id').val();
                if (!congressId) {
                    alert('Vui lòng chọn kỳ đại hội');
                    return;
                }
                // Chuyển congressId dưới dạng query param
                window.location.href = `/admin/reports/voting/export?congress_id=${congressId}`;
            });
        });
    </script>
@endpush

