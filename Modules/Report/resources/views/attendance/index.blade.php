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
                    <h1>Báo cáo tham dự</h1>
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
                    <table class="table table-bordered text-center" id="attendance-report-table">
                        <thead>
                        <tr>
                            <th>Số cổ đông ủy quyền</th>
                            <th>Tổng số cổ phần ủy quyền</th>
                            <th>Số cổ đông tham dự</th>
                            <th>Tổng số cổ phần tham dự</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="col-md-8">
                        <strong class="col-md-8 text-right">Vốn điều lệ:</strong>
                        <div id="total_shares" class="col-md-4 text-center">0</div>
                    </div>
                    <div class="col-md-8">
                        <strong class="col-md-8 text-right">Tổng số lượng cổ phần:</strong>
                        <div id="participated_shares" class="col-md-4 text-center">0</div>
                    </div>
                    <div class="col-md-8">
                        <strong class="col-md-8 text-right">Tỷ lệ tham dự ĐHCĐ:</strong>
                        <span id="participation_rate" class="col-md-4 text-center">0.00%</span>
                    </div>
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
                    url: '{{ route("reports.attendances.load") }}',
                    data: {congress_id: congressId},
                    success: function (res) {
                        if (!res.success) {
                            alert('Không lấy được dữ liệu');
                            return;
                        }

                        const data = res.data;

                        // Cập nhật tổng quan
                        $('#total_shares').text(data.total_shares);
                        $('#participated_shares').text(data.participated_shares);
                        $('#participation_rate').text(data.participation_rate + '%');

                        // Cập nhật bảng vote report
                        const tbody = $('#attendance-report-table tbody');
                        tbody.empty();

                        const row = `
                        <tr>
                            <td>${data.proxy_count}</td>
                            <td>${data.proxy_shares}</td>
                            <td>${data.attended_count}</td>
                            <td>${data.attended_shares}</td>
                        </tr>
                    `;
                        tbody.append(row);
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
                window.location.href = `/admin/reports/attendances/export?congress_id=${congressId}`;
            });
        });
    </script>
@endpush

