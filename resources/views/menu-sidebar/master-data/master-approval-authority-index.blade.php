@extends('layouts.app')

@push('styles')
<style>
    /* Dark mode comprehensive styling */
    [data-bs-theme="dark"] .page-content {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .section {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .row {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .col,
    [data-bs-theme="dark"] [class*="col-"] {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .card {
        background-color: #1e1e2d !important;
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .card-header {
        background-color: #2c3142 !important;
        border-color: #3a3f51 !important;
        color: #e4e4e7 !important;
        padding-bottom: 1rem !important;
        margin-bottom: 0 !important;
    }
    
    [data-bs-theme="dark"] .card-body {
        background-color: #1e1e2d !important;
        color: #e4e4e7 !important;
        padding-top: 1.5rem !important;
    }
    
    [data-bs-theme="dark"] h1,
    [data-bs-theme="dark"] h2,
    [data-bs-theme="dark"] h3,
    [data-bs-theme="dark"] h4,
    [data-bs-theme="dark"] h5,
    [data-bs-theme="dark"] h6 {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] p {
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .text-muted {
        color: #a1a1a1 !important;
    }
    
    [data-bs-theme="dark"] .table {
        color: #e4e4e7 !important;
        border-color: #2c3142 !important;
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .table thead {
        background-color: #2c3142 !important;
        color: #e4e4e7 !important;
    }
    
    [data-bs-theme="dark"] .table tbody {
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .table td,
    [data-bs-theme="dark"] .table th {
        border-color: #2c3142 !important;
        color: #e4e4e7 !important;
        background-color: transparent !important;
    }
    
    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    
    [data-bs-theme="dark"] .badge {
        border-color: #3a3f51 !important;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Master Approval Authority</h3>
                <p class="text-subtitle text-muted">Manajemen hak approve untuk setiap user</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Master Approval Authority</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Approval Authority</h5>
                    <a href="{{ route('master-approval-authority.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Authority
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Departemen</th>
                                <th>Role Level</th>
                                <th>Jenis Approval</th>
                                <th>Limit</th>
                                <th>Can Approve Self</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($authorities as $index => $authority)
                                <tr>
                                    <td>{{ $authorities->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-content bg-primary">
                                                    {{ strtoupper(substr($authority->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <strong>{{ $authority->user->name }}</strong>
                                                <br><small class="text-muted">{{ $authority->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $deptColors = [
                                                'warehouse' => 'primary',
                                                'quality' => 'success',
                                                'ppic' => 'warning',
                                                'finance' => 'dark'
                                            ];
                                            $color = $deptColors[$authority->departemen] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ strtoupper($authority->departemen) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $levelColors = [
                                                'supervisor' => 'info',
                                                'manager' => 'warning',
                                                'director' => 'danger'
                                            ];
                                            $color = $levelColors[$authority->role_level] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($authority->role_level) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ str_replace('_', ' ', ucwords($authority->jenis_approval, '_')) }}</small>
                                    </td>
                                    <td>
                                        @if($authority->approval_limit > 0)
                                            <strong>Rp {{ number_format($authority->approval_limit, 0, ',', '.') }}</strong>
                                        @else
                                            <span class="text-muted">No Limit</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($authority->can_approve_self)
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Yes</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($authority->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('master-approval-authority.show', $authority) }}" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('master-approval-authority.edit', $authority) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('master-approval-authority.destroy', $authority) }}" 
                                                  method="POST" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Yakin ingin menghapus approval authority ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Belum ada data approval authority</p>
                                        <a href="{{ route('master-approval-authority.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah Authority
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($authorities->hasPages())
                    <div class="mt-3">
                        {{ $authorities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
