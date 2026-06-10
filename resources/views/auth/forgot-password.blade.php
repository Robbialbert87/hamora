@extends('layouts.auth')

@section('title', 'Lupa Password - HAMORA')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo.webp') }}" alt="HAMORA" style="width: 70px; height: 70px; border-radius: 18px; object-fit: cover; margin: 0 auto 20px; display: block; box-shadow: 0 12px 40px rgba(5, 150, 105, 0.3);">
            <h1 class="login-title">Lupa Password</h1>
            <p class="login-subtitle">Masukkan email untuk mereset password</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; border-radius: 12px; padding: 12px 18px; margin-bottom: 24px; font-size: 14px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="Masukkan email">
                @error('email')
                    <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Kirim Link Reset Password</button>

            <div class="login-footer" style="margin-top: 20px;">
                <a href="{{ route('login') }}">Kembali ke login</a>
            </div>
        </form>
    </div>
</div>
@endsection
