@extends('layouts.admin')

@section('title', 'Candidates')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Candidates Management</h1>
            <p class="page-subtitle mb-0">Manage all candidates across elections</p>
        </div>
        <div>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-plus me-2"></i>Add Candidate
                </button>
                <ul class="dropdown-menu">
                    @if(isset($activeElections) && $activeElections->count() > 0)
                        @foreach($activeElections as $election)
                            <li><a class="dropdown-item" href="{{ route('admin.elections.candidates.create', $election) }}">to {{ $election->name }}</a></li>
                        @endforeach
                    @else
                        <li><span class="dropdown-item text-muted">No active elections</span></li>
                    @endif
                </ul>
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
            <div class="card stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $activeCandidates ?? 0 }}</div>
                            <div class="stats-label">Active Candidates</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-person-check" style="font-size: 2rem; opacity: 0.7;"></i>
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
                            <div class="stats-number">{{ $totalVotes ?? 0 }}</div>
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
                            <div class="stats-number">{{ $positions ?? 0 }}</div>
                            <div class="stats-label">Positions</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-list" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Candidates Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-person-lines-fill me-2"></i>
                All Candidates
            </h5>
        </div>
        <div class="card-body">
            @if(isset($candidates) && $candidates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Election</th>
                                <th>Position</th>
                                <th>Party</th>
                                <th>Votes</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($candidates as $candidate)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if(isset($candidate->photo) && $candidate->photo)
                                                    <img src="{{ $candidate->photo }}" alt="{{ $candidate->name }}" class="rounded-circle" width="40" height="40">
                                                @else
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $candidate->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $candidate->student_id ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $candidate->election->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $candidate->election->status ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $candidate->position->name ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($candidate->party) && $candidate->party)
                                            <span class="badge bg-primary">{{ $candidate->party->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">Independent</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ $candidate->votes_count ?? 0 }}</strong>
                                    </td>
                                    <td>
                                        @if(($candidate->status ?? 'active') === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-person-x" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">No Candidates Found</h4>
                    <p class="text-muted mb-4">No candidates have been added yet. Add candidates to elections to get started.</p>
                    @if(isset($activeElections) && $activeElections->count() > 0)
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-plus me-2"></i>Add First Candidate
                            </button>
                            <ul class="dropdown-menu">
                                @foreach($activeElections as $election)
                                    <li><a class="dropdown-item" href="{{ route('admin.elections.candidates.create', $election) }}">to {{ $election->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('admin.elections.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-plus me-2"></i>Create Election First
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection