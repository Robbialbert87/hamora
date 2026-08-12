<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'HAMORA')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .public-header {
            background: linear-gradient(120deg, #5156be 0%, #4aa0d5 100%);
            color: #fff;
        }
        .public-header .brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .public-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(30, 41, 59, 0.08);
        }
        .public-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 12px 12px 0 0;
        }
        .public-footer {
            font-size: 12.5px;
            color: #64748b;
        }
        .required-mark {
            color: #dc2626;
        }
        .honeypot-field {
            position: absolute;
            left: -5000px;
            top: -5000px;
            opacity: 0;
            height: 0;
            width: 0;
            overflow: hidden;
        }
        .form-label {
            font-weight: 600;
            font-size: 13.5px;
            color: #334155;
        }
    </style>
</head>
<body>
    <div class="public-header">
        <div class="container py-3 d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo.webp') }}" alt="HAMORA" style="width: 36px; height: 36px; border-radius: 8px;">
            <div class="lh-sm">
                <span class="brand d-block" style="font-size: 18px;">HAMORA</span>
                <small class="d-block" style="font-size: 12px; opacity: .9;">Himpunan Arsip Manajemen Online RSUD Abdul Manap</small>
            </div>
        </div>
    </div>

    <div class="container py-4">
        @yield('content')
    </div>

    <div class="container text-center public-footer pb-4">
        HAMORA &middot; Himpunan Arsip Manajemen Online RSUD Abdul Manap
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
