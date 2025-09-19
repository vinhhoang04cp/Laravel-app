<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bỏ phiếu - {{ $session->title }}</title>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap4/bootstrap.min.css') }}">
    <style>
        body { background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .vote-box { width: 100%; max-width: 500px; background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 5px 30px rgba(0,0,0,0.15); }
        .vote-box h3 { margin-bottom: 25px; text-align: center; font-weight: bold; color: #333; }
        .error { color: #dc3545; font-size: 90%; }
    </style>
</head>
<body>

<div class="vote-box">
    <h3>Bỏ phiếu cho: {{ $session->title }}</h3>
    <p class="text-muted text-center">{{ $session->description }}</p>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <!-- Form bỏ phiếu -->
    <form action="{{ route('vote.public.submit', $session->id) }}" method="POST">
        @csrf
        <input type="hidden" name="shareholder_id" value="{{ $shareholder->id }}">
        <input type="hidden" name="ownership_registration_number" value="{{ $shareholder->ownership_registration_number }}">
        <input type="hidden" name="otp_code" value="{{ $shareholder->otp_code }}">

        <div class="form-group mb-3">
            <label>Mã số ĐKSH</label>
            <input type="text" value="{{ $shareholder->ownership_registration_number }}" class="form-control" readonly>
        </div>

        <div class="form-group mb-3">
            <label>Số Cổ Phần</label>
            <input type="number" value="{{ $shareholder->shares }}" class="form-control" readonly>
            <input type="hidden" name="shares" value="{{ $shareholder->shares }}">
        </div>

        <div class="form-group mb-3">
            <label for="choice">Lựa chọn</label>
            <select name="choice" class="form-control" required>
                <option value="yes">Đồng ý</option>
                <option value="no">Không đồng ý</option>
                <option value="abstain">Không ý kiến</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success btn-block">
            <i class="voyager-check"></i> Gửi phiếu
        </button>
    </form>
</div>
</body>
</html>
