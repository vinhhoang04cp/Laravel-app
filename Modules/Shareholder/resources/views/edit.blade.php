@extends('master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <h1>Chỉnh sửa thông tin cổ đông</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('shareholders.update', $shareholder->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="full_name">Họ tên</label>
                            <input type="text" name="full_name"
                                   class="form-control @error('full_name') is-invalid @enderror"
                                   id="full_name" value="{{ old('full_name', $shareholder->full_name) }}" required>
                            @error('full_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ownership_registration_number">Số ĐKSH</label>
                            <input type="text" name="ownership_registration_number"
                                   class="form-control @error('ownership_registration_number') is-invalid @enderror"
                                   id="ownership_registration_number"
                                   value="{{ old('ownership_registration_number', $shareholder->ownership_registration_number) }}"
                                   required>
                            @error('ownership_registration_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ownership_registration_issue_date">Ngày cấp</label>
                            <input type="date" name="ownership_registration_issue_date"
                                   id="ownership_registration_issue_date"
                                   value="{{ old('ownership_registration_issue_date', $shareholder->ownership_registration_issue_date) }}"
                                   class="form-control @error('ownership_registration_issue_date') is-invalid @enderror"
                                   required>
                            @error('ownership_registration_issue_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Địa chỉ liên hệ</label>
                            <input type="text" name="address"
                                   class="form-control @error('address') is-invalid @enderror"
                                   id="address" value="{{ old('address', $shareholder->address) }}" required>
                            @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" value="{{ old('email', $shareholder->email) }}" required>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" value="{{ old('phone', $shareholder->phone) }}" required>
                            @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="shares">Cộng</label>
                            <input type="text" name="shares" class="form-control @error('shares') is-invalid @enderror"
                                   id="shares" value="{{ old('shares', $shareholder->shares) }}" required>
                            @error('shares')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nationality">Quốc tịch</label>
                            <input type="text" name="nationality"
                                   class="form-control @error('nationality') is-invalid @enderror"
                                   id="nationality" value="{{ old('nationality', $shareholder->nationality) }}"
                                   required>
                            @error('nationality')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="registration_status">Đăng ký</label>
                            <select name="registration_status" id="registration_status"
                                    class="form-control @error('registration_status') is-invalid @enderror" required>
                                <option
                                    value="Chưa đăng ký" {{ old('registration_status', $shareholder->registration_status) == 'Chưa đăng ký' ? 'selected' : '' }}>
                                    Chưa đăng ký
                                </option>
                                <option
                                    value="Đã đăng ký" {{ old('registration_status', $shareholder->registration_status) == 'Đã đăng ký' ? 'selected' : '' }}>
                                    Đã đăng ký
                                </option>
                                <option
                                    value="Ủy quyền" {{ old('registration_status', $shareholder->registration_status) == 'Ủy quyền' ? 'selected' : '' }}>
                                    Ủy quyền
                                </option>
                            </select>
                            @error('registration_status')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="transaction_date">Ngày giao dịch</label>
                            <input type="date" name="transaction_date" id="transaction_date"
                                   value="{{ old('transaction_date', $shareholder->transaction_date) }}"
                                   class="form-control @error('transaction_date') is-invalid @enderror" required>
                            @error('transaction_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('congresses.index') }}" class="btn btn-secondary">Huỷ</a>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
