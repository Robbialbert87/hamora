@extends('layouts.app')

@section('title', isset($defaultStatus) && $defaultStatus === 'aktif' ? 'Dokumen Aktif - HAMORA' : (isset($defaultStatus) && $defaultStatus === 'kadaluarsa' ? 'Dokumen Kadaluarsa - HAMORA' : (($defaultStatus ?? false) ? ucfirst($defaultStatus) . ' - HAMORA' : 'Dokumen - HAMORA')))
@section('page-title', isset($defaultStatus) && $defaultStatus === 'aktif' ? 'Dokumen Aktif' : (isset($defaultStatus) && $defaultStatus === 'kadaluarsa' ? 'Dokumen Kadaluarsa' : (($defaultStatus ?? false) ? ucfirst($defaultStatus) : 'Dokumen')))

@section('content')
@if(isset($defaultStatus) && in_array($defaultStatus, ['aktif', 'kadaluarsa']))
<style>
    #filter-group-kategori, #filter-group-status { display: none !important; }
</style>
@endif
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card table-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Daftar Dokumen</h2>
                <p class="card-subtitle">{{ isset($defaultStatus) && $defaultStatus === 'aktif' ? 'Dokumen yang sedang berlaku' : (isset($defaultStatus) && $defaultStatus === 'kadaluarsa' ? 'Dokumen yang sudah melewati masa berlaku' : 'Kelola seluruh dokumen') }}</p>
            </div>
            <div class="card-header-actions">
                @if(!isset($defaultStatus) || !in_array($defaultStatus, ['aktif', 'kadaluarsa']))
                <a href="{{ route('documents.create') }}" class="btn-emerald btn-sm">
                    <i class="fas fa-plus"></i> Upload Dokumen
                </a>
                @endif
                @can('kelola bidang')
                @if(isset($defaultStatus) && $defaultStatus === 'kadaluarsa')
                <form action="{{ route('dashboard.notifikasi-kadaluarsa') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-outline-glass btn-sm" onclick="return confirm('Kirim notifikasi dokumen kadaluarsa ke email?')">
                        <i class="fas fa-bell"></i> Kirim Notifikasi
                    </button>
                </form>
                @endif
                @endcan
            </div>
        </div>

        {{-- Filter Row --}}
        <div class="filter-row">
            <div class="form-group">
                <label class="form-label">Cari Dokumen</label>
                <input type="text" id="filter-nama" class="form-control" placeholder="Cari nomor atau nama dokumen...">
            </div>
            <div class="form-group">
                <label class="form-label">Tahun</label>
                <select id="filter-tahun" class="form-select">
                    <option value="">Semua</option>
                    @foreach(range(date('Y') + 1, date('Y') - 10) as $thn)
                    <option value="{{ $thn }}">{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Bidang</label>
                <select id="filter-bidang" class="form-select">
                    <option value="">Semua</option>
                    @foreach($bidang ?? [] as $b)
                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" id="filter-group-kategori">
                <label class="form-label">Kategori</label>
                <select id="filter-kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach($kategori ?? [] as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" id="filter-group-status">
                <label class="form-label">Status</label>
                <select id="filter-status" class="form-select">
                    <option value="">Semua</option>
                    <option value="aktif">Aktif</option>
                    <option value="draft">Draft</option>
                    <option value="direvisi">Direvisi</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                </select>
            </div>
            <div class="filter-actions">
                <div class="filter-btn-row">
                    <button class="btn-emerald btn-sm" id="btn-cari"><i class="fas fa-search"></i> Cari</button>
                    <button class="btn-outline-glass btn-sm" id="btn-reset"><i class="fas fa-undo"></i> Reset</button>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="documents-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Dokumen</th>
                        <th>Nama Dokumen</th>
                        <th>Bidang</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        @if(isset($defaultStatus))
        $('#filter-status').val('{{ $defaultStatus }}');
        @endif

        var table = $('#documents-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("documents.data") }}',
                data: function(d) {
                    d.global_search = $('#filter-nama').val();
                    d.tahun = $('#filter-tahun').val();
                    d.bidang_id = $('#filter-bidang').val();
                    d.kategori_id = $('#filter-kategori').val();
                    d.status = $('#filter-status').val();
                }
            },
            searching: false,
            responsive: false,
            scrollX: true,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nomor_dokumen', name: 'nomor_dokumen' },
                { data: 'nama_dokumen', name: 'nama_dokumen', render: function(data, type, row) {
                    if (type === 'display') {
                        return '<span style="max-width: 250px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="' + $('<span>').text(data).html() + '">' + $('<span>').text(data).html() + '</span>';
                    }
                    return data;
                } },
                { data: 'bidang', name: 'bidang.nama' },
                { data: 'kategori', name: 'kategori.nama' },
                { data: 'tahun', name: 'tahun' },
                { data: 'status_badge', name: 'status' },
                { data: 'action', name: 'aksi', orderable: false, searchable: false }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.11/i18n/id.json'
            },
            order: [[5, 'desc']]
        });

        var searchTimeout;

        $('#filter-nama').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                table.draw();
            }, 300);
        });

        $('#btn-cari').on('click', function() {
            table.draw();
        });

        $('#btn-reset').on('click', function() {
            $('#filter-nama').val('');
            $('#filter-tahun').val('');
            $('#filter-bidang').val('');
            $('#filter-kategori').val('');
            $('#filter-status').val('');
            table.draw();
        });

        $('#filter-tahun, #filter-bidang, #filter-kategori, #filter-status').on('change', function() {
            table.draw();
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Dokumen?',
                text: 'Yakin ingin menghapus "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire('Terhapus!', 'Dokumen berhasil dihapus.', 'success');
                            table.draw();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus dokumen.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
