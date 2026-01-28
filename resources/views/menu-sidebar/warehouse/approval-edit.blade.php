{{-- Include layout utama (Sidebar dan footer) --}}
@extends('layouts.app')

{{-- Set title berdasarkan page --}}
@section('title', 'Edit Approval - ' . $approval->nomor_approval)

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Approval Warehouse & Manager</h3>
                    <p class="text-subtitle text-muted">Nomor: <strong>{{ $approval->nomor_approval }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Edit Approval Warehouse & Manager</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('warehouse.approval.update', $approval) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan:</strong> Hanya data dengan status <strong>Pending</strong> yang dapat diedit.
                        </div>

                        {{-- ============ HEADER GLOBAL ============ --}}
                        <div class="section-header" style="background-color: #E7E6E6; padding: 10px 15px; font-weight: bold; border-left: 4px solid #FF6B35; margin-top: 20px; margin-bottom: 15px;">📋 Header - Informasi Umum Approval</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_approval" class="form-label">Nomor Approval</label>
                                    <input type="text" class="form-control" id="nomor_approval" name="nomor_approval" 
                                           value="{{ $approval->nomor_approval }}" readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
                                    <input type="text" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" 
                                           value="{{ $approval->tanggal_pengajuan->format('d-m-Y H:i:s') }}" readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_referensi" class="form-label">Nomor Referensi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nomor_referensi') is-invalid @enderror" id="nomor_referensi" name="nomor_referensi" 
                                           value="{{ old('nomor_referensi', $approval->nomor_referensi) }}" required>
                                    @error('nomor_referensi')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pengaju" class="form-label">Pengaju</label>
                                    <input type="text" class="form-control" id="pengaju" name="pengaju" 
                                           value="{{ $approval->pengaju }}" readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="deskripsi_pengajuan" class="form-label">Deskripsi Pengajuan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi_pengajuan') is-invalid @enderror" id="deskripsi_pengajuan" name="deskripsi_pengajuan" 
                                              rows="3" required>{{ old('deskripsi_pengajuan', $approval->deskripsi_pengajuan) }}</textarea>
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
                                
                                <div class="section-header" style="background-color: #E7E6E6; padding: 10px 15px; font-weight: bold; border-left: 4px solid #FF6B35; margin-top: 20px; margin-bottom: 15px;">✅ Validasi Warehouse Supervisor</div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ws_status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                            <select class="form-select @error('ws_status_approval') is-invalid @enderror" id="ws_status_approval" name="ws_status_approval" required>
                                                <option value="pending" @if(old('ws_status_approval', $approval->ws_status_approval) === 'pending') selected @endif>⏳ Pending (Menunggu)</option>
                                                <option value="approved" @if(old('ws_status_approval', $approval->ws_status_approval) === 'approved') selected @endif>✓ Approved (Disetujui)</option>
                                                <option value="rejected" @if(old('ws_status_approval', $approval->ws_status_approval) === 'rejected') selected @endif>✗ Rejected (Ditolak)</option>
                                                <option value="need_revision" @if(old('ws_status_approval', $approval->ws_status_approval) === 'need_revision') selected @endif>⚠ Need Revision (Perlu Revisi)</option>
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
                                                   value="{{ old('ws_tanggal_approval', $approval->ws_tanggal_approval?->format('Y-m-d')) }}">
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
                                                   value="{{ old('ws_nama_approver', $approval->ws_nama_approver) }}" required>
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
                                                <option value="aman" @if(old('ws_kondisi_barang', $approval->ws_kondisi_barang) === 'aman') selected @endif>✓ Aman (Penyimpanan OK)</option>
                                                <option value="perlu_penanganan" @if(old('ws_kondisi_barang', $approval->ws_kondisi_barang) === 'perlu_penanganan') selected @endif>⚠ Perlu Penanganan Khusus</option>
                                                <option value="tidak_layak" @if(old('ws_kondisi_barang', $approval->ws_kondisi_barang) === 'tidak_layak') selected @endif>✗ Tidak Layak Simpan</option>
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
                                                      rows="3" placeholder="Catatan validasi penerimaan barang...">{{ old('ws_catatan', $approval->ws_catatan) }}</textarea>
                                            @error('ws_catatan')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ============ TAB 2: PRODUCTION MANAGER ============ --}}
                            <div class="tab-pane fade" id="manager" role="tabpanel" aria-labelledby="manager-tab">
                                
                                <div class="section-header" style="background-color: #E7E6E6; padding: 10px 15px; font-weight: bold; border-left: 4px solid #FF6B35; margin-top: 20px; margin-bottom: 15px;">✅ Approval Production Manager</div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pm_status_approval" class="form-label">Status Approval <span class="text-danger">*</span></label>
                                            <select class="form-select @error('pm_status_approval') is-invalid @enderror" id="pm_status_approval" name="pm_status_approval" required>
                                                <option value="pending" @if(old('pm_status_approval', $approval->pm_status_approval) === 'pending') selected @endif>⏳ Pending (Menunggu)</option>
                                                <option value="approved" @if(old('pm_status_approval', $approval->pm_status_approval) === 'approved') selected @endif>✓ Approved (Disetujui)</option>
                                                <option value="rejected" @if(old('pm_status_approval', $approval->pm_status_approval) === 'rejected') selected @endif>✗ Rejected (Ditolak)</option>
                                                <option value="need_revision" @if(old('pm_status_approval', $approval->pm_status_approval) === 'need_revision') selected @endif>⚠ Need Revision (Perlu Revisi)</option>
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
                                                   value="{{ old('pm_tanggal_approval', $approval->pm_tanggal_approval?->format('Y-m-d')) }}">
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
                                                   value="{{ old('pm_nama_approver', $approval->pm_nama_approver) }}" required>
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
                                                <option value="rework" @if(old('pm_keputusan', $approval->pm_keputusan) === 'rework') selected @endif>⚙ Rework (Dikerjakan Ulang)</option>
                                                <option value="repair" @if(old('pm_keputusan', $approval->pm_keputusan) === 'repair') selected @endif>🔧 Repair (Diperbaiki)</option>
                                                <option value="scrap" @if(old('pm_keputusan', $approval->pm_keputusan) === 'scrap') selected @endif>✗ Scrap (Dibuang)</option>
                                                <option value="use_as_is" @if(old('pm_keputusan', $approval->pm_keputusan) === 'use_as_is') selected @endif>✓ Use As Is (Pakai Apa Adanya)</option>
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
                                                      rows="3" placeholder="Alasan dan detail keputusan...">{{ old('pm_catatan', $approval->pm_catatan) }}</textarea>
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
                                    <i class="bi bi-check"></i> Update Data
                                </button>
                                <a href="{{ route('warehouse.approval.show', $approval) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection
