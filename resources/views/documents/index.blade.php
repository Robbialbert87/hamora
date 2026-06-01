@extends('layouts.app')

@section('title', 'Dokumen - HAMORA')
@section('page-title', 'Dokumen')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card table-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Daftar Dokumen</h2>
                <p class="card-subtitle">Kelola seluruh dokumen</p>
            </div>
            <div class="card-header-actions">
                <a href="{{ route('documents.create') }}" class="btn-emerald btn-sm">
                    <i class="fas fa-plus"></i> Upload Dokumen
                </a>
            </div>
        </div>

        {{-- Filter Row --}}
        <div class="filter-row">
            <div class="form-group">
                <label class="form-label">Nomor/Nama Dokumen</label>
                <input type="text" id="filter-nama" class="form-control" placeholder="Cari dokumen...">
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
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select id="filter-kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach($kategori ?? [] as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
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
        var table = $('#documents-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("documents.data") }}',
                data: function(d) {
                    d.nama_dokumen = $('#filter-nama').val();
                    d.tahun = $('#filter-tahun').val();
                    d.bidang_id = $('#filter-bidang').val();
                    d.kategori_id = $('#filter-kategori').val();
                    d.status = $('#filter-status').val();
                    d.global_search = $('#global-search').val();
                }
            },
            responsive: false,
            scrollX: true,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nomor_dokumen', name: 'nomor_dokumen' },
                { data: 'nama_dokumen', name: 'nama_dokumen' },
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

        $('#global-search').on('input', function() {
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

        $('#filter-nama').on('keyup', function(e) {
            if (e.key === 'Enter') {
                table.draw();
            }
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
