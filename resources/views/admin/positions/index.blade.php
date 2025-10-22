@extends('layouts.admin')

@section('title', 'Positions')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ $election->name }} - Positions</h1>
            <p class="page-subtitle mb-0">Manage election positions and their configurations</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.elections.positions.create', $election) }}" class="btn btn-primary">
                    <i class="bi bi-plus me-2"></i>New Position
                </a>
                <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Election
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $positions->count() }}</div>
                            <div class="stats-label">Total Positions</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-list" style="font-size: 2rem; opacity: 0.7;"></i>
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

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $filledPositions ?? 0 }}</div>
                            <div class="stats-label">Filled Positions</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-check-circle" style="font-size: 2rem; opacity: 0.7;"></i>
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
                            <div class="stats-number">{{ $emptyPositions ?? 0 }}</div>
                            <div class="stats-label">Empty Positions</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-exclamation-circle" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Positions List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Election Positions
            </h5>
        </div>
        <div class="card-body">
            @if($positions->count() > 0)
                <div class="row">
                    @foreach($positions as $position)
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $position->name }}</h6>
                                            <small class="text-muted">{{ $position->description ?? 'No description' }}</small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.elections.positions.show', [$election, $position]) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.elections.positions.edit', [$election, $position]) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.elections.candidates.index', $election) }}?position={{ $position->id }}"><i class="bi bi-people me-2"></i>View Candidates</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.elections.positions.destroy', [$election, $position]) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="border-end">
                                                <h6 class="mb-1 text-primary">{{ $position->candidates_count ?? 0 }}</h6>
                                                <small class="text-muted">Candidates</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="border-end">
                                                <h6 class="mb-1 text-success">{{ $position->max_selections ?? 1 }}</h6>
                                                <small class="text-muted">Max Select</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="mb-1 text-info">{{ $position->votes_count ?? 0 }}</h6>
                                            <small class="text-muted">Votes</small>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if(($position->candidates_count ?? 0) > 0)
                                                <span class="badge bg-success"><i class="bi bi-check me-1"></i>Has Candidates</span>
                                            @else
                                                <span class="badge bg-warning"><i class="bi bi-exclamation me-1"></i>No Candidates</span>
                                            @endif
                                        </div>
                                        <div>
                                            <small class="text-muted">Order: {{ $position->order ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.elections.candidates.create', $election) }}?position={{ $position->id }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-plus me-1"></i>Add Candidate
                                        </a>
                                        <a href="{{ route('admin.elections.positions.show', [$election, $position]) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-list-ul" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">No Positions Found</h4>
                    <p class="text-muted mb-4">You haven't created any positions for this election yet. Positions are required before adding candidates.</p>
                    <a href="{{ route('admin.elections.positions.create', $election) }}" class="btn btn-primary">
                        <i class="bi bi-plus me-2"></i>Create First Position
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection