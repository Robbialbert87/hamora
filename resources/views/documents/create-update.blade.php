@extends('layouts.app')

@section('title', 'Update Dokumen - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">Update Dokumen</li>
                </ol>
            </div>
            <h4 class="page-title">Update Dokumen</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Pilih Jenis Update</h4>
                <p class="text-muted mb-4">Pilih jenis perubahan yang akan dilakukan pada dokumen</p>

                <div class="row g-4">
                    <div class="col-md-4">
                        <a href="{{ route('documents.create.update.diubah') }}" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body text-center py-5">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="rounded-circle bg-soft-primary d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                            <i class="ti ti-pencil font-28 text-primary"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Diubah</h5>
                                    <p class="text-muted">Revisi dokumen dengan perubahan konten secara menyeluruh</p>
                                    <span class="btn btn-primary btn-sm"><i class="ti ti-arrow-right"></i> Pilih</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('documents.create.update.dicabut') }}" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body text-center py-5">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="rounded-circle bg-soft-danger d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                            <i class="ti ti-archive font-28 text-danger"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Dicabut</h5>
                                    <p class="text-muted">Arsipkan atau nonaktifkan dokumen yang tidak lagi berlaku</p>
                                    <span class="btn btn-primary btn-sm"><i class="ti ti-arrow-right"></i> Pilih</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('documents.create.update.dicabut-sebagian') }}" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body text-center py-5">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="rounded-circle bg-soft-warning d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                            <i class="ti ti-file-text font-28 text-warning"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Dicabut Sebagian</h5>
                                    <p class="text-muted">Revisi parsial dimana poin tertentu dihapus dari dokumen</p>
                                    <span class="btn btn-primary btn-sm"><i class="ti ti-arrow-right"></i> Pilih</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('documents.create') }}" class="btn btn-outline-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
