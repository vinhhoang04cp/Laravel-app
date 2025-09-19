<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <!-- Styles -->
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* chiếm toàn bộ chiều cao màn hình */
        }

        img {
            display: block;
            width: 100%;
            height: auto;
        }

        .banner {
            flex: 0 0 auto;
            margin: 0;
            padding: 0;
        }

        .footer {
            flex: 0 0 auto;
            margin: 0; /* bỏ khoảng trắng mặc định */
            padding: 32px 86px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--color-background-brand-bold-default, #008789);
        }

        .copyright {
            display: flex;
            align-items: center;
            gap: 20px;
            white-space: nowrap; /* không cho text tự xuống dòng */
        }

        .copyright p {
            color: #FFF;
            margin: 0;
            font-family: 'Inter-Regular', sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            letter-spacing: -0.4px;
        }

        .footer ul {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer ul li,
        .footer ul li a {
            color: #FFF;
            text-decoration: none;
            font-family: 'Inter-Regular', sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            letter-spacing: -0.4px;
        }

        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .footer {
                flex-direction: column;
                gap: 12px;
                padding: 16px 24px;
                text-align: center;
            }

            .copyright {
                flex-direction: column;
                gap: 8px;
                white-space: normal; /* cho phép text xuống dòng */
            }
        }
    </style>
</head>
<body class="antialiased">

<div class="banner">
    <img src="{{ asset('images/banner/banner-pc.png') }}" alt="ABBANK Banner">
</div>

<footer class="footer">
    <div class="copyright">
        <img src="{{ asset('images/logo/logo-footer.png') }}" alt="Logo Footer">
        <p>Bản quyền ©2024 ABBANK</p>
    </div>
    <ul>
        <li><a href="https://abbank.vn/tin-tuc/noi-dung-niem-yet">Nội dung niêm yết</a></li>
        <li>|</li>
        <li><a href="https://abbank.vn/lien-he.html">Liên hệ</a></li>
        <li>|</li>
        <li><a href="https://abbank.vn/ve-chung-toi.html">Về chúng tôi</a></li>
    </ul>
</footer>

</body>
</html>
