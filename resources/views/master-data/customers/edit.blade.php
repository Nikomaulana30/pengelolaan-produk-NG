@extends('layouts.app')

@section('title', 'Edit Master Customer')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Customer</h3>
                <p class="text-subtitle text-muted">Update data customer untuk Return NG Workflow</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('master-customer.index') }}">Master Customer</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Customer</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Edit Customer</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">Validasi Gagal!</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('master-customer.update', $masterCustomer) }}" method="POST" id="customerForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Kode Customer (Read-only) -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="kode_customer" class="form-label">Kode Customer</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="kode_customer" 
                                               value="{{ $masterCustomer->kode_customer }}"
                                               disabled>
                                        <small class="text-muted">Kode tidak dapat diubah</small>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="is_active" class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                                   @checked($masterCustomer->is_active)>
                                            <label class="form-check-label" for="is_active">
                                                Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nama Customer -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="nama_customer" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama_customer') is-invalid @enderror" 
                                               id="nama_customer" 
                                               name="nama_customer" 
                                               placeholder="Nama Customer"
                                               value="{{ old('nama_customer', $masterCustomer->nama_customer) }}"
                                               required>
                                        @error('nama_customer')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Customer -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="email_customer" class="form-label">Email Customer <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email_customer') is-invalid @enderror" 
                                               id="email_customer" 
                                               name="email_customer" 
                                               placeholder="email@customer.com"
                                               value="{{ old('email_customer', $masterCustomer->email_customer) }}"
                                               required>
                                        @error('email_customer')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Telepon Customer -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="telepon_customer" class="form-label">Telepon Customer <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('telepon_customer') is-invalid @enderror" 
                                               id="telepon_customer" 
                                               name="telepon_customer" 
                                               placeholder="Nomor Telepon"
                                               value="{{ old('telepon_customer', $masterCustomer->telepon_customer) }}"
                                               required>
                                        @error('telepon_customer')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Alamat Customer -->
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="alamat_customer" class="form-label">Alamat Customer <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('alamat_customer') is-invalid @enderror" 
                                                  id="alamat_customer" 
                                                  name="alamat_customer" 
                                                  rows="3"
                                                  placeholder="Alamat lengkap customer"
                                                  required>{{ old('alamat_customer', $masterCustomer->alamat_customer) }}</textarea>
                                        @error('alamat_customer')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kategori Customer -->
                                <div class="col-md-4 col-12">
                                    <div class="form-group mb-3">
                                        <label for="kategori_customer" class="form-label">Kategori Customer <span class="text-danger">*</span></label>
                                        <select class="form-select @error('kategori_customer') is-invalid @enderror" 
                                                id="kategori_customer" 
                                                name="kategori_customer"
                                                required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="vip" @selected(old('kategori_customer', $masterCustomer->kategori_customer) === 'vip')>VIP</option>
                                            <option value="regular" @selected(old('kategori_customer', $masterCustomer->kategori_customer) === 'regular')>Regular</option>
                                            <option value="new" @selected(old('kategori_customer', $masterCustomer->kategori_customer) === 'new')>New</option>
                                        </select>
                                        @error('kategori_customer')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Payment Terms -->
                                <div class="col-md-4 col-12">
                                    <div class="form-group mb-3">
                                        <label for="payment_terms" class="form-label">Payment Terms <span class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_terms') is-invalid @enderror" 
                                                id="payment_terms" 
                                                name="payment_terms"
                                                required>
                                            <option value="">Pilih Payment Terms</option>
                                            <option value="cod" @selected(old('payment_terms', $masterCustomer->payment_terms) === 'cod')>COD</option>
                                            <option value="30_days" @selected(old('payment_terms', $masterCustomer->payment_terms) === '30_days')>30 Hari</option>
                                            <option value="45_days" @selected(old('payment_terms', $masterCustomer->payment_terms) === '45_days')>45 Hari</option>
                                            <option value="60_days" @selected(old('payment_terms', $masterCustomer->payment_terms) === '60_days')>60 Hari</option>
                                        </select>
                                        @error('payment_terms')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Credit Limit -->
                                <div class="col-md-4 col-12">
                                    <div class="form-group mb-3">
                                        <label for="credit_limit" class="form-label">Credit Limit</label>
                                        <input type="number" 
                                               class="form-control @error('credit_limit') is-invalid @enderror" 
                                               id="credit_limit" 
                                               name="credit_limit" 
                                               placeholder="0"
                                               value="{{ old('credit_limit', $masterCustomer->credit_limit ?? 0) }}"
                                               min="0"
                                               step="0.01">
                                        @error('credit_limit')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Person -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="contact_person" class="form-label">Contact Person</label>
                                        <input type="text" 
                                               class="form-control @error('contact_person') is-invalid @enderror" 
                                               id="contact_person" 
                                               name="contact_person" 
                                               placeholder="Nama Contact Person"
                                               value="{{ old('contact_person', $masterCustomer->contact_person) }}">
                                        @error('contact_person')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone Contact Person -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label for="phone_contact_person" class="form-label">Telepon Contact Person</label>
                                        <input type="text" 
                                               class="form-control @error('phone_contact_person') is-invalid @enderror" 
                                               id="phone_contact_person" 
                                               name="phone_contact_person" 
                                               placeholder="Nomor Telepon"
                                               value="{{ old('phone_contact_person', $masterCustomer->phone_contact_person) }}">
                                        @error('phone_contact_person')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-check-circle me-2"></i>Update Customer
                                    </button>
                                    <a href="{{ route('master-customer.show', $masterCustomer) }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Batal
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
