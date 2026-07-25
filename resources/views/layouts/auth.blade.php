<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name', 'HAMORA'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

    <!-- App CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body id="body" class="auth-page">
    <!-- Full-Screen Welcome Section -->
    <div class="auth-welcome">
        <div class="auth-welcome-content">
            <div class="auth-welcome-logo">
                <img src="{{ asset('images/logo.webp') }}" alt="HAMORA" class="auth-logo-img">
            </div>
            <h1 class="auth-welcome-title">Selamat Datang di <span class="text-warning">HAMORA</span></h1>
            <p class="auth-welcome-subtitle">Himpunan Arsip Manajemen Online RSUD Abdul Manap</p>
            <div class="auth-welcome-divider"></div>

            @hasSection('login-button')
                @yield('login-button')
            @else
                <button type="button" class="btn btn-login-trigger" onclick="openLoginModal()">
                    <i class="ti ti-login me-2"></i>Masuk
                </button>
            @endif
        </div>

        @hasSection('inline-content')
            <div class="auth-inline-card">
                @yield('inline-content')
            </div>
        @endif

        <div class="auth-welcome-footer">
            <p>&copy; {{ date('Y') }} RSUD Abdul Manap. All rights reserved.</p>
        </div>
    </div>

    @yield('content')

    <!-- Vendor JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

    <!-- App JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openLoginModal() {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }
    </script>

    @yield('scripts')
</body>

</html>
