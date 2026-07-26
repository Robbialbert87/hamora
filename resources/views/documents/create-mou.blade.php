@extends('layouts.app')

@section('title', 'Upload MOU - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">Upload MOU (Kerja Sama)</li>
                </ol>
            </div>
            <h4 class="page-title">Upload MOU (Kerja Sama)</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Form Upload MOU (Kerja Sama)</h4>
                <p class="text-muted mb-4">Lengkapi form di bawah untuk upload dokumen MOU dengan masa berlaku</p>

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="jenis_upload" value="mou">

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_dokumen"
                                    class="form-control @error('nomor_dokumen') is-invalid @enderror"
                                    value="{{ old('nomor_dokumen') }}" required
                                    placeholder="Contoh: MOU/001/HAMORA/2024">
                                @error('nomor_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="nama_dokumen"
                                    class="form-control @error('nama_dokumen') is-invalid @enderror"
                                    value="{{ old('nama_dokumen') }}" required
                                    placeholder="Nama lengkap dokumen MOU">
                                @error('nama_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <select name="tahun"
                                    class="form-select @error('tahun') is-invalid @enderror">
                                    @foreach (range(date('Y') + 1, date('Y') - 10) as $thn)
                                        <option value="{{ $thn }}"
                                            {{ old('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Bidang</label>
                                <select name="bidang_id"
                                    class="form-select @error('bidang_id') is-invalid @enderror">
                                    <option value="">Pilih Bidang</option>
                                    @foreach ($bidang ?? [] as $b)
                                        <option value="{{ $b->id }}"
                                            {{ old('bidang_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama }}</option>
                                    @endforeach
                                </select>
                                @error('bidang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id"
                                    class="form-select @error('kategori_id') is-invalid @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategori ?? [] as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_terbit"
                                    class="form-control @error('tanggal_terbit') is-invalid @enderror"
                                    value="{{ old('tanggal_terbit') }}">
                                @error('tanggal_terbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Berlaku <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_berlaku"
                                    class="form-control @error('tanggal_berlaku') is-invalid @enderror"
                                    value="{{ old('tanggal_berlaku') }}">
                                @error('tanggal_berlaku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">File PDF <span class="text-danger">*</span></label>
                                <input type="file" name="file_pdf"
                                    class="form-control @error('file_pdf') is-invalid @enderror"
                                    accept="application/pdf" required>
                                @error('file_pdf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format: PDF, Maks: 20MB</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft">Draft</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="kadaluarsa">Kadaluarsa</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
