@extends('layouts.app')

@section('title', 'System Monitoring')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>System Monitoring</h3>
                <p class="text-subtitle text-muted">Monitor system performance and health</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Monitoring</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <!-- System Health -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">🔧 System Health Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-server text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Database</h6>
                                    <span class="badge bg-success">Online</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-wifi text-success" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Network</h6>
                                    <span class="badge bg-success">Connected</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-cpu text-warning" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">CPU Usage</h6>
                                    <span class="badge bg-warning">65%</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="text-center p-3 border rounded">
                                    <i class="bi bi-memory text-info" style="font-size: 2rem;"></i>
                                    <h6 class="mt-2">Memory</h6>
                                    <span class="badge bg-info">45%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity -->
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>👥 Active Users</h4>
                    </div>
                    <div class="card-body">
                        <p class="lead text-center">{{ auth()->user() ? '1' : '0' }} Users Online</p>
                        <div class="list-group">
                            @auth
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ auth()->user()->name }}</strong>
                                    <br><small class="text-muted">{{ auth()->user()->email }}</small>
                                </div>
                                <span class="badge bg-success rounded-pill">Online</span>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>📊 System Metrics</h4>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-3">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 65%">
                                CPU: 65%
                            </div>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 45%">
                                Memory: 45%
                            </div>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 30%">
                                Disk: 30%
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 25%">
                                Network: 25%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>📝 Recent System Activities</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">System Startup</h6>
                                    <small>{{ now()->subMinutes(30)->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Application successfully started</p>
                                <small class="text-success">✓ Completed</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Database Connection</h6>
                                    <small>{{ now()->subMinutes(25)->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Database connection established</p>
                                <small class="text-success">✓ Completed</small>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">User Login</h6>
                                    <small>{{ now()->subMinutes(10)->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">User session created</p>
                                <small class="text-info">ℹ Active</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection