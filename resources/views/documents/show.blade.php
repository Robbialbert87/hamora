@extends('layouts.app')

@section('title', $document->nama_dokumen . ' - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($document->nama_dokumen, 30) }}</li>
                </ol>
            </div>
            <h4 class="page-title text-truncate" style="max-width: 350px;">{{ $document->nama_dokumen }}</h4>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left Column: Metadata --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Detail Dokumen</h4>
                <p class="text-muted mb-4">Informasi lengkap dokumen</p>

                {{-- Riwayat Dokumen --}}
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Riwayat Dokumen</h6>
                    @forelse($revisionHistory as $i => $doc)
                    @php
                        $isLatest = $doc->id === $latestDocId;
                        $isCurrent = $doc->id === $document->id;
                    @endphp
                    <div class="d-flex gap-3 mb-3 {{ $isCurrent ? 'p-2 rounded bg-soft-primary' : '' }}">
                        <div class="text-center" style="width: 24px;">
                            @if($doc->status === 'dicabut')
                                <i class="ti ti-archive text-muted"></i>
                            @elseif($isLatest || $isCurrent)
                                <i class="ti ti-check-circle text-primary"></i>
                            @else
                                <i class="ti ti-file text-muted"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <a href="{{ route('documents.show', $doc->id) }}" class="fw-medium {{ $isCurrent ? 'text-primary' : ($isLatest ? 'text-primary' : 'text-dark') }}">
                                    {{ $doc->nomor_dokumen }}
                                </a>
                                @if($doc->status === 'dicabut')
                                    <span class="badge bg-secondary">Dicabut</span>
                                @elseif($isCurrent)
                                    <span class="badge bg-primary">v{{ $doc->versi ?? '1' }}</span>
                                @elseif($isLatest)
                                    <span class="badge bg-primary">Saat ini v{{ $doc->versi ?? '1' }}</span>
                                @else
                                    <span class="badge bg-light text-dark">Lama v{{ $doc->versi ?? '1' }}</span>
                                @endif
                            </div>
                            <small class="text-muted">{{ $doc->nama_dokumen }}</small>
                        </div>
                    </div>
                    @empty
                    <small class="text-muted">Tidak ada riwayat revisi.</small>
                    @endforelse
                </div>

                <hr>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Nomor Dokumen</label>
                    <span class="fw-medium">{{ $document->nomor_dokumen }}</span>
                </div>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Nama Dokumen</label>
                    <span class="fw-medium" style="word-break: break-word;">{{ $document->nama_dokumen }}</span>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Bidang</label>
                        <span class="fw-medium">{{ $document->bidang->nama ?? '-' }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Kategori</label>
                        <span class="fw-medium">{{ $document->kategori->nama ?? '-' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Tahun</label>
                        <span class="fw-medium">{{ $document->tahun }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Versi</label>
                        <span class="fw-medium">v{{ $document->versi ?? '1' }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Status</label>
                    <span class="badge badge-{{ $document->status }}" style="font-size: 13px; padding: 6px 12px;">{{ ucfirst($document->status) }}</span>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Tanggal Terbit</label>
                        <span class="fw-medium">{{ $document->tanggal_terbit ? $document->tanggal_terbit->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Tanggal Berlaku</label>
                        <span class="fw-medium">{{ $document->tanggal_berlaku ? $document->tanggal_berlaku->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>

                @if($document->status === 'dicabut' && $document->tanggal_pencabutan)
                <div class="mb-3 p-3 bg-light rounded">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="ti ti-archive text-muted"></i>
                        <small class="text-muted">Dicabut pada</small>
                    </div>
                    <span class="fw-medium">{{ $document->tanggal_pencabutan->format('d/m/Y') }}</span>
                </div>
                @endif

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Diupload oleh</label>
                    <span class="fw-medium">{{ $document->uploader->name ?? '-' }}</span>
                </div>

                <div class="mb-4">
                    <label class="text-muted text-uppercase small d-block mb-1">Deskripsi</label>
                    <span>{{ $document->deskripsi ?? '-' }}</span>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('documents.download', $document->id) }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-download me-1"></i> Download
                    </a>
                    @can('edit dokumen')
                    <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-pencil me-1"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: PDF Preview --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Preview PDF</h4>
                <p class="text-muted mb-3">Pratinjau dokumen</p>

                <div class="pdf-toolbar d-none" id="pdf-toolbar">
                    <button class="btn btn-outline-secondary btn-sm" id="prev-page"><i class="ti ti-chevron-left"></i></button>
                    <span id="page-info">Halaman <span id="current-page">1</span> dari <span id="total-pages">0</span></span>
                    <button class="btn btn-outline-secondary btn-sm" id="next-page"><i class="ti ti-chevron-right"></i></button>
                    <div class="ms-auto d-flex gap-2 align-items-center">
                        <button class="btn btn-outline-secondary btn-sm" id="zoom-out"><i class="ti ti-minus"></i></button>
                        <span id="zoom-level" class="small text-muted">100%</span>
                        <button class="btn btn-outline-secondary btn-sm" id="zoom-in"><i class="ti ti-plus"></i></button>
                    </div>
                </div>

                <div class="pdf-container" id="pdf-container">
                    <div id="pdf-loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat PDF...</p>
                    </div>
                    <canvas id="pdf-canvas" style="display: none;"></canvas>
                    <div id="pdf-error" style="display: none;" class="text-center py-5 text-muted">
                        <i class="ti ti-alert-triangle font-48 mb-3 text-warning"></i>
                        <p>Gagal memuat PDF</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var url = '{{ route("documents.preview", $document->id) }}';
        var pdfDoc = null;
        var pageNum = 1;
        var scale = 1.0;
        var canvas = document.getElementById('pdf-canvas');
        var ctx = canvas.getContext('2d');
        var loading = document.getElementById('pdf-loading');
        var errorEl = document.getElementById('pdf-error');
        var toolbar = document.getElementById('pdf-toolbar');

        function renderPage(num) {
            pdfDoc.getPage(num).then(function(page) {
                var viewport = page.getViewport({ scale: scale });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                return page.render(renderContext).promise;
            }).then(function() {
                document.getElementById('current-page').textContent = num;
                document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
            });
        }

        function loadPDF() {
            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                pdfDoc = pdf;
                document.getElementById('total-pages').textContent = pdf.numPages;
                loading.style.display = 'none';
                canvas.style.display = 'block';
                toolbar.classList.remove('d-none');
                renderPage(1);
            }).catch(function(err) {
                console.error('PDF load error:', err);
                loading.style.display = 'none';
                errorEl.style.display = 'block';
            });
        }

        document.getElementById('prev-page').addEventListener('click', function() {
            if (pageNum <= 1) return;
            pageNum--;
            renderPage(pageNum);
        });

        document.getElementById('next-page').addEventListener('click', function() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            renderPage(pageNum);
        });

        document.getElementById('zoom-in').addEventListener('click', function() {
            scale = Math.min(scale + 0.25, 3.0);
            renderPage(pageNum);
        });

        document.getElementById('zoom-out').addEventListener('click', function() {
            scale = Math.max(scale - 0.25, 0.5);
            renderPage(pageNum);
        });

        loadPDF();
    });
</script>
@endsection
