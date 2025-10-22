@extends('layouts.app')

@section('title', 'Manage Elections')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="sidebar p-3">
                <h6 class="text-muted mb-3">ADMIN PANEL</h6>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.parties.index') }}">
                            <i class="bi bi-people"></i> Parties
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.elections.index') }}">
                            <i class="bi bi-calendar-event"></i> Elections
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-calendar-event text-primary"></i> Election Management</h2>
                <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Create Election
                </a>
            </div>
            
            @if($elections->count() > 0)
                <!-- Active Elections -->
                @php $activeElections = $elections->where('status', 'active'); @endphp
                @if($activeElections->count() > 0)
                <div class="mb-4">
                    <h4 class="text-success mb-3"><i class="bi bi-broadcast"></i> Active Elections</h4>
                    <div class="row">
                        @foreach($activeElections as $election)
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ $election->name }}</h5>
                                        <span class="badge bg-light text-success">LIVE</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">{{ $election->description }}</p>
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <strong>{{ $election->positions()->count() }}</strong>
                                            <br><small class="text-muted">Positions</small>
                                        </div>
                                        <div class="col-4">
                                            <strong>{{ $election->candidates()->count() }}</strong>
                                            <br><small class="text-muted">Candidates</small>
                                        </div>
                                        <div class="col-4">
                                            <strong>{{ $election->votes()->count() }}</strong>
                                            <br><small class="text-muted">Votes</small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.elections.results', $election->id) }}" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-graph-up"></i> Results
                                        </a>
                                        <form action="{{ route('admin.elections.close', $election->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to close this election? This action cannot be undone.')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-stop"></i> Close
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> Ends: {{ $election->end_date->format('M d, Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- All Elections Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list"></i> All Elections</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Election</th>
                                        <th>Status</th>
                                        <th>Period</th>
                                        <th>Positions</th>
                                        <th>Candidates</th>
                                        <th>Votes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($elections as $election)
                                    <tr>
                                        <td>
                                            <strong>{{ $election->name }}</strong>
                                            @if($election->description)
                                                <br><small class="text-muted">{{ Str::limit($election->description, 50) }}</small>
                                            @endif
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
                                                <strong>Start:</strong> {{ $election->start_date->format('M d, Y H:i') }}<br>
                                                <strong>End:</strong> {{ $election->end_date->format('M d, Y H:i') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $election->positions()->count() }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $election->candidates()->count() }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $election->votes()->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-outline-primary" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($election->status === 'upcoming')
                                                    <a href="{{ route('admin.elections.edit', $election->id) }}" class="btn btn-outline-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.elections.activate', $election->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to activate this election?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success" title="Activate">
                                                            <i class="bi bi-play"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($election->status === 'closed')
                                                    <a href="{{ route('admin.elections.results', $election->id) }}" class="btn btn-outline-success" title="View Results">
                                                        <i class="bi bi-graph-up"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-3 text-muted">No Elections Created</h3>
                        <p class="text-muted">Create your first election to start the voting process.</p>
                        <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Create First Election
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection