@extends('layouts.app')

@section('title', 'All Candidates')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="bi bi-person-badge text-success me-2"></i>All Candidates</h3>
                <p class="text-muted mb-0">Manage all candidates across all elections</p>
            </div>
            <div>
                <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-calendar-event me-2"></i>Elections
                </a>
                <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-flag me-2"></i>Parties
                </a>
            </div>
        </div>

        @if($candidates->count() > 0)
            <!-- Candidates by Election -->
            @foreach($candidates as $electionName => $electionCandidates)
                @php
                    $election = $electionCandidates->first()->election;
                @endphp
                
                <div class="card mb-4 border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><i class="bi bi-calendar-event me-2"></i>{{ $electionName }}</h5>
                                <p class="mb-0 opacity-75">{{ $election->description }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-end">
                                    <div class="small opacity-75">{{ $electionCandidates->count() }} Candidates</div>
                                </div>
                                @if($election->status === 'active')
                                    <span class="badge bg-success fs-6"><i class="bi bi-circle-fill me-1"></i>Active</span>
                                @elseif($election->status === 'closed')
                                    <span class="badge bg-secondary fs-6"><i class="bi bi-check-circle me-1"></i>Closed</span>
                                @else
                                    <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i>Draft</span>
                                @endif
                                <a href="{{ route('admin.elections.candidates.index', $election) }}" class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Candidates by Position -->
                        @php
                            $candidatesByPosition = $electionCandidates->groupBy('position.name');
                        @endphp
                        
                        @foreach($candidatesByPosition as $positionName => $positionCandidates)
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person-circle me-2"></i>{{ $positionName }}
                                    <span class="badge bg-light text-dark ms-2">{{ $positionCandidates->count() }} candidates</span>
                                </h6>
                                
                                <div class="row g-3">
                                    @foreach($positionCandidates as $candidate)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card border candidate-card h-100">
                                            <div class="card-body p-3">
                                                <!-- Candidate Header -->
                                                <div class="d-flex align-items-start mb-2">
                                                    <div class="candidate-avatar me-3">
                                                        @if($candidate->photo)
                                                            <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                                                 alt="{{ $candidate->name }}" 
                                                                 class="rounded-circle"
                                                                 style="width: 45px; height: 45px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" 
                                                                 style="width: 45px; height: 45px; font-size: 1.2rem; font-weight: bold;">
                                                                {{ substr($candidate->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $candidate->name }}</h6>
                                                        @if($candidate->party)
                                                            <span class="badge bg-{{ $candidate->party->color }} small">
                                                                <i class="bi bi-flag me-1"></i>{{ $candidate->party->name }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary small">
                                                                <i class="bi bi-person me-1"></i>Independent
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Stats -->
                                                <div class="row text-center small">
                                                    <div class="col-6">
                                                        <div class="text-primary">
                                                            <i class="bi bi-ballot"></i>
                                                            <div class="fw-bold">{{ $candidate->votes->count() }}</div>
                                                            <div class="text-muted">Votes</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-success">
                                                            <i class="bi bi-award"></i>
                                                            <div class="fw-bold small">
                                                                @if($election->status === 'closed' && $candidate->isWinner())
                                                                    <span class="text-warning"><i class="bi bi-trophy"></i></span>
                                                                @elseif($election->status === 'active')
                                                                    <span class="text-primary">Active</span>
                                                                @else
                                                                    <span class="text-muted">Draft</span>
                                                                @endif
                                                            </div>
                                                            <div class="text-muted">Status</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="card-footer bg-white border-0 p-2">
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.elections.candidates.show', [$election, $candidate]) }}" 
                                                       class="btn btn-outline-info btn-sm flex-fill">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if($election->status === 'draft')
                                                        <a href="{{ route('admin.elections.candidates.edit', [$election, $candidate]) }}" 
                                                           class="btn btn-outline-primary btn-sm flex-fill">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="my-4">
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            <!-- Summary Cards -->
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-calendar-event text-primary fs-1"></i>
                            <h4 class="mt-2">{{ $elections->count() }}</h4>
                            <p class="text-muted mb-0">Elections</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-person-badge text-success fs-1"></i>
                            <h4 class="mt-2">{{ $candidates->flatten()->count() }}</h4>
                            <p class="text-muted mb-0">Total Candidates</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-flag text-info fs-1"></i>
                            <h4 class="mt-2">{{ $candidates->flatten()->where('party_id', '!=', null)->count() }}</h4>
                            <p class="text-muted mb-0">Party Affiliated</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <i class="bi bi-person text-warning fs-1"></i>
                            <h4 class="mt-2">{{ $candidates->flatten()->where('party_id', null)->count() }}</h4>
                            <p class="text-muted mb-0">Independent</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-person-badge display-1 text-muted"></i>
                </div>
                <h4 class="text-muted mb-2">No Candidates Found</h4>
                <p class="text-muted mb-4">Create an election and add candidates to get started.</p>
                <a href="{{ route('admin.elections.create') }}" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle me-2"></i>Create Election
                </a>
                <a href="{{ route('admin.parties.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-flag me-2"></i>Create Party
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.candidate-card {
    transition: all 0.2s ease;
    border-color: #e5e7eb !important;
}

.candidate-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    border-color: #667eea !important;
}

.btn {
    transition: all 0.2s ease;
    border-radius: 0.375rem;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.7em;
    border-radius: 0.375rem;
}
</style>
@endpush