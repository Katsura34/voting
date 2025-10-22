@extends('layouts.app')

@section('title', 'Global Candidates Management')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header with Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="bi bi-person-badge text-success me-2"></i>Candidates Management</h3>
                <p class="text-muted mb-0">Manage all candidates across elections - Add, assign to positions, and organize by groups</p>
            </div>
            <div>
                <a href="{{ route('admin.candidates.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Add New Candidate
                </a>
            </div>
        </div>

        @if($candidates->count() > 0)
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-primary mb-2">
                                <i class="bi bi-people fs-2"></i>
                            </div>
                            <h4 class="mb-0">{{ $candidates->flatten()->count() }}</h4>
                            <p class="text-muted mb-0">Total Candidates</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-info mb-2">
                                <i class="bi bi-calendar-event fs-2"></i>
                            </div>
                            <h4 class="mb-0">{{ $elections->count() }}</h4>
                            <p class="text-muted mb-0">Elections</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-warning mb-2">
                                <i class="bi bi-trophy fs-2"></i>
                            </div>
                            <h4 class="mb-0">{{ $elections->where('status', 'active')->count() }}</h4>
                            <p class="text-muted mb-0">Active Elections</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-success mb-2">
                                <i class="bi bi-flag fs-2"></i>
                            </div>
                            <h4 class="mb-0">{{ $candidates->flatten()->where('party_id', '!=', null)->count() }}</h4>
                            <p class="text-muted mb-0">Party Affiliated</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidates by Election -->
            @foreach($candidates as $electionName => $electionCandidates)
                @php
                    $election = $electionCandidates->first()->election;
                    $candidatesByPosition = $electionCandidates->groupBy('position.name');
                @endphp
                
                <div class="card mb-4 border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">
                                    <i class="bi bi-calendar-event text-primary me-2"></i>{{ $electionName }}
                                </h5>
                                <p class="text-muted small mb-0">{{ $election->description }}</p>
                            </div>
                            <div>
                                @if($election->status === 'active')
                                    <span class="badge bg-success fs-6"><i class="bi bi-circle-fill me-1"></i>Active</span>
                                @elseif($election->status === 'closed')
                                    <span class="badge bg-secondary fs-6"><i class="bi bi-check-circle me-1"></i>Closed</span>
                                @else
                                    <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i>Draft</span>
                                @endif
                                <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="bi bi-eye me-1"></i>View Election
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @foreach($candidatesByPosition as $positionName => $positionCandidates)
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person-circle me-2"></i>{{ $positionName }}
                                    <span class="badge bg-info ms-2">{{ $positionCandidates->count() }} candidate(s)</span>
                                </h6>
                                
                                <div class="row g-3">
                                    @foreach($positionCandidates as $candidate)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border candidate-card">
                                            <div class="card-body p-3">
                                                <!-- Candidate Header -->
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="candidate-avatar me-3">
                                                        @if($candidate->photo)
                                                            <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                                                 alt="{{ $candidate->name }}" 
                                                                 class="rounded-circle"
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" 
                                                                 style="width: 50px; height: 50px; font-size: 1.2rem; font-weight: bold;">
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
                                                
                                                <!-- Candidate Bio -->
                                                @if($candidate->bio)
                                                    <p class="text-muted small mb-2">{{ Str::limit($candidate->bio, 80) }}</p>
                                                @else
                                                    <p class="text-muted small mb-2 fst-italic">No biography provided</p>
                                                @endif
                                                
                                                <!-- Votes Count (if election is closed) -->
                                                @if($election->status === 'closed')
                                                    <div class="text-center">
                                                        <div class="text-primary">
                                                            <i class="bi bi-ballot"></i>
                                                            <div class="small text-muted">Votes</div>
                                                            <div class="fw-bold">{{ $candidate->votes->count() }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="card-footer bg-white border-0 p-2">
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.candidates.show', $candidate) }}" 
                                                       class="btn btn-outline-info btn-sm flex-fill">
                                                        <i class="bi bi-eye me-1"></i>View
                                                    </a>
                                                    @if($election->status === 'draft')
                                                        <a href="{{ route('admin.candidates.edit', $candidate) }}" 
                                                           class="btn btn-outline-primary btn-sm flex-fill">
                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm flex-fill" 
                                                                onclick="confirmDelete('{{ $candidate->name }}', {{ $candidate->id }})">
                                                            <i class="bi bi-trash me-1"></i>Delete
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            <!-- Hidden Delete Forms -->
            @foreach($candidates->flatten() as $candidate)
                <form method="POST" 
                      action="{{ route('admin.candidates.destroy', $candidate) }}" 
                      id="deleteForm{{ $candidate->id }}" 
                      style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-person-badge display-1 text-muted"></i>
                </div>
                <h4 class="text-muted mb-2">No Candidates Found</h4>
                <p class="text-muted mb-4">Start by adding your first candidate to the system.</p>
                <a href="{{ route('admin.candidates.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Add Your First Candidate
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.candidate-card {
    transition: all 0.3s ease;
    border-color: #e5e7eb !important;
}

.candidate-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    border-color: #667eea !important;
}

.btn {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline-info:hover {
    background: #0dcaf0;
    border-color: #0dcaf0;
    box-shadow: 0 4px 12px rgba(13, 202, 240, 0.4);
}

.btn-outline-danger:hover {
    background: #dc3545;
    border-color: #dc3545;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.badge {
    font-size: 0.75em;
    border-radius: 0.5rem;
}

.card {
    border-radius: 1rem;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

.candidate-avatar img {
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(candidateName, candidateId) {
        if (confirm(`Are you sure you want to delete candidate "${candidateName}"? This action cannot be undone.`)) {
            document.getElementById('deleteForm' + candidateId).submit();
        }
    }
</script>
@endpush