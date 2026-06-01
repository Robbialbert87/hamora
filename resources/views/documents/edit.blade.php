@extends('layouts.app')

@section('title', 'Edit Dokumen - HAMORA')
@section('page-title', 'Edit Dokumen')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Form Edit Dokumen</h2>
                <p class="card-subtitle">Ubah data dokumen</p>
            </div>
        </div>

        <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_dokumen" class="form-control @error('nomor_dokumen') is-invalid @enderror"
                               value="{{ old('nomor_dokumen', $document->nomor_dokumen) }}" required>
                        @error('nomor_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_dokumen" class="form-control @error('nama_dokumen') is-invalid @enderror"
                               value="{{ old('nama_dokumen', $document->nama_dokumen) }}" required>
                        @error('nama_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select @error('tahun') is-invalid @enderror">
                            @foreach(range(date('Y') + 1, date('Y') - 10) as $thn)
                            <option value="{{ $thn }}" {{ old('tahun', $document->tahun) == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                        @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Bidang</label>
                        <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror">
                            <option value="">Pilih Bidang</option>
                            @foreach($bidang ?? [] as $b)
                            <option value="{{ $b->id }}" {{ old('bidang_id', $document->bidang_id) == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                            @endforeach
                        </select>
                        @error('bidang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori ?? [] as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $document->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="draft" {{ old('status', $document->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="aktif" {{ old('status', $document->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="direvisi" {{ old('status', $document->status) == 'direvisi' ? 'selected' : '' }}>Direvisi</option>
                            <option value="kadaluarsa" {{ old('status', $document->status) == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Terbit</label>
                        <input type="date" name="tanggal_terbit" class="form-control @error('tanggal_terbit') is-invalid @enderror"
                               value="{{ old('tanggal_terbit', $document->tanggal_terbit ? $document->tanggal_terbit->format('Y-m-d') : '') }}">
                        @error('tanggal_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="date" name="tanggal_berlaku" class="form-control @error('tanggal_berlaku') is-invalid @enderror"
                               value="{{ old('tanggal_berlaku', $document->tanggal_berlaku ? $document->tanggal_berlaku->format('Y-m-d') : '') }}">
                        @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Dokumen Revisi Dari</label>
                        <select name="parent_document_id" class="form-select">
                            <option value="">Bukan Revisi</option>
                            @foreach($documents ?? [] as $d)
                            <option value="{{ $d->id }}" {{ old('parent_document_id', $document->parent_document_id) == $d->id ? 'selected' : '' }}>
                                {{ $d->nomor_dokumen }} - {{ $d->nama_dokumen }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="4">{{ old('deskripsi', $document->deskripsi) }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">File PDF</label>
                        @if($document->file_pdf)
                        <div class="mb-2">
                            <span class="text-muted" style="font-size: 13px;">
                                <i class="fas fa-file-pdf text-danger"></i> File saat ini: {{ $document->file_pdf }}
                            </span>
                        </div>
                        @endif
                        <input type="file" name="file_pdf" class="form-control @error('file_pdf') is-invalid @enderror" accept="application/pdf">
                        <div class="text-muted mt-1" style="font-size: 12px;">Kosongkan jika tidak ingin mengubah file. Format: PDF, Maks: 20MB</div>
                        @error('file_pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Update Dokumen</button>
                <a href="{{ route('documents.index') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</section>
@endsection
