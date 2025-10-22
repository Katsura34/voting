@extends('layouts.app')

@section('title', $position->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <!-- Position Details -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-award"></i> {{ $position->name }}</h3>
                </div>
                <div class="card-body">
                    <!-- Election Info -->
                    <div class="alert alert-info">
                        <strong><i class="bi bi-calendar-event"></i> Election:</strong> {{ $election->name }}<br>
                        <strong><i class="bi bi-calendar-range"></i> Period:</strong> {{ $election->start_date->format('M d, Y H:i') }} - {{ $election->end_date->format('M d, Y H:i') }}
                    </div>
                    
                    @if($position->description)
                        <div class="mb-4">
                            <h5><i class="bi bi-file-text"></i> Description</h5>
                            <p class="lead">{{ $position->description }}</p>
                        </div>
                    @endif
                    
                    <div class="row text-center mb-4">
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-warning mb-1">{{ $position->candidates()->count() }}/2</h4>
                                <small class="text-muted">Candidates</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-info mb-1">{{ $position->votes()->count() }}</h4>
                                <small class="text-muted">Votes Cast</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-primary mb-1">{{ $position->order }}</h4>
                                <small class="text-muted">Display Order</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-success mb-1">{{ $position->votes()->distinct('user_id')->count() }}</h4>
                                <small class="text-muted">Voters</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Position Candidates -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Candidates for {{ $position->name }}</h5>
                        @if($election->status === 'upcoming' && $position->candidates()->count() < 2)
                            <a href="{{ route('admin.elections.candidates.create', $election->id) }}?position_id={{ $position->id }}" class="btn btn-success btn-sm">
                                <i class="bi bi-person-plus"></i> Add Candidate
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($position->candidates()->count() > 0)
                        <div class="row">
                            @foreach($position->candidates as $candidate)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100" style="border-left: 4px solid {{ $candidate->party->color }};">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    @if($candidate->photo)
                                                        <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->first_name }}" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: {{ $candidate->party->color }}20;">
                                                            <i class="bi bi-person-fill" style="color: {{ $candidate->party->color }}; font-size: 1.5rem;"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-1">{{ $candidate->first_name }} {{ $candidate->last_name }}</h5>
                                                    <p class="text-muted mb-1">{{ $candidate->usn }}</p>
                                                    <span class="badge" style="background-color: {{ $candidate->party->color }}; color: white;">
                                                        {{ $candidate->party->name }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($candidate->bio)
                                                <p class="text-muted mb-3">{{ Str::limit($candidate->bio, 100) }}</p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-info">{{ $candidate->votes()->count() }} votes</span>
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
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No Candidates Yet</h5>
                            <p class="text-muted">This position needs candidates to participate in the election.</p>
                            @if($election->status === 'upcoming')
                                <a href="{{ route('admin.elections.candidates.create', $election->id) }}?position_id={{ $position->id }}" class="btn btn-success">
                                    <i class="bi bi-person-plus"></i> Add First Candidate
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Position Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Position Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($election->status === 'upcoming')
                            <a href="{{ route('admin.elections.positions.edit', [$election->id, $position->id]) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit Position
                            </a>
                            
                            @if($position->candidates()->count() == 0)
                                <form action="{{ route('admin.elections.positions.destroy', [$election->id, $position->id]) }}" method="POST" onsubmit="return confirm('Delete this position? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Delete Position
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-outline-secondary" disabled title="Cannot delete position with candidates">
                                    <i class="bi bi-lock"></i> Protected (Has Candidates)
                                </button>
                            @endif
                        @endif
                        
                        <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Back to Election
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Position Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Position Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        <span class="text-muted">{{ $position->created_at->format('M d, Y H:i A') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <span class="text-muted">{{ $position->updated_at->format('M d, Y H:i A') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Election Status:</strong><br>
                        @if($election->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($election->status === 'upcoming')
                            <span class="badge bg-warning">Upcoming</span>
                        @else
                            <span class="badge bg-secondary">Closed</span>
                        @endif
                    </div>
                    <div class="mb-0">
                        <strong>Candidate Slots:</strong><br>
                        @if($position->candidates()->count() == 2)
                            <span class="badge bg-success">Full (2/2)</span>
                        @elseif($position->candidates()->count() == 1)
                            <span class="badge bg-warning">Half Full (1/2)</span>
                        @else
                            <span class="badge bg-danger">Empty (0/2)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection