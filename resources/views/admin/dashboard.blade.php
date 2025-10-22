@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="sidebar p-3">
                <h6 class="text-muted mb-3">ADMIN PANEL</h6>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.parties.index') }}">
                            <i class="bi bi-people"></i> Parties ({{ $totalParties }}/2)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.elections.index') }}">
                            <i class="bi bi-calendar-event"></i> Elections ({{ $totalElections }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.analytics') }}">
                            <i class="bi bi-graph-up"></i> Analytics
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-speedometer2 text-primary"></i> Admin Dashboard</h2>
                <span class="badge bg-primary">{{ now()->format('M d, Y') }}</span>
            </div>
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                            <h3 class="mt-2">{{ $totalParties }}</h3>
                            <p class="mb-0">Political Parties</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <i class="bi bi-calendar-event" style="font-size: 2rem;"></i>
                            <h3 class="mt-2">{{ $totalElections }}</h3>
                            <p class="mb-0">Total Elections</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card text-center bg-warning text-white">
                        <div class="card-body">
                            <i class="bi bi-person-check" style="font-size: 2rem;"></i>
                            <h3 class="mt-2">{{ $totalCandidates }}</h3>
                            <p class="mb-0">Total Candidates</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card text-center bg-info text-white">
                        <div class="card-body">
                            <i class="bi bi-ballot" style="font-size: 2rem;"></i>
                            <h3 class="mt-2">{{ $totalVotes }}</h3>
                            <p class="mb-0">Total Votes</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Election Status -->
            @if($activeElection)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-broadcast"></i> Active Election</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4>{{ $activeElection->name }}</h4>
                                    <p class="text-muted mb-2">{{ $activeElection->description }}</p>
                                    <p class="mb-0">
                                        <strong>Period:</strong> {{ $activeElection->start_date->format('M d, Y H:i') }} - {{ $activeElection->end_date->format('M d, Y H:i') }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('admin.elections.show', $activeElection->id) }}" class="btn btn-success">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                    <a href="{{ route('admin.elections.results', $activeElection->id) }}" class="btn btn-outline-success">
                                        <i class="bi bi-graph-up"></i> Live Results
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> No active elections. <a href="{{ route('admin.elections.create') }}">Create a new election</a> to get started.
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Recent Elections -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Elections</h5>
                        </div>
                        <div class="card-body">
                            @if($recentElections->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Period</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentElections as $election)
                                            <tr>
                                                <td>
                                                    <strong>{{ $election->name }}</strong>
                                                    <br><small class="text-muted">{{ $election->description }}</small>
                                                </td>
                                                <td>
                                                    @if($election->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($election->status === 'upcoming')
                                                        <span class="badge bg-warning">Upcoming</span>
                                                    @else
                                                        <span class="badge bg-secondary">Closed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>
                                                        {{ $election->start_date->format('M d') }} - {{ $election->end_date->format('M d, Y') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if($election->status === 'closed')
                                                        <a href="{{ route('admin.elections.results', $election->id) }}" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-graph-up"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-3">No elections created yet.</p>
                                    <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus"></i> Create First Election
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- System Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-gear"></i> System Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Political Parties</span>
                                    <span class="badge bg-{{ $totalParties == 2 ? 'success' : 'warning' }}">{{ $totalParties }}/2</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" style="width: {{ ($totalParties/2)*100 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Active Elections</span>
                                    <span class="badge bg-{{ $activeElection ? 'success' : 'secondary' }}">{{ $activeElection ? '1' : '0' }}</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Total Students</span>
                                    <span class="badge bg-info">{{ $totalStudents }}</span>
                                </div>
                            </div>
                            
                            @if($totalParties < 2)
                                <div class="alert alert-warning alert-sm">
                                    <i class="bi bi-exclamation-triangle"></i> Create {{ 2 - $totalParties }} more {{ Str::plural('party', 2 - $totalParties) }} to start elections.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection