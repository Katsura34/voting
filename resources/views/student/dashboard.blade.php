@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Welcome Section -->
        <div class="col-12 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="bi bi-person-circle"></i> Welcome, {{ auth()->user()->first_name }}!</h2>
                            <p class="mb-0">USN: {{ auth()->user()->usn }} | Role: {{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="bi bi-ballot" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Election Status -->
        @if($activeElection)
            <div class="col-md-8 mb-4">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-broadcast"></i> Active Election</h4>
                    </div>
                    <div class="card-body">
                        <h3>{{ $activeElection->name }}</h3>
                        <p class="text-muted mb-3">{{ $activeElection->description }}</p>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong><i class="bi bi-calendar-plus"></i> Started:</strong><br>
                                <span class="text-muted">{{ $activeElection->start_date->format('M d, Y H:i A') }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="bi bi-calendar-x"></i> Ends:</strong><br>
                                <span class="text-muted">{{ $activeElection->end_date->format('M d, Y H:i A') }}</span>
                            </div>
                        </div>
                        
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <h4 class="text-primary mb-1">{{ $activeElection->positions()->count() }}</h4>
                                    <small class="text-muted">Positions</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <h4 class="text-warning mb-1">{{ $activeElection->candidates()->count() }}</h4>
                                    <small class="text-muted">Candidates</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <h4 class="text-success mb-1">{{ $activeElection->votes()->count() }}</h4>
                                    <small class="text-muted">Total Votes</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($hasVoted)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> You have successfully cast your vote! Thank you for participating.
                            </div>
                            <button class="btn btn-success disabled" disabled>
                                <i class="bi bi-check2"></i> Vote Submitted
                            </button>
                        @else
                            <a href="{{ route('student.vote.index') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-ballot"></i> Cast Your Vote
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Voting Progress -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Voting Progress</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalStudents = \App\Models\User::where('role', 'student')->count();
                            $totalVoters = $activeElection->votes()->distinct('user_id')->count();
                            $participationRate = $totalStudents > 0 ? ($totalVoters / $totalStudents) * 100 : 0;
                        @endphp
                        
                        <div class="text-center mb-3">
                            <h3 class="text-primary">{{ number_format($participationRate, 1) }}%</h3>
                            <p class="text-muted mb-0">Participation Rate</p>
                        </div>
                        
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $participationRate }}%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between text-sm">
                            <span><strong>{{ $totalVoters }}</strong> voted</span>
                            <span><strong>{{ $totalStudents }}</strong> total</span>
                        </div>
                        
                        @if(!$hasVoted)
                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle"></i> <strong>Your vote is pending!</strong> 
                                Make sure to cast your vote before the election ends.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- No Active Election -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-3 text-muted">No Active Elections</h3>
                        <p class="text-muted">There are currently no elections open for voting. Please check back later.</p>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Election History -->
        @if($pastElections->count() > 0)
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Past Elections</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Election</th>
                                        <th>Period</th>
                                        <th>Your Participation</th>
                                        <th>Results</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pastElections as $election)
                                    <tr>
                                        <td>
                                            <strong>{{ $election->name }}</strong>
                                            @if($election->description)
                                                <br><small class="text-muted">{{ $election->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                {{ $election->start_date->format('M d') }} - {{ $election->end_date->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @php
                                                $userVoted = $election->votes()->where('user_id', auth()->id())->exists();
                                            @endphp
                                            @if($userVoted)
                                                <span class="badge bg-success"><i class="bi bi-check"></i> Voted</span>
                                            @else
                                                <span class="badge bg-secondary">Did not vote</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($election->status === 'closed')
                                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#resultsModal{{ $election->id }}">
                                                    <i class="bi bi-trophy"></i> View Results
                                                </button>
                                            @else
                                                <small class="text-muted">Pending</small>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection