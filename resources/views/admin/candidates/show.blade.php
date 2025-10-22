@extends('layouts.app')

@section('title', $candidate->first_name . ' ' . $candidate->last_name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <!-- Candidate Profile -->
            <div class="card" style="border-left: 4px solid {{ $candidate->party->color }};">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="bi bi-person-circle"></i> {{ $candidate->first_name }} {{ $candidate->last_name }}</h3>
                        <span class="badge" style="background-color: {{ $candidate->party->color }}; color: white; font-size: 1rem;">
                            {{ $candidate->party->name }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Election & Position Info -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="bi bi-calendar-event"></i> Election:</strong> {{ $election->name }}
                            </div>
                            <div class="col-md-6">
                                <strong><i class="bi bi-award"></i> Position:</strong> {{ $candidate->position->name }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($candidate->photo)
                                <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->first_name }} {{ $candidate->last_name }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; background-color: {{ $candidate->party->color }}20;">
                                    <i class="bi bi-person-fill" style="font-size: 4rem; color: {{ $candidate->party->color }};"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <strong><i class="bi bi-card-text"></i> USN:</strong> {{ $candidate->usn }}
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="bi bi-people-fill"></i> Political Party:</strong>
                                <span class="badge ms-2" style="background-color: {{ $candidate->party->color }}; color: white;">
                                    {{ $candidate->party->name }}
                                </span>
                                <br><small class="text-muted">{{ $candidate->party->slogan }}</small>
                            </div>
                            
                            @if($candidate->bio)
                                <div class="mb-3">
                                    <strong><i class="bi bi-file-person"></i> Biography:</strong>
                                    <p class="mt-2">{{ $candidate->bio }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Vote Statistics -->
                    <div class="row text-center mt-4">
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-success mb-1">{{ $candidate->votes()->count() }}</h4>
                                <small class="text-muted">Total Votes</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                @php
                                    $totalPositionVotes = $candidate->position->votes()->count();
                                    $percentage = $totalPositionVotes > 0 ? ($candidate->votes()->count() / $totalPositionVotes) * 100 : 0;
                                @endphp
                                <h4 class="text-primary mb-1">{{ number_format($percentage, 1) }}%</h4>
                                <small class="text-muted">Vote Share</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                @php
                                    $positionCandidates = $candidate->position->candidates;
                                    $rank = $positionCandidates->sortByDesc(function($c) { return $c->votes()->count(); })->search(function($c) use ($candidate) { return $c->id === $candidate->id; }) + 1;
                                @endphp
                                <h4 class="text-warning mb-1">#{{ $rank }}</h4>
                                <small class="text-muted">Current Rank</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Vote Progress -->
            @if($election->status !== 'upcoming')
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Voting Progress</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalPositionVotes = $candidate->position->votes()->count();
                            $candidateVotes = $candidate->votes()->count();
                            $percentage = $totalPositionVotes > 0 ? ($candidateVotes / $totalPositionVotes) * 100 : 0;
                        @endphp
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $candidate->first_name }} {{ $candidate->last_name }}</span>
                            <span><strong>{{ $candidateVotes }}</strong> votes ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="progress mb-3" style="height: 20px;">
                            <div class="progress-bar" style="width: {{ $percentage }}%; background-color: {{ $candidate->party->color }};"></div>
                        </div>
                        
                        <!-- Opponent Comparison -->
                        @foreach($candidate->position->candidates()->where('id', '!=', $candidate->id)->get() as $opponent)
                            @php
                                $opponentVotes = $opponent->votes()->count();
                                $opponentPercentage = $totalPositionVotes > 0 ? ($opponentVotes / $totalPositionVotes) * 100 : 0;
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $opponent->first_name }} {{ $opponent->last_name }}</span>
                                <span><strong>{{ $opponentVotes }}</strong> votes ({{ number_format($opponentPercentage, 1) }}%)</span>
                            </div>
                            <div class="progress mb-3" style="height: 20px;">
                                <div class="progress-bar" style="width: {{ $opponentPercentage }}%; background-color: {{ $opponent->party->color }};"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Candidate Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Candidate Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($election->status === 'upcoming')
                            <a href="{{ route('admin.elections.candidates.edit', [$election->id, $candidate->id]) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit Candidate
                            </a>
                            
                            <form action="{{ route('admin.elections.candidates.destroy', [$election->id, $candidate->id]) }}" method="POST" onsubmit="return confirm('Delete this candidate? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Delete Candidate
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Back to Election
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Candidate Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Candidate Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Full Name:</strong><br>
                        <span class="text-muted">{{ $candidate->first_name }} {{ $candidate->last_name }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>University Serial Number:</strong><br>
                        <span class="text-muted">{{ $candidate->usn }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Political Affiliation:</strong><br>
                        <span class="badge" style="background-color: {{ $candidate->party->color }}; color: white;">
                            {{ $candidate->party->name }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Registered:</strong><br>
                        <span class="text-muted">{{ $candidate->created_at->format('M d, Y H:i A') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <span class="text-muted">{{ $candidate->updated_at->format('M d, Y H:i A') }}</span>
                    </div>
                    <div class="mb-0">
                        <strong>Voting Status:</strong><br>
                        @if($election->status === 'active')
                            <span class="badge bg-success">Currently Receiving Votes</span>
                        @elseif($election->status === 'upcoming')
                            <span class="badge bg-warning">Awaiting Election</span>
                        @else
                            <span class="badge bg-secondary">Election Completed</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection