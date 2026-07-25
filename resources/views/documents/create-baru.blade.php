@extends('layouts.app')

@section('title', 'Upload Dokumen Baru - HAMORA')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">Upload Baru</li>
                </ol>
            </div>
            <h4 class="page-title">Upload Dokumen Baru</h4>
        </div>
    </div>
</div>

<form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" novalidate id="formUploadBaru">
@csrf
<input type="hidden" name="jenis_upload" value="baru">

<div class="row g-4">
    {{-- Kolom Kiri: Form Fields --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="upload-form-icon">
                        <i class="ti ti-file-invoice"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-semibold">Informasi Dokumen</h5>
                        <p class="text-muted mb-0" style="font-size: 13px;">Lengkapi data dokumen yang akan diupload</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="ti ti-alert-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="upload-label">Nomor Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_dokumen"
                            class="form-control @error('nomor_dokumen') is-invalid @enderror"
                            value="{{ old('nomor_dokumen') }}" required
                            placeholder="001/HAMORA/2025">
                        @error('nomor_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="upload-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_dokumen"
                            class="form-control @error('nama_dokumen') is-invalid @enderror"
                            value="{{ old('nama_dokumen') }}" required
                            placeholder="Nama lengkap dokumen">
                        @error('nama_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="upload-label">Tahun <span class="text-danger">*</span></label>
                        <select name="tahun" class="form-select @error('tahun') is-invalid @enderror">
                            @foreach (range(date('Y') + 1, date('Y') - 10) as $thn)
                                <option value="{{ $thn }}" {{ old('tahun', date('Y')) == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="upload-label">Bidang <span class="text-danger">*</span></label>
                        <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                            @foreach ($bidang ?? [] as $b)
                                <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                            @endforeach
                        </select>
                        @error('bidang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="upload-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                            <option value="">Pilih</option>
                            @foreach ($kategori ?? [] as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="upload-label">Tanggal Terbit <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_terbit"
                            class="form-control @error('tanggal_terbit') is-invalid @enderror"
                            value="{{ old('tanggal_terbit', date('Y-m-d')) }}">
                        @error('tanggal_terbit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="upload-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="kadaluarsa" {{ old('status') === 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="upload-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="2" placeholder="Ringkasan isi dokumen...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: File Upload + Actions --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="upload-form-icon" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                        <i class="ti ti-paperclip"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-semibold">File Dokumen</h5>
                        <p class="text-muted mb-0" style="font-size: 13px;">Upload file PDF dokumen</p>
                    </div>
                </div>

                <div class="upload-zone flex-grow-1" id="uploadZone">
                    <input type="file" name="file_pdf" id="fileInput" accept="application/pdf" class="d-none" required>
                    <div class="upload-zone-content" id="uploadZoneContent">
                        <div class="upload-zone-icon">
                            <i class="ti ti-cloud-upload"></i>
                        </div>
                        <p class="upload-zone-title">Seret & lepas file PDF</p>
                        <p class="upload-zone-subtitle">atau klik untuk memilih</p>
                        <div class="upload-zone-info">
                            <i class="ti ti-info-circle"></i>
                            PDF &middot; Maks. 20MB
                        </div>
                    </div>
                    <div class="upload-zone-preview d-none" id="uploadPreview">
                        <div class="upload-zone-file">
                            <div class="upload-zone-file-icon">
                                <i class="ti ti-file-text"></i>
                            </div>
                            <div class="upload-zone-file-info">
                                <span class="upload-zone-file-name" id="fileName"></span>
                                <span class="upload-zone-file-size" id="fileSize"></span>
                            </div>
                            <button type="button" class="upload-zone-file-remove" id="fileRemove" title="Hapus file">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                    @error('file_pdf')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('documents.create') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <i class="ti ti-device-floppy me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const zone = document.getElementById('uploadZone');
        const input = document.getElementById('fileInput');
        const content = document.getElementById('uploadZoneContent');
        const preview = document.getElementById('uploadPreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const removeBtn = document.getElementById('fileRemove');
        const form = document.getElementById('formUploadBaru');
        const btnSubmit = document.getElementById('btnSubmit');

        zone.addEventListener('click', function(e) {
            if (e.target === removeBtn || removeBtn.contains(e.target)) return;
            input.click();
        });

        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            zone.classList.add('dragover');
        });

        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            zone.classList.remove('dragover');
        });

        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            zone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                handleFile(e.dataTransfer.files[0]);
            }
        });

        input.addEventListener('change', function() {
            if (this.files.length) handleFile(this.files[0]);
        });

        removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            input.value = '';
            content.classList.remove('d-none');
            preview.classList.add('d-none');
        });

        function handleFile(file) {
            if (file.type !== 'application/pdf') {
                window.showToast ? showToast('error', 'Hanya file PDF yang diperbolehkan.') : alert('Hanya file PDF yang diperbolehkan.');
                input.value = '';
                return;
            }
            if (file.size > 20 * 1024 * 1024) {
                window.showToast ? showToast('error', 'Ukuran file maksimal 20MB.') : alert('Ukuran file maksimal 20MB.');
                input.value = '';
                return;
            }
            fileName.textContent = file.name;
            fileSize.textContent = formatSize(file.size);
            content.classList.add('d-none');
            preview.classList.remove('d-none');
        }

        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        form.addEventListener('submit', function() {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
        });
    });
</script>
@endsection
