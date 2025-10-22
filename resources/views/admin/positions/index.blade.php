@extends('layouts.app')

@section('title', 'Positions - ' . $election->name)

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Election Header -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="bi bi-calendar-event me-2"></i>{{ $election->name }}</h4>
                        <p class="mb-0 opacity-75">{{ $election->description }}</p>
                    </div>
                    <div class="text-end">
                        @if($election->status === 'active')
                            <span class="badge bg-success fs-6"><i class="bi bi-circle-fill me-1"></i>Active</span>
                        @elseif($election->status === 'closed')
                            <span class="badge bg-secondary fs-6"><i class="bi bi-check-circle me-1"></i>Closed</span>
                        @else
                            <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i>Pending</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.elections.index') }}">Elections</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.elections.show', $election) }}">{{ $election->name }}</a></li>
                <li class="breadcrumb-item active">Positions</li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="bi bi-people text-primary me-2"></i>Election Positions</h3>
                <p class="text-muted mb-0">Manage voting positions for this election</p>
            </div>
            <div>
                <a href="{{ route('admin.elections.elections.candidates.index', $election) }}" class="btn btn-outline-success me-2">
                    <i class="bi bi-person-badge me-1"></i>View Candidates
                </a>
                @if($election->status === 'pending')
                    <a href="{{ route('admin.elections.positions.create', $election) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Add Position
                    </a>
                @endif
            </div>
        </div>

        @if($positions->count() > 0)
            <!-- Positions Grid -->
            <div class="row">
                @foreach($positions as $position)
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 position-card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">{{ $position->name }}</h5>
                            <span class="badge bg-info">Order: {{ $position->order }}</span>
                        </div>
                        
                        <div class="card-body">
                            @if($position->description)
                                <p class="text-muted small mb-3">{{ $position->description }}</p>
                            @endif
                            
                            <!-- Position Stats -->
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="text-success">
                                        <i class="bi bi-person-badge fs-4"></i>
                                        <div class="small text-muted">Candidates</div>
                                        <div class="fw-bold">{{ $position->candidates->count() }}/2</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-primary">
                                        <i class="bi bi-ballot fs-4"></i>
                                        <div class="small text-muted">Votes</div>
                                        <div class="fw-bold">{{ $position->votes->count() }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Candidates List -->
                            @if($position->candidates->count() > 0)
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Candidates:</h6>
                                    @foreach($position->candidates as $candidate)
                                        <div class="d-flex align-items-center mb-2 p-2 bg-light rounded">
                                            <div class="bg-{{ $candidate->party->color ?? 'secondary' }} rounded-circle me-2" 
                                                 style="width: 8px; height: 8px;"></div>
                                            <small class="fw-medium">{{ $candidate->name }}</small>
                                            <small class="text-muted ms-auto">{{ $candidate->party->name ?? 'Independent' }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="bi bi-person-plus text-muted fs-1"></i>
                                    <p class="text-muted mb-0">No candidates yet</p>
                                </div>
                            @endif
                        </div>

                        @if($election->status === 'pending')
                        <div class="card-footer bg-white border-0">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('admin.elections.positions.edit', [$election, $position]) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.elections.positions.destroy', [$election, $position]) }}" 
                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this position?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Summary Stats -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-bar-chart me-2"></i>Election Summary</h5>
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="text-primary">
                                        <i class="bi bi-people fs-2"></i>
                                        <div class="h4 mt-2">{{ $positions->count() }}</div>
                                        <div class="text-muted">Total Positions</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-success">
                                        <i class="bi bi-person-badge fs-2"></i>
                                        <div class="h4 mt-2">{{ $election->candidates->count() }}</div>
                                        <div class="text-muted">Total Candidates</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-info">
                                        <i class="bi bi-ballot fs-2"></i>
                                        <div class="h4 mt-2">{{ $election->votes->count() }}</div>
                                        <div class="text-muted">Total Votes</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-warning">
                                        <i class="bi bi-percent fs-2"></i>
                                        <div class="h4 mt-2">
                                            @php
                                                $totalUsers = App\Models\User::where('role', 'student')->count();
                                                $totalVoters = $election->votes->pluck('user_id')->unique()->count();
                                                $turnout = $totalUsers > 0 ? round(($totalVoters / $totalUsers) * 100, 1) : 0;
                                            @endphp
                                            {{ $turnout }}%
                                        </div>
                                        <div class="text-muted">Voter Turnout</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-people display-1 text-muted"></i>
                </div>
                <h4 class="text-muted mb-2">No Positions Created</h4>
                <p class="text-muted mb-4">Start by adding positions like President, Vice President, Secretary, etc.</p>
                @if($election->status === 'pending')
                    <a href="{{ route('admin.elections.positions.create', $election) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Create Your First Position
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.position-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.position-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    flex: 1;
}
</style>
@endpush