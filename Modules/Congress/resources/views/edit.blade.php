@extends('voyager::master')

@section('content')
    <section class="content-header mb-3">
        <div class="container-fluid">
            <h1 class="text-primary"><i class="voyager-calendar"></i> Cập nhật kỳ đại hội</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('congresses.update', $congress->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Cột trái: Thông tin chung --}}
                    <div class="col-md-6">
                        <div class="card shadow rounded-lg border-0 mb-3">
                            <div class="card-header bg-primary text-white d-flex align-items-center"
                                 style="font-size: 1.1rem; font-weight: 600; padding: 1rem 1.25rem;">
                                <i class="voyager-info-circled mr-2" style="font-size: 1.3rem;"></i>
                                Thông tin chung
                            </div>
                            <div class="card-body">
                                {{-- Tên kỳ đại hội --}}
                                <div class="form-group">
                                    <label for="name">Tên kỳ đại hội <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name" value="{{ old('name', $congress->name) }}" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                {{-- Loại đại hội --}}
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label>Loại đại hội <span class="text-danger">*</span></label>--}}
                                {{--                                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">--}}
                                {{--                                        <label--}}
                                {{--                                            class="btn btn-outline-light text-dark bg-white flex-fill {{ old('type', $congress->type) == 'Thường niên' ? 'active focus' : '' }}">--}}
                                {{--                                            <input type="radio" name="type"--}}
                                {{--                                                   value="Thường niên" {{ old('type', $congress->type) == 'Thường niên' ? 'checked' : '' }}>--}}
                                {{--                                            <i class="voyager-check"></i> Thường niên--}}
                                {{--                                        </label>--}}
                                {{--                                        <label--}}
                                {{--                                            class="btn btn-outline-light text-dark bg-white flex-fill {{ old('type', $congress->type) == 'Bất thường' ? 'active focus' : '' }}">--}}
                                {{--                                            <input type="radio" name="type"--}}
                                {{--                                                   value="Bất thường" {{ old('type', $congress->type) == 'Bất thường' ? 'checked' : '' }}>--}}
                                {{--                                            <i class="voyager-warning"></i> Bất thường--}}
                                {{--                                        </label>--}}
                                {{--                                    </div>--}}
                                {{--                                    @error('type') <span--}}
                                {{--                                        class="invalid-feedback d-block">{{ $message }}</span> @enderror--}}
                                {{--                                </div>--}}

                                <div class="form-group">
                                    <label for="type">Loại đại hội <span class="text-danger">*</span></label>
                                    <select name="type" id="type"
                                            class="form-control @error('type') is-invalid @enderror"
                                            required>
                                        <option
                                            value="Thường niên" {{ $congress->type == 'Thường niên' ? 'selected' : '' }}>
                                            Thường niên
                                        </option>
                                        <option
                                            value="Bất thường" {{ $congress->type == 'Bất thường' ? 'selected' : '' }}>
                                            Bất thường
                                        </option>
                                        <option
                                            value="Lấy ý kiến cổ đông bằng Văn bản" {{ $congress->type == 'Lấy ý kiến cổ đông bằng Văn bản' ? 'selected' : '' }}>
                                            Lấy ý kiến cổ đông bằng Văn bản
                                        </option>
                                    </select>
                                    @error('type') <span
                                        class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                {{-- Thời gian tổ chức --}}
                                <div class="form-group">
                                    <label for="scheduled_at">Thời gian tổ chức <span
                                            class="text-danger">*</span></label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                           value="{{ old('scheduled_at', \Carbon\Carbon::parse($congress->scheduled_at)->format('Y-m-d\TH:i')) }}"
                                           class="form-control @error('scheduled_at') is-invalid @enderror" required>
                                    @error('scheduled_at') <span
                                        class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                {{-- Hình thức tổ chức --}}
                                <div class="form-group">
                                    <label for="organization_form">Hình thức tổ chức <span class="text-danger">*</span></label>
                                    <select name="organization_form" id="organization_form"
                                            class="form-control @error('organization_form') is-invalid @enderror"
                                            required>
                                        <option
                                            value="Trực tiếp" {{ $congress->organization_form == 'Trực tiếp' ? 'selected' : '' }}>
                                            Trực tiếp
                                        </option>
                                        <option
                                            value="Trực tuyến" {{ $congress->organization_form == 'Trực tuyến' ? 'selected' : '' }}>
                                            Trực tuyến
                                        </option>
                                        <option
                                            value="Kết hợp" {{ $congress->organization_form == 'Kết hợp' ? 'selected' : '' }}>
                                            Kết hợp
                                        </option>
                                    </select>
                                    @error('organization_form') <span
                                        class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                {{-- Địa điểm tổ chức --}}
                                <div class="form-group">
                                    <label for="location">Địa điểm / Link <span class="text-danger">*</span></label>
                                    <input type="text" name="location"
                                           class="form-control @error('location') is-invalid @enderror"
                                           id="location" value="{{ old('location', $congress->location) }}" required>
                                    @error('location') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cột phải: Chương trình chi tiết --}}
                    <div class="col-md-6">
                        <div class="card shadow rounded-lg border-0 mb-3">
                            <div class="card-header bg-success text-white d-flex align-items-center"
                                 style="font-size: 1.1rem; font-weight: 600; padding: 1rem 1.25rem;">
                                <i class="voyager-list mr-2" style="font-size: 1.3rem;"></i>
                                Chương trình chi tiết
                            </div>
                            <div class="card-body">
                                <table class="table table-hover table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th style="width:5%">#</th>
                                        <th>Nội dung</th>
                                        <th style="width:37%">Thời gian diễn ra</th>
                                        <th style="width:10%" class="text-center">Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody id="agenda-body">
                                    @forelse($congress->details as $i => $item)
                                        <tr>
                                            <td class="align-middle text-center">{{ $i + 1 }}</td>
                                            <td>
                                                <input type="text" name="agenda[{{ $i }}][title]"
                                                       value="{{ $item->title }}"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="datetime-local" name="agenda[{{ $i }}][scheduled_at]"
                                                       value="{{ $item->scheduled_at }}"
                                                       class="form-control">
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-danger btn-sm remove-agenda">
                                                    <i class="voyager-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-muted text-center">
                                            <td colspan="4">Chưa có nội dung</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-success" id="add-agenda">
                                    <i class="voyager-plus"></i> Thêm nội dung
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="text-right mt-3">
                    <a href="{{ route('congresses.index') }}" class="btn btn-secondary">
                        <i class="voyager-x"></i> Huỷ
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="voyager-check"></i> Cập nhật đại hội
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('javascript')
    <script src="{{ asset('/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(function () {
            // Thêm agenda
            $(document).on('click', '#add-agenda', function () {
                var tbody = $('#agenda-body');
                if (tbody.find('tr.text-muted').length) tbody.empty();
                var index = tbody.find('tr').length;

                var row = `
                <tr>
                    <td class="align-middle text-center">${index + 1}</td>
                    <td>
                        <input type="text" name="agenda[${index}][title]" class="form-control" placeholder="Nhập nội dung..." required>
                    </td>
                    <td>
                        <input type="datetime-local" name="agenda[${index}][scheduled_at]" class="form-control">
                    </td>
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-danger btn-sm remove-agenda">
                            <i class="voyager-trash"></i>
                        </button>
                    </td>
                </tr>`;
                tbody.append(row);
            });

            // Xoá agenda
            $(document).on('click', '.remove-agenda', function () {
                $(this).closest('tr').remove();
                updateIndex();
                if ($('#agenda-body tr').length === 0) {
                    $('#agenda-body').html('<tr class="text-muted text-center"><td colspan="3">Chưa có nội dung</td></tr>');
                }
            });

            // Sortable
            $('#agenda-body').sortable({items: 'tr', update: updateIndex});

            // Cập nhật số thứ tự
            function updateIndex() {
                $('#agenda-body tr').each(function (i) {
                    $(this).find('td:first').text(i + 1);
                });
            }
        });
    </script>
@endpush
