@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2"><i class="bi bi-house me-2"></i>Welcome, {{ auth()->user()->first_name }}!</h1>
    <div class="text-muted">
        <i class="bi bi-calendar3 me-1"></i>{{ now()->format('M d, Y') }}
    </div>
</div>

<!-- Active Election Alert -->
@if($activeElection)
    @if(!$hasVotedInActive)
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-megaphone me-3" style="font-size: 2rem;"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">{{ $activeElection->name }} is Active!</h5>
                    <p class="mb-2">{{ $activeElection->description }}</p>
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>Voting ends: {{ $activeElection->end_date->format('M d, Y \a\t H:i') }}
                    </small>
                </div>
                <div>
                    <a href="{{ route('student.vote.index') }}" class="btn btn-primary">
                        <i class="bi bi-ballot me-1"></i>Vote Now
                    </a>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @else
        <div class="alert alert-success" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle me-3" style="font-size: 2rem;"></i>
                <div>
                    <h5 class="alert-heading mb-1">You've Successfully Voted!</h5>
                    <p class="mb-0">Thank you for participating in {{ $activeElection->name }}.</p>
                </div>
            </div>
        </div>
    @endif
@endif

<div class="row">
    <!-- Voting Status Card -->
    <div class="col-lg-8 mb-4">
        @if($activeElection)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-ballot-fill me-2"></i>{{ $activeElection->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($hasVotedInActive)
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-success">Voting Complete</h4>
                            <p class="text-muted">You have successfully cast your vote in this election.</p>
                            
                            <div class="mt-4">
                                <h6>Your Votes:</h6>
                                @foreach($userVotes as $vote)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <div>
                                            <strong>{{ $vote->position->name }}</strong>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge" style="background-color: {{ $vote->candidate->party->color }};">
                                                {{ $vote->candidate->name }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $vote->candidate->party->name }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-ballot text-primary" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">Ready to Vote</h4>
                            <p class="text-muted">{{ $activeElection->description }}</p>
                            
                            <div class="row text-start mt-4">
                                <div class="col-md-6">
                                    <strong>Voting Period:</strong><br>
                                    <small class="text-muted">
                                        {{ $activeElection->start_date->format('M d, Y H:i') }} - 
                                        {{ $activeElection->end_date->format('M d, Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <strong>Positions:</strong><br>
                                    <small class="text-muted">{{ $activeElection->positions->count() }} positions to vote for</small>
                                </div>
                            </div>
                            
                            <a href="{{ route('student.vote.index') }}" class="btn btn-primary btn-lg mt-4">
                                <i class="bi bi-ballot me-2"></i>Cast Your Vote
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">No Active Election</h4>
                    <p class="text-muted">There are currently no elections open for voting.</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Upcoming Elections -->
        @if($upcomingElections->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Upcoming Elections</h6>
                </div>
                <div class="card-body">
                    @foreach($upcomingElections as $election)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-1">{{ $election->name }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $election->start_date->format('M d, Y') }}
                                </small>
                            </div>
                            <span class="badge bg-warning text-dark">{{ $election->status }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Voting History -->
        @if($votingHistory->count() > 0)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Voting History</h6>
                </div>
                <div class="card-body">
                    @foreach($votingHistory as $vote)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-1">{{ $vote->election->name }}</h6>
                                <small class="text-muted">
                                    {{ $vote->position->name }}: {{ $vote->candidate->name }}
                                    <br>{{ $vote->created_at->format('M d, Y') }}
                                </small>
                            </div>
                            <span class="badge" style="background-color: {{ $vote->candidate->party->color }};">{{ $vote->candidate->party->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection