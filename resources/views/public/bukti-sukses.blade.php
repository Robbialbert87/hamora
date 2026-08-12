@extends('layouts.public')

@section('title', 'Data Terkirim - HAMORA')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card public-card">
            <div class="card-body text-center px-4 py-5">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                    style="width: 72px; height: 72px; background:#ecfdf3;">
                    <i class="ti ti-check" style="font-size: 34px; color:#1ab45d;"></i>
                </div>
                <h4 class="fw-semibold mb-2">Data Berhasil Dikirim</h4>
                <p class="text-muted mb-1" style="font-size: 14px;">
                    Terima kasih. Data <strong>{{ $rekap->nama }}</strong> telah kami terima.
                </p>
                <p class="text-muted mb-4" style="font-size: 13px;">Tim kami akan memverifikasi dan merekap data yang masuk.</p>
                <a href="{{ route('bukti.publik.form', $rekap->token) }}" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kirim Data Lain
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
