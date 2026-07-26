@extends('layouts.app')

@section('title', isset($defaultStatus) && $defaultStatus === 'aktif' ? 'Dokumen Aktif - HAMORA' : (isset($defaultStatus) && $defaultStatus === 'kadaluarsa' ? 'Dokumen Kadaluarsa - HAMORA' : (($defaultStatus ?? false) ? ucfirst($defaultStatus) . ' - HAMORA' : 'Dokumen - HAMORA')))

@section('content')
@if(isset($defaultStatus) && in_array($defaultStatus, ['aktif', 'kadaluarsa']))
<style>
    #filter-group-kategori, #filter-group-status { display: none !important; }
</style>
@endif

@php
    $pageTitle = isset($defaultStatus) && $defaultStatus === 'aktif' ? 'Dokumen Aktif' : (isset($defaultStatus) && $defaultStatus === 'kadaluarsa' ? 'Dokumen Kadaluarsa' : (($defaultStatus ?? false) ? ucfirst($defaultStatus) : 'Dokumen'));
    $pageDesc = isset($defaultStatus) && $defaultStatus === 'aktif' ? 'Dokumen yang sedang berlaku' : (isset($defaultStatus) && $defaultStatus === 'kadaluarsa' ? 'Dokumen yang sudah melewati masa berlaku' : 'Kelola seluruh dokumen');
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $pageTitle }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">{{ $pageTitle }}</h5>
                        <p class="text-muted mb-0" style="font-size: 12.5px;">{{ $pageDesc }}</p>
                    </div>
                    @if(!isset($defaultStatus) || !in_array($defaultStatus, ['aktif', 'kadaluarsa']))
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-plus"></i> Upload
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('documents.create.baru') }}"><i class="ti ti-file-text me-2"></i>Dokumen Baru</a></li>
                                <li><a class="dropdown-item" href="{{ route('mou.create') }}"><i class="ti ti-note me-2"></i>MOU / Kerja Sama</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('documents.create.update') }}"><i class="ti ti-refresh me-2"></i>Update / Revisi</a></li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="row g-2 align-items-end mb-3">
                    <div class="col-md-3 col-lg-2">
                        <label class="form-label">Cari</label>
                        <input type="text" id="filter-nama" class="form-control form-control-sm" placeholder="Nomor atau nama...">
                    </div>
                    <div class="col-md-2 col-lg-1">
                        <label class="form-label">Tahun</label>
                        <select id="filter-tahun" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @foreach(range(date('Y') + 1, date('Y') - 10) as $thn)
                            <option value="{{ $thn }}">{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-2">
                        <label class="form-label">Bidang</label>
                        <select id="filter-bidang" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @foreach($bidang ?? [] as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-3" id="filter-group-kategori">
                        <label class="form-label">Kategori</label>
                        <select id="filter-kategori" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @foreach($kategori ?? [] as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-3" id="filter-group-status">
                        <label class="form-label">Status</label>
                        <select id="filter-status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="aktif">Aktif</option>
                            <option value="draft">Draft</option>
                            <option value="direvisi">Direvisi</option>
                            <option value="diubah">Diubah</option>
                            <option value="kadaluarsa">Kadaluarsa</option>
                            <option value="dicabut">Dicabut</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-auto ms-auto">
                        <label class="form-label d-none d-lg-block">&nbsp;</label>
                        <div class="d-flex gap-1 align-items-center">
                            <button class="btn btn-primary btn-sm" id="btn-cari"><i class="ti ti-search"></i></button>
                            <button class="btn btn-outline-secondary btn-sm" id="btn-reset"><i class="ti ti-refresh"></i></button>
                        </div>
                    </div>
                </div>

                <table class="table table-sm table-hover w-100" id="documents-table" style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                            <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Nomor Dokumen</th>
                            <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Nama Dokumen</th>
                            <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Bidang</th>
                            <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Kategori</th>
                            <th class="text-center" style="width: 105px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Terbit</th>
                            <th class="text-center" style="width: 90px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Status</th>
                            <th class="text-center" style="width: 100px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
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
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            responsive: false,
            pageLength: 10,
            dom: '<"row px-2 mt-2"<"col-12"t>><"row align-items-center mt-2 px-2"<"col"l><"col-auto"i><"col"p>>',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'nomor_dokumen', name: 'nomor_dokumen' },
                { data: 'nama_dokumen', name: 'nama_dokumen', render: function(data, type, row) {
                    if (type === 'display') {
                        return '<span class="d-inline-block text-truncate" style="max-width: 220px;" title="' + $('<span>').text(data).html() + '">' + $('<span>').text(data).html() + '</span>';
                    }
                    return data;
                } },
                { data: 'bidang', name: 'bidang.nama' },
                { data: 'kategori', name: 'kategori.nama' },
                { data: 'tanggal_terbit_formatted', name: 'tanggal_terbit', className: 'text-center' },
                { data: 'status_badge', name: 'status', className: 'text-center' },
                { data: 'action', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                url: '/assets/lang/Indonesian.json',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '',
                lengthMenu: '_MENU_'
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
                html: 'Yakin ingin menghapus <strong>"' + name + '"</strong>?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
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
                            Swal.fire('Berhasil!', 'Dokumen berhasil dihapus.', 'success');
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