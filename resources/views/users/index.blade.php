@extends('layouts.app')

@section('title', 'Users - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
            <h4 class="page-title">Users</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Daftar Users</h4>
                        <p class="text-muted mb-0">Kelola pengguna sistem</p>
                    </div>
                    <div>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Tambah User
                        </a>
                    </div>
                </div>

                <table class="table table-sm table-hover w-100" id="users-table" style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="5" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                            <th data-priority="1" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Nama</th>
                            <th data-priority="4" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">NIP</th>
                            <th data-priority="3" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Bidang</th>
                            <th data-priority="2" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Role</th>
                            <th class="text-center" data-priority="6" style="width: 90px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Status</th>
                            <th class="text-center" data-priority="0" style="width: 100px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users ?? [] as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-soft-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; min-width: 36px;">
                                        <span class="fw-bold text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="fw-medium">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->nip ?? '-' }}</td>
                            <td>{{ $user->bidang->nama ?? '-' }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                <span class="badge bg-soft-primary text-primary">
                                    {{ ucfirst($role->name) }}
                                </span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                @if($user->is_active)
                                <span class="badge bg-soft-success text-success">Aktif</span>
                                @else
                                <span class="badge bg-soft-warning text-warning">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <button class="btn btn-outline-danger btn-sm btn-delete-user"
                                            data-url="{{ route('users.destroy', $user->id) }}"
                                            data-name="{{ $user->name }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada user</td>
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
        $('#users-table').DataTable({
            responsive: true,
            language: {
                url: '/assets/lang/Indonesian.json'
            }
        });

        $(document).on('click', '.btn-delete-user', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus User?',
                text: 'Yakin ingin menghapus user "' + name + '"?',
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
                            Swal.fire('Terhapus!', 'User berhasil dihapus.', 'success');
                            location.reload();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus user.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
