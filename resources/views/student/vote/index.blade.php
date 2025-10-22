@extends('layouts.app')

@section('title', 'Cast Your Vote')

@section('content')
<div class="container mt-4">
    @if($activeElection)
        <div class="row">
            <!-- Voting Form -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-ballot"></i> {{ $activeElection->name }}</h4>
                    </div>
                    <div class="card-body">
                        @if($hasVoted)
                            <div class="alert alert-success text-center">
                                <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                                <h3 class="mt-3">Vote Already Cast!</h3>
                                <p class="mb-0">You have already participated in this election. Thank you!</p>
                            </div>
                        @else
                            <form action="{{ route('student.vote.store') }}" method="POST" id="voting-form">
                                @csrf
                                <input type="hidden" name="election_id" value="{{ $activeElection->id }}">
                                
                                @foreach($positions as $position)
                                    <div class="mb-4">
                                        <h5 class="text-primary border-bottom pb-2">
                                            <i class="bi bi-award"></i> {{ $position->name }}
                                            @if($position->description)
                                                <small class="text-muted d-block">{{ $position->description }}</small>
                                            @endif
                                        </h5>
                                        
                                        @if($position->candidates->count() > 0)
                                            <div class="row">
                                                @foreach($position->candidates as $candidate)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card candidate-card h-100" style="cursor: pointer; border: 2px solid {{ $candidate->party->color }}20;" onclick="selectCandidate({{ $position->id }}, {{ $candidate->id }}, this)">
                                                            <div class="card-body text-center">
                                                                @if($candidate->photo)
                                                                    <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->first_name }} {{ $candidate->last_name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                                @else
                                                                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: {{ $candidate->party->color }}20;">
                                                                        <i class="bi bi-person-fill" style="font-size: 2rem; color: {{ $candidate->party->color }};"></i>
                                                                    </div>
                                                                @endif
                                                                
                                                                <h5 class="mb-1">{{ $candidate->first_name }} {{ $candidate->last_name }}</h5>
                                                                <p class="text-muted mb-2">USN: {{ $candidate->usn }}</p>
                                                                
                                                                <div class="d-flex align-items-center justify-content-center mb-2">
                                                                    <div class="rounded-pill px-3 py-1" style="background-color: {{ $candidate->party->color }}; color: white; font-size: 0.85rem;">
                                                                        <i class="bi bi-people-fill"></i> {{ $candidate->party->name }}
                                                                    </div>
                                                                </div>
                                                                
                                                                @if($candidate->bio)
                                                                    <p class="text-muted small">{{ Str::limit($candidate->bio, 100) }}</p>
                                                                @endif
                                                                
                                                                <div class="form-check mt-3">
                                                                    <input class="form-check-input" type="radio" name="votes[{{ $position->id }}]" value="{{ $candidate->id }}" id="candidate_{{ $candidate->id }}" required>
                                                                    <label class="form-check-label fw-bold" for="candidate_{{ $candidate->id }}">
                                                                        Vote for {{ $candidate->first_name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle"></i> No candidates available for this position.
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                
                                @if($positions->count() > 0 && $positions->sum(function($p) { return $p->candidates->count(); }) > 0)
                                    <!-- Voting Confirmation -->
                                    <div class="card bg-light mt-4">
                                        <div class="card-body">
                                            <h5><i class="bi bi-shield-check"></i> Voting Confirmation</h5>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="confirm-vote" required>
                                                <label class="form-check-label" for="confirm-vote">
                                                    I confirm that I have made my choices carefully and understand that my vote cannot be changed once submitted.
                                                </label>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                                                </a>
                                                <button type="submit" class="btn btn-success btn-lg" id="submit-vote-btn" disabled>
                                                    <i class="bi bi-send"></i> Submit My Vote
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Election Info Sidebar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Election Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Election:</strong> {{ $activeElection->name }}</p>
                        @if($activeElection->description)
                            <p><strong>Description:</strong> {{ $activeElection->description }}</p>
                        @endif
                        <p><strong>Ends:</strong> {{ $activeElection->end_date->format('M d, Y H:i A') }}</p>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <strong>Voting Statistics:</strong>
                            <ul class="list-unstyled mt-2">
                                <li><i class="bi bi-award"></i> {{ $positions->count() }} {{ Str::plural('Position', $positions->count()) }}</li>
                                <li><i class="bi bi-person-check"></i> {{ $positions->sum(function($p) { return $p->candidates->count(); }) }} {{ Str::plural('Candidate', $positions->sum(function($p) { return $p->candidates->count(); })) }}</li>
                                <li><i class="bi bi-ballot"></i> {{ $activeElection->votes()->count() }} {{ Str::plural('Vote', $activeElection->votes()->count()) }} cast</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-shield-exclamation"></i> Voting Guidelines</h5>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>You can vote for <strong>one candidate per position</strong></li>
                            <li>You must vote for <strong>all positions</strong> to submit</li>
                            <li>Your vote is <strong>secret and secure</strong></li>
                            <li>Once submitted, <strong>votes cannot be changed</strong></li>
                            <li>Results will be available after the election closes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-3 text-muted">No Active Election</h3>
                        <p class="text-muted">There is currently no election open for voting. Please check back later.</p>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function selectCandidate(positionId, candidateId, cardElement) {
        // Remove selection from other cards in the same position
        const positionCards = document.querySelectorAll(`input[name="votes[${positionId}]"]`).forEach(function(input) {
            input.closest('.card').style.borderColor = input.closest('.card').style.borderColor.replace('2px solid', '2px solid');
            input.closest('.card').classList.remove('bg-light');
        });
        
        // Select the clicked candidate
        document.getElementById(`candidate_${candidateId}`).checked = true;
        cardElement.style.borderWidth = '3px';
        cardElement.classList.add('bg-light');
        
        checkFormCompletion();
    }
    
    function checkFormCompletion() {
        const positions = document.querySelectorAll('input[type="radio"][name^="votes"]');
        const selectedPositions = new Set();
        
        positions.forEach(function(radio) {
            if (radio.checked) {
                const positionId = radio.name.match(/\[(\d+)\]/)[1];
                selectedPositions.add(positionId);
            }
        });
        
        const totalPositions = {{ $positions->count() }};
        const confirmCheckbox = document.getElementById('confirm-vote');
        const submitButton = document.getElementById('submit-vote-btn');
        
        if (selectedPositions.size === totalPositions && confirmCheckbox && confirmCheckbox.checked) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }
    
    // Enable submit button when confirmation is checked
    document.getElementById('confirm-vote')?.addEventListener('change', checkFormCompletion);
    
    // Add event listeners to all radio buttons
    document.querySelectorAll('input[type="radio"][name^="votes"]').forEach(function(radio) {
        radio.addEventListener('change', checkFormCompletion);
    });
    
    // Form submission confirmation
    document.getElementById('voting-form')?.addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to submit your vote? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
</script>
@endsection