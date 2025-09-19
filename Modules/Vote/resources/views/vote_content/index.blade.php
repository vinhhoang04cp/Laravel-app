@extends('voyager::master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection

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
                    <h1>Danh sách nội dung phiếu bầu</h1>
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
                        <button class="btn btn-success mr-3" data-toggle="modal" data-target="#createModal"
                                id="btnCreateVoteContent">
                            <i class="voyager-plus"></i> Thêm mới
                        </button>
                    </div>
                    <table id="votes-content-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nội dung</th>
                            <th>Loại</th>
                            <th>Hành động</th> {{-- Cột mới --}}
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
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="voteContentModalLabel">Tạo nội dung phiếu bầu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="voteContentForm">
                        @csrf
                        <input type="hidden" name="vote_content_id" id="vote_content_id">

                        <!-- Tên kỳ đại hội -->
                        <div class="form-group">
                            <label for="congress_id">Tên kỳ đại hội</label>
                            <select name="congress_id" id="congress_id" class="form-control" required>
                                <option value="">-- Chọn kỳ đại hội --</option>
                            </select>
                        </div>

                        <!-- Loại -->
                        <div class="form-group">
                            <label for="type">Loại</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">-- Chọn loại --</option>
                                <option value="Phiếu biểu quyết">Phiếu biểu quyết</option>
                                <option value="Phiếu bầu thành viên HĐQT">Phiếu bầu thành viên HĐQT</option>
                            </select>
                        </div>

                        <!-- Nội dung -->
                        <div class="form-group">
                            <label for="title">Nội dung</label>
                            <textarea class="form-control" id="title" name="title" rows="4" required></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" form="voteContentForm" class="btn btn-primary">Lưu</button>
                </div>
            </div>
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
            const toastEl = $('.toastr-message');
            if (toastEl.length) {
                const type = toastEl.data('type');
                const message = toastEl.data('message');

                // Map class name theo loại
                let classMap = {
                    success: 'toastrDefaultSuccess',
                    error: 'toastrDefaultError',
                    warning: 'toastrDefaultWarning',
                    info: 'toastrDefaultInfo'
                };

                let className = classMap[type] || 'toastrDefaultInfo';

                // Tạo toast bằng toastr class định nghĩa sẵn
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "timeOut": "3000"
                };
                toastr[className.replace('toastrDefault', '').toLowerCase()](message);
            }
        });

        $(function () {
            $('#votes-content-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('votes.content.list') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'type', name: 'type'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}, // cột mới
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#votes-content-table_wrapper .col-md-6:eq(0)');
        });

        $(document).on('click', '.btn-delete-content', function () {
            const roleId = $(this).data('id');
            const roleName = $(this).data('name');

            Swal.fire({
                title: 'Bạn có chắc?',
                html: `Bạn muốn xoá <strong>${roleName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Huỷ',
                confirmButtonColor: '#e3342f'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/votes/content/${roleId}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            $('#roles-table').DataTable().ajax.reload(null, false);

                            toastr.success('Xoá role thành công!');
                        },
                        error: function (xhr) {
                            toastr.error('Xoá role thất bại!');
                        }
                    });
                }
            });
        });

        let saveMethod = 'create'; // mặc định là create

        function loadCongressOptions(selectedId = '') {
            let $select = $('#congress_id');
            $select.html('<option value="">-- Chọn kỳ đại hội --</option>');

            return $.get('{{ route("congresses.list") }}', function (res) {
                res.data.forEach(function (item) {
                    $select.append(`<option value="${item.id}">${item.name}</option>`);
                });

                if (selectedId) {
                    $select.val(selectedId).trigger('change');
                }
            });
        }

        // Mở modal thêm mới
        $('#btnCreateVoteContent').on('click', function () {
            saveMethod = 'create';
            $('#voteContentForm')[0].reset();
            $('#vote_content_id').val('');
            $('#voteContentModalLabel').text('Thêm mới');
            loadCongressOptions(); // load dropdown rỗng
        });

        // Mở modal update
        $(document).on('click', '.btn-edit', function () {
            saveMethod = 'update';
            let id = $(this).data('id');
            let url = `{{ route('votes.content.detail', ':id') }}`.replace(':id', id);

            $.get(url, function (data) {
                $('#vote_content_id').val(data.id);
                $('#type').val(data.type);
                $('#title').val(data.title);

                // Load dropdown và set selected
                loadCongressOptions(data.congress_id);

                $('#voteContentModalLabel').text('Chỉnh sửa');
                $('#createModal').modal('show');
            });
        });

        // Submit form
        $('#voteContentForm').on('submit', function (e) {
            e.preventDefault();
            let formData = $(this).serialize();

            if (saveMethod === 'create') {
                $.post('{{ route('votes.content.store') }}', formData, function () {
                    $('#createModal').modal('hide');
                    $('#votes-content-table').DataTable().ajax.reload();
                });
            } else {
                let id = $('#vote_content_id').val();
                let url = `{{ route('votes.content.update', ':id') }}`.replace(':id', id);

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: formData,
                    success: function () {
                        $('#createModal').modal('hide');
                        $('#votes-content-table').DataTable().ajax.reload();
                    }
                });
            }
        });
    </script>
@endpush
