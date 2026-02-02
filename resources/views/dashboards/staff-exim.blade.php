@extends('layouts.app')

@section('title', 'Staff EXIM Dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Staff EXIM Dashboard</h3>
                <p class="text-subtitle text-muted">Kelola customer complaints dan dokumen retur</p>
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
                                <div class="stats-icon blue mb-2">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">My Complaints</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['my_complaints'] }}</h6>
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
                                <div class="stats-icon red mb-2">
                                    <i class="bi bi-file-text-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pending Review</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['pending_review'] }}</h6>
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
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Completed Today</h6>
                                <h6 class="font-extrabold mb-0">{{ $data['completed_today'] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12 col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h4>My Recent Assignments</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Customer</th>
                                        <th>Produk</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data['recent_assignments'] as $complaint)
                                    <tr>
                                        <td><strong>{{ $complaint->nomor_complaint }}</strong></td>
                                        <td>{{ $complaint->nama_customer }}</td>
                                        <td>{{ $complaint->produk }}</td>
                                        <td>
                                            @if($complaint->priority == 'critical')
                                                <span class="badge bg-danger">🔴 Critical</span>
                                            @elseif($complaint->priority == 'high')
                                                <span class="badge bg-warning">🟠 High</span>
                                            @elseif($complaint->priority == 'medium')
                                                <span class="badge bg-info">🟡 Medium</span>
                                            @else
                                                <span class="badge bg-success">🟢 Low</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($complaint->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($complaint->status == 'submitted')
                                                <span class="badge bg-warning">Submitted</span>
                                            @elseif($complaint->status == 'processing')
                                                <span class="badge bg-info">Processing</span>
                                            @else
                                                <span class="badge bg-success">Completed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('customer-complaint.show', $complaint) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No assignments yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h4>Dokumen Retur Pending</h4>
                    </div>
                    <div class="card-body">
                        @forelse($data['dokumen_retur_pending'] as $dokumen)
                        <div class="alert alert-warning mb-2">
                            <h6 class="mb-1"><strong>{{ $dokumen->nomor_dokumen }}</strong></h6>
                            <p class="mb-1"><small>Customer: {{ $dokumen->customerComplaint->nama_customer }}</small></p>
                            <a href="{{ route('dokumen-retur.show', $dokumen) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-arrow-right"></i> Review
                            </a>
                        </div>
                        @empty
                        <p class="text-muted text-center">No pending dokumen retur</p>
                        @endforelse
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('master-customer.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-person-lines-fill"></i> Master Customer
                            </a>
                            <a href="{{ route('customer-complaint.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> New Complaint
                            </a>
                            <a href="{{ route('customer-complaint.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-list-ul"></i> All Complaints
                            </a>
                            <a href="{{ route('final-quality-check.index') }}" class="btn btn-outline-success">
                                <i class="bi bi-shield-check"></i> Final Quality Check
                            </a>
                            <a href="{{ route('return-reports.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-speedometer2"></i> Dashboard Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
