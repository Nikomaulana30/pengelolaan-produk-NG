@extends('layouts.app')

@section('title', 'Disposisi Assignment')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-diagram-3"></i> Disposisi Assignment</h3>
                    <p class="text-subtitle text-muted">Manage disposisi assignments untuk barang NG</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('disposisi-assignment.create') }}" class="btn btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> Assign Disposisi
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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-warning">{{ $totalAssignments }}</h4>
                        <small class="text-muted">Total Assignments</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-info">{{ $pendingCount }}</h4>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-primary">{{ $inProgressCount }}</h4>
                        <small class="text-muted">In Progress</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-success">{{ $completedCount }}</h4>
                        <small class="text-muted">Completed</small>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Daftar Disposisi Assignment</h5>
                </div>
                <div class="card-body">
                    <!-- Search & Filter Section -->
                    <div class="row mb-3">
                        <div class="col-12 col-md-3">
                            <form method="GET" action="{{ route('disposisi-assignment.index') }}" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari nomor penyimpanan..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-12 col-md-3">
                            <form method="GET" action="{{ route('disposisi-assignment.index') }}" class="d-flex gap-2">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-12 col-md-4">
                            <form method="GET" action="{{ route('disposisi-assignment.index') }}" class="d-flex gap-2">
                                <select name="master_disposisi_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Disposisi</option>
                                    @foreach ($disposisis as $disp)
                                        @if ($disp->is_active)
                                            <option value="{{ $disp->id }}" {{ request('master_disposisi_id') == $disp->id ? 'selected' : '' }}>
                                                {{ $disp->nama_disposisi }} ({{ ucfirst(str_replace('_', ' ', $disp->jenis_tindakan)) }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-12 col-md-2">
                            @if (request('search') || request('status') || request('master_disposisi_id'))
                                <a href="{{ route('disposisi-assignment.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Penyimpanan</th>
                                    <th>Produk</th>
                                    <th>Disposisi</th>
                                    <th>Status</th>
                                    <th>Assigned By</th>
                                    <th>Assigned At</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assignments as $assignment)
                                    <tr>
                                        <td>
                                            <strong>{{ $assignment->penyimpananNg->nomor_storage }}</strong>
                                        </td>
                                        <td>{{ $assignment->penyimpananNg->nama_barang ?? 'N/A' }}</td>
                                        <td>
                                            @if($assignment->disposisi)
                                                <span class="badge bg-info">{{ $assignment->disposisi->nama_disposisi }}</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Ada Disposisi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($assignment->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($assignment->status === 'in_progress')
                                                <span class="badge bg-primary">In Progress</span>
                                            @elseif ($assignment->status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-secondary">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>{{ $assignment->assignedBy?->name ?? 'System' }}</td>
                                        <td>{{ $assignment->assigned_at?->format('d M Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('disposisi-assignment.show', $assignment) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if ($assignment->status === 'pending')
                                                    <form action="{{ route('disposisi-assignment.mark-in-progress', $assignment) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-outline-info" title="Start">
                                                            <i class="bi bi-play-fill"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('disposisi-assignment.destroy', $assignment) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus assignment ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox"></i> Tidak ada disposisi assignment
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $assignments->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
