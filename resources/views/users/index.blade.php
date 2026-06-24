@extends('layouts.app')

@section('title', 'Users - HAMORA')
@section('page-title', 'Users')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card table-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Daftar Users</h2>
                <p class="card-subtitle">Kelola pengguna sistem</p>
            </div>
            <div class="card-header-actions">
                <a href="{{ route('users.create') }}" class="btn-emerald btn-sm">
                    <i class="fas fa-plus"></i> Tambah User
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="users-table">
                <thead>
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
                            <div class="table-user">
                                <div class="table-avatar" style="background: linear-gradient(135deg, var(--emerald-light), var(--emerald));">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="table-user-info">
                                    <span class="table-user-name">{{ $user->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->nip ?? '-' }}</td>
                        <td>{{ $user->bidang->nama ?? '-' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                            <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: var(--emerald-light); padding: 4px 10px; border-radius: 20px; font-size: 12px;">
                                {{ ucfirst($role->name) }}
                            </span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->is_active)
                            <span class="status-badge completed">Aktif</span>
                            @else
                            <span class="status-badge pending">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn-outline-glass btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($user->id !== auth()->id())
                                <button class="btn-outline-glass btn-sm btn-delete-user"
                                        data-url="{{ route('users.destroy', $user->id) }}"
                                        data-name="{{ $user->name }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada user</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
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
