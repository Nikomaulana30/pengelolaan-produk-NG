@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-person-circle"></i> Detail User</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap pengguna</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary float-end">
                        <i class="bi bi-arrow-left"></i> Kembali
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

        <section class="section">
            <div class="row">
                <!-- User Detail Card -->
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Informasi User</h5>
                        </div>
                        <div class="card-body">
                            <!-- User Header -->
                            <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                                <div class="avatar avatar-lg bg-light-primary me-3">
                                    <span class="avatar-initials rounded-full">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                </div>
                            </div>

                            <!-- User Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">ID User</label>
                                        <p class="field-value"><strong>{{ $user->id }}</strong></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Email Address</label>
                                        <p class="field-value">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Nama Lengkap</label>
                                        <p class="field-value"><strong>{{ $user->name }}</strong></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-box">
                                        <label class="field-label">Role</label>
                                        <p class="field-value">
                                            @if ($user->role === 'admin')
                                                <span class="badge bg-danger"><i class="bi bi-shield-lock me-1"></i>Admin</span>
                                            @else
                                                <span class="badge bg-info"><i class="bi bi-person me-1"></i>User</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Timestamp Info -->
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted small mb-2">
                                        <strong>Dibuat:</strong>
                                    </p>
                                    <p class="small">{{ $user->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-2">
                                        <strong>Diperbarui:</strong>
                                    </p>
                                    <p class="small">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Aksi</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('user.edit', $user) }}" class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-pencil"></i> Edit User
                            </a>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('user.destroy', $user) }}" method="POST" style="display:inline;" class="delete-form w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger w-100 delete-btn" data-name="{{ $user->name }}">
                                        <i class="bi bi-trash"></i> Hapus User
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info small" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Ini adalah akun Anda, tidak dapat dihapus
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Status</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>User Status</span>
                                    <span class="badge bg-success">Aktif</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Akses Sistem</span>
                                    @if ($user->role === 'admin')
                                        <span class="badge bg-danger">Full Access</span>
                                    @else
                                        <span class="badge bg-warning">Limited Access</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
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

    .avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
    }

    .avatar-initials {
        font-size: 32px;
    }

    .bg-light-primary {
        background-color: rgba(0, 123, 255, 0.1);
    }

    .field-box {
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .field-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 6px;
    }

    .field-value {
        font-size: 14px;
        color: #333;
        margin: 0;
    }

    .btn {
        border-radius: 6px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
    }

    .badge {
        font-size: 12px;
        padding: 6px 10px;
    }

    .list-group-item {
        border: none;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush
@endsection
