@extends('layouts.app')

@section('title', 'Detail Customer')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Customer</h3>
                <p class="text-subtitle text-muted">Informasi lengkap customer dan activity history</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('master-customer.index') }}">Master Customer</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Customer</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Customer Information -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">{{ $masterCustomer->nama_customer }}</h4>
                        <div class="btn-group" role="group">
                            <a href="{{ route('master-customer.edit', $masterCustomer) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a>
                            <form action="{{ route('master-customer.destroy', $masterCustomer) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash me-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Kode Customer</p>
                                <h5>{{ $masterCustomer->kode_customer }}</h5>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Status</p>
                                @if($masterCustomer->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Email</p>
                                <p><a href="mailto:{{ $masterCustomer->email_customer }}">{{ $masterCustomer->email_customer }}</a></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-2">Telepon</p>
                                <p><a href="tel:{{ $masterCustomer->telepon_customer }}">{{ $masterCustomer->telepon_customer }}</a></p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <p class="text-muted mb-2">Alamat</p>
                                <p>{{ $masterCustomer->alamat_customer }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <p class="text-muted mb-2">Kategori</p>
                                <p>
                                    @if($masterCustomer->kategori_customer === 'vip')
                                        <span class="badge bg-warning">VIP</span>
                                    @elseif($masterCustomer->kategori_customer === 'regular')
                                        <span class="badge bg-primary">Regular</span>
                                    @else
                                        <span class="badge bg-info">New</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-2">Payment Terms</p>
                                <p>{{ ucfirst(str_replace('_', ' ', $masterCustomer->payment_terms)) }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-2">Credit Limit</p>
                                <p>Rp {{ number_format($masterCustomer->credit_limit ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($masterCustomer->contact_person)
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted mb-2">Contact Person</p>
                                    <p>{{ $masterCustomer->contact_person }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-2">Telepon Contact Person</p>
                                    <p><a href="tel:{{ $masterCustomer->phone_contact_person }}">{{ $masterCustomer->phone_contact_person }}</a></p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Sidebar -->
            <div class="col-12 col-lg-4">
                <!-- Customer Statistics -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">Statistik Customer</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted mb-1">Total Complaints</p>
                            <h4>{{ $stats['total_complaints'] ?? 0 }}</h4>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted mb-1">Pending</p>
                            <h4 class="text-warning">{{ $stats['pending_complaints'] ?? 0 }}</h4>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted mb-1">Resolved</p>
                            <h4 class="text-success">{{ $stats['resolved_complaints'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Account Created -->
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-2">Dibuat</p>
                        <p>{{ $masterCustomer->created_at->format('d M Y H:i') }}</p>
                        <hr>
                        <p class="text-muted mb-2">Terakhir Diupdate</p>
                        <p>{{ $masterCustomer->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Complaints -->
        @if($masterCustomer->customerComplaints->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Recent Complaints (Last 10)</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No Complaint</th>
                                            <th>Produk</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($masterCustomer->customerComplaints as $complaint)
                                            <tr>
                                                <td>{{ $complaint->nomor_complaint }}</td>
                                                <td>{{ $complaint->produk }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $complaint->status === 'resolved' ? 'success' : ($complaint->status === 'processing' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($complaint->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $complaint->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-info">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
