@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>My Profile</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Profile Card -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body pt-4">
                        <div class="d-flex flex-column align-items-center text-center">
                            <!-- Avatar -->
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}?t={{ time() }}" alt="{{ $user->name }}" 
                                     class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="rounded-circle mb-3 bg-primary d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px;">
                                    <span class="text-white" style="font-size: 48px; font-weight: bold;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                            @endif

                            <div class="mt-3">
                                <h4 class="card-title">{{ $user->name }}</h4>
                                <p class="text-muted mb-1">
                                    <span class="badge bg-{{ $user->getRoleBadgeColor() }}">
                                        {{ $user->getRoleDisplayName() }}
                                    </span>
                                </p>
                                <p class="text-muted small mb-3">{{ $user->email }}</p>

                                @if($user->phone)
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-telephone me-2"></i>{{ $user->phone }}
                                    </p>
                                @endif

                                @if($user->department)
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-building me-2"></i>{{ $user->department }}
                                    </p>
                                @endif

                                <p class="text-muted small">
                                    <i class="bi bi-check-circle me-2"></i>
                                    @if($user->is_active)
                                        <span class="text-success">Active</span>
                                    @else
                                        <span class="text-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>

                            @if($user->bio)
                                <p class="text-muted small mt-3">{{ $user->bio }}</p>
                            @endif

                            <div class="mt-4 w-100">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-pencil me-2"></i>Edit Profile
                                </a>
                                <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                                    <i class="bi bi-gear me-2"></i>Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informasi Akun</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Nama Lengkap</h6>
                            </div>
                            <div class="col-sm-9 text-muted">
                                {{ $user->name }}
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-muted">
                                {{ $user->email }}
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">No. Telepon</h6>
                            </div>
                            <div class="col-sm-9 text-muted">
                                {{ $user->phone ?? '-' }}
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Departemen</h6>
                            </div>
                            <div class="col-sm-9 text-muted">
                                {{ $user->department ?? '-' }}
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Role</h6>
                            </div>
                            <div class="col-sm-9 text-muted">
                                <span class="badge bg-{{ $user->getRoleBadgeColor() }}">
                                    {{ $user->getRoleDisplayName() }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Status</h6>
                            </div>
                            <div class="col-sm-9 text-muted">
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Member Sejak</h6>
                            </div>
                            <div class="col-sm-9 text-muted">
                                {{ $user->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Keamanan</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Kelola password dan keamanan akun Anda</p>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-key me-2"></i>Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
