@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle mb-0">Welcome to your admin dashboard overview</p>
        </div>
        <div>
            <span class="badge bg-primary px-3 py-2">
                <i class="bi bi-calendar3 me-1"></i>
                {{ now()->format('M d, Y') }}
            </span>
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $totalElections ?? 0 }}</div>
                            <div class="stats-label">Total Elections</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-calendar-event" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $totalCandidates ?? 0 }}</div>
                            <div class="stats-label">Total Candidates</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-person-badge" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $totalVotes ?? 0 }}</div>
                            <div class="stats-label">Total Votes</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-check2-square" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $activeElections ?? 0 }}</div>
                            <div class="stats-label">Active Elections</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-lightning" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Elections -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        Recent Elections
                    </h5>
                    <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-primary btn-sm">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentElections) && $recentElections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Election Name</th>
                                        <th>Status</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentElections as $election)
                                        <tr>
                                            <td>
                                                <strong>{{ $election->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($election->description, 50) }}</small>
                                            </td>
                                            <td>
                                                @if($election->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($election->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-secondary">Completed</span>
                                                @endif
                                            </td>
                                            <td>{{ $election->start_date?->format('M d, Y') ?? 'Not set' }}</td>
                                            <td>{{ $election->end_date?->format('M d, Y') ?? 'Not set' }}</td>
                                            <td>
                                                <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; color: #dee2e6;"></i>
                            <p class="text-muted mt-2 mb-0">No elections found</p>
                            <a href="{{ route('admin.elections.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus"></i> Create First Election
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & System Info -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-fill me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus me-2"></i>New Election
                        </a>
                        <a href="{{ route('admin.candidates.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-person-plus me-2"></i>Add Candidate
                        </a>
                        <a href="{{ route('admin.parties.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people me-2"></i>New Party
                        </a>
                        <a href="{{ route('admin.analytics') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-graph-up me-2"></i>View Analytics
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        System Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-success mb-2">
                                    <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                                </div>
                                <small class="text-muted">Database</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-success mb-2">
                                    <i class="bi bi-check-circle-fill" style="font-size: 1.5rem;"></i>
                                </div>
                                <small class="text-muted">Server</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="text-center">
                                <small class="text-muted">
                                    Last updated: {{ now()->format('H:i A') }}
                                </small>
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
    // Auto-refresh stats every 30 seconds
    setInterval(function() {
        // Add AJAX call to refresh statistics if needed
        console.log('Stats could be refreshed here');
    }, 30000);
</script>
@endpush