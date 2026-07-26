@extends('layouts.app')

@section('title', 'Dashboard - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row">
    <div class="col-lg-9">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold">Total Dokumen</p>
                                <h3 class="my-1 font-20 fw-bold">{{ $totalDokumen ?? 0 }}</h3>
                                <p class="mb-0 text-truncate text-muted">Semua dokumen terdaftar</p>
                            </div>
                            <div class="col-3 align-self-center">
                                <div class="d-flex justify-content-center align-items-center thumb-md bg-light-alt rounded-circle mx-auto">
                                    <i class="ti ti-file-text font-24 align-self-center text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold">Dokumen Aktif</p>
                                <h3 class="my-1 font-20 fw-bold">{{ $dokumenAktif ?? 0 }}</h3>
                                <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i></span> Status aktif</p>
                            </div>
                            <div class="col-3 align-self-center">
                                <div class="d-flex justify-content-center align-items-center thumb-md bg-soft-success rounded-circle mx-auto">
                                    <i class="ti ti-check font-24 align-self-center text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold">Dokumen Direvisi</p>
                                <h3 class="my-1 font-20 fw-bold">{{ $dokumenDirevisi ?? 0 }}</h3>
                                <p class="mb-0 text-truncate text-muted">Menunggu revisi</p>
                            </div>
                            <div class="col-3 align-self-center">
                                <div class="d-flex justify-content-center align-items-center thumb-md bg-soft-warning rounded-circle mx-auto">
                                    <i class="ti ti-refresh font-24 align-self-center text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-9">
                                <p class="text-dark mb-0 fw-semibold">Dokumen Kadaluarsa</p>
                                <h3 class="my-1 font-20 fw-bold">{{ $dokumenKadaluarsa ?? 0 }}</h3>
                                <p class="mb-0 text-truncate text-muted"><span class="text-danger"><i class="mdi mdi-trending-down"></i></span> Perlu diperbarui</p>
                            </div>
                            <div class="col-3 align-self-center">
                                <div class="d-flex justify-content-center align-items-center thumb-md bg-soft-danger rounded-circle mx-auto">
                                    <i class="ti ti-alert-triangle font-24 align-self-center text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Chart Tahun -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Dokumen per Tahun</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="chartTahun"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Chart Bidang -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Dokumen per Bidang</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="d-flex align-items-center justify-content-center" style="min-height: 220px;">
                        <canvas id="chartBidang" style="max-height: 220px; max-width: 220px;"></canvas>
                    </div>
                    <h6 class="bg-light-alt py-3 px-2 mb-0">
                        <i class="ti ti-calendar align-self-center icon-xs me-1"></i>
                        Total: {{ $totalDokumen ?? 0 }} Dokumen
                    </h6>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table border-dashed mb-0">
                        <thead>
                            <tr>
                                <th>Bidang</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chartBidangLabels ?? [] as $i => $bidang)
                            <tr>
                                <td>{{ $bidang }}</td>
                                <td class="text-end">{{ $chartBidangData[$i] ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Middle Row -->
<div class="row">
    <!-- Chart Kategori -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Dokumen per Kategori</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="d-flex align-items-center justify-content-center" style="min-height: 200px;">
                        <canvas id="chartKategori" style="max-height: 200px; max-width: 200px;"></canvas>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-lg">
                            <h4 class="card-title mt-0 mb-2">Distribusi</h4>
                            @forelse($chartKategoriLabels ?? [] as $i => $kategori)
                            <div class="d-flex align-items-center mb-1">
                                <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background: {{ ['#556ee5','#4aa0d5','#22c55e','#eab308','#dc2626'][$i % 5] }};"></span>
                                <span class="text-muted">{{ $kategori }}</span>
                            </div>
                            @empty
                            <p class="text-muted">Belum ada data</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <h2 class="m-0 align-self-center">{{ $totalUser ?? 0 }}</h2>
                    <div class="d-block ms-2 align-self-center">
                        <span class="text-warning">Total Pengguna</span>
                        <h5 class="my-1">Pengguna Terdaftar</h5>
                        <p class="mb-0 text-muted">Seluruh pengguna yang terdaftar di sistem HAMORA.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Status Dokumen</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="progress mb-1">
                    @php
                        $total = max($totalDokumen ?? 1, 1);
                        $pctAktif = round(($dokumenAktif ?? 0) / $total * 100);
                        $pctRevisi = round(($dokumenDirevisi ?? 0) / $total * 100);
                        $pctKadaluarsa = round(($dokumenKadaluarsa ?? 0) / $total * 100);
                        $pctSisa = max(100 - $pctAktif - $pctRevisi - $pctKadaluarsa, 0);
                    @endphp
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: {{ $pctAktif }}%" aria-valuenow="{{ $pctAktif }}" aria-valuemin="0" aria-valuemax="100">{{ $pctAktif }}%</div>
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctRevisi }}%" aria-valuenow="{{ $pctRevisi }}" aria-valuemin="0" aria-valuemax="100">{{ $pctRevisi }}%</div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pctKadaluarsa }}%" aria-valuenow="{{ $pctKadaluarsa }}" aria-valuemin="0" aria-valuemax="100">{{ $pctKadaluarsa }}%</div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <small class="text-muted"><i class="fas fa-caret-right font-16 text-success"></i> Aktif {{ $pctAktif }}%</small>
                    <small class="text-muted"><i class="fas fa-caret-right font-16 text-warning"></i> Direvisi {{ $pctRevisi }}%</small>
                    <small class="text-muted"><i class="fas fa-caret-right font-16 text-danger"></i> Kadaluarsa {{ $pctKadaluarsa }}%</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen Terbaru -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Dokumen Terbaru</h4>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-3" style="max-height: 360px; overflow-y: auto;">
                    @forelse($dokumenTerbaru ?? [] as $doc)
                    <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                        <div class="icon-info-activity">
                            <div class="d-flex justify-content-center align-items-center thumb-sm bg-light-alt rounded-circle">
                                <i class="ti ti-file-text"></i>
                            </div>
                        </div>
                        <div class="activity-info-text ms-3 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-muted mb-0 font-13 w-75">
                                    <span class="fw-semibold text-dark">{{ $doc->nama_dokumen }}</span>
                                </p>
                                <small class="text-muted">{{ $doc->created_at ? $doc->created_at->diffForHumans() : '-' }}</small>
                            </div>
                            <small class="text-muted">{{ $doc->nomor_dokumen }} &middot; {{ $doc->bidang->nama ?? '-' }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">Belum ada dokumen</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Tables -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Dokumen Berdasarkan Bidang</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-top-0">Bidang</th>
                                <th class="border-top-0 text-end">Jumlah</th>
                                <th class="border-top-0 text-end">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chartBidangLabels ?? [] as $i => $bidang)
                            @php
                                $count = $chartBidangData[$i] ?? 0;
                                $pct = $totalDokumen > 0 ? round($count / $totalDokumen * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td>{{ $bidang }}</td>
                                <td class="text-end">{{ $count }}<small class="text-muted"> ({{ $pct }}%)</small></td>
                                <td class="text-end">{{ $pct }}% <i class="fas fa-caret-up text-success font-16"></i></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Dokumen Berdasarkan Kategori</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-top-0">Kategori</th>
                                <th class="border-top-0 text-end">Jumlah</th>
                                <th class="border-top-0 text-end">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chartKategoriLabels ?? [] as $i => $kategori)
                            @php
                                $count = $chartKategoriData[$i] ?? 0;
                                $pct = $totalDokumen > 0 ? round($count / $totalDokumen * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td>{{ $kategori }}</td>
                                <td class="text-end">{{ $count }}<small class="text-muted"> ({{ $pct }}%)</small></td>
                                <td class="text-end">{{ $pct }}% <i class="fas fa-caret-up text-success font-16"></i></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var colors = ['#556ee5','#4aa0d5','#22c55e','#eab308','#dc2626','#8b5cf6','#ec4899','#f97316'];

        // Chart Tahun
        var ctxTahun = document.getElementById('chartTahun');
        if (ctxTahun) {
            new Chart(ctxTahun.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($chartTahunLabels ?? []),
                    datasets: [{
                        label: 'Jumlah Dokumen',
                        data: @json($chartTahunData ?? []),
                        backgroundColor: 'rgba(85, 110, 229, 0.6)',
                        borderColor: 'rgba(85, 110, 229, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#6c757d' },
                            grid: { color: '#dee2e6' }
                        },
                        x: {
                            ticks: { color: '#6c757d' },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // Chart Bidang
        var ctxBidang = document.getElementById('chartBidang');
        if (ctxBidang) {
            new Chart(ctxBidang.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartBidangLabels ?? []),
                    datasets: [{
                        data: @json($chartBidangData ?? []),
                        backgroundColor: colors.slice(0, {{ count($chartBidangLabels ?? []) }}),
                        borderColor: '#fff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Chart Kategori
        var ctxKategori = document.getElementById('chartKategori');
        if (ctxKategori) {
            new Chart(ctxKategori.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartKategoriLabels ?? []),
                    datasets: [{
                        data: @json($chartKategoriData ?? []),
                        backgroundColor: colors.slice(0, {{ count($chartKategoriLabels ?? []) }}),
                        borderColor: '#fff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
@endsection
