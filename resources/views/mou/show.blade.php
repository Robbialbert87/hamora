@extends('layouts.app')

@section('title', $mou->judul . ' - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
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
                <h4 class="card-title mb-1">Detail MOU</h4>
                <p class="text-muted mb-4">Informasi lengkap MOU / perjanjian kerja sama</p>

            {{-- Riwayat Perpanjangan --}}
            @if(count($revisionHistory) > 0)
            <div class="mb-0">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-title mb-0">Riwayat Perpanjangan</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ count($revisionHistory) - 1 }} perpanjangan</span>
                </div>

                <div class="revision-timeline">
                    @php
                        $visibleCount = 3;
                        $totalRevisions = count($revisionHistory);
                        $hideUntil = $totalRevisions - $visibleCount;
                    @endphp
                    @foreach($revisionHistory as $idx => $rev)
                    @php
                        $isLatest = $rev->id === $latestMouId;
                        $isCurrent = $rev->id === $mou->id;
                        $isLastAll = $idx === $totalRevisions - 1;
                        $isHidden = $idx < $hideUntil;
                        $statusIcons = [
                            'aktif'     => 'ti ti-circle-check',
                            'kadaluarsa'=> 'ti ti-clock',
                            'dicabut'   => 'ti ti-circle-x',
                        ];
                        $icon = $statusIcons[$rev->status] ?? 'ti ti-file';
                    @endphp
                    <a href="{{ route('mou.show', $rev->id) }}" class="revision-item {{ $isCurrent ? 'current' : '' }} {{ ($isLastAll && !$isHidden) ? 'is-last' : '' }}" @if($isHidden) style="display:none;" id="revision-extra-{{ $idx }}" @endif>
                        <div class="revision-node">
                            <div class="revision-icon {{ $isCurrent ? 'active' : ($rev->status === 'aktif' ? 'success' : ($rev->status === 'dicabut' ? 'danger' : 'default')) }}">
                                <i class="{{ $icon }}"></i>
                            </div>
                            @if(!$isLastAll)
                            <div class="revision-line {{ $rev->status === 'aktif' ? 'line-success' : '' }}"></div>
                            @endif
                        </div>
                        <div class="revision-content">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-semibold {{ $isCurrent ? 'text-primary' : 'text-dark' }}" style="font-size: 13px;">
                                    {{ $rev->versi === 1 ? 'Perjanjian Awal' : 'Perpanjangan #' . ($rev->versi - 1) }}
                                </span>
                                <span class="text-muted" style="font-size: 12px;">{{ $rev->nomor }}</span>
                                @if($isCurrent)
                                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 10px;">Sedang Dilihat</span>
                                @elseif($isLatest)
                                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 10px;">Aktif</span>
                                @elseif($rev->status === 'dicabut')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 10px;">Dicabut</span>
                                @endif
                            </div>
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ $rev->pihak }}</p>
                            <div class="d-flex align-items-center gap-3" style="font-size: 11.5px;">
                                <span class="text-muted"><i class="ti ti-calendar" style="font-size: 12px;"></i> {{ $rev->mulai_perjanjian->format('d/m/Y') }} s/d {{ $rev->akhir_perjanjian->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @if($totalRevisions > $visibleCount)
                <button type="button" id="btn-toggle-revision" class="btn btn-outline-secondary btn-sm w-100 mt-2" onclick="toggleRevisionHistory()">
                    <i class="ti ti-chevron-down me-1"></i> Lihat Semua Riwayat ({{ count($revisionHistory) - 1 }} perpanjangan)
                </button>
                @endif
            </div>
            @endif

            <hr class="my-4">

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Pihak</label>
                    <span class="fw-medium" style="word-break: break-word;">{{ $mou->pihak }}</span>
                </div>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Judul</label>
                    <span class="fw-medium" style="word-break: break-word;">{{ $mou->judul }}</span>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Bidang</label>
                        <span class="fw-medium">{{ $mou->bidang ? $mou->bidang->nama : '-' }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Kategori</label>
                        <span class="fw-medium">{{ $mou->kategori ? $mou->kategori->nama : '-' }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Nomor</label>
                    <span class="fw-medium">{{ $mou->nomor }}</span>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Mulai Perjanjian</label>
                        <span class="fw-medium">{{ $mou->mulai_perjanjian ? $mou->mulai_perjanjian->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Akhir Perjanjian</label>
                        <span class="fw-medium">{{ $mou->akhir_perjanjian ? $mou->akhir_perjanjian->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Masa Berlaku</label>
                        <span class="fw-medium">{{ $mou->masa_berlaku_formatted }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Keterangan</label>
                        <span class="fw-medium">{{ $mou->keterangan }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Status</label>
                    @php
                        $labels = ['aktif' => 'Aktif', 'kadaluarsa' => 'Kadaluarsa', 'dicabut' => 'Dicabut'];
                        $colors = ['aktif' => 'success', 'kadaluarsa' => 'danger', 'dicabut' => 'secondary'];
                    @endphp
                    <span class="badge badge-{{ $mou->status }}" style="font-size: 13px; padding: 6px 12px;">{{ $labels[$mou->status] ?? $mou->status }}</span>
                    @if($mou->versi > 1)
                        <span class="badge bg-info" style="font-size: 13px; padding: 6px 12px;">Perpanjangan #{{ $mou->versi - 1 }}</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Diupload oleh</label>
                    <span class="fw-medium">{{ $mou->uploader->name ?? '-' }}</span>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('mou.download', $mou->id) }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-download me-1"></i> Download
                    </a>
                    @can('upload dokumen')
                    <a href="{{ route('mou.renew', $mou->id) }}" class="btn btn-outline-warning btn-sm">
                        <i class="ti ti-refresh me-1"></i> Perpanjang
                    </a>
                    @endcan
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
    function toggleRevisionHistory() {
        var btn = document.getElementById('btn-toggle-revision');
        var extras = document.querySelectorAll('[id^="revision-extra-"]');
        var isExpanded = extras.length > 0 && extras[0].style.display !== 'none';

        extras.forEach(function(el) {
            el.style.display = isExpanded ? 'none' : '';
        });

        if (isExpanded) {
            btn.innerHTML = '<i class="ti ti-chevron-down me-1"></i> Lihat Semua Riwayat ({{ count($revisionHistory) - 1 }} perpanjangan)';
        } else {
            btn.innerHTML = '<i class="ti ti-chevron-up me-1"></i> Tutup Riwayat';
        }
    }

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
                var renderContext = { canvasContext: ctx, viewport: viewport };
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
