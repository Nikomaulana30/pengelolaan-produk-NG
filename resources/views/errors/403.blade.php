@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="container-fluid">
    <div class="page-content">
        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-shield-lock text-danger" style="font-size: 5rem;"></i>
                            </div>
                            <h2 class="text-danger mb-3">403 - Akses Ditolak</h2>
                            <p class="text-muted mb-4">
                                {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
                            </p>
                            
                            <div class="alert alert-warning text-start">
                                <h6><i class="bi bi-info-circle me-2"></i>Informasi Akses:</h6>
                                <ul class="mb-0">
                                    <li>Role Anda: <strong>{{ auth()->user()->getRoleDisplayName() }}</strong></li>
                                    <li>Halaman ini memerlukan hak akses yang berbeda</li>
                                </ul>
                            </div>

                            <div class="d-flex gap-2 justify-content-center mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                    <i class="bi bi-house me-1"></i> Ke Dashboard
                                </a>
                                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
