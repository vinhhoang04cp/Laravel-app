<!DOCTYPE html>
<html lang="en-SG">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <title>
    </title>
    <style>
        body { line-height:108%; font-family:arial; font-size:11pt }
        h1, h2, h3, h4, h5, h6, p { margin:0pt 0pt 8pt }
        li { margin-top:0pt; margin-bottom:8pt }
        h1 { margin-top:18pt; margin-bottom:4pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-family:'timesnewroman', serif; font-size:20pt; font-weight:normal; color:#0f4761 }
        h2 { margin-top:8pt; margin-bottom:4pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-family:'timesnewroman', serif; font-size:16pt; font-weight:normal; color:#0f4761 }
        h3 { margin-top:8pt; margin-bottom:4pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-family:arial; font-size:14pt; font-weight:normal; color:#0f4761 }
        h4 { margin-top:4pt; margin-bottom:2pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-family:arial; font-size:11pt; font-weight:normal; font-style:italic; color:#0f4761 }
        h5 { margin-top:4pt; margin-bottom:2pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-family:arial; font-size:11pt; font-weight:normal; color:#0f4761 }
        h6 { margin-top:2pt; margin-bottom:0pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-family:arial; font-size:11pt; font-weight:normal; font-style:italic; color:#595959 }
        .Heading7 { margin-top:2pt; margin-bottom:0pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-size:11pt; font-weight:normal; color:#595959 }
        .Heading8 { margin-bottom:0pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-size:11pt; font-weight:normal; font-style:italic; color:#272727 }
        .Heading9 { margin-bottom:0pt; page-break-inside:avoid; page-break-after:avoid; line-height:108%; font-size:11pt; font-weight:normal; color:#272727 }
        .IntenseQuote { margin:18pt 43.2pt; text-align:center; line-height:108%; border-top:0.75pt solid #0f4761; border-bottom:0.75pt solid #0f4761; padding-top:10pt; padding-bottom:10pt; font-size:11pt; font-style:italic; color:#0f4761 }
        .ListParagraph { margin-left:36pt; margin-bottom:8pt; line-height:108%; font-size:11pt }
        .Quote { margin-top:8pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:11pt; font-style:italic; color:#404040 }
        .Subtitle { margin-bottom:8pt; line-height:108%; font-size:14pt; letter-spacing:0.75pt; color:#595959 }
        .Title { margin-bottom:4pt; line-height:normal; font-family:'timesnewroman', serif; font-size:28pt; letter-spacing:-0.5pt }
        span.Heading1Char { font-family:'timesnewroman', serif; font-size:20pt; color:#0f4761 }
        span.Heading2Char { font-family:'timesnewroman', serif; font-size:16pt; color:#0f4761 }
        span.Heading3Char { font-size:14pt; color:#0f4761 }
        span.Heading4Char { font-style:italic; color:#0f4761 }
        span.Heading5Char { color:#0f4761 }
        span.Heading6Char { font-style:italic; color:#595959 }
        span.Heading7Char { color:#595959 }
        span.Heading8Char { font-style:italic; color:#272727 }
        span.Heading9Char { color:#272727 }
        span.IntenseEmphasis { font-style:italic; color:#0f4761 }
        span.IntenseQuoteChar { font-style:italic; color:#0f4761 }
        span.IntenseReference { font-weight:bold; font-variant:small-caps; letter-spacing:0.25pt; color:#0f4761 }
        span.QuoteChar { font-style:italic; color:#404040 }
        span.SubtitleChar { font-size:14pt; letter-spacing:0.75pt; color:#595959 }
        span.TitleChar { font-family:'timesnewroman', serif; font-size:28pt; letter-spacing:-0.5pt }
        .awlist1 { list-style:none; counter-reset:awlistcounter2_0 5 }
        .awlist1 > li:before { content:'-'; counter-increment:awlistcounter2_0 }
        @media (max-width: 900px) {
            img {
                max-width: 100%;
                height: auto;
            }

            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td, th {
                padding: 8px;
                text-align: left;
                border: 1px solid #ddd;
            }
        }
        .vote-card {
            page-break-after: always; /* Mỗi phiếu bắt buộc sang trang mới */
            break-after: page;        /* Trình duyệt mới (chrome/edge) */
        }
        @media print {
            .vote-card {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
<button id="export-pdf" class="btn btn-success mt-3">
    <i class="fa fa-file-pdf"></i> Xuất PDF
</button>
<div id="print-area">
    @foreach($shareholders as $s)
    <div class="vote-card">
        <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:16pt">
            <img src="{{ asset('images/logo/logo-2x.png') }}" width="258" height="36" alt="A blue and white logo&#xA;&#xA;AI-generated content may be incorrect." />
        </p>
        <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:16pt">
            <strong>&#xa0;</strong>
        </p>
        <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:16pt">
            <strong>PHIẾU BIỂU QUYẾT</strong>
        </p>
        <p style="margin-bottom:0pt; text-align:center; line-height:108%; font-size:12pt">
            <strong>Thông qua Nghị quyết của Đại hội đồng Cổ đông </strong>
        </p>
        <p style="margin-bottom:0pt; text-align:center; line-height:108%; font-size:12pt">
            <strong>về Phương án tăng mức vốn điều lệ ABBANK</strong>
        </p>
        <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:12pt">
            <strong>&#xa0;</strong>
        </p>
        <p style="margin-left:36pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <a id="_Hlk160808387"></a><a id="_Hlk162417301"><strong>&#xa0;</strong></a>
        </p>
        <p style="margin-left:36pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>Tên Cổ đông: </strong>
            <strong>{{ $s->full_name }}</strong>
        </p>
        <p style="margin-left:36pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>Địa chỉ:</strong>
            <strong>{{ $s->address }}</strong>
        </p>
        <p style="margin-left:36pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>Số ĐT:</strong>
            <strong> {{ $s->phone }}</strong>
        </p>
        <p style="margin-left:36pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>Số thứ tự tại Danh sách chốt:</strong>
            <strong> {{ $s->id }}</strong>
        </p>
        <p style="margin-left:35.45pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>&#xa0;</strong>
        </p>
        <p style="margin-left:35.45pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>&#xa0;</strong>
        </p>
        <p style="margin-left:35.45pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>Quốc tịch:</strong>
            <strong>{{ $s->nationality }}</strong>
        </p>
        <p style="margin-left:36pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>Số ĐKSH:</strong>
            <strong>{{ $s->ownership_registration_number }}</strong>
        </p>
        <p style="margin-left:36pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>Số cổ phần sở hữu:</strong><strong>&#xa0; </strong><strong>{{ $s->ownership_registration_number }}</strong><strong>cổ phần</strong>
        </p>
        <p style="margin-left:36pt; margin-bottom:0pt; text-align:justify; line-height:normal; font-size:12pt">
            <strong>Số phiếu biểu quyết: </strong><strong>{{ $s->ownership_registration_number }}</strong><strong>phiếu</strong>
        </p>
        <p style="line-height:108%; font-size:12pt">
            &#xa0;
        </p>
        <p style="line-height:108%; font-size:12pt">
            Nội dung biểu quyết:
        </p>
        <p style="text-align:justify; line-height:108%; font-size:12pt">
            <a id="_Hlk206169066"><span style="letter-spacing:-0.3pt">Thông qua Phương án tăng mức vốn điều lệ Ngân hàng Thương mại Cổ phần An Bình (ABBANK) kèm theo Nghị Quyết của Đại hội đồng Cổ đông theo Tờ trình số: 03/TT-HĐQT.25 và Thông báo Lấy ý kiến Cổ đông số: 36/TB-HĐQT.25 ngày 28/8/2025 của Hội đồng Quản trị ABBANK.</span></a>
        </p>
        <p>
            <span style="letter-spacing:-0.3pt; color:#c00000">(</span><em><span style="letter-spacing:-0.3pt; color:#c00000">Quý Cổ đông chỉ chọn một phương án biểu quyết bằng cách đánh dấu (</span></em><em><span style="font-family:'Segoe UI Symbol'; letter-spacing:-0.3pt; color:#c00000">✓</span></em><em><span style="letter-spacing:-0.3pt; color:#c00000">) hoặc (X) vào một ô bên dưới)</span></em>
        </p>
        <p style="line-height:108%; font-size:6pt">
            <em><span style="letter-spacing:-0.1pt">&#xa0;</span></em>
        </p>
        <p style="line-height:108%; font-size:12pt">
            <span style="height:0pt; display:block; position:absolute; z-index:0"><img src="{{ asset('images/render/shape.png') }}" width="24" height="22" alt="" style="margin-top:0.6pt; margin-left:16.8pt; position:absolute" /></span><span style="height:0pt; display:block; position:absolute; z-index:2"><img src="{{ asset('images/render/shape.png') }}" width="24" height="22" alt="" style="margin-top:0.6pt; margin-left:354.6pt; position:absolute" /></span><span style="height:0pt; display:block; position:absolute; z-index:1"><img src="{{ asset('images/render/shape.png') }}" width="24" height="22" alt="" style="margin-top:0.6pt; margin-left:189pt; position:absolute" /></span>&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; Tán thành <span style="width:13.95pt; display:inline-block">&#xa0;</span> <span style="width:32.67pt; display:inline-block">&#xa0;</span><span style="width:36pt; display:inline-block">&#xa0;</span>&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; Không tán thành <span style="width:22.58pt; display:inline-block">&#xa0;</span><span style="width:36pt; display:inline-block">&#xa0;</span>&#xa0;&#xa0;&#xa0;&#xa0; Không có ý kiến
        </p>
        <p style="line-height:108%; font-size:6pt">
            &#xa0;
        </p>
        <p style="margin-left:180pt; line-height:108%; font-size:12pt">
            ……………………., ngày……tháng……năm 2025
        </p>
        <p style="margin-left:180pt; margin-bottom:0pt; line-height:108%; font-size:12pt">
            <strong>&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </strong><strong>CỔ ĐÔNG/NGƯỜI ĐẠI DIỆN CỔ ĐÔNG </strong>
        </p>
        <p style="margin-left:180pt; margin-bottom:0pt">
            <em>&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </em><em>(Ký, ghi rõ họ tên và đóng dấu nếu là tổ chức)</em>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <strong><em>&#xa0;</em></strong>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <strong><em>&#xa0;</em></strong>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <strong><em>&#xa0;</em></strong>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <strong><em>&#xa0;</em></strong>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <strong><em>&#xa0;</em></strong>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <strong><em>&#xa0;</em></strong>
        </p>
        <p style="margin-bottom:0pt; line-height:normal; border-bottom:1.5pt solid #000000; padding-bottom:1pt">
            <strong><em>&#xa0;</em></strong>
        </p>
        <p style="margin-bottom:0pt; text-align:justify; line-height:normal">
            <em><span style="color:#c00000">Quý Cổ đông gửi Phiếu biểu quyết này về ABBANK </span></em><strong><em><span style="color:#c00000">chậm nhất 17 giờ ngày 15/9/2025</span></em></strong><em><span style="color:#c00000"> theo một trong các cách thức sau đây:</span></em>
        </p>
        <ul class="awlist1" style="margin:0pt; padding-left:0pt">
            <li class="ListParagraph" style="margin-left:21.3pt; margin-bottom:0pt; text-indent:-18pt; text-align:justify; line-height:normal; color:#c00000">
                <span style="width:14.34pt; font:7pt 'timesnewroman', serif; display:inline-block">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><em>Bỏ trong phong bì dán kín và giao trực tiếp hoặc gửi thư đến </em><strong><em>Văn phòng HĐQT Tầng 3 Toà nhà Geleximco, Số 36 Hoàng Cầu, phường Ô Chợ Dừa, thành phố Hà Nội.</em></strong>
            </li>
            <li class="ListParagraph" style="margin-left:21.3pt; margin-bottom:0pt; text-indent:-18pt; text-align:justify; line-height:normal; color:#c00000">
                <span style="width:14.34pt; font:7pt 'timesnewroman', serif; display:inline-block">&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0;&#xa0; </span><em>Chụp ảnh hoặc scan và gửi qua email đến địa chỉ: </em><a href="mailto:ir@abbank.vn" style="text-decoration:none"><strong><em><u><span style="color:#c00000">ir@abbank.vn</span></u></em></strong></a><em>.</em><em>&#xa0; </em>
            </li>
        </ul>
        <p style="margin-bottom:0pt; text-align:justify; line-height:normal; border-bottom:1.5pt solid #000000; padding-bottom:1pt">
            <em><span style="color:#c00000">Quý Cổ đông cần thêm thông tin xin liên hệ Văn phòng HĐQT ĐT: 024-37612888 máy lẻ 1382 hoặc 1383, Email: </span></em><a href="mailto:ir@abbank.vn" style="text-decoration:none"><em><u><span style="color:#c00000">ir@abbank.vn</span></u></em></a><em><span style="color:#c00000">. </span></em>
        </p>
        <p style="margin-bottom:0pt; text-align:justify; line-height:normal">
            <em><span style="color:#c00000">&#xa0;</span></em>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <strong><em>Phiếu biểu quyết được xem là không hợp lệ khi thuộc một trong các trường hợp sau:</em></strong>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <em>1. Phiếu biểu quyết gửi về ABBANK sau thời hạn quy định;</em>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <em>2. Phiếu biểu quyết để trống lựa chọn hoặc biểu quyết từ 02 lựa chọn trở lên;</em>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <em>3. Phiếu biểu quyết không có chữ ký của Cổ đông/Người đại diện Cổ đông;</em>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <em>4. Phiếu biểu quyết không đúng mẫu; sai thông tin xác thực Cổ đông hoặc số cổ phần sở hữu; </em>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <em>5. Nội dung Phiếu biểu quyết bị tẩy, xoá; gạch, sửa; ghi thêm nội dung khác; </em>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <em>6. Phong bì thư chứa Phiếu biểu quyết đã bị mở hoặc không còn nguyên vẹn;</em>
        </p>
        <p style="margin-bottom:0pt; line-height:normal">
            <em>7. Các trường hợp khác theo quy định.</em>
        </p>
        <p style="bottom: 10px; right: 10px; position: absolute;"><a href="https://abbank.vn" target="_blank" style="font-size:11px; color: #d0d0d0">Design by cuongpt1@abbank.vn</a></p>
    </div>
        <br/>
    @endforeach
</div>
</body>
<script src="{{ asset('plugins/html2pdf/html2pdf.bundle.min.js') }}"></script>
<script>
    document.getElementById("export-pdf").addEventListener("click", function () {
        const element = document.getElementById("print-area");

        const opt = {
            margin:       0.5, // lề 0.5 inch
            filename:     'shareholders.pdf', // tên file PDF
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 }, // tăng chất lượng
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        // Xuất PDF
        html2pdf().set(opt).from(element).save();
    });
</script>
{{--<script>--}}
{{--    window.onload = function() {--}}
{{--        // Gọi in ngay--}}
{{--        window.print();--}}

{{--        // Sau khi in xong thì chuyển trang--}}
{{--        window.onafterprint = function() {--}}
{{--            let currentPage = {{ $shareholders->currentPage() }};--}}
{{--            let lastPage    = {{ $shareholders->lastPage() }};--}}
{{--            if (currentPage < lastPage) {--}}
{{--                // Gọi trang tiếp theo--}}
{{--                window.location.href = "?page=" + (currentPage + 1);--}}
{{--            } else {--}}
{{--                alert("✅ Đã in xong tất cả!");--}}
{{--                window.close();--}}
{{--            }--}}
{{--        };--}}
{{--    };--}}
{{--</script>--}}
</html>
