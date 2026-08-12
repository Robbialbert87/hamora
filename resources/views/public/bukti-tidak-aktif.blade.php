@extends('layouts.public')

@section('title', 'Link Tidak Tersedia - HAMORA')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-6">
        <div class="card public-card">
            <div class="card-body text-center px-4 py-5">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                    style="width: 72px; height: 72px; background:#fef2f2;">
                    <i class="ti ti-link-off" style="font-size: 34px; color:#dc2626;"></i>
                </div>
                <h4 class="fw-semibold mb-2">Link Tidak Tersedia</h4>
                <p class="text-muted mb-1" style="font-size: 14px;">{{ $pesan }}</p>
                <p class="text-muted mb-0" style="font-size: 13px;">Silakan hubungi admin atau gunakan link lain untuk mengirim data.</p>
            </div>
        </div>
    </div>
</div>
@endsection
