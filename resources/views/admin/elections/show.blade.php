@extends('layouts.admin')

@section('title', 'Election Details')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ $election->name }}</h1>
            <p class="page-subtitle mb-0">Election details and management</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.elections.edit', $election) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Election Status Card -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $election->candidates_count ?? 0 }}</div>
                            <div class="stats-label">Candidates</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-person-badge" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $election->positions_count ?? 0 }}</div>
                            <div class="stats-label">Positions</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-list" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $election->votes_count ?? 0 }}</div>
                            <div class="stats-label">Total Votes</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-check-square" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $election->turnout_percentage ?? '0%' }}</div>
                            <div class="stats-label">Turnout</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-percent" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Election Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Election Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Election Name</h6>
                            <p class="mb-0">{{ $election->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Status</h6>
                            @if($election->status === 'active')
                                <span class="badge bg-success"><i class="bi bi-lightning me-1"></i>Active</span>
                            @elseif($election->status === 'pending')
                                <span class="badge bg-warning"><i class="bi bi-clock me-1"></i>Pending</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-check me-1"></i>Completed</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Start Date</h6>
                            <p class="mb-0">{{ $election->start_date ? $election->start_date->format('M d, Y H:i') : 'Not set' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">End Date</h6>
                            <p class="mb-0">{{ $election->end_date ? $election->end_date->format('M d, Y H:i') : 'Not set' }}</p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted mb-1">Description</h6>
                            <p class="mb-0">{{ $election->description ?? 'No description provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.elections.positions.index', $election) }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-list me-2"></i>Manage Positions
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.elections.candidates.index', $election) }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-people me-2"></i>Manage Candidates
                            </a>
                        </div>
                        @if($election->status === 'pending')
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('admin.elections.activate', $election) }}" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-play me-2"></i>Activate Election
                                    </button>
                                </form>
                            </div>
                        @endif
                        @if($election->status === 'active')
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('admin.elections.close', $election) }}" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="bi bi-stop me-2"></i>Close Election
                                    </button>
                                </form>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <a href="{{ route('admin.elections.results', $election) }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-bar-chart me-2"></i>View Results
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Created</h6>
                                <small class="text-muted">{{ $election->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        @if($election->start_date)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Voting Starts</h6>
                                    <small class="text-muted">{{ $election->start_date->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                        @if($election->end_date)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Voting Ends</h6>
                                    <small class="text-muted">{{ $election->end_date->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-activity me-2"></i>
                        Recent Activity
                    </h6>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-success text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small">New candidate added</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-primary text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-pencil"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small">Election details updated</p>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item d-flex align-items-center">
                            <div class="activity-icon bg-info text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small">Election created</p>
                                <small class="text-muted">{{ $election->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: -1.3rem;
        top: 1.5rem;
        width: 2px;
        height: calc(100% - 1rem);
        background-color: #dee2e6;
    }
    
    .timeline-marker {
        position: absolute;
        left: -1.75rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #dee2e6;
    }
</style>
@endpush