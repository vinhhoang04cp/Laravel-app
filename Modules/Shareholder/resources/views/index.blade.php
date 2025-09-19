@extends('master')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endpush

@section('content')
    @if(session('toast'))
        <div class="toastr-message"
             data-type="{{ session('toast.type') }}"
             data-message="{{ session('toast.message') }}">
        </div>
    @endif

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Danh sách Cổ đông</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('shareholders.create') }}" class="btn btn-success">+ Tạo mới</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách Cổ đông</h3>
                </div>
                <div class="card-body">
                    <table id="shareholders-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>Họ tên</th>
                            <th>Số ĐKSH</th>
                            <th>Ngày cấp</th>
                            <th>Địa chỉ liên hệ</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Quốc tịch</th>
                            <th>Cộng</th>
                            <th>Ngày giao dịch</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Dữ liệu sẽ được load bằng Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- DataTables & Plugins -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/plugins/jszip/jszip.min.js"></script>
    <script src="/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="/plugins/toastr/toastr.min.js"></script>
    <script src="/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
        $(function () {
            $('#shareholders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('shareholders.list') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `<input type="checkbox" class="select-row" value="${data}">`;
                        }
                    },
                    {data: 'full_name', name: 'full_name'},
                    {data: 'ownership_registration_number', name: 'ownership_registration_number'},
                    {data: 'ownership_registration_issue_date', name: 'ownership_registration_issue_date'},
                    {data: 'address', name: 'address'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'nationality', name: 'nationality'},
                    {data: 'shares', name: 'shares'},
                    {data: 'transaction_date', name: 'transaction_date'},
                    {data: 'email_status', name: 'email_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#shareholders-table_wrapper .col-md-6:eq(0)');
        })

        $(document).on('click', '.btn-delete-shareholder', function () {
            const shareholderId = $(this).data('id');
            const shareholderName = $(this).data('name');

            Swal.fire({
                title: 'Bạn có chắc?',
                html: `Bạn muốn xoá cổ đông <strong>${shareholderName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Huỷ',
                confirmButtonColor: '#e3342f'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/shareholders/${shareholderId}/delete`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            $('#shareholders-table').DataTable().ajax.reload(null, false);

                            toastr.success('Xoá nội dung thành công!');
                        },
                        error: function (xhr) {
                            toastr.error('Xoá nội dung dùng thất bại!');
                        }
                    });
                }
            });
        });

        $(document).on('click', '#sendMailBtn', function() {
            alert('cuongpt');
            let id = $(this).data('id');
            let name = $(this).data('name');

            if (confirm("Bạn có chắc muốn gửi thư mời cho cổ đông: " + name + " ?")) {
                $.ajax({
                    url: '/shareholders/' + id + '/invite',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        alert(res.success);
                        $('#shareholders-table').DataTable().ajax.reload(); // reload datatable
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.error || 'Có lỗi xảy ra');
                    }
                });
            }
        });
    </script>
@endpush
