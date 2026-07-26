@extends('layouts.app')

@section('title', 'Trashed MOU - HAMORA')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mou.index') }}">MOU</a></li>
                    <li class="breadcrumb-item active">Trashed</li>
                </ol>
            </div>
            <h4 class="page-title">MOU Terhapus</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">MOU Terhapus</h5>
                        <p class="text-muted mb-0" style="font-size: 12.5px;">Daftar MOU yang telah dihapus</p>
                    </div>
                    <a href="{{ route('mou.index') }}" class="btn btn-outline-secondary btn-sm btn-icon" title="Kembali">
                        <i class="ti ti-arrow-left"></i>
                    </a>
                </div>

                <table class="table table-sm table-hover w-100" style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                            <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Pihak</th>
                            <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Judul</th>
                            <th class="text-center" style="width: 120px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Tanggal Hapus</th>
                            <th class="text-center" style="width: 130px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mou ?? [] as $m)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $m->pihak }}</td>
                            <td>{{ $m->judul }}</td>
                            <td class="text-center">{{ $m->deleted_at ? $m->deleted_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <form action="{{ route('mou.restore', $m->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-light-success btn-icon" title="Restore">
                                            <i class="ti ti-restore"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-light-danger btn-icon btn-force-delete-mou"
                                            data-url="{{ route('mou.force-delete', $m->id) }}"
                                            data-name="{{ $m->judul }}" title="Hapus Permanen">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 24px;">Tidak ada MOU terhapus</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-force-delete-mou', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Permanen?',
                html: 'Yakin ingin menghapus permanen <strong>"' + name + '"</strong>?<br>Tindakan ini tidak bisa dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus permanen!',
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
                            Swal.fire('Berhasil!', 'MOU berhasil dihapus permanen.', 'success');
                            location.reload();
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
