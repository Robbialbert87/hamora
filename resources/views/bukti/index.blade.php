@extends('layouts.app')

@section('title', 'Pengumpulan Data - HAMORA')

@section('content')
<style>
    @media (max-width: 767.98px) {
        #bukti-table { min-width: 720px; }
        #bukti-table td, #bukti-table th { white-space: nowrap; font-size: 12px; padding: 6px 8px; }
        .btn-upload-text { display: none; }
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item active">Pengumpulan Data</li>
                </ol>
            </div>
            <h4 class="page-title">Pengumpulan Data</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">Formulir</h5>
                        <p class="text-muted mb-0" style="font-size: 12.5px;">Buat formulir, dapatkan link publik, lalu kumpulkan data dari pihak luar tanpa login</p>
                    </div>
                    <a href="{{ route('bukti.create') }}" class="btn btn-primary btn-sm" style="flex: 0 0 auto;">
                        <i class="ti ti-plus"></i><span class="btn-upload-text"> Buat Formulir</span>
                    </a>
                </div>

                <div class="table-responsive" style="margin: 0 -0.75rem; padding: 0 0.75rem;">
                    <table class="table table-sm table-hover w-100" id="bukti-table" style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Nama Formulir</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Deskripsi</th>
                                <th class="text-center d-none d-md-table-cell" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Kolom</th>
                                <th class="text-center d-none d-md-table-cell" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Entri</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Link Publik</th>
                                <th class="text-center" style="width: 80px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Status</th>
                                <th class="text-center d-none d-md-table-cell" style="width: 120px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Dibuat</th>
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
        var table = $('#bukti-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("bukti.data") }}',
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            responsive: false,
            pageLength: 10,
            columnDefs: [
                { targets: [3, 4, 7], className: 'd-none d-md-table-cell' }
            ],
            dom: '<"row px-2 mt-2"<"col-12"t>><"row align-items-center mt-2 px-2"<"col"l><"col-auto"i><"col"p>>',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'nama', name: 'nama' },
                { data: 'deskripsi_short', name: 'deskripsi', orderable: false },
                { data: 'fields_count', name: 'fields_count', className: 'text-center' },
                { data: 'records_count', name: 'records_count', className: 'text-center' },
                { data: 'link_publik', name: 'token', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status', className: 'text-center' },
                { data: 'created_formatted', name: 'created_at', className: 'text-center' },
                { data: 'action', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                url: '/assets/lang/Indonesian.json',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '',
                lengthMenu: '_MENU_',
                search: 'Cari: '
            },
            order: [[7, 'desc']]
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Formulir?',
                html: 'Yakin ingin menghapus <strong>"' + name + '"</strong>? Semua entri dan file yang terkirim juga akan terhapus.',
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
                            Swal.fire('Berhasil!', 'Formulir berhasil dihapus.', 'success');
                            table.draw();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus formulir.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
