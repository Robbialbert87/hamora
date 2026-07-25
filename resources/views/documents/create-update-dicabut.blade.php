@extends('layouts.app')

@section('title', 'Update Dokumen - Dicabut - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">Dicabut</li>
                </ol>
            </div>
            <h4 class="page-title">Update Dokumen - Dicabut</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Dicabut</h4>
                        <p class="text-muted mb-0">Arsipkan atau nonaktifkan dokumen yang tidak lagi berlaku</p>
                    </div>
                    <a href="{{ route('documents.create.update') }}" class="btn btn-outline-secondary btn-sm"><i class="ti ti-arrow-left"></i> Kembali</a>
                </div>

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="formUpload" novalidate>
                    @csrf
                    <input type="hidden" name="jenis_upload" value="dicabut">
                    <input type="hidden" name="parent_document_id" id="parentDocumentId" value="">

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    {{-- Pencarian Dokumen --}}
                    <div class="mb-4">
                        <label class="form-label">Cari Dokumen yang akan dicabut <span class="text-danger">*</span></label>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="filterPencarian"
                                    placeholder="Cari Nomor atau Nama Dokumen" autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="filterBidang">
                                    <option value="">Semua Bidang</option>
                                    @foreach ($bidang ?? [] as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="filterKategori">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategori ?? [] as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="filterTahun">
                                    <option value="">Semua Tahun</option>
                                    @foreach (range(date('Y') + 1, date('Y') - 10) as $thn)
                                        <option value="{{ $thn }}">{{ $thn }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="button" class="btn btn-primary btn-sm flex-fill"
                                    onclick="filterDocuments()"><i class="ti ti-search"></i> Cari</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill"
                                    onclick="resetFilter()"><i class="ti ti-refresh"></i> Reset</button>
                            </div>
                        </div>
                        <div id="documentList"
                            style="max-height: 260px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; display: none;">
                            <div id="filterEmpty" class="px-3 py-3 text-muted text-center"
                                style="font-size: 14px; display: none;">Tidak ada dokumen yang cocok dengan pencarian.</div>
                            @forelse($documents ?? [] as $d)
                                <div class="document-item px-3 py-2" data-id="{{ $d->id }}"
                                    data-nomor="{{ strtolower(e($d->nomor_dokumen)) }}"
                                    data-nama="{{ strtolower(e($d->nama_dokumen)) }}"
                                    data-bidang="{{ $d->bidang_id }}"
                                    data-kategori="{{ $d->kategori_id }}"
                                    data-tahun="{{ $d->tahun }}"
                                    data-tanggal-berlaku="{{ $d->tanggal_berlaku ? $d->tanggal_berlaku->format('Y-m-d') : '' }}"
                                    style="cursor: pointer; border-bottom: 1px solid #dee2e6; transition: background 0.15s;"
                                    onclick="selectDoc({{ $d->id }}, '{{ e($d->nomor_dokumen) }} - {{ e($d->nama_dokumen) }}')">
                                    <div class="fw-medium" style="font-size: 14px;">
                                        {{ $d->nomor_dokumen }} - {{ $d->nama_dokumen }}</div>
                                    <small class="text-muted">{{ $d->bidang?->nama ?? '-' }} |
                                        {{ $d->kategori?->nama ?? '-' }} | {{ $d->tahun }}</small>
                                </div>
                            @empty
                                <div class="px-3 py-3 text-muted text-center" style="font-size: 14px;">Tidak ada dokumen yang tersedia.</div>
                            @endforelse
                        </div>
                        <div id="selectedDoc" class="mt-2" style="display: none;">
                            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-light">
                                <i class="ti ti-circle-check text-success"></i>
                                <span id="selectedDocText" class="text-success fw-medium" style="font-size: 14px;"></span>
                                <button type="button" class="btn btn-sm ms-auto"
                                    onclick="clearDoc()"
                                    style="background: none; border: none; color: #6c757d; padding: 4px 8px;">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div id="sectionArsip" class="form-section active">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="alert alert-info d-flex align-items-center gap-2" role="alert">
                                    <i class="ti ti-info-circle text-primary"></i>
                                    <span>Dokumen yang dipilih akan diarsipkan dan tidak lagi aktif. Proses ini tidak membutuhkan upload file baru.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Pencabutan <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_pencabutan"
                                        class="form-control @error('tanggal_pencabutan') is-invalid @enderror"
                                        value="{{ old('tanggal_pencabutan', date('Y-m-d')) }}">
                                    @error('tanggal_pencabutan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Keterangan Pencabutan</label>
                                    <textarea name="keterangan_pencabutan" class="form-control" rows="3" placeholder="Alasan pencabutan dokumen (opsional)">{{ old('keterangan_pencabutan') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
                        <a href="{{ route('documents.create.update') }}" class="btn btn-outline-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        function filterDocuments() {
            var pencarian = document.getElementById('filterPencarian').value.toLowerCase().trim();
            var bidang = document.getElementById('filterBidang').value;
            var kategori = document.getElementById('filterKategori').value;
            var tahun = document.getElementById('filterTahun').value;

            document.getElementById('documentList').style.display = '';

            var items = document.querySelectorAll('.document-item');
            var hasVisible = false;
            items.forEach(function(it) {
                var match = true;
                if (pencarian && !it.getAttribute('data-nomor').includes(pencarian) && !it.getAttribute('data-nama')
                    .includes(pencarian)) match = false;
                if (bidang && it.getAttribute('data-bidang') !== bidang) match = false;
                if (kategori && it.getAttribute('data-kategori') !== kategori) match = false;
                if (tahun && it.getAttribute('data-tahun') !== tahun) match = false;
                it.style.display = match ? '' : 'none';
                if (match) hasVisible = true;
            });

            var empty = document.getElementById('filterEmpty');
            if (empty) empty.style.display = hasVisible ? 'none' : '';

            var sel = document.getElementById('selectedDoc');
            if (sel && sel.style.display !== 'none') {
                var pid = document.getElementById('parentDocumentId').value;
                var stillVisible = false;
                items.forEach(function(it) {
                    if (it.getAttribute('data-id') === pid && it.style.display !== 'none') stillVisible = true;
                });
                if (!stillVisible) clearDoc();
            }
        }

        function resetFilter() {
            document.getElementById('filterPencarian').value = '';
            document.getElementById('filterBidang').value = '';
            document.getElementById('filterKategori').value = '';
            document.getElementById('filterTahun').value = '';
            document.getElementById('parentDocumentId').value = '';
            document.getElementById('selectedDoc').style.display = 'none';
            document.getElementById('documentList').style.display = 'none';
            var empty = document.getElementById('filterEmpty');
            if (empty) empty.style.display = 'none';
        }

        function selectDoc(id, text) {
            document.getElementById('parentDocumentId').value = id;
            document.getElementById('selectedDocText').textContent = text;
            document.getElementById('selectedDoc').style.display = '';
            document.querySelectorAll('.document-item').forEach(function(it) {
                it.style.display = 'none';
            });
        }

        function clearDoc() {
            document.getElementById('parentDocumentId').value = '';
            document.getElementById('selectedDoc').style.display = 'none';
            document.querySelectorAll('.document-item').forEach(function(it) {
                it.style.display = '';
            });
        }

        function attachFilters() {
            var ids = ['filterPencarian', 'filterBidang', 'filterKategori', 'filterTahun'];
            ids.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.addEventListener('input', filterDocuments);
                if (el && el.tagName === 'SELECT') el.addEventListener('change', filterDocuments);
            });
        }

        attachFilters();
    </script>
@endsection
