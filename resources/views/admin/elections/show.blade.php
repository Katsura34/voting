@extends('layouts.app')

@section('title', $election->name)

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Election Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="bi bi-calendar-event"></i> {{ $election->name }}</h3>
                        @if($election->status === 'active')
                            <span class="badge bg-success fs-6">ACTIVE</span>
                        @elseif($election->status === 'upcoming')
                            <span class="badge bg-warning fs-6">UPCOMING</span>
                        @else
                            <span class="badge bg-secondary fs-6">CLOSED</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($election->description)
                        <p class="lead">{{ $election->description }}</p>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="bi bi-calendar-plus"></i> Start:</strong> {{ $election->start_date->format('M d, Y H:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="bi bi-calendar-x"></i> End:</strong> {{ $election->end_date->format('M d, Y H:i A') }}
                        </div>
                    </div>
                    
                    <div class="row mt-3 text-center">
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-primary mb-1">{{ $election->positions()->count() }}</h4>
                                <small class="text-muted">Positions</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-warning mb-1">{{ $election->candidates()->count() }}</h4>
                                <small class="text-muted">Candidates</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-success mb-1">{{ $election->votes()->count() }}</h4>
                                <small class="text-muted">Votes Cast</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-info mb-1">{{ $election->votes()->distinct('user_id')->count() }}</h4>
                                <small class="text-muted">Voters</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Positions Management -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-award"></i> Election Positions</h5>
                        @if($election->status === 'upcoming')
                            <a href="{{ route('admin.elections.positions.create', $election->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i> Add Position
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($election->positions()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Description</th>
                                        <th>Candidates</th>
                                        <th>Votes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($election->positions as $position)
                                    <tr>
                                        <td>
                                            <strong>{{ $position->name }}</strong>
                                            <br><small class="text-muted">Order: {{ $position->order }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($position->description, 50) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $position->candidates()->count() }}/2</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $position->votes()->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.elections.positions.show', [$election->id, $position->id]) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($election->status === 'upcoming')
                                                    <a href="{{ route('admin.elections.positions.edit', [$election->id, $position->id]) }}" class="btn btn-outline-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    @if($position->candidates()->count() == 0)
                                                        <form action="{{ route('admin.elections.positions.destroy', [$election->id, $position->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this position?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-award text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No positions added yet.</p>
                            @if($election->status === 'upcoming')
                                <a href="{{ route('admin.elections.positions.create', $election->id) }}" class="btn btn-primary">
                                    <i class="bi bi-plus"></i> Add First Position
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Candidates Management -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Election Candidates</h5>
                        @if($election->status === 'upcoming' && $election->positions()->count() > 0)
                            <a href="{{ route('admin.elections.candidates.create', $election->id) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-person-plus"></i> Add Candidate
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($election->candidates()->count() > 0)
                        @foreach($election->positions as $position)
                            @if($position->candidates()->count() > 0)
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2">
                                        <i class="bi bi-award"></i> {{ $position->name }}
                                        <small class="text-muted">({{ $position->candidates()->count() }}/2 candidates)</small>
                                    </h6>
                                    <div class="row">
                                        @foreach($position->candidates as $candidate)
                                            <div class="col-md-6 mb-3">
                                                <div class="card" style="border-left: 4px solid {{ $candidate->party->color }};">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                @if($candidate->photo)
                                                                    <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->first_name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                                                @else
                                                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: {{ $candidate->party->color }}20;">
                                                                        <i class="bi bi-person-fill" style="color: {{ $candidate->party->color }};"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="mb-1">{{ $candidate->first_name }} {{ $candidate->last_name }}</h6>
                                                                <p class="text-muted mb-1">{{ $candidate->usn }}</p>
                                                                <span class="badge" style="background-color: {{ $candidate->party->color }}; color: white;">{{ $candidate->party->name }}</span>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="{{ route('admin.elections.candidates.show', [$election->id, $candidate->id]) }}" class="btn btn-outline-primary" title="View">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                    @if($election->status === 'upcoming')
                                                                        <a href="{{ route('admin.elections.candidates.edit', [$election->id, $candidate->id]) }}" class="btn btn-outline-warning" title="Edit">
                                                                            <i class="bi bi-pencil"></i>
                                                                        </a>
                                                                        <form action="{{ route('admin.elections.candidates.destroy', [$election->id, $candidate->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this candidate?')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                                                <i class="bi bi-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No candidates added yet.</p>
                            @if($election->status === 'upcoming' && $election->positions()->count() > 0)
                                <a href="{{ route('admin.elections.candidates.create', $election->id) }}" class="btn btn-success">
                                    <i class="bi bi-person-plus"></i> Add First Candidate
                                </a>
                            @elseif($election->positions()->count() == 0)
                                <p class="text-muted">Add positions first before adding candidates.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Election Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Election Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($election->status === 'upcoming')
                            <a href="{{ route('admin.elections.edit', $election->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit Election
                            </a>
                            @if($election->positions()->count() > 0 && $election->candidates()->count() > 0)
                                <form action="{{ route('admin.elections.activate', $election->id) }}" method="POST" onsubmit="return confirm('Activate this election? Students will be able to vote.')">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-play"></i> Activate Election
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-outline-secondary" disabled title="Add positions and candidates first">
                                    <i class="bi bi-exclamation-triangle"></i> Ready to Activate
                                </button>
                            @endif
                        @endif
                        
                        @if($election->status === 'active')
                            <a href="{{ route('admin.elections.results', $election->id) }}" class="btn btn-info">
                                <i class="bi bi-graph-up"></i> View Live Results
                            </a>
                            <form action="{{ route('admin.elections.close', $election->id) }}" method="POST" onsubmit="return confirm('Close this election? This will stop voting and declare winners.')">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-stop"></i> Close Election
                                </button>
                            </form>
                        @endif
                        
                        @if($election->status === 'closed')
                            <a href="{{ route('admin.elections.results', $election->id) }}" class="btn btn-success">
                                <i class="bi bi-trophy"></i> View Final Results
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Back to Elections
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.elections.positions.index', $election->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-award"></i> Manage Positions
                        </a>
                        <a href="{{ route('admin.elections.candidates.index', $election->id) }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-people"></i> Manage Candidates
                        </a>
                        @if($election->status !== 'upcoming')
                            <a href="{{ route('admin.elections.results', $election->id) }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-graph-up"></i> View Results
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection