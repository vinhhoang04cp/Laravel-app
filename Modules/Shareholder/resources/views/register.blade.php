<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tham dự Đại hội cổ đông</title>
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap4/bootstrap.min.css') }}">

    <style>
        body {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-box {
            width: 100%;
            max-width: 500px;
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.15);
            top: 300px;
        }
        .register-box h3 {
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold;
            color: #333;
        }
        .error {
            color: #dc3545;
            font-size: 90%;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h3>Đăng ký tham dự Đại hội</h3>

    <!-- 🔔 Hiển thị thông báo -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('shareholders.register.submit') }}" method="POST">
        @csrf
        <input type="hidden" id="token" name="token" value="{{ $shareholder->token }}">
        <input type="hidden" name="confirmation_token" value="{{ $shareholder->confirmation_token }}">

        <!-- Họ tên cổ đông -->
        <div class="form-group mb-3">
            <label for="full_name">Họ và tên cổ đông</label>
            <input type="text" id="name" name="full_name" class="form-control" required>
            @error('full_name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Số ĐKSH *</label>
            <input type="text" name="ownership_registration_number" class="form-control" required>
        </div>

        <!-- Hình thức tham dự -->
        <div class="form-group mb-3">
            <label for="registration_type">Hình thức tham dự</label>
            <select id="registration_type" name="registration_type" class="form-control" required>
                <option value="register">Trực tiếp</option>
                <option value="proxy">Ủy quyền</option>
            </select>
        </div>

        <!-- Thông tin người được ủy quyền -->
        <div class="proxy-info" id="proxy-info" style="display:none">
            <h5>Thông tin người được ủy quyền</h5>
            <div class="form-group mb-3">
                <label for="proxy_name">Họ và tên</label>
                <input type="text" id="proxy_name" name="proxy_name" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="proxy_phone">Số điện thoại</label>
                <input type="text" id="proxy_phone" name="proxy_phone" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="proxy_id">CCCD/CMND/Hộ chiếu</label>
                <input type="text" id="proxy_id" name="proxy_id" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const select = document.getElementById("registration_type");
        const proxyInfo = document.getElementById("proxy-info");

        select.addEventListener("change", function () {
            if (this.value === "proxy") {
                proxyInfo.style.display = "block";
            } else {
                proxyInfo.style.display = "none";
            }
        });
    });
</script>
</body>
</html>
