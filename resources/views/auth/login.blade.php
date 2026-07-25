@extends('layouts.auth')

@section('title', 'Login - HAMORA')

@section('content')
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content login-modal-content">
            <div class="modal-header login-modal-header">
                <div class="d-flex align-items-center">
                    <div class="login-modal-icon">
                        <i class="ti ti-user"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="modal-title" id="loginModalLabel">Masuk ke HAMORA</h5>
                        <p class="text-muted mb-0 font-12">Silakan masukkan kredensial Anda</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-1"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check-circle me-1"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label" for="nip">
                            <i class="ti ti-id me-1"></i>NIP
                        </label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" placeholder="Masukkan NIP Anda" value="{{ old('nip') }}" required autofocus autocomplete="off">
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" for="password">
                            <i class="ti ti-lock me-1"></i>Password
                        </label>
                        <div class="position-relative">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                            <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-muted border-0 bg-transparent" onclick="togglePassword()" tabindex="-1">
                                <i class="ti ti-eye" id="togglePasswordIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check form-switch form-switch-success">
                            <input class="form-check-input" type="checkbox" id="customSwitchSuccess" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="customSwitchSuccess">Ingat saya</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-muted font-13 text-decoration-none">
                                <i class="ti ti-key me-1"></i>Lupa password?
                            </a>
                        @endif
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit" id="btnLogin">
                            <span class="btn-text">Masuk</span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Memproses...
                            </span>
                            <i class="ti ti-login ms-1 btn-text"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var loginModal = document.getElementById('loginModal');
    var hasError = @json(session('error'));

    if (hasError) {
        openLoginModal();
    }
});

function togglePassword() {
    var passwordInput = document.getElementById('password');
    var toggleIcon = document.getElementById('togglePasswordIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('ti-eye');
        toggleIcon.classList.add('ti-eye-off');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('ti-eye-off');
        toggleIcon.classList.add('ti-eye');
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    var btn = document.getElementById('btnLogin');
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loading').classList.remove('d-none');
    btn.disabled = true;
});
</script>
@endsection
