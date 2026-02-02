@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Admin Dashboard</h3>
                <p class="text-subtitle text-muted">Overview sistem return management</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Users</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['total_users'] }}</h6>
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
                                <div class="stats-icon red mb-2">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Complaints</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['total_complaints'] }}</h6>
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
                                    <i class="bi bi-gear-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Reworks</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['total_reworks'] }}</h6>
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
                                    <i class="bi bi-truck"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Shipments</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['total_shipments'] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Overview -->
        <div class="row">
            <div class="col-6 col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon bg-warning mb-2">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pending</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['pending_complaints'] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon bg-info mb-2">
                                    <i class="bi bi-arrow-repeat"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">In Progress</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['in_progress'] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon bg-success mb-2">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Completed</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['completed'] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Complaints & User Stats -->
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Customer Complaints</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Customer</th>
                                        <th>Produk</th>
                                        <th>Status</th>
                                        <th>Staff</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['recent_complaints'] as $complaint)
                                    <tr>
                                        <td><strong>{{ $complaint->nomor_complaint }}</strong></td>
                                        <td>{{ $complaint->nama_customer }}</td>
                                        <td>{{ $complaint->produk }}</td>
                                        <td>
                                            @if($complaint->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($complaint->status == 'submitted')
                                                <span class="badge bg-warning">Submitted</span>
                                            @elseif($complaint->status == 'processing')
                                                <span class="badge bg-info">Processing</span>
                                            @elseif($complaint->status == 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($complaint->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $complaint->staffExim->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('customer-complaint.show', $complaint) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No complaints yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Users by Role</h4>
                    </div>
                    <div class="card-body">
                        @foreach($data['user_stats'] as $stat)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ ucwords(str_replace('_', ' ', $stat->role)) }}</h6>
                            </div>
                            <div>
                                <span class="badge bg-primary">{{ $stat->count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Quick Links</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('master-customer.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-person-lines-fill"></i> Master Customer
                            </a>
                            <a href="{{ route('master-produk.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-box"></i> Master Produk
                            </a>
                            <a href="{{ route('customer-complaint.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i> Customer Complaints
                            </a>
                            <a href="{{ route('warehouse-verification.index') }}" class="btn btn-outline-warning">
                                <i class="bi bi-clipboard-check"></i> Warehouse Verification
                            </a>
                            <a href="{{ route('quality-reinspection.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i> Quality Reinspection
                            </a>
                            <a href="{{ route('production-rework.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-gear-fill"></i> Production Rework
                            </a>
                            <a href="{{ route('return-shipment.index') }}" class="btn btn-outline-success">
                                <i class="bi bi-truck"></i> Return Shipment
                            </a>
                            <a href="{{ route('return-reports.index') }}" class="btn btn-outline-dark">
                                <i class="bi bi-graph-up"></i> Executive Reports
                            </a>
                            <a href="{{ route('user.index') }}" class="btn btn-primary">
                                <i class="bi bi-people"></i> User Management
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
