@extends('layouts.app')

@section('title', 'Vote Confirmation')

@section('content')
<div class="text-center mb-5">
    <div class="mb-4">
        <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
    </div>
    
    <h1 class="h2 text-success mb-3">Vote Successfully Submitted!</h1>
    <p class="lead text-muted">Thank you for participating in {{ $activeElection->name }}</p>
    
    <div class="alert alert-success d-inline-block">
        <i class="bi bi-info-circle me-2"></i>
        Your vote has been recorded and cannot be changed. Results will be available after the election closes.
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Your Voting Receipt
                </h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Election:</strong><br>
                        <span class="text-muted">{{ $activeElection->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Vote Time:</strong><br>
                        <span class="text-muted">{{ now()->format('M d, Y \a\t H:i:s') }}</span>
                    </div>
                </div>
                
                <h5 class="mb-3">Your Votes:</h5>
                
                @foreach($userVotes as $vote)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h6 class="mb-0 text-primary">{{ $vote->position->name }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        @if($vote->candidate->photo)
                                            <img src="{{ Storage::url($vote->candidate->photo) }}" 
                                                 class="rounded-circle me-3" width="50" height="50" 
                                                 style="object-fit: cover;" alt="{{ $vote->candidate->name }}">
                                        @else
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center me-3"
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-person-fill text-muted" style="font-size: 1.5rem;"></i>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <h6 class="mb-1">{{ $vote->candidate->name }}</h6>
                                            <small class="text-muted">Candidate</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <span class="badge px-3 py-2" style="background-color: {{ $vote->candidate->party->color }}; font-size: 0.9rem;">
                                        {{ $vote->candidate->party->name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="text-center mt-4">
                    <div class="alert alert-info">
                        <h6 class="mb-2"><i class="bi bi-shield-check me-2"></i>Your Vote is Secure</h6>
                        <small class="text-muted">
                            Your identity is protected and your vote is anonymous. 
                            Only aggregated results will be published.
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="text-center mt-4">
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-house me-2"></i>Return to Dashboard
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-printer me-2"></i>Print Receipt
                </button>
            </div>
        </div>
        
        <!-- What's Next -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>What Happens Next?</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <i class="bi bi-clock text-primary me-3 mt-1" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6>Voting Continues</h6>
                                <small class="text-muted">Other students can continue voting until {{ $activeElection->end_date->format('M d, Y \a\t H:i') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <i class="bi bi-bar-chart text-success me-3 mt-1" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6>Results Announced</h6>
                                <small class="text-muted">Election results will be published after voting closes</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="bi bi-bell me-1"></i>
                        You'll be notified when results are available
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .btn, .navbar, footer, .card-header {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    
    .text-success {
        color: #000 !important;
    }
}
</style>
@endpush