@extends('layouts.admin')

@section('title', 'Analytics')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Analytics & Reports</h1>
            <p class="page-subtitle mb-0">Comprehensive voting system analytics and insights</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" data-period="7">7 Days</button>
                <button type="button" class="btn btn-primary btn-sm" data-period="30">30 Days</button>
                <button type="button" class="btn btn-outline-primary btn-sm" data-period="90">90 Days</button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $totalVoters ?? 0 }}</h3>
                            <p class="mb-0 small">Total Registered Voters</p>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-people" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up"></i> +12% from last month
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $votingTurnout ?? '0%' }}</h3>
                            <p class="mb-0 small">Voting Turnout</p>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-percent" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up"></i> +5% from last election
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $avgVotingTime ?? '2.5m' }}</h3>
                            <p class="mb-0 small">Avg. Voting Time</p>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-clock" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="bi bi-arrow-down"></i> -15% faster
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $systemUptime ?? '99.9%' }}</h3>
                            <p class="mb-0 small">System Uptime</p>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-shield-check" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="bi bi-check"></i> Excellent performance
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Voting Trends Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Voting Trends
                    </h5>
                </div>
                <div class="card-body">
                    <div id="votingTrendsChart" style="height: 300px;">
                        <!-- Chart will be rendered here -->
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center">
                                <i class="bi bi-bar-chart" style="font-size: 3rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-2 mb-0">Chart will be rendered here</p>
                                <small class="text-muted">Integration with Chart.js pending</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Elections -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>
                        Top Performing Elections
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($topElections) && count($topElections) > 0)
                        @foreach($topElections as $index => $election)
                            <div class="d-flex align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                <div class="me-3">
                                    <span class="badge bg-primary rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $election['name'] ?? 'Election Name' }}</h6>
                                    <small class="text-muted">{{ $election['turnout'] ?? '0' }}% turnout</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> {{ $election['votes'] ?? 0 }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-bar-chart" style="font-size: 2rem; color: #dee2e6;"></i>
                            <p class="text-muted mt-2 mb-0">No data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Demographic Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart me-2"></i>
                        Voter Demographics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center mb-3">
                                <div class="progress mx-auto mb-2" style="width: 80px; height: 80px; background: conic-gradient(#007bff 0deg 180deg, #e9ecef 180deg 360deg); border-radius: 50%;">
                                    <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-white rounded-circle" style="margin: 8px;">
                                        <strong>50%</strong>
                                    </div>
                                </div>
                                <small class="text-muted">Male Voters</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center mb-3">
                                <div class="progress mx-auto mb-2" style="width: 80px; height: 80px; background: conic-gradient(#dc3545 0deg 180deg, #e9ecef 180deg 360deg); border-radius: 50%;">
                                    <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-white rounded-circle" style="margin: 8px;">
                                        <strong>50%</strong>
                                    </div>
                                </div>
                                <small class="text-muted">Female Voters</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Age Distribution</h6>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small>18-25 years</small>
                                <small>45%</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 45%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small>26-35 years</small>
                                <small>30%</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 30%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small>36+ years</small>
                                <small>25%</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-activity me-2"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-success text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1">New voter registered</p>
                                <small class="text-muted">John Doe registered 2 minutes ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-primary text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-check-square"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1">Vote cast successfully</p>
                                <small class="text-muted">Student ID: 2021001 voted 5 minutes ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-warning text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-calendar-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1">New election created</p>
                                <small class="text-muted">"Student Council 2025" created 1 hour ago</small>
                            </div>
                        </div>
                        
                        <div class="activity-item d-flex align-items-center">
                            <div class="activity-icon bg-info text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-gear"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1">System maintenance completed</p>
                                <small class="text-muted">Database optimized 2 hours ago</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            View All Activity
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-download me-2"></i>
                        Export Reports
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-excel me-2"></i>
                                    Export to Excel
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-danger">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>
                                    Export to PDF
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-success">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    Export to CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Period filter buttons
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('[data-period]').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            
            // Add active class to clicked button
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
            
            // Here you would typically make an AJAX call to update the data
            const period = this.getAttribute('data-period');
            console.log('Loading data for period:', period);
        });
    });
</script>
@endpush