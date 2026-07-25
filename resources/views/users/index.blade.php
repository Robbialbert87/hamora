@extends('layouts.app')

@section('title', 'Users - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
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

                <div class="table-responsive">
                    <table class="table table-bordered" id="users-table">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Bidang</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
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
                                <td>
                                    @if($user->is_active)
                                    <span class="badge bg-soft-success text-success">Aktif</span>
                                    @else
                                    <span class="badge bg-soft-warning text-warning">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-pencil me-1"></i> Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <button class="btn btn-outline-danger btn-sm btn-delete-user"
                                                data-url="{{ route('users.destroy', $user->id) }}"
                                                data-name="{{ $user->name }}">
                                            <i class="ti ti-trash me-1"></i> Hapus
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
