@extends('layouts.app')

@section('title', 'Vote - ' . $activeElection->name)

@section('content')
<div class="text-center mb-4">
    <h1 class="h2"><i class="bi bi-ballot me-2"></i>{{ $activeElection->name }}</h1>
    <p class="lead text-muted">{{ $activeElection->description }}</p>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Important:</strong> You can vote for ONE candidate per position. Once submitted, votes cannot be changed.
    </div>
</div>

<form action="{{ route('student.vote.store') }}" method="POST" id="votingForm">
    @csrf
    
    @foreach($activeElection->positions as $position)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>{{ $position->name }}
                    @if($position->description)
                        <small class="d-block text-white-50 mt-1">{{ $position->description }}</small>
                    @endif
                </h4>
            </div>
            <div class="card-body">
                @if($position->candidates->count() > 0)
                    <div class="row">
                        @foreach($position->candidates as $candidate)
                            <div class="col-md-6 mb-3">
                                <div class="card candidate-card h-100" style="cursor: pointer;" 
                                     onclick="selectCandidate({{ $position->id }}, {{ $candidate->id }}, this)">
                                    <div class="card-body text-center">
                                        @if($candidate->photo)
                                            <img src="{{ Storage::url($candidate->photo) }}" 
                                                 class="rounded-circle mb-3" width="100" height="100" 
                                                 style="object-fit: cover;" alt="{{ $candidate->name }}">
                                        @else
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                 style="width: 100px; height: 100px;">
                                                <i class="bi bi-person-fill text-muted" style="font-size: 2.5rem;"></i>
                                            </div>
                                        @endif
                                        
                                        <h5 class="card-title mb-2">{{ $candidate->name }}</h5>
                                        
                                        <div class="mb-3">
                                            <span class="badge px-3 py-2" style="background-color: {{ $candidate->party->color }}; font-size: 0.9rem;">
                                                {{ $candidate->party->name }}
                                            </span>
                                        </div>
                                        
                                        @if($candidate->bio)
                                            <p class="text-muted small mb-2">{{ Str::limit($candidate->bio, 100) }}</p>
                                        @endif
                                        
                                        @if($candidate->platform)
                                            <div class="text-start">
                                                <small class="fw-bold">Platform:</small>
                                                <small class="d-block text-muted">{{ Str::limit($candidate->platform, 150) }}</small>
                                            </div>
                                        @endif
                                        
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="radio" 
                                                   name="votes[{{ $position->id }}]" 
                                                   value="{{ $candidate->id }}" 
                                                   id="candidate_{{ $candidate->id }}" required>
                                            <label class="form-check-label fw-bold" for="candidate_{{ $candidate->id }}">
                                                Select {{ $candidate->name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No candidates available for this position.</p>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
    
    <!-- Voting Summary -->
    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-check2-square me-2"></i>Review Your Votes</h5>
        </div>
        <div class="card-body">
            <div id="votingSummary">
                <p class="text-muted">Please select candidates for all positions above.</p>
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="confirmVote" required>
                <label class="form-check-label" for="confirmVote">
                    <strong>I confirm that I have reviewed my selections and understand that votes cannot be changed once submitted.</strong>
                </label>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary me-md-2">
                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                </a>
                <button type="submit" class="btn btn-success btn-lg" id="submitVote" disabled>
                    <i class="bi bi-ballot-fill me-2"></i>Submit My Votes
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
.candidate-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.candidate-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.candidate-card.selected {
    border-color: #198754;
    box-shadow: 0 8px 25px rgba(25,135,84,0.3);
}

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}
</style>
@endpush

@push('scripts')
<script>
function selectCandidate(positionId, candidateId, cardElement) {
    // Remove selected class from all cards in this position
    const allCards = document.querySelectorAll('.candidate-card');
    const positionCards = document.querySelectorAll(`input[name="votes[${positionId}]"]`);
    
    positionCards.forEach(radio => {
        radio.closest('.candidate-card').classList.remove('selected');
    });
    
    // Add selected class to clicked card
    cardElement.classList.add('selected');
    
    // Check the radio button
    document.getElementById(`candidate_${candidateId}`).checked = true;
    
    // Update voting summary
    updateVotingSummary();
}

function updateVotingSummary() {
    const summaryDiv = document.getElementById('votingSummary');
    const submitButton = document.getElementById('submitVote');
    const confirmCheckbox = document.getElementById('confirmVote');
    
    const selectedVotes = document.querySelectorAll('input[type="radio"]:checked');
    const totalPositions = {{ $activeElection->positions->count() }};
    
    if (selectedVotes.length === totalPositions) {
        let summaryHTML = '<h6>Your Selections:</h6>';
        
        selectedVotes.forEach(vote => {
            const candidateCard = vote.closest('.candidate-card');
            const candidateName = candidateCard.querySelector('.card-title').textContent;
            const partyBadge = candidateCard.querySelector('.badge');
            const partyName = partyBadge.textContent;
            const partyColor = partyBadge.style.backgroundColor;
            const positionName = vote.closest('.card').querySelector('.card-header h4').textContent.split(' ')[0];
            
            summaryHTML += `
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <strong>${positionName}:</strong> ${candidateName}
                    </div>
                    <div>
                        <span class="badge" style="background-color: ${partyColor}">${partyName}</span>
                    </div>
                </div>
            `;
        });
        
        summaryDiv.innerHTML = summaryHTML;
        
        // Enable submit button when all positions are selected and confirmed
        confirmCheckbox.addEventListener('change', function() {
            submitButton.disabled = !this.checked;
        });
        
    } else {
        summaryDiv.innerHTML = `<p class="text-muted">Please select candidates for all ${totalPositions} positions (${selectedVotes.length}/${totalPositions} selected).</p>`;
        submitButton.disabled = true;
        confirmCheckbox.checked = false;
    }
}

// Confirmation before submission
document.getElementById('votingForm').addEventListener('submit', function(e) {
    if (!confirm('Are you sure you want to submit your votes? This action cannot be undone.')) {
        e.preventDefault();
    }
});

// Initialize summary on page load
document.addEventListener('DOMContentLoaded', updateVotingSummary);
</script>
@endpush