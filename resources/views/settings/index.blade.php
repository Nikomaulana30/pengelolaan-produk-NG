@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Settings</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Security Settings -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="bi bi-shield-lock me-2"></i>Keamanan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <h6>Password</h6>
                                <p class="text-muted small">Ubah password akun Anda</p>
                            </div>
                            <div class="col-sm-6 text-end">
                                <a href="{{ route('profile.change-password') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-key me-2"></i>Ubah Password
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <h6>Two-Factor Authentication</h6>
                                <p class="text-muted small">Tambahkan lapisan keamanan ekstra ke akun Anda</p>
                            </div>
                            <div class="col-sm-6 text-end">
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="bi bi-lock me-2"></i>Segera Hadir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Preferences -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="bi bi-bell me-2"></i>Pengaturan Notifikasi
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="notificationForm" action="{{ route('settings.update-notifications') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" 
                                           name="email_notifications" value="1" 
                                           {{ $user->email_notifications ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications">
                                        <strong>Email Notifications</strong>
                                        <small class="text-muted d-block">Terima notifikasi penting melalui email</small>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="approval_notifications" 
                                           name="approval_notifications" value="1" 
                                           {{ $user->approval_notifications ? 'checked' : '' }}>
                                    <label class="form-check-label" for="approval_notifications">
                                        <strong>Approval Notifications</strong>
                                        <small class="text-muted d-block">Notifikasi untuk approval dan persetujuan</small>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="activity_notifications" 
                                           name="activity_notifications" value="1" 
                                           {{ $user->activity_notifications ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activity_notifications">
                                        <strong>Activity Notifications</strong>
                                        <small class="text-muted d-block">Notifikasi aktivitas di sistem</small>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-2"></i>Simpan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="bi bi-info-circle me-2"></i>Informasi Akun
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Email</strong>
                            </div>
                            <div class="col-sm-8 text-muted">
                                {{ $user->email }}
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Role</strong>
                            </div>
                            <div class="col-sm-8 text-muted">
                                <span class="badge bg-{{ $user->getRoleBadgeColor() }}">
                                    {{ $user->getRoleDisplayName() }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status</strong>
                            </div>
                            <div class="col-sm-8 text-muted">
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Member Sejak</strong>
                            </div>
                            <div class="col-sm-8 text-muted">
                                {{ $user->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Akses Cepat</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-person me-2"></i>My Profile</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-pencil me-2"></i>Edit Profile</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-key me-2"></i>Change Password</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Bantuan</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Butuh bantuan? Hubungi tim support kami</p>
                        <a href="mailto:support@example.com" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-envelope me-2"></i>Kontak Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple theme form submission - no event listener interference
        // Let form submit naturally, page will reload with new theme
        
        // Only handle notification form
        const notificationForm = document.getElementById('notificationForm');
        if (notificationForm) {
            notificationForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';
                
                // Let form submit normally
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 2000);
            });
        }
    });
</script>
@endsection
