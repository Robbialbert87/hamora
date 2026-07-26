@extends('layouts.app')

@section('title', 'MOU - HAMORA')

@section('content')
<style>
    #filterRow { display: block; }
    @media (max-width: 767.98px) {
        #filterRow { display: none !important; }
        #filterRow.mobile-show { display: block !important; }
        .btn-upload-text { display: none; }
        .card-body { overflow-x: auto; }
        #mou-table { min-width: 580px; }
        #mou-table td, #mou-table th { white-space: nowrap; font-size: 12px; padding: 6px 8px; }
        .dataTables_info { font-size: 11px; }
        .dataTables_length select, .dataTables_length label { font-size: 12px; }
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item active">MOU</li>
                </ol>
            </div>
            <h4 class="page-title">MOU</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">MOU</h5>
                        <p class="text-muted mb-0" style="font-size: 12.5px;">Kelola seluruh MOU dan perjanjian kerja sama</p>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button class="btn btn-outline-secondary btn-sm d-md-none" type="button" onclick="document.getElementById('filterRow').classList.toggle('mobile-show')" title="Filter">
                            <i class="ti ti-filter"></i>
                        </button>
                        @can('upload dokumen')
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-plus"></i><span class="btn-upload-text"> Upload</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('mou.create') }}"><i class="ti ti-note me-2"></i>MOU Baru</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('mou.select-renew') }}"><i class="ti ti-refresh me-2"></i>Perpanjang MOU</a></li>
                            </ul>
                        </div>
                        @endcan
                    </div>
                </div>

                <div id="filterRow">
                    <div class="row g-2 align-items-end mb-3">
                        <div class="col-6 col-md-4 col-lg-3">
                            <label class="form-label">Cari</label>
                            <input type="text" id="filter-cari" class="form-control form-control-sm" placeholder="Pihak, judul, atau nomor...">
                        </div>
                        <div class="col-6 col-md-2 col-lg-3">
                            <label class="form-label">Status</label>
                            <select id="filter-status" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="aktif">Aktif</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                                <option value="dicabut">Dicabut</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 col-lg-auto">
                            <label class="form-label d-none d-lg-block">&nbsp;</label>
                            <div class="d-flex gap-1 align-items-center">
                                <button class="btn btn-primary btn-sm" id="btn-cari"><i class="ti ti-search"></i></button>
                                <button class="btn btn-outline-secondary btn-sm" id="btn-reset"><i class="ti ti-refresh"></i></button>
                                <div class="vr mx-1 d-none d-lg-block"></div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm btn-icon" type="button" data-bs-toggle="dropdown" title="Pilih Kolom"><i class="ti ti-columns"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end" id="colvis-menu" style="min-width: 180px;">
                                        <li><h6 class="dropdown-header" style="font-size: 11px;">Pilih Kolom</h6></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="1" checked> Pihak</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="2" checked> Judul</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="3"> Bidang</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="4"> Kategori</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="5" checked> Nomor</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="6" checked> Mulai</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="7"> Masa Berlaku</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="8" checked> Akhir</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="9" checked> Keterangan</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="10" checked> Status</label></li>
                                        <li><label class="dropdown-item"><input type="checkbox" class="colvis-check me-2" data-col="11" checked> Aksi</label></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button class="dropdown-item text-center" id="colvis-reset" style="font-size: 11px;">Reset semua</button></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-md-none mb-2">
                    <small class="text-muted" style="font-size: 11px;"><i class="ti ti-info-circle me-1"></i>Geser tabel ke kanan untuk melihat kolom lain</small>
                </div>

                <div class="table-responsive" style="margin: 0 -0.75rem; padding: 0 0.75rem;">
                    <table class="table table-sm table-hover w-100" id="mou-table" style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Pihak</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Judul</th>
                                <th class="d-none d-md-table-cell" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Bidang</th>
                                <th class="d-none d-md-table-cell" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Kategori</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Nomor</th>
                                <th class="text-center d-none d-md-table-cell" style="width: 90px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Mulai</th>
                                <th class="text-center d-none d-md-table-cell" style="width: 90px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Masa Berlaku</th>
                                <th class="text-center" style="width: 90px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Akhir</th>
                                <th class="d-none d-md-table-cell" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Keterangan</th>
                                <th class="text-center" style="width: 90px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Status</th>
                                <th class="text-center d-none d-md-table-cell" style="width: 60px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Versi</th>
                                <th class="text-center" style="width: 100px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#mou-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("mou.data") }}',
                data: function(d) {
                    d.global_search = $('#filter-cari').val();
                    d.status = $('#filter-status').val();
                }
            },
            searching: false,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            responsive: false,
            pageLength: 10,
            columnDefs: [
                { targets: [3, 4, 6, 7, 9, 11], className: 'd-none d-md-table-cell' }
            ],
            dom: '<"row px-2 mt-2"<"col-12"t>><"row align-items-center mt-2 px-2"<"col"l><"col-auto"i><"col"p>>',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'pihak', name: 'pihak', render: function(data, type, row) {
                    if (type === 'display') {
                        return '<span class="d-inline-block text-truncate" style="max-width: 180px;" title="' + $('<span>').text(data).html() + '">' + $('<span>').text(data).html() + '</span>';
                    }
                    return data;
                } },
                { data: 'judul', name: 'judul', render: function(data, type, row) {
                    if (type === 'display') {
                        return '<span class="d-inline-block text-truncate" style="max-width: 220px;" title="' + $('<span>').text(data).html() + '">' + $('<span>').text(data).html() + '</span>';
                    }
                    return data;
                } },
                { data: 'bidang_nama', name: 'bidang_nama', visible: false },
                { data: 'kategori_nama', name: 'kategori_nama', visible: false },
                { data: 'nomor', name: 'nomor' },
                { data: 'mulai_formatted', name: 'mulai_perjanjian', className: 'text-center' },
                { data: 'masa_berlaku_formatted', name: 'masa_berlaku', className: 'text-center', visible: false },
                { data: 'akhir_formatted', name: 'akhir_perjanjian', className: 'text-center' },
                { data: 'keterangan', name: 'keterangan', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status', className: 'text-center' },
                { data: 'versi_badge', name: 'versi', className: 'text-center' },
                { data: 'action', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                url: '/assets/lang/Indonesian.json',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '',
                lengthMenu: '_MENU_'
            },
            order: [[10, 'desc']]
        });

        var searchTimeout;

        $('#filter-cari').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                table.draw();
            }, 300);
        });

        $('#btn-cari').on('click', function() {
            table.draw();
        });

        $('#btn-reset').on('click', function() {
            $('#filter-cari').val('');
            $('#filter-status').val('');
            table.draw();
        });

        $('#filter-status').on('change', function() {
            table.draw();
        });

        // Column Visibility
        $(document).on('change', '.colvis-check', function() {
            var colIdx = parseInt($(this).data('col'));
            table.column(colIdx).visible($(this).is(':checked'));
        });

        $('#colvis-reset').on('click', function() {
            var defaultHidden = [3, 4, 7];
            $('.colvis-check').each(function() {
                var colIdx = parseInt($(this).data('col'));
                var show = defaultHidden.indexOf(colIdx) === -1;
                $(this).prop('checked', show);
                table.column(colIdx).visible(show);
            });
        });

        table.on('column-visibility.dt', function(e, settings, column, state) {
            $('.colvis-check[data-col="' + column + '"]').prop('checked', state);
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus MOU?',
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
                            Swal.fire('Berhasil!', 'MOU berhasil dihapus.', 'success');
                            table.draw();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus MOU.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection