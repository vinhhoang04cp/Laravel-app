@extends('voyager::master')

@section('css')
    <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
                    <h1>Danh sách Kỳ đại hội</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="col-sm-6">
                        <a href="{{ route('congresses.create') }}" class="btn btn-primary">
                            <i class="voyager-plus"></i> <span>Tạo mới</span>
                        </a>
                        <button class="btn btn-success" data-toggle="modal" data-target="#importFileModal">
                            <i class="voyager-upload"></i> <span>Import Excel</span>
                        </button>
                    </div>
                    <table id="congresses-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Ngày tạo</th>
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

    <!-- Modal -->
    <div class="modal fade" id="importFileModal" tabindex="-1" role="dialog" aria-labelledby="importFileModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="importFileForm" enctype="multipart/form-data" action="{{ route('congresses.import') }}"
                  method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title">Import file</h5>
                    </div>

                    <div class="modal-body">
                        <!-- Tên kỳ đại hội -->
                        <div class="form-group">
                            <label for="congress_id">Tên kỳ đại hội</label>
                            <select name="congress_id" id="congress_id" class="form-control" required>
                                <option value="">-- Chọn kỳ đại hội --</option>
                            </select>
                        </div>

                        <!-- File import -->
                        <div class="form-group">
                            <label for="excel_file">Chọn file import</label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control-file"
                                   accept=".xls,.xlsx" style="width: 100%;" required>
                        </div>

                        <!-- Link tải template -->
                        <div class="form-group">
                            <a href="{{ asset('templates/template_danh_sach_co_dong.xlsx') }}" download>
                                Tải template mẫu
                            </a>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Import file</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('javascript')
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
            var $toastr = $('.toastr-message');
            if ($toastr.length) {
                var type = $toastr.data('type');     // success | error | warning | info
                var message = $toastr.data('message');
                if (type && message) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 6000,
                        positionClass: "toast-top-right"
                    };
                    toastr[type](message);
                }
            }
        });

        $(function () {
            $('#congresses-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('congresses.list') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });
        })

        $(document).on('click', '.btn-delete-congress', function () {
            const congressId = $(this).data('id');
            const congressName = $(this).data('name');

            Swal.fire({
                title: 'Bạn có chắc?',
                html: `Bạn muốn xoá kỳ đại hội <strong>${congressName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Huỷ',
                confirmButtonColor: '#e3342f'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/congresses/${congressId}/delete`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            $('#congresses-table').DataTable().ajax.reload(null, false);

                            toastr.success('Xoá nội dung thành công!');
                        },
                        error: function (xhr) {
                            toastr.error('Xoá nội dung dùng thất bại!');
                        }
                    });
                }
            });
        });

        $('#importFileModal').on('show.bs.modal', function () {

            let $select = $('#congress_id');
            $select.html('<option value="">-- Chọn kỳ đại hội --</option>');

            $.get('{{ route("congresses.list") }}', function (res) {
                res.data.forEach(function (item) {
                    $select.append(`<option value="${item.id}">${item.name}</option>`);
                });
            });
        });

        $('#importFileModal').on('hidden.bs.modal', function () {
            let $select = $('#congress_id');
            $select.html('<option value="">-- Chọn kỳ đại hội --</option>');

            // Nếu có input file thì clear luôn
            $(this).find('input[type="file"]').val('');
        });
    </script>
@endpush

