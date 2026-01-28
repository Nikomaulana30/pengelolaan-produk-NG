<!-- Test Activity Logging -->
<div class="card mt-4">
    <div class="card-header bg-info">
        <h5 class="text-white mb-0">Activity History - {{ class_basename($penyimpananNg) }}</h5>
    </div>
    <div class="card-body">
        @php
            $history = $penyimpananNg->activityLogs()->latest()->get();
        @endphp

        @if($history->count() > 0)
            <div class="timeline">
                @foreach($history as $log)
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-{{ $log->action === 'approved' ? 'success' : ($log->action === 'rejected' ? 'danger' : 'info') }}">
                            <i class="bi bi-{{ $log->action === 'approved' ? 'check-circle' : ($log->action === 'rejected' ? 'x-circle' : 'clock') }}"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <h6 class="mb-0">
                                        <strong>{{ ucfirst($log->action) }}</strong>
                                        @if($log->user)
                                            <small class="text-muted">by {{ $log->user->name }}</small>
                                        @endif
                                    </h6>
                                    <p class="mb-1">{{ $log->description }}</p>
                                </div>
                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                            
                            @if($log->old_value || $log->new_value)
                                <div class="small text-muted">
                                    @if($log->old_value)
                                        <span class="badge bg-light text-dark">Old: {{ substr($log->old_value, 0, 50) }}...</span>
                                    @endif
                                    @if($log->new_value)
                                        <span class="badge bg-success">New: {{ substr($log->new_value, 0, 50) }}...</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle me-2"></i>
                No activity history yet
            </div>
        @endif
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 5px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 3px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #f8f9fa;
}

.timeline-content {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
}
</style>
