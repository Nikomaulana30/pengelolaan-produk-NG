@extends('layouts.app')

@section('title', 'Master Approval Authority')

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
    
    [data-bs-theme="dark"] .card-title,
    [data-bs-theme="dark"] .card-text {
        color: #e4e4e7 !important;
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
    
    [data-bs-theme="dark"] small {
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
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-shield-check"></i> Master Approval Authority</h3>
                    <p class="text-subtitle text-muted">Kelola otorisasi approval dan wewenang pengguna</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('master-approval.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Tambah Authority
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Daftar Master Approval Authority</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Departemen</th>
                                    <th>Role Level</th>
                                    <th>Jenis Approval</th>
                                    <th>Limit</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($authorities as $approval)
                                    <tr>
                                        <td><strong>{{ $approval->user->name ?? $approval->user_id }}</strong></td>
                                        <td>{{ $approval->departemen }}</td>
                                        <td>
                                            @if ($approval->role_level === 'manager')
                                                <span class="badge bg-info">Manager</span>
                                            @elseif ($approval->role_level === 'director')
                                                <span class="badge bg-primary">Director</span>
                                            @elseif ($approval->role_level === 'supervisor')
                                                <span class="badge bg-secondary">Supervisor</span>
                                            @elseif ($approval->role_level === 'ceo')
                                                <span class="badge bg-danger">CEO</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $approval->role_level }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($approval->jenis_approval === 'purchase')
                                                <span class="badge bg-warning">Purchase</span>
                                            @elseif ($approval->jenis_approval === 'invoice')
                                                <span class="badge bg-info">Invoice</span>
                                            @elseif ($approval->jenis_approval === 'defect')
                                                <span class="badge bg-danger">Defect</span>
                                            @elseif ($approval->jenis_approval === 'disposal')
                                                <span class="badge bg-secondary">Disposal</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $approval->jenis_approval }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($approval->approval_limit && $approval->approval_limit > 0)
                                                <strong>{{ 'Rp ' . number_format($approval->approval_limit, 0, ',', '.') }}</strong>
                                            @else
                                                <span class="text-muted">Unlimited</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($approval->is_active)
                                                <span class="badge bg-success">✓ Active</span>
                                            @else
                                                <span class="badge bg-secondary">✗ Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('master-approval.show', $approval) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('master-approval.edit', $approval) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('master-approval.destroy', $approval) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada data Master Approval Authority
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $authorities->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
