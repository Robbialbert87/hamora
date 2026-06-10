@extends('layouts.auth')

@section('title', 'Konfirmasi Password - HAMORA')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo.webp') }}" alt="HAMORA" style="width: 70px; height: 70px; border-radius: 18px; object-fit: cover; margin: 0 auto 20px; display: block; box-shadow: 0 12px 40px rgba(5, 150, 105, 0.3);">
            <h1 class="login-title">Konfirmasi Password</h1>
            <p class="login-subtitle">Konfirmasi password Anda untuk melanjutkan</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-input" required autocomplete="current-password" placeholder="Masukkan password">
                @error('password')
                    <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Konfirmasi</button>
        </form>
    </div>
</div>
@endsection
