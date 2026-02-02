@extends('layouts.app')

@section('title')
Customer Complaint
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Customer Complaint Management</h3>
                <p class="text-subtitle text-muted">Tahap 1: Kelola complaint dari customer</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Complaint</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon red mb-2">
                                    <i class="iconly-boldDanger"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Complaint</h6>
                                <h6 class="font-extrabold mb-0">{{ $complaints->total() }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Processing</h6>
                                <h6 class="font-extrabold mb-0">{{ $complaints->where('status', 'processing')->count() }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon green mb-2">
                                    <i class="iconly-boldBookmark"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Completed</h6>
                                <h6 class="font-extrabold mb-0">{{ $complaints->where('status', 'completed')->count() }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="iconly-boldShow"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">High Priority</h6>
                                <h6 class="font-extrabold mb-0">{{ $complaints->whereIn('priority', ['high', 'critical'])->count() }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Customer Complaints</h4>
                        <a href="{{ route('customer-complaint.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> New Complaint
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Complaint ID</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($complaints as $complaint)
                                    <tr>
                                        <td class="fw-bold">{{ $complaint->nomor_complaint }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $complaint->nama_customer }}</strong><br>
                                                <small class="text-muted">{{ $complaint->email_customer }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $complaint->produk }}<br>
                                            <small class="text-muted">{{ $complaint->quantity_ng }} units NG</small>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($complaint->priority == 'critical') bg-danger
                                                @elseif($complaint->priority == 'high') bg-warning
                                                @elseif($complaint->priority == 'medium') bg-info
                                                @else bg-secondary
                                                @endif">
                                                {{ $complaint->priority_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($complaint->status == 'completed') bg-success
                                                @elseif($complaint->status == 'processing') bg-primary
                                                @elseif($complaint->status == 'cancelled') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                {{ $complaint->status_label }}
                                            </span>
                                        </td>
                                        <td>{{ $complaint->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('customer-complaint.show', $complaint->id) }}">
                                                        <i class="bi bi-eye"></i> View Details
                                                    </a></li>
                                                    @can('update', $complaint)
                                                    <li><a class="dropdown-item" href="{{ route('customer-complaint.edit', $complaint->id) }}">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a></li>
                                                    @endcan
                                                    @if($complaint->status == 'submitted' && !$complaint->dokumenRetur)
                                                    <li><a class="dropdown-item" href="{{ route('dokumen-retur.create') }}?complaint={{ $complaint->id }}">
                                                        <i class="bi bi-file-text"></i> Create Return Document
                                                    </a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                                <h5 class="mt-3">No complaints yet</h5>
                                                <p class="text-muted">Start by creating your first customer complaint</p>
                                                @can('create', App\Models\CustomerComplaint::class)
                                                <a href="{{ route('customer-complaint.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-plus"></i> New Complaint
                                                </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $complaints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Status Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Return NG Workflow Steps</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="workflow-step active">
                                        <div class="step-number">1</div>
                                        <div class="step-title">Customer Complaint</div>
                                        <div class="step-desc">Staff Export/Import</div>
                                    </div>
                                    <div class="workflow-step">
                                        <div class="step-number">2</div>
                                        <div class="step-title">Dokumen Retur</div>
                                        <div class="step-desc">Warehouse</div>
                                    </div>
                                    <div class="workflow-step">
                                        <div class="step-number">3</div>
                                        <div class="step-title">Warehouse Verification</div>
                                        <div class="step-desc">Warehouse</div>
                                    </div>
                                    <div class="workflow-step">
                                        <div class="step-number">4</div>
                                        <div class="step-title">Quality Reinspection</div>
                                        <div class="step-desc">Quality Manager</div>
                                    </div>
                                    <div class="workflow-step">
                                        <div class="step-number">5</div>
                                        <div class="step-title">Production Rework</div>
                                        <div class="step-desc">Production Manager</div>
                                    </div>
                                    <div class="workflow-step">
                                        <div class="step-number">6</div>
                                        <div class="step-title">Final Quality Check</div>
                                        <div class="step-desc">Export/Import</div>
                                    </div>
                                    <div class="workflow-step">
                                        <div class="step-number">7</div>
                                        <div class="step-title">Return Shipment</div>
                                        <div class="step-desc">Warehouse</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.workflow-step {
    text-align: center;
    position: relative;
    flex: 1;
    margin: 0 10px;
}

.workflow-step:not(:last-child):after {
    content: '';
    position: absolute;
    top: 20px;
    right: -20px;
    width: 40px;
    height: 2px;
    background: #ddd;
    z-index: -1;
}

.workflow-step.active:not(:last-child):after {
    background: #007bff;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 2px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
    color: #666;
}

.workflow-step.active .step-number {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

.step-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
    font-size: 14px;
}

.step-desc {
    font-size: 12px;
    color: #666;
}
</style>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
<script>
    // Simple DataTable
    let table1 = document.querySelector('#table1');
    let dataTable = new simpleDatatables.DataTable(table1);
</script>
@endpush