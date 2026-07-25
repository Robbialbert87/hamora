@extends('layouts.auth')

@section('title', 'Konfirmasi Password - HAMORA')

@section('inline-content')
<div class="auth-inline-header">
    <h4>Konfirmasi Password</h4>
    <p class="text-muted mb-0">Konfirmasi password Anda untuk melanjutkan</p>
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="form-group mb-3">
        <label class="form-label" for="password">
            <i class="ti ti-lock me-1"></i>Password
        </label>
        <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password" placeholder="Masukkan password">
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i>Konfirmasi
        </button>
    </div>
</form>
@endsection
