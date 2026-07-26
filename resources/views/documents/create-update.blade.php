@extends('layouts.app')

@section('title', 'Update Dokumen - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">Update</li>
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
                <div class="mb-4">
                    <h4 class="card-title mb-0">Pilih Jenis Update</h4>
                    <p class="text-muted mb-0">Pilih jenis pembaruan yang ingin dilakukan pada dokumen</p>
                </div>

                <div class="row g-4">
                    <!-- Diubah -->
                    <div class="col-md-4">
                        <a href="{{ route('documents.create.update.diubah') }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm" style="transition: all 0.2s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)';">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <div class=" rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background: linear-gradient(135deg, #556ee5, #7b8ff7);">
                                            <i class="ti ti-pencil text-white" style="font-size: 28px;"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-2" style="color: #343a40;">Diubah</h5>
                                    <p class="card-text text-muted mb-0" style="font-size: 13.5px; line-height: 1.6;">
                                        Revisi dokumen dengan perubahan konten secara menyeluruh. Dokumen lama akan ditandai sebagai "telah diubah".
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-0 pb-4 text-center">
                                    <span class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-arrow-right me-1"></i> Pilih
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Dicabut -->
                    <div class="col-md-4">
                        <a href="{{ route('documents.create.update.dicabut') }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm" style="transition: all 0.2s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)';">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <div class=" rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background: linear-gradient(135deg, #ff6b6b, #ff8787);">
                                            <i class="ti ti-archive text-white" style="font-size: 28px;"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-2" style="color: #343a40;">Dicabut</h5>
                                    <p class="card-text text-muted mb-0" style="font-size: 13.5px; line-height: 1.6;">
                                        Nonaktifkan atau arsipkan dokumen yang tidak lagi berlaku. Tidak membutuhkan upload file baru.
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-0 pb-4 text-center">
                                    <span class="btn btn-outline-danger btn-sm">
                                        <i class="ti ti-arrow-right me-1"></i> Pilih
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Dicabut Sebagian -->
                    <div class="col-md-4">
                        <a href="{{ route('documents.create.update.dicabut-sebagian') }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm" style="transition: all 0.2s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)';">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <div class=" rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background: linear-gradient(135deg, #f7b731, #fcc146);">
                                            <i class="ti ti-scissors text-white" style="font-size: 28px;"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-2" style="color: #343a40;">Dicabut Sebagian</h5>
                                    <p class="card-text text-muted mb-0" style="font-size: 13.5px; line-height: 1.6;">
                                        Revisi parsial dimana poin-poin tertentu dihapus dari dokumen. Upload PDF hasil revisi.
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-0 pb-4 text-center">
                                    <span class="btn btn-outline-warning btn-sm">
                                        <i class="ti ti-arrow-right me-1"></i> Pilih
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
