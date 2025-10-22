@extends('layouts.admin')

@section('title', 'Parties')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Political Parties</h1>
            <p class="page-subtitle mb-0">Manage political parties and organizations</p>
        </div>
        <div>
            <a href="{{ route('admin.parties.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>New Party
            </a>
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
                            <div class="stats-number">{{ $parties->count() ?? 0 }}</div>
                            <div class="stats-label">Total Parties</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-people" style="font-size: 2rem; opacity: 0.7;"></i>
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
                            <div class="stats-number">{{ $activeParties ?? 0 }}</div>
                            <div class="stats-label">Active Parties</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-check-circle" style="font-size: 2rem; opacity: 0.7;"></i>
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
            <div class="card stats-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $independentCandidates ?? 0 }}</div>
                            <div class="stats-label">Independent</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-person" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Parties Grid -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-people me-2"></i>
                All Parties
            </h5>
        </div>
        <div class="card-body">
            @if(isset($parties) && $parties->count() > 0)
                <div class="row">
                    @foreach($parties as $party)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            @if(isset($party->logo) && $party->logo)
                                                <img src="{{ $party->logo }}" alt="{{ $party->name }}" class="rounded" width="50" height="50">
                                            @else
                                                <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-people text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $party->name }}</h6>
                                            <small class="text-muted">{{ $party->abbreviation ?? 'N/A' }}</small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.parties.show', $party) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.parties.edit', $party) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.parties.destroy', $party) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted small mb-3">{{ Str::limit($party->description ?? 'No description provided', 100) }}</p>
                                    
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h6 class="mb-1 text-primary">{{ $party->candidates_count ?? 0 }}</h6>
                                                <small class="text-muted">Candidates</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-1 text-success">{{ $party->votes_count ?? 0 }}</h6>
                                            <small class="text-muted">Total Votes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            Created {{ $party->created_at ? $party->created_at->diffForHumans() : 'Recently' }}
                                        </small>
                                        @if(($party->status ?? 'active') === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">No Parties Found</h4>
                    <p class="text-muted mb-4">You haven't created any political parties yet. Get started by creating your first party.</p>
                    <a href="{{ route('admin.parties.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus me-2"></i>Create First Party
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection