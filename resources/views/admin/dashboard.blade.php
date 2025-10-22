@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h1>
    <div class="text-muted">
        <i class="bi bi-calendar3 me-1"></i>{{ now()->format('M d, Y') }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $stats['total_students'] }}</h5>
                        <small>Students</small>
                    </div>
                    <i class="bi bi-people-fill" style="font-size: 2rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $stats['total_parties'] }}</h5>
                        <small>Parties</small>
                    </div>
                    <i class="bi bi-flag-fill" style="font-size: 2rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $stats['total_elections'] }}</h5>
                        <small>Elections</small>
                    </div>
                    <i class="bi bi-calendar-event-fill" style="font-size: 2rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $stats['active_elections'] }}</h5>
                        <small>Active</small>
                    </div>
                    <i class="bi bi-play-circle-fill" style="font-size: 2rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $stats['total_votes'] }}</h5>
                        <small>Total Votes</small>
                    </div>
                    <i class="bi bi-ballot-fill" style="font-size: 2rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Election Results -->
@if($activeElection)
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Live Results - {{ $activeElection->name }}</h5>
            </div>
            <div class="card-body">
                @if(count($chartData) > 0)
                    @foreach($chartData as $index => $position)
                        <div class="mb-4">
                            <h6 class="fw-bold">{{ $position['position'] }}</h6>
                            <canvas id="chart-{{ $index }}" width="400" height="200"></canvas>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-4">No votes cast yet.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.elections.show', $activeElection) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Election
                    </a>
                    <a href="{{ route('admin.elections.results', $activeElection) }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-2"></i>Full Results
                    </a>
                    <form action="{{ route('admin.elections.close', $activeElection) }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to close this election?')">
                            <i class="bi bi-stop-circle me-2"></i>Close Election
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Elections -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Elections</h5>
                <a href="{{ route('admin.elections.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus me-1"></i>New Election
                </a>
            </div>
            <div class="card-body">
                @if($recentElections->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentElections as $election)
                                    <tr>
                                        <td class="fw-bold">{{ $election->name }}</td>
                                        <td>
                                            @if($election->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($election->status === 'closed')
                                                <span class="badge bg-secondary">Closed</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $election->start_date->format('M d, Y H:i') }}</td>
                                        <td>{{ $election->end_date->format('M d, Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.elections.show', $election) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No elections found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($activeElection && count($chartData) > 0)
<script>
@foreach($chartData as $index => $position)
    // Chart for {{ $position['position'] }}
    const ctx{{ $index }} = document.getElementById('chart-{{ $index }}').getContext('2d');
    new Chart(ctx{{ $index }}, {
        type: 'bar',
        data: {
            labels: @json(array_column($position['candidates'], 'name')),
            datasets: [{
                label: 'Votes',
                data: @json(array_column($position['candidates'], 'votes')),
                backgroundColor: @json(array_column($position['candidates'], 'color')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
@endforeach
</script>
@endif
@endpush