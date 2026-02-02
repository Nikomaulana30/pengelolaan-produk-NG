@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3><i class="bi bi-pencil-square"></i> Edit User</h3>
                    <p class="text-subtitle text-muted">Ubah data pengguna</p>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary float-end">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Form Edit User</h5>
                            <p class="text-muted small">Perbarui data pengguna</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user.update', $user) }}" method="POST" class="needs-validation">
                                @csrf
                                @method('PUT')

                                <!-- Nama User -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama User <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" placeholder="Contoh: John Doe"
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" placeholder="Contoh: user@example.com"
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Email harus unik dan tidak boleh sama dengan user lain</small>
                                </div>

                                <!-- Password (Optional) -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru (Biarkan kosong jika tidak ingin mengubah)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Minimal 8 karakter">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Hanya isi jika ingin mengubah password</small>
                                </div>

                                <!-- Konfirmasi Password -->
                                @if (old('password'))
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                                               required>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin" @selected(old('role', $user->role) === 'admin')>🔴 Administrator</option>
                                        <option value="staff_exim" @selected(old('role', $user->role) === 'staff_exim')>🔵 Staff EXIM</option>
                                        <option value="supervisor_warehouse" @selected(old('role', $user->role) === 'supervisor_warehouse')>🟢 Supervisor Warehouse</option>
                                        <option value="manager_quality" @selected(old('role', $user->role) === 'manager_quality')>🟡 Manager Quality</option>
                                        <option value="manager_production" @selected(old('role', $user->role) === 'manager_production')>🟠 Manager Production</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        • <strong>Administrator:</strong> Akses penuh semua menu (Master Data, All Workflows, Reports, User Management)<br>
                                        • <strong>Staff EXIM:</strong> Customer Complaint, Final Quality Check, Return Shipment, Export Reports<br>
                                        • <strong>Supervisor Warehouse:</strong> Dokumen Retur, Warehouse Verification, Incoming, Racks to Ship<br>
                                        • <strong>Manager Quality:</strong> Quality Reinspection, Scrap Disposal, Printing, Master Defect/Vendor<br>
                                        • <strong>Manager Production:</strong> Production Rework, Rework Methods, Production Dashboard
                                    </small>
                                </div>

                                <!-- Status Aktif -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">User Aktif</label>
                                    </div>
                                    <small class="text-muted">User yang tidak aktif tidak dapat login ke sistem</small>
                                </div>

                                <!-- User Info -->
                                <div class="alert alert-info" role="alert">
                                    <small>
                                        <strong>Informasi User:</strong><br>
                                        • ID: {{ $user->id }}<br>
                                        • Dibuat: {{ $user->created_at->format('d M Y, H:i') }}<br>
                                        • Diperbarui: {{ $user->updated_at->format('d M Y, H:i') }}
                                    </small>
                                </div>

                                <hr>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Batal
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 500;
        color: #495057;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
        font-size: 14px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .text-danger {
        color: #dc3545;
    }

    .text-muted {
        color: #6c757d;
        font-size: 13px;
    }

    .btn {
        border-radius: 6px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
    }

    .alert {
        border-radius: 6px;
        border: none;
    }
</style>
@endpush
@endsection
