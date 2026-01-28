@extends('layouts.app')

@section('title', 'Approval - Warehouse & Manager')

@push('styles')
    <style>
        .section-header {
            background-color: #E7E6E6;
            padding: 10px 15px;
            font-weight: bold;
            border-left: 4px solid #FF6B35;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .nav-tabs .nav-link {
            color: #495057;
            border: 1px solid #ddd;
        }
        .nav-tabs .nav-link.active {
            background-color: #FF6B35;
            color: white;
            border-color: #FF6B35;
        }
    </style>
@endpush

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Approval - Warehouse & Production Manager</h3>
                    <p class="text-subtitle text-muted">Form Persetujuan Warehouse & Manager</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Approval Warehouse & Manager</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('warehouse.approval.store') }}" method="POST">
                        @csrf
                        
                        {{-- Display Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Validation Errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        {{-- ============ HEADER GLOBAL ============ --}}
                        <div class="section-header">📋 Header - Informasi Umum Approval</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_approval" class="form-label">Nomor Approval <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nomor_approval" name="nomor_approval" 
                                           value="APV-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" 
                                           value="{{ date('d-m-Y H:i:s') }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_referensi" class="form-label">Nomor Referensi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nomor_referensi') is-invalid @enderror" id="nomor_referensi" name="nomor_referensi" 
                                           value="{{ old('nomor_referensi') }}"
                                           placeholder="Contoh: PB-20251215-0001" required>
                                    @error('nomor_referensi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pengaju" class="form-label">Pengaju <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pengaju" name="pengaju" 
                                           value="{{ auth()->user()->name }}" 
                                           readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="deskripsi_pengajuan" class="form-label">Deskripsi Pengajuan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi_pengajuan') is-invalid @enderror" id="deskripsi_pengajuan" name="deskripsi_pengajuan" 
                                              rows="3" placeholder="Jelaskan pengajuan approval..." required>{{ old('deskripsi_pengajuan') }}</textarea>
                                    @error('deskripsi_pengajuan')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ============ TAB SECTION ============ --}}
                        <ul class="nav nav-tabs mt-4 mb-3" id="approvalTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="warehouse-tab" data-bs-toggle="tab" data-bs-target="#warehouse" 
                                        type="button" role="tab" aria-controls="warehouse" aria-selected="true">
                                    🏢 Warehouse Supervisor
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="manager-tab" data-bs-toggle="tab" data-bs-target="#manager" 
                                        type="button" role="tab" aria-controls="manager" aria-selected="false">
                                    ⚙ Production Manager
                                </button>
                            </li>
                        </ul>

                        {{-- ============ TAB 1: WAREHOUSE SUPERVISOR ============ --}}
                        <div class="tab-content" id="approvalTabContent">
                            <div class="tab-pane fade show active" id="warehouse" role="tabpanel" aria-labelledby="warehouse-tab">
                                
                                <div class="section-header">✅ Validasi Warehouse Supervisor</div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ws_status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                            <select class="form-select @error('ws_status_approval') is-invalid @enderror" id="ws_status_approval" name="ws_status_approval" required>
                                                <option value="pending" @if(old('ws_status_approval') === 'pending') selected @endif>⏳ Pending (Menunggu)</option>
                                                <option value="approved" @if(old('ws_status_approval') === 'approved') selected @endif>✓ Approved (Disetujui)</option>
                                                <option value="rejected" @if(old('ws_status_approval') === 'rejected') selected @endif>✗ Rejected (Ditolak)</option>
                                                <option value="need_revision" @if(old('ws_status_approval') === 'need_revision') selected @endif>⚠ Need Revision (Perlu Revisi)</option>
                                            </select>
                                            @error('ws_status_approval')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ws_tanggal_approval" class="form-label">Tanggal Approval <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('ws_tanggal_approval') is-invalid @enderror" id="ws_tanggal_approval" name="ws_tanggal_approval" 
                                                   value="{{ old('ws_tanggal_approval') }}">
                                            @error('ws_tanggal_approval')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ws_nama_approver" class="form-label">Nama Warehouse Supervisor <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('ws_nama_approver') is-invalid @enderror" id="ws_nama_approver" name="ws_nama_approver" 
                                                   value="{{ old('ws_nama_approver', auth()->user()->name) }}" required>
                                            @error('ws_nama_approver')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ws_kondisi_barang" class="form-label">Kondisi Barang di Gudang <span class="text-danger">*</span></label>
                                            <select class="form-select @error('ws_kondisi_barang') is-invalid @enderror" id="ws_kondisi_barang" name="ws_kondisi_barang">
                                                <option value="">-- Pilih Kondisi --</option>
                                                <option value="aman" @if(old('ws_kondisi_barang') === 'aman') selected @endif>✓ Aman (Penyimpanan OK)</option>
                                                <option value="perlu_penanganan" @if(old('ws_kondisi_barang') === 'perlu_penanganan') selected @endif>⚠ Perlu Penanganan Khusus</option>
                                                <option value="tidak_layak" @if(old('ws_kondisi_barang') === 'tidak_layak') selected @endif>✗ Tidak Layak Simpan</option>
                                            </select>
                                            @error('ws_kondisi_barang')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="ws_catatan" class="form-label">Catatan Warehouse Supervisor</label>
                                            <textarea class="form-control @error('ws_catatan') is-invalid @enderror" id="ws_catatan" name="ws_catatan" 
                                                      rows="3" placeholder="Catatan validasi penerimaan barang...">{{ old('ws_catatan') }}</textarea>
                                            @error('ws_catatan')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ============ TAB 2: PRODUCTION MANAGER ============ --}}
                            <div class="tab-pane fade" id="manager" role="tabpanel" aria-labelledby="manager-tab">
                                
                                <div class="section-header">✅ Approval Production Manager</div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pm_status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                            <select class="form-select @error('pm_status_approval') is-invalid @enderror" id="pm_status_approval" name="pm_status_approval" required>
                                                <option value="pending" @if(old('pm_status_approval') === 'pending') selected @endif>⏳ Pending (Menunggu)</option>
                                                <option value="approved" @if(old('pm_status_approval') === 'approved') selected @endif>✓ Approved (Disetujui)</option>
                                                <option value="rejected" @if(old('pm_status_approval') === 'rejected') selected @endif>✗ Rejected (Ditolak)</option>
                                                <option value="need_revision" @if(old('pm_status_approval') === 'need_revision') selected @endif>⚠ Need Revision (Perlu Revisi)</option>
                                            </select>
                                            @error('pm_status_approval')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pm_tanggal_approval" class="form-label">Tanggal Approval <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('pm_tanggal_approval') is-invalid @enderror" id="pm_tanggal_approval" name="pm_tanggal_approval" 
                                                   value="{{ old('pm_tanggal_approval') }}">
                                            @error('pm_tanggal_approval')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pm_nama_approver" class="form-label">Nama Production Manager <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('pm_nama_approver') is-invalid @enderror" id="pm_nama_approver" name="pm_nama_approver" 
                                                   value="{{ old('pm_nama_approver', auth()->user()->name) }}" required>
                                            @error('pm_nama_approver')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pm_keputusan" class="form-label">Keputusan <span class="text-danger">*</span></label>
                                            <select class="form-select @error('pm_keputusan') is-invalid @enderror" id="pm_keputusan" name="pm_keputusan">
                                                <option value="">-- Pilih Keputusan --</option>
                                                <option value="rework" @if(old('pm_keputusan') === 'rework') selected @endif>⚙ Rework (Dikerjakan Ulang)</option>
                                                <option value="repair" @if(old('pm_keputusan') === 'repair') selected @endif>🔧 Repair (Diperbaiki)</option>
                                                <option value="scrap" @if(old('pm_keputusan') === 'scrap') selected @endif>✗ Scrap (Dibuang)</option>
                                                <option value="use_as_is" @if(old('pm_keputusan') === 'use_as_is') selected @endif>✓ Use As Is (Pakai Apa Adanya)</option>
                                            </select>
                                            @error('pm_keputusan')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="pm_catatan" class="form-label">Catatan Production Manager</label>
                                            <textarea class="form-control @error('pm_catatan') is-invalid @enderror" id="pm_catatan" name="pm_catatan" 
                                                      rows="3" placeholder="Alasan dan detail keputusan...">{{ old('pm_catatan') }}</textarea>
                                            @error('pm_catatan')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============ TOMBOL SUBMIT ============ --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-send"></i> Submit Approval
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Form
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ============ TABEL RIWAYAT ============ --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Riwayat Approval Warehouse & Manager</h4>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Approval</th>
                                    <th>Referensi</th>
                                    <th>Tanggal</th>
                                    <th>Warehouse Approval</th>
                                    <th>Manager Approval</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approvals as $approval)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $approval->nomor_approval }}</strong></td>
                                        <td>{{ $approval->nomor_referensi }}</td>
                                        <td>{{ $approval->tanggal_pengajuan->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if ($approval->ws_status_approval === 'approved')
                                                <span class="badge bg-success">✓ Approved</span>
                                            @elseif ($approval->ws_status_approval === 'rejected')
                                                <span class="badge bg-danger">✗ Rejected</span>
                                            @elseif ($approval->ws_status_approval === 'need_revision')
                                                <span class="badge bg-warning">⚠ Revision</span>
                                            @else
                                                <span class="badge bg-secondary">⏳ Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($approval->pm_status_approval === 'approved')
                                                <span class="badge bg-success">✓ Approved</span>
                                            @elseif ($approval->pm_status_approval === 'rejected')
                                                <span class="badge bg-danger">✗ Rejected</span>
                                            @elseif ($approval->pm_status_approval === 'need_revision')
                                                <span class="badge bg-warning">⚠ Revision</span>
                                            @else
                                                <span class="badge bg-secondary">⏳ Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($approval->status_keseluruhan === 'approved')
                                                <span class="badge bg-success">✓ Approved</span>
                                            @elseif ($approval->status_keseluruhan === 'rejected')
                                                <span class="badge bg-danger">✗ Rejected</span>
                                            @elseif ($approval->status_keseluruhan === 'need_revision')
                                                <span class="badge bg-warning">⚠ Revision</span>
                                            @else
                                                <span class="badge bg-secondary">⏳ Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('warehouse.approval.show', $approval) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if ($approval->status_keseluruhan === 'pending')
                                                <a href="{{ route('warehouse.approval.edit', $approval) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('warehouse.approval.destroy', $approval) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($approvals->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $approvals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('ws_tanggal_approval').valueAsDate = new Date();
        document.getElementById('pm_tanggal_approval').valueAsDate = new Date();
    </script>
@endpush
