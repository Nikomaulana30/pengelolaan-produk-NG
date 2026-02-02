@extends('layouts.app')

@section('title', 'Master Customer')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Master Customer</h3>
                <p class="text-subtitle text-muted">Kelola data customer untuk Return NG Workflow</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Master Customer</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
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
                            <h6 class="text-muted font-semibold">Total Customers</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats['total'] ?? 0 }}</h6>
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
                                <i class="iconly-boldTicket"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Active</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats['active'] ?? 0 }}</h6>
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
                            <div class="stats-icon orange mb-2">
                                <i class="iconly-boldStar"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">VIP Customers</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats['vip'] ?? 0 }}</h6>
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
                                <i class="iconly-boldGraph"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">New This Month</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats['new_this_month'] ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('master-customer.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by name, code, or email..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="kategori">
                                    <option value="">All Categories</option>
                                    <option value="vip" {{ request('kategori') === 'vip' ? 'selected' : '' }}>VIP</option>
                                    <option value="regular" {{ request('kategori') === 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="new" {{ request('kategori') === 'new' ? 'selected' : '' }}>New</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-funnel"></i> Filter
                                    </button>
                                    <a href="{{ route('master-customer.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </a>
                                    <a href="{{ route('master-customer.create') }}" class="btn btn-success">
                                        <i class="bi bi-plus-circle"></i> Add Customer
                                    </a>
                                    <button type="button" class="btn btn-info" onclick="exportData()">
                                        <i class="bi bi-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Customer List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Customer Name</th>
                                    <th>Category</th>
                                    <th>Contact</th>
                                    <th>Payment Terms</th>
                                    <th>Credit Limit</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers ?? [] as $customer)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $customer->kode_customer }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $customer->nama_customer }}</strong><br>
                                            <small class="text-muted">{{ $customer->email_customer }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($customer->kategori_customer)
                                            @case('vip')
                                                <span class="badge bg-warning">VIP</span>
                                                @break
                                            @case('regular')
                                                <span class="badge bg-primary">Regular</span>
                                                @break
                                            @case('new')
                                                <span class="badge bg-info">New</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $customer->contact_person ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $customer->telepon_customer }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($customer->payment_terms)
                                            @case('cod')
                                                <span class="badge bg-success">COD</span>
                                                @break
                                            @case('30_days')
                                                <span class="badge bg-primary">30 Days</span>
                                                @break
                                            @case('45_days')
                                                <span class="badge bg-warning">45 Days</span>
                                                @break
                                            @case('60_days')
                                                <span class="badge bg-danger">60 Days</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            Rp {{ number_format($customer->credit_limit, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($customer->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('master-customer.show', $customer) }}" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('master-customer.edit', $customer) }}" 
                                               class="btn btn-outline-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-{{ $customer->is_active ? 'secondary' : 'success' }} btn-sm"
                                                    onclick="toggleStatus({{ $customer->id }}, {{ $customer->is_active ? 'false' : 'true' }})">
                                                <i class="bi bi-{{ $customer->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="deleteCustomer({{ $customer->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                            <h5 class="mt-2">No customers found</h5>
                                            <p class="text-muted">Get started by adding your first customer</p>
                                            <a href="{{ route('master-customer.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle"></i> Add Customer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($customers) && $customers->hasPages())
                    <div class="row">
                        <div class="col-12">
                            {{ $customers->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(customerId, newStatus) {
    const action = newStatus ? 'activate' : 'deactivate';
    
    Swal.fire({
        title: `${action.charAt(0).toUpperCase() + action.slice(1)} Customer?`,
        text: `Are you sure you want to ${action} this customer?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: newStatus ? '#28a745' : '#6c757d',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, ${action}!`
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/master-customer/${customerId}/toggle-status`;
            
            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';
            
            form.appendChild(csrfField);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function deleteCustomer(customerId) {
    Swal.fire({
        title: 'Delete Customer?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/master-customer/${customerId}`;
            
            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfField);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("master-customer.export") }}?' + params.toString();
}
</script>
@endpush

@push('styles')
<style>
/* Stats Icons */
.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stats-icon.blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-icon.green {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stats-icon.orange {
    background: linear-gradient(135deg, #fd746c 0%, #ff9068 100%);
}

.stats-icon.red {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.empty-state {
    padding: 2rem;
}

.btn-group .btn {
    margin: 0 1px;
}
</style>
@endpush