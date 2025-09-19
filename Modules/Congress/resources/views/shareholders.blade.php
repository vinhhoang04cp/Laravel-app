@extends('voyager::master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <style>
        #shareholders-table td,
        #shareholders-table th {
            white-space: nowrap;
        }

        [class^="voyager-"], [class*=" voyager-"] {
            position: relative;
            top: 2px;
        }

        .error {
            color: #dc3545;
            font-size: 90%;
        }

        .voyager table.dataTable thead th.sorting_asc:after {
            display: none;
        }

        /* Đặt đoạn này sau bootstrap.css để override */
        #shareholderModal .input-group {
            display: flex;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
        }

        /* Input chính */
        #shareholderModal .input-group .form-control {
            flex: 1;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: none; /* bỏ đường viền phải để nối liền với % */
            background-color: #fff; /* Ghi đè nền xám của readonly */
            box-shadow: none;
            height: calc(1.5em + .75rem); /* đảm bảo cao bằng bootstrap input */
        }

        /* Đặc biệt cho readonly (nếu bootstrap vẫn override) */
        #shareholderModal .input-group .form-control[readonly] {
            background-color: #fff !important;
            opacity: 1;
            cursor: default;
        }

        /* Container append chỉ chứa % */
        #shareholderModal .input-group-append {
            display: flex;
        }

        /* Ô chứa ký tự % */
        #shareholderModal .input-group-text {
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 0 .25rem .25rem 0;
            padding: 0 .75rem;
            display: flex;
            align-items: center;
            height: calc(1.5em + .75rem); /* đồng bộ chiều cao */
            color: #495057;
        }

    </style>
@endsection

@section('content')
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>Danh sách cổ đông</h1>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <button class="btn btn-success mr-2" data-toggle="modal" data-target="#shareholderModal"
                                id="btnAddShareholder">
                            <i class="voyager-plus"></i> Thêm mới
                        </button>
                        <button id="sendMailBtn" class="btn btn-warning">
                            <i class="voyager-mail"></i> Gửi mail
                        </button>
                        {{--                        <a href="{{ route('congresses.shareholders.ballots.export', ['congress_id' => $congressId]) }}"--}}
                        {{--                           class="btn btn-primary" target="_blank">--}}
                        {{--                            <i class="fa fa-file-pdf"></i> Xuất phiếu biểu quyết--}}
                        {{--                        </a>--}}
                    </div>
                    <div class="table-responsive">
                        <table id="shareholders-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Họ tên</th>
                                <th>Mã định dạnh NĐT (SID)</th>
                                <th>Mã nhà đầu tư (Investor code)</th>
                                <th>Số ĐKSH</th>
                                <th>Ngày cấp</th>
                                <th>Địa chỉ liên hệ</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>Quốc tịch</th>
                                <th>Số lượng chứng khoán nắm giữ (Chưa lưu ký)</th>
                                <th>Số lượng chứng khoán nắm giữ (Lưu ký)</th>
                                <th>Số lượng chứng khoán nắm giữ (Tổng cộng)</th>
                                <th>Số lượng quyền phân bổ (Chưa lưu ký)</th>
                                <th>Số lượng quyền phân bổ (Lưu ký)</th>
                                <th>Số lượng quyền phân bổ (Tổng cộng)</th>
                                <th>Tỷ lệ</th>
                                <th>Ngày giao dịch</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="shareholderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="shareholderModalTitle">Tạo thông tin cổ đông</h5>
                </div>

                <div class="modal-body">
                    <form id="shareholderForm">
                        @csrf
                        <input type="hidden" name="shareholder_id" id="shareholder_id">
                        <input type="hidden" id="congress_id" name="congress_id" value="{{ $congressId }}">
                        <input type="hidden" name="totalAllocation" id="totalAllocation">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Họ tên *</label>
                                <input type="text" name="full_name" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mã định danh NĐT (SID)</label>
                                <input type="text" name="sid" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mã nhà đầu tư (Investor code)</label>
                                <input type="text" name="investor_code" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số ĐKSH *</label>
                                <input type="text" name="ownership_registration_number" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Ngày cấp *</label>
                                <input type="date" name="ownership_registration_issue_date" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Địa chỉ liên hệ *</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Điện thoại *</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số lượng chứng khoán (Chưa lưu ký)</label>
                                <input type="number" name="share_unregistered" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số lượng chứng khoán (Lưu ký)</label>
                                <input type="number" name="share_deposited" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số lượng chứng khoán (Tổng cộng)</label>
                                <input type="number" name="share_total" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số lượng quyền phân bổ (Chưa lưu ký)</label>
                                <input type="number" name="allocation_unregistered" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số lượng quyền phân bổ (Lưu ký)</label>
                                <input type="number" name="allocation_deposited" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số lượng quyền phân bổ (Tổng cộng)</label>
                                <input type="number" name="allocation_total" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tỷ lệ</label>
                                <div class="input-group">
                                    <input type="number" name="ratio" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Quốc tịch *</label>
                                <input type="text" name="nationality" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Đăng ký</label>
                                <select name="registration_status" class="form-control">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Ngày giao dịch *</label>
                                <input type="date" name="transaction_date" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" form="shareholderForm" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="voteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title">Danh sách nội dung thực hiện biểu quyết</h5>
                </div>

                <div class="modal-body">
                    <form id="voteForm">
                        @csrf
                        <input type="hidden" name="shareholder_id" id="shareholder_id">
                        <input type="hidden" id="congress_id" name="congress_id" value="{{ $congressId }}">

                        <table id="vote-session-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Nội dung Biểu quyết</th>
                                <th class="text-center">Tán thành</th>
                                <th class="text-center">Không tán thành</th>
                                <th class="text-center">Không ý kiến</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Dữ liệu sẽ được load bằng Ajax -->
                            </tbody>
                        </table>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success" form="voteForm">Xác nhận bỏ phiếu</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <!-- jQuery + Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>

    <script>
        $(function () {
            let table = $('#shareholders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('congresses.shareholders.list', $congressId) }}',
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'checkbox', orderable: false, searchable: false},
                    {data: 'full_name'},
                    {data: 'sid'},
                    {data: 'investor_code'},
                    {data: 'ownership_registration_number'},
                    {data: 'ownership_registration_issue_date'},
                    {data: 'address'},
                    {data: 'email'},
                    {data: 'phone'},
                    {data: 'nationality'},
                    {data: 'share_unregistered'},
                    {data: 'share_deposited'},
                    {data: 'share_total'},
                    {data: 'allocation_unregistered'},
                    {data: 'allocation_deposited'},
                    {data: 'allocation_total'},
                    {data: 'ratio'},
                    {data: 'transaction_date'},
                    {data: 'email_status'},
                    {data: 'action', orderable: false, searchable: false},
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });

            table.buttons().container().appendTo('#shareholders-table_wrapper .col-md-6:eq(0)');

            table.on('xhr.dt', function (e, settings, json, xhr) {
                if (json.totalAllocation !== undefined) {
                    $('#totalAllocation').val(
                        json.totalAllocation
                    );
                }
            });

            $('#select-all').on('click', function () {
                $('.row-checkbox').prop('checked', this.checked);
            });

            // Hàm cập nhật ratio
            function updateRatio() {
                let slqpb_tong = parseFloat($('input[name="allocation_total"]').val()) || 0;
                let allocationTotal = parseFloat($('input[name="totalAllocation"]').val()) || 0;
                let ratio = allocationTotal > 0 ? (slqpb_tong / (allocationTotal + slqpb_tong)) * 100 : 100;
                $('input[name="ratio"]').val(ratio.toFixed(3));
            }

            // Cập nhật ratio khi người dùng thay đổi share_total hoặc allocation_total
            $(document).on('input', 'input[name="allocation_total"]', updateRatio);

            let saveMethod = 'create';

            $('#btnAddShareholder').on('click', function () {
                saveMethod = 'create';
                $('#shareholderForm')[0].reset();
                $('#shareholder_id').val('');
                $('#shareholderModalTitle').text('Tạo thông tin cổ đông');
                updateRatio(); // reset ratio khi mở modal tạo mới
            });

            // Edit
            $(document).on('click', '.btn-edit', function () {
                saveMethod = 'update';
                let id = $(this).data('id');
                let url = `{{ route('shareholders.detail', ':id') }}`.replace(':id', id);

                $.get(url, function (data) {
                    $('#shareholder_id').val(data.id);
                    $('[name="full_name"]').val(data.full_name);
                    $('[name="sid"]').val(data.sid);
                    $('[name="investor_code"]').val(data.investor_code);
                    $('[name="ownership_registration_number"]').val(data.ownership_registration_number);
                    $('[name="ownership_registration_issue_date"]').val(data.ownership_registration_issue_date);
                    $('[name="address"]').val(data.address);
                    $('[name="email"]').val(data.email);
                    $('[name="phone"]').val(data.phone);
                    $('[name="shares"]').val(data.shares);
                    $('[name="allocation_unregistered"]').val(data.allocation_unregistered);
                    $('[name="allocation_deposited"]').val(data.allocation_deposited);
                    $('[name="allocation_total"]').val(data.allocation_total);
                    $('[name="share_unregistered"]').val(data.share_unregistered);
                    $('[name="share_deposited"]').val(data.share_deposited);
                    $('[name="share_total"]').val(data.share_total);
                    $('[name="nationality"]').val(data.nationality);
                    $('[name="registration_status"]').val(data.registration_status);
                    $('[name="transaction_date"]').val(data.transaction_date);

                    updateRatio(); // tính ratio khi load dữ liệu edit

                    $('#shareholderModalTitle').text('Chỉnh sửa thông tin cổ đông');
                    $('#shareholderModal').modal('show');
                });
            });

            // Validation + Submit
            $('#shareholderForm').validate({
                rules: {
                    full_name: {required: true},
                    ownership_registration_number: {required: true},
                    ownership_registration_issue_date: {required: true, date: true},
                    address: {required: true},
                    email: {required: true, email: true},
                    phone: {required: true},
                    shares: {required: true, number: true},
                    nationality: {required: true},
                    transaction_date: {required: true, date: true},
                    share_unregistered: {required: true, number: true},
                    allocation_unregistered: {required: true, number: true},
                    sid: {required: true},
                },
                messages: {
                    full_name: "Vui lòng nhập họ tên",
                    ownership_registration_number: "Vui lòng nhập số ĐKSH",
                    ownership_registration_issue_date: "Vui lòng chọn ngày cấp",
                    address: "Vui lòng nhập địa chỉ",
                    email: "Vui lòng nhập email hợp lệ",
                    phone: "Vui lòng nhập số điện thoại",
                    shares: "Vui lòng nhập số lượng cổ phần",
                    nationality: "Vui lòng nhập quốc tịch",
                    transaction_date: "Vui lòng chọn ngày giao dịch",
                    share_unregistered: "Vui lòng nhập số lượng chứng khoán",
                    allocation_unregistered: "Vui lòng nhập số lượng quyền phân bổ",
                    sid: "Vui lòng nhập mã định danh NĐT",
                },
                errorClass: 'error',
                submitHandler: function (form) {
                    let formData = $(form).serialize();
                    let url, method;

                    if (saveMethod === 'create') {
                        url = '{{ route('shareholders.store') }}';
                        method = 'POST';
                    } else {
                        let id = $('#shareholder_id').val();
                        url = `{{ route('shareholders.update', ':id') }}`.replace(':id', id);
                        method = 'PUT';
                    }

                    $.ajax({
                        url: url,
                        method: method,
                        data: formData,
                        success: function () {
                            $('#shareholderModal').modal('hide');
                            table.ajax.reload();
                            toastr.success('Lưu thông tin cổ đông thành công!');
                        },
                        error: function () {
                            toastr.error('Có lỗi xảy ra, vui lòng thử lại!');
                        },
                        complete: function () {
                            $('.modal-backdrop').remove();
                        }
                    });
                }
            });

            // Delete
            $(document).on('click', '.btn-delete', function () {
                let shareholder_id = $(this).data('id');

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Hành động này sẽ không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/congresses/{{ $congressId }}/shareholders/remove',
                            type: 'POST',
                            data: {
                                shareholder_id: shareholder_id,
                                _method: 'DELETE',
                                congress_id: {{ $congressId }},
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (res) {
                                toastr.success(res.message);
                                table.ajax.reload();
                            },
                            error: function () {
                                toastr.error('Có lỗi xảy ra, vui lòng thử lại!');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '#sendMailBtn', function () {
                // Lấy danh sách ID đã chọn
                let ids = [];
                $('input[name="shareholder_ids[]"]:checked').each(function () {
                    ids.push($(this).val());
                });

                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa chọn',
                        text: 'Vui lòng chọn ít nhất 1 cổ đông để gửi thư mời.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Xác nhận',
                    text: "Bạn có chắc muốn gửi thư mời cho " + ids.length + " cổ đông đã chọn?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, gửi ngay',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('shareholders.invite') }}',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                shareholder_ids: ids
                            },
                            success: function (res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công',
                                    text: res.success || 'Đã gửi thư mời thành công!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                $('#shareholders-table').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: xhr.responseJSON?.error || 'Có lỗi xảy ra'
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.btn-print', function () {
                let shareholder_id = $(this).data('id');
                let congress_id = "{{ $congressId }}"; // render sẵn trong Blade

                let url = '/admin/congresses/print?congress_id=' + congress_id + '&shareholder_id=' + shareholder_id;

                window.open(url, '_blank');
            });

            $(document).on('click', '.btn-vote', function () {
                let shareholder_id = $(this).data('id');   // lấy id từ button
                let targetModal = $('#voteModal');
                let congressId = {{ $congressId }}

                targetModal.find('#vote-session-table tbody').html('');
                targetModal.find('#shareholder_id').val(shareholder_id);  // gán vào input hidden

                $.ajax({
                    url: '/admin/vote/sessions/list',
                    type: 'GET',
                    data: {congress_id: congressId},
                    success: function (res) {
                        let rows = '';
                        $.each(res.data, function (i, item) {
                            rows += `
                    <tr>
                        <td class="text-center">${i + 1}</td>
                        <td>${item.title}</td>
                        <td class="text-center">
                            <input type="radio" name="choices[${item.id}]" value="YES" required>
                        </td>
                        <td class="text-center">
                            <input type="radio" name="choices[${item.id}]" value="NO">
                        </td>
                        <td class="text-center">
                            <input type="radio" name="choices[${item.id}]" value="ABSTAIN">
                        </td>
                    </tr>
                `;
                        });
                        targetModal.find('#vote-session-table tbody').html(rows);
                        targetModal.modal('show');
                    },
                    error: function () {
                        alert('Không load được dữ liệu biểu quyết');
                    }
                });
            });

            $('#voteForm').validate({
                rules: {
                    "choices[]": {required: true},
                },
                messages: {
                    "choices[]": "Vui lòng chọn ý kiến biểu quyết",
                },
                errorClass: 'error',
                submitHandler: function (form) {
                    let formData = $(form).serialize();
                    let url = '{{ route('vote.ui.votes.submit') }}';

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        success: function () {
                            $('#voteModal').modal('hide');
                            toastr.success('Vote thành công!');
                        },
                        error: function () {
                            $('#voteModal').modal('hide');
                            toastr.error('Có lỗi xảy ra, vui lòng thử lại!');
                        },
                        complete: function () {
                            $('.modal-backdrop').remove();
                        }
                    });
                }
            });
        });
    </script>
@endsection
