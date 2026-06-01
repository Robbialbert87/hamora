@extends('layouts.app')

@section('title', 'Dashboard - HAMORA')
@section('page-title', 'Dashboard')

@section('content')
<section class="stats-grid">
    <div class="glass-card glass-card-3d stat-card">
        <div class="stat-card-inner">
            <div class="stat-info">
                <h3>Total Dokumen</h3>
                <div class="stat-value">{{ $totalDokumen ?? 0 }}</div>
            </div>
            <div class="stat-icon cyan">
                <i class="fas fa-file-alt fa-2x" style="color: var(--emerald-light);"></i>
            </div>
        </div>
    </div>

    <div class="glass-card glass-card-3d stat-card">
        <div class="stat-card-inner">
            <div class="stat-info">
                <h3>Dokumen Aktif</h3>
                <div class="stat-value">{{ $dokumenAktif ?? 0 }}</div>
            </div>
            <div class="stat-icon success">
                <i class="fas fa-check-circle fa-2x" style="color: var(--success);"></i>
            </div>
        </div>
    </div>

    <div class="glass-card glass-card-3d stat-card">
        <div class="stat-card-inner">
            <div class="stat-info">
                <h3>Dokumen Direvisi</h3>
                <div class="stat-value">{{ $dokumenDirevisi ?? 0 }}</div>
            </div>
            <div class="stat-icon magenta">
                <i class="fas fa-sync-alt fa-2x" style="color: var(--gold);"></i>
            </div>
        </div>
    </div>

    <div class="glass-card glass-card-3d stat-card">
        <div class="stat-card-inner">
            <div class="stat-info">
                <h3>Dokumen Kadaluarsa</h3>
                <div class="stat-value">{{ $dokumenKadaluarsa ?? 0 }}</div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-clock fa-2x" style="color: var(--coral);"></i>
            </div>
        </div>
    </div>
</section>

<section class="content-grid">
    <div class="glass-card chart-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Dokumen per Tahun</h2>
                <p class="card-subtitle">Jumlah dokumen berdasarkan tahun</p>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="chartTahun" height="200"></canvas>
        </div>
    </div>

    <div class="glass-card activity-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Dokumen per Bidang</h2>
                <p class="card-subtitle">Distribusi dokumen per bidang</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center" style="flex:1; min-height: 200px;">
            <canvas id="chartBidang" style="max-height: 220px; max-width: 220px;"></canvas>
        </div>
        <div class="mt-3" id="chartBidangLegend"></div>
    </div>
</section>

<section class="content-grid">
    <div class="glass-card activity-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Dokumen per Kategori</h2>
                <p class="card-subtitle">Distribusi dokumen per kategori</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center" style="flex:1; min-height: 200px;">
            <canvas id="chartKategori" style="max-height: 220px; max-width: 220px;"></canvas>
        </div>
        <div class="mt-3" id="chartKategoriLegend"></div>
    </div>

    <div class="glass-card table-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Dokumen Terbaru</h2>
                <p class="card-subtitle">10 dokumen terakhir yang diupload</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Dokumen</th>
                        <th>Nama Dokumen</th>
                        <th>Bidang</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal Upload</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumenTerbaru ?? [] as $doc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $doc->nomor_dokumen }}</td>
                        <td>{{ $doc->nama_dokumen }}</td>
                        <td>{{ $doc->bidang->nama ?? '-' }}</td>
                        <td>{{ $doc->kategori->nama ?? '-' }}</td>
                        <td><span class="badge badge-{{ $doc->status }}">{{ ucfirst($doc->status) }}</span></td>
                        <td>{{ $doc->created_at ? $doc->created_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada dokumen</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        {{-- Chart Tahun --}}
        var ctxTahun = document.getElementById('chartTahun');
        if (ctxTahun) {
            new Chart(ctxTahun.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($chartTahunLabels ?? []),
                    datasets: [{
                        label: 'Jumlah Dokumen',
                        data: @json($chartTahunData ?? []),
                        backgroundColor: 'rgba(52, 211, 153, 0.6)',
                        borderColor: 'rgba(52, 211, 153, 1)',
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
                            ticks: { color: 'var(--text-muted)' },
                            grid: { color: 'var(--glass-border)' }
                        },
                        x: {
                            ticks: { color: 'var(--text-muted)' },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        {{-- Chart Bidang --}}
        var ctxBidang = document.getElementById('chartBidang');
        if (ctxBidang) {
            new Chart(ctxBidang.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartBidangLabels ?? []),
                    datasets: [{
                        data: @json($chartBidangData ?? []),
                        backgroundColor: ['rgba(52, 211, 153, 0.7)', 'rgba(212, 165, 116, 0.7)', 'rgba(224, 122, 95, 0.7)', 'rgba(34, 197, 94, 0.7)', 'rgba(14, 165, 233, 0.7)'],
                        borderColor: ['rgba(52, 211, 153, 1)', 'rgba(212, 165, 116, 1)', 'rgba(224, 122, 95, 1)', 'rgba(34, 197, 94, 1)', 'rgba(14, 165, 233, 1)'],
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        {{-- Chart Kategori --}}
        var ctxKategori = document.getElementById('chartKategori');
        if (ctxKategori) {
            new Chart(ctxKategori.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($chartKategoriLabels ?? []),
                    datasets: [{
                        data: @json($chartKategoriData ?? []),
                        backgroundColor: ['rgba(212, 165, 116, 0.7)', 'rgba(52, 211, 153, 0.7)', 'rgba(224, 122, 95, 0.7)', 'rgba(14, 165, 233, 0.7)', 'rgba(34, 197, 94, 0.7)'],
                        borderColor: ['rgba(212, 165, 116, 1)', 'rgba(52, 211, 153, 1)', 'rgba(224, 122, 95, 1)', 'rgba(14, 165, 233, 1)', 'rgba(34, 197, 94, 1)'],
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
@endsection
