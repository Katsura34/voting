@extends('layouts.app')

@section('title', 'Candidates - ' . $election->name)

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
                <li class="breadcrumb-item"><a href="{{ route('admin.elections.index') }}" class="text-decoration-none">Elections</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.elections.show', $election) }}" class="text-decoration-none">{{ $election->name }}</a></li>
                <li class="breadcrumb-item active">Candidates</li>
            </ol>
        </nav>

        <!-- Header with Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="bi bi-person-badge text-success me-2"></i>Election Candidates</h3>
                <p class="text-muted mb-0">Manage candidates for this election</p>
            </div>
            <div>
                <a href="{{ route('admin.elections.positions.index', $election) }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-people me-2"></i>View Positions
                </a>
                @if($election->status === 'pending')
                    <a href="{{ route('admin.elections.candidates.create', $election) }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Add Candidate
                    </a>
                @endif
            </div>
        </div>

        @if($candidates->count() > 0)
            <!-- Candidates by Position -->
            @foreach($election->positions->sortBy('order') as $position)
                @php
                    $positionCandidates = $candidates->where('position_id', $position->id);
                @endphp
                
                <div class="card mb-4 border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-person-circle text-primary me-2"></i>{{ $position->name }}
                            </h5>
                            <div>
                                <span class="badge bg-info me-2">Order: {{ $position->order }}</span>
                                <span class="badge bg-{{ $positionCandidates->count() >= 2 ? 'success' : 'warning' }}">
                                    {{ $positionCandidates->count() }}/2 Candidates
                                </span>
                            </div>
                        </div>
                        @if($position->description)
                            <p class="text-muted small mb-0 mt-2">{{ $position->description }}</p>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        @if($positionCandidates->count() > 0)
                            <div class="row g-4">
                                @foreach($positionCandidates as $candidate)
                                <div class="col-md-6">
                                    <div class="card h-100 border candidate-card">
                                        <div class="card-body">
                                            <!-- Candidate Header -->
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="candidate-avatar me-3">
                                                    @if($candidate->photo)
                                                        <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                                             alt="{{ $candidate->name }}" 
                                                             class="rounded-circle"
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" 
                                                             style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                                                            {{ substr($candidate->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold">{{ $candidate->name }}</h6>
                                                    <div class="d-flex align-items-center mb-2">
                                                        @if($candidate->party)
                                                            <span class="badge bg-{{ $candidate->party->color }} me-2">
                                                                <i class="bi bi-flag me-1"></i>{{ $candidate->party->name }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary me-2">
                                                                <i class="bi bi-person me-1"></i>Independent
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Candidate Bio -->
                                            @if($candidate->bio)
                                                <p class="text-muted small mb-3">{{ Str::limit($candidate->bio, 120) }}</p>
                                            @else
                                                <p class="text-muted small mb-3 fst-italic">No biography provided</p>
                                            @endif
                                            
                                            <!-- Candidate Stats -->
                                            <div class="row text-center mb-3">
                                                <div class="col-6">
                                                    <div class="text-primary">
                                                        <i class="bi bi-ballot fs-5"></i>
                                                        <div class="small text-muted">Votes Received</div>
                                                        <div class="fw-bold">{{ $candidate->votes->count() }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-success">
                                                        <i class="bi bi-award fs-5"></i>
                                                        <div class="small text-muted">Status</div>
                                                        <div class="fw-bold small">
                                                            @if($election->status === 'closed' && $candidate->isWinner())
                                                                <span class="text-warning"><i class="bi bi-trophy"></i> Winner</span>
                                                            @elseif($election->status === 'active')
                                                                <span class="text-primary">Running</span>
                                                            @else
                                                                <span class="text-muted">Registered</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        @if($election->status === 'pending')
                                        <div class="card-footer bg-white border-0">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.elections.candidates.show', [$election, $candidate]) }}" 
                                                   class="btn btn-outline-info btn-sm flex-fill">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                                <a href="{{ route('admin.elections.candidates.edit', [$election, $candidate]) }}" 
                                                   class="btn btn-outline-primary btn-sm flex-fill">
                                                    <i class="bi bi-pencil me-1"></i>Edit
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm flex-fill" 
                                                        onclick="confirmDelete('{{ $candidate->name }}', {{ $candidate->id }})">
                                                    <i class="bi bi-trash me-1"></i>Delete
                                                </button>
                                            </div>
                                        </div>
                                        @else
                                        <div class="card-footer bg-white border-0">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.elections.candidates.show', [$election, $candidate]) }}" 
                                                   class="btn btn-outline-info btn-sm w-100">
                                                    <i class="bi bi-eye me-1"></i>View Details
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Add Candidate Button for Position -->
                            @if($election->status === 'pending' && $positionCandidates->count() < 2)
                                <div class="text-center mt-4">
                                    <a href="{{ route('admin.elections.candidates.create', $election) }}?position={{ $position->id }}" 
                                       class="btn btn-outline-success">
                                        <i class="bi bi-plus-circle me-2"></i>Add Candidate for {{ $position->name }}
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- Empty State for Position -->
                            <div class="text-center py-4">
                                <i class="bi bi-person-plus text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-2">No candidates for {{ $position->name }}</h6>
                                @if($election->status === 'pending')
                                    <a href="{{ route('admin.elections.candidates.create', $election) }}?position={{ $position->id }}" 
                                       class="btn btn-success mt-2">
                                        <i class="bi bi-plus-circle me-2"></i>Add First Candidate
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            
            <!-- Summary Card -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-bar-chart me-2"></i>Candidate Summary</h5>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="text-primary">
                                <i class="bi bi-people fs-2"></i>
                                <div class="h4 mt-2">{{ $election->positions->count() }}</div>
                                <div class="text-muted">Positions</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-success">
                                <i class="bi bi-person-badge fs-2"></i>
                                <div class="h4 mt-2">{{ $candidates->count() }}</div>
                                <div class="text-muted">Total Candidates</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-info">
                                <i class="bi bi-flag fs-2"></i>
                                <div class="h4 mt-2">{{ $candidates->where('party_id', '!=', null)->count() }}</div>
                                <div class="text-muted">Party Affiliated</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-warning">
                                <i class="bi bi-person fs-2"></i>
                                <div class="h4 mt-2">{{ $candidates->where('party_id', null)->count() }}</div>
                                <div class="text-muted">Independent</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Global Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-person-badge display-1 text-muted"></i>
                </div>
                <h4 class="text-muted mb-2">No Candidates Registered</h4>
                <p class="text-muted mb-4">Start by adding candidates to the election positions.</p>
                @if($election->status === 'pending')
                    <a href="{{ route('admin.elections.candidates.create', $election) }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Add Your First Candidate
                    </a>
                @endif
            </div>
        @endif
        
        <!-- Hidden Delete Forms -->
        @foreach($candidates as $candidate)
            <form method="POST" 
                  action="{{ route('admin.elections.candidates.destroy', [$election, $candidate]) }}" 
                  id="deleteForm{{ $candidate->id }}" 
                  style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
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

.btn-outline-success:hover {
    background: #198754;
    border-color: #198754;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.4);
}

.badge {
    font-size: 0.75em;
    border-radius: 0.5rem;
}

.breadcrumb-item a {
    color: #667eea;
    font-weight: 500;
}

.breadcrumb-item a:hover {
    color: #5a67d8;
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