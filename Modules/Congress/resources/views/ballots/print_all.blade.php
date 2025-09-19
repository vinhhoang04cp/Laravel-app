<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .ballot {
            page-break-after: always;
            border: 1px solid #000;
            padding: 20px;
            margin-bottom: 20px;
        }
        h3 { text-align: center; margin-bottom: 20px; }
        .footer { text-align: right; font-size: 12px; margin-top: 40px; }
    </style>
</head>
<body>
@foreach($shareholders as $s)
    <div class="ballot">
        <h3>PHIẾU BIỂU QUYẾT</h3>
        <p><b>Họ tên cổ đông:</b> {{ $s->full_name }}</p>
        <p><b>Email:</b> {{ $s->email }}</p>
        <p><b>Điện thoại:</b> {{ $s->phone }}</p>
        <p><b>Địa chỉ:</b> {{ $s->address }}</p>
        <p><b>Quốc tịch:</b> {{ $s->nationality }}</p>
        <p><b>Số đăng ký sở hữu:</b> {{ $s->ownership_registration_number }}</p>
        <p><b>Số cổ phần sở hữu:</b> {{ $s->shares }}</p>
        <p><b>Số phiếu biểu quyết:</b> {{ $s->shares }}</p>

        <hr>
        <p><b>Nội dung biểu quyết:</b> Thông qua phương án tăng vốn điều lệ ABBANK...</p>
        <p>
            [ ] Tán thành &nbsp;&nbsp;
            [ ] Không tán thành &nbsp;&nbsp;
            [ ] Không có ý kiến
        </p>

        <div class="footer">
            Ngày .... tháng .... năm .... <br>
            Ký tên: ___________________
        </div>
    </div>
@endforeach
</body>
</html>
