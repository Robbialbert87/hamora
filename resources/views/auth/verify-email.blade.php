@extends('layouts.auth')

@section('title', 'Verifikasi Email - HAMORA')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo.png') }}" alt="HAMORA" style="width: 70px; height: 70px; border-radius: 18px; object-fit: cover; margin: 0 auto 20px; display: block; box-shadow: 0 12px 40px rgba(5, 150, 105, 0.3);">
            <h1 class="login-title">Verifikasi Email</h1>
            <p class="login-subtitle">Terima kasih telah mendaftar! Silakan verifikasi email Anda.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; border-radius: 12px; padding: 12px 18px; margin-bottom: 24px; font-size: 14px;">
                Link verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 24px; line-height: 1.6;">
            Sebelum melanjutkan, klik link verifikasi yang telah kami kirim ke email Anda. Jika tidak menerima email, klik tombol di bawah.
        </p>

        <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom: 16px;">
            @csrf
            <button type="submit" class="btn btn-primary">Kirim Ulang Email Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-secondary">Logout</button>
        </form>
    </div>
</div>
@endsection
