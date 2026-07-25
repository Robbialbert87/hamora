@extends('layouts.app')

@section('title', 'Upload Dokumen - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">Upload Dokumen</li>
                </ol>
            </div>
            <h4 class="page-title">Upload Dokumen</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Pilih Jenis Upload</h4>
                <p class="text-muted mb-4">Pilih jenis dokumen yang akan diupload</p>

                <div class="row g-4">
                    <div class="col-md-12">
                        <a href="{{ route('documents.create.baru') }}" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body text-center py-5">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="rounded-circle bg-soft-primary d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                            <i class="ti ti-upload font-28 text-primary"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Upload Dokumen Baru</h5>
                                    <p class="text-muted">Upload dokumen baru tanpa masa berlaku, seperti kebijakan internal, SOP, atau laporan</p>
                                    <span class="btn btn-primary btn-sm"><i class="ti ti-arrow-right"></i> Pilih</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
