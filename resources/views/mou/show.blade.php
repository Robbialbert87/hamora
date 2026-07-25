@extends('layouts.app')

@section('title', $mou->judul . ' - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mou.index') }}">MOU</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($mou->judul, 30) }}</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $mou->judul }}</h4>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="card-title mb-0">Detail MOU</h4>
                    <p class="text-muted mb-0">Informasi lengkap MOU / perjanjian kerja sama</p>
                </div>

                <div class="detail-label">Pihak</div>
                <div class="detail-value" style="overflow-wrap: break-word; word-break: break-word;">{{ $mou->pihak }}</div>

                <div class="detail-label">Judul</div>
                <div class="detail-value" style="overflow-wrap: break-word; word-break: break-word;">{{ $mou->judul }}</div>

                <div class="detail-label">Bidang</div>
                <div class="detail-value">{{ $mou->bidang ? $mou->bidang->nama : '-' }}</div>

                <div class="detail-label">Kategori</div>
                <div class="detail-value">{{ $mou->kategori ? $mou->kategori->nama : '-' }}</div>

                <div class="detail-label">Nomor</div>
                <div class="detail-value">{{ $mou->nomor }}</div>

                <div class="row mb-3">
                    <div class="col-6">
                        <div class="detail-label">Mulai Perjanjian</div>
                        <div class="detail-value" style="font-size: 14px;">{{ $mou->mulai_perjanjian ? $mou->mulai_perjanjian->format('d/m/Y') : '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Akhir Perjanjian</div>
                        <div class="detail-value" style="font-size: 14px;">{{ $mou->akhir_perjanjian ? $mou->akhir_perjanjian->format('d/m/Y') : '-' }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <div class="detail-label">Masa Berlaku</div>
                        <div class="detail-value" style="font-size: 14px;">{{ $mou->masa_berlaku_formatted }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Keterangan</div>
                        <div class="detail-value" style="font-size: 14px;">{{ $mou->keterangan }}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="detail-label">Status</div>
                    @php
                        $labels = ['aktif' => 'Aktif', 'kadaluarsa' => 'Kadaluarsa', 'dicabut' => 'Dicabut'];
                        $colors = ['aktif' => 'success', 'kadaluarsa' => 'danger', 'dicabut' => 'secondary'];
                    @endphp
                    <div><span class="badge bg-{{ $colors[$mou->status] ?? 'secondary' }}" style="font-size: 14px; padding: 8px 16px;">{{ $labels[$mou->status] ?? $mou->status }}</span></div>
                </div>

                <div class="mb-4">
                    <div class="detail-label">Diupload oleh</div>
                    <div class="detail-value" style="font-size: 14px;">{{ $mou->uploader->name ?? '-' }}</div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('mou.download', $mou->id) }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-download me-1"></i> Download
                    </a>
                    @can('edit dokumen')
                    <a href="{{ route('mou.edit', $mou->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-pencil me-1"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('mou.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="card-title mb-0">Preview PDF</h4>
                    <p class="text-muted mb-0">Pratinjau dokumen MOU</p>
                </div>

                <div class="pdf-toolbar" id="pdf-toolbar" style="display: none;">
                    <button class="btn btn-outline-secondary btn-sm" id="prev-page"><i class="ti ti-chevron-left"></i></button>
                    <span id="page-info">Halaman <span id="current-page">1</span> dari <span id="total-pages">0</span></span>
                    <button class="btn btn-outline-secondary btn-sm" id="next-page"><i class="ti ti-chevron-right"></i></button>
                    <div class="ms-auto d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" id="zoom-out"><i class="ti ti-minus"></i></button>
                        <span id="zoom-level" style="font-size: 14px; color: var(--bs-secondary); display: flex; align-items: center;">100%</span>
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
                        <i class="ti ti-alert-triangle" style="font-size: 48px; color: var(--bs-danger);"></i>
                        <p class="mt-2">Gagal memuat PDF</p>
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
        var url = '{{ route("mou.preview", $mou->id) }}';
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
                toolbar.style.display = 'flex';
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