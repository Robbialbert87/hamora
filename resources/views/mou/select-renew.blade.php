@extends('layouts.app')

@section('title', 'Pilih MOU untuk Diperpanjang - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mou.index') }}">MOU</a></li>
                    <li class="breadcrumb-item active">Perpanjang</li>
                </ol>
            </div>
            <h4 class="page-title">Perpanjang MOU</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <h4 class="card-title mb-0">Pilih MOU untuk Diperpanjang</h4>
                    <p class="text-muted mb-0">Cari dan pilih MOU yang ingin diperpanjang masa berlakunya</p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cari MOU <span class="text-danger">*</span></label>
                    <div class="row g-2 align-items-end mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Cari</label>
                            <input type="text" class="form-control form-control-sm" id="filterPencarian"
                                placeholder="Cari Pihak, Judul, atau Nomor..." autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select form-select-sm" id="filterStatus">
                                <option value="">Semua</option>
                                <option value="aktif">Aktif</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Bidang</label>
                            <select class="form-select form-select-sm" id="filterBidang">
                                <option value="">Semua</option>
                                @foreach($bidang as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kategori</label>
                            <select class="form-select form-select-sm" id="filterKategori">
                                <option value="">Semua</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label d-none d-lg-block">&nbsp;</label>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-primary btn-sm" onclick="filterMou()"><i class="ti ti-search"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilter()"><i class="ti ti-refresh"></i></button>
                            </div>
                        </div>
                    </div>

                    <div id="mouList"
                        style="max-height: 320px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px; display: none;">
                        <div id="filterEmpty" class="px-3 py-3 text-muted text-center"
                            style="font-size: 14px; display: none;">Tidak ada MOU yang cocok dengan pencarian.</div>
                        @forelse($mouList as $m)
                            <a href="{{ route('mou.renew', $m->id) }}"
                                class="mou-item d-block px-3 py-2 text-decoration-none"
                                data-id="{{ $m->id }}"
                                data-pihak="{{ strtolower(e($m->pihak)) }}"
                                data-judul="{{ strtolower(e($m->judul)) }}"
                                data-nomor="{{ strtolower(e($m->nomor)) }}"
                                data-bidang="{{ $m->bidang_id }}"
                                data-kategori="{{ $m->kategori_id }}"
                                data-status="{{ $m->status }}"
                                style="border-bottom: 1px solid #dee2e6; transition: background 0.15s;"
                                onmouseover="this.style.background='#f0f3ff'"
                                onmouseout="this.style.background=''">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-medium" style="font-size: 14px; color: #343a40;">
                                            {{ $m->judul }}
                                        </div>
                                        <div class="text-muted" style="font-size: 12.5px;">
                                            {{ $m->nomor }} &middot; {{ $m->pihak }}
                                        </div>
                                        <div class="mt-1" style="font-size: 12px; color: #6c757d;">
                                            {{ $m->bidang?->nama ?? '-' }} &middot;
                                            {{ $m->kategori?->nama ?? '-' }}
                                        </div>
                                        <div class="mt-1" style="font-size: 12.5px; color: #495057;">
                                            <i class="ti ti-calendar" style="font-size: 13px;"></i>
                                            Periode: {{ $m->mulai_perjanjian ? $m->mulai_perjanjian->format('d/m/Y') : '-' }} s/d {{ $m->akhir_perjanjian ? $m->akhir_perjanjian->format('d/m/Y') : '-' }}
                                        </div>
                                    </div>
                                    <div class="text-end flex-shrink-0 ms-3">
                                        <div class="d-flex gap-1 justify-content-end flex-wrap">
                                            <span class="badge bg-secondary" style="font-size: 11px; padding: 4px 8px;">
                                                {{ $m->versi === 1 ? 'Perjanjian Awal' : 'Perpanjangan #' . ($m->versi - 1) }}
                                            </span>
                                            @php
                                                $labels = ['aktif' => 'Aktif', 'kadaluarsa' => 'Kadaluarsa'];
                                                $colors = ['aktif' => 'success', 'kadaluarsa' => 'danger'];
                                            @endphp
                                            <span class="badge bg-{{ $colors[$m->status] ?? 'secondary' }}" style="font-size: 11px; padding: 4px 8px;">{{ $labels[$m->status] ?? $m->status }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-3 py-3 text-muted text-center" style="font-size: 14px;">Tidak ada MOU yang bisa diperpanjang.</div>
                        @endforelse
                    </div>

                    <div id="selectedMou" class="mt-2" style="display: none;">
                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-light">
                            <i class="ti ti-circle-check text-success"></i>
                            <span id="selectedMouText" class="text-success fw-medium" style="font-size: 14px;"></span>
                            <button type="button" class="btn btn-sm ms-auto"
                                onclick="clearMou()"
                                style="background: none; border: none; color: #6c757d; padding: 4px 8px;">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info d-flex align-items-center gap-2 mb-0" role="alert">
                    <i class="ti ti-info-circle text-primary"></i>
                    <span>Klik salah satu MOU di atas untuk langsung ke form perpanjangan.</span>
                </div>

                <div class="mt-3 text-end">
                    <a href="{{ route('mou.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function filterMou() {
        var pencarian = document.getElementById('filterPencarian').value.toLowerCase().trim();
        var status = document.getElementById('filterStatus').value;
        var bidang = document.getElementById('filterBidang').value;
        var kategori = document.getElementById('filterKategori').value;

        document.getElementById('mouList').style.display = '';

        var items = document.querySelectorAll('.mou-item');
        var hasVisible = false;
        items.forEach(function(it) {
            var match = true;
            if (pencarian) {
                var pihak = it.getAttribute('data-pihak') || '';
                var judul = it.getAttribute('data-judul') || '';
                var nomor = it.getAttribute('data-nomor') || '';
                if (!pihak.includes(pencarian) && !judul.includes(pencarian) && !nomor.includes(pencarian)) {
                    match = false;
                }
            }
            if (status && it.getAttribute('data-status') !== status) match = false;
            if (bidang && it.getAttribute('data-bidang') !== bidang) match = false;
            if (kategori && it.getAttribute('data-kategori') !== kategori) match = false;
            if (match) {
                it.style.removeProperty('display');
            } else {
                it.style.setProperty('display', 'none', 'important');
            }
            if (match) hasVisible = true;
        });

        var empty = document.getElementById('filterEmpty');
        if (empty) empty.style.display = hasVisible ? 'none' : '';
    }

    function resetFilter() {
        document.getElementById('filterPencarian').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterBidang').value = '';
        document.getElementById('filterKategori').value = '';
        document.querySelectorAll('.mou-item').forEach(function(it) {
            it.style.removeProperty('display');
        });
        document.getElementById('mouList').style.display = 'none';
        document.getElementById('selectedMou').style.display = 'none';
        var empty = document.getElementById('filterEmpty');
        if (empty) empty.style.display = 'none';
    }

    function clearMou() {
        document.getElementById('selectedMou').style.display = 'none';
        document.querySelectorAll('.mou-item').forEach(function(it) {
            it.style.removeProperty('display');
        });
        document.getElementById('mouList').style.display = 'none';
    }

    function attachFilters() {
        var ids = ['filterPencarian', 'filterStatus', 'filterBidang', 'filterKategori'];
        ids.forEach(function(id) {
            var el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', filterMou);
                if (el.tagName === 'SELECT') el.addEventListener('change', filterMou);
            }
        });
    }

    attachFilters();
</script>
@endsection
