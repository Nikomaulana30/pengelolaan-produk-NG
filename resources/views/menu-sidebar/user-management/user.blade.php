@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-people"></i> Manajemen User</h3>
                    <p class="text-subtitle text-muted">Kelola semua pengguna sistem</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('user.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Tambah User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Daftar User</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 25%">Nama User</th>
                                    <th style="width: 25%">Email</th>
                                    <th style="width: 15%">Role</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md me-3 bg-light-primary">
                                                    <span class="avatar-initials rounded-full">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->getRoleBadgeColor() }}">
                                                {{ $user->getRoleDisplayName() }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($user->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1" role="group">
                                                <a href="{{ route('user.show', $user) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('user.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if ($user->id !== auth()->id())
                                                    <form action="{{ route('user.destroy', $user) }}" method="POST" style="display:inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn" title="Hapus" data-name="{{ $user->name }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Belum ada user</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- SweetAlert2 Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                e.preventDefault();
                e.stopPropagation();

                const button = e.target.closest('.delete-btn');
                const form = button.closest('.delete-form');
                const name = button.getAttribute('data-name');

                Swal.fire({
                    title: 'Hapus User?',
                    html: `Apakah Anda yakin ingin menghapus user <strong>${name}</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash"></i> Ya, Hapus',
                    cancelButtonText: 'Batal',
                    buttonsStyling: true,
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        setTimeout(() => {
                            form.submit();
                        }, 300);
                    }
                });

                return false;
            }
        });
    });
</script>

@push('styles')
<style>
    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #007bff;
    }

    .avatar-md {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .avatar-initials {
        font-size: 14px;
    }

    .bg-light-primary {
        background-color: rgba(0, 123, 255, 0.1);
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-group .btn {
        border: 1px solid #dee2e6;
    }

    .btn-group .btn:hover {
        background-color: #f0f0f0;
    }
</style>
@endpush
@endsection
