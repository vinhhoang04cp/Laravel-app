<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Hệ thống cổ đông') }}</title>
    <link href="{{ asset('plugins/bootstrap5/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        footer {
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
            {{ config('app.name', 'Hệ thống cổ đông') }}
        </a>
    </div>
</nav>

<main class="container py-5">
    @yield('content')
</main>

<footer>
    © {{ date('Y') }} {{ config('app.name', 'Hệ thống cổ đông') }}. All rights reserved.
</footer>

<script src="{{ asset('plugins/bootstrap5/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
