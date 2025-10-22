@extends('layouts.app')

@section('title', 'Results - ' . $election->name)

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Election Header -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            <i class="bi bi-trophy me-2"></i>{{ $election->name }} - Results
                        </h4>
                        <p class="mb-0 opacity-75">Final election results and analytics</p>
                    </div>
                    <div class="text-end">
                        <div class="h5 mb-0"><i class="bi bi-calendar-check me-1"></i>Closed</div>
                        <small class="opacity-75">{{ $election->end_date->format('M j, Y g:i A') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Election Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-people text-primary fs-1"></i>
                        <h4 class="mt-2">{{ $election->positions->count() }}</h4>
                        <p class="text-muted mb-0">Positions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-person-badge text-success fs-1"></i>
                        <h4 class="mt-2">{{ $election->candidates->count() }}</h4>
                        <p class="text-muted mb-0">Candidates</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-ballot text-info fs-1"></i>
                        <h4 class="mt-2">{{ $totalVotes }}</h4>
                        <p class="text-muted mb-0">Total Votes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-percent text-warning fs-1"></i>
                        <h4 class="mt-2">{{ $voterTurnout }}%</h4>
                        <p class="text-muted mb-0">Voter Turnout</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Election Winners -->
        @if($election->winners->count() > 0)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-trophy text-warning me-2"></i>Election Winners</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($election->winners as $winner)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card bg-gradient border-0 shadow-sm winner-card">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="bi bi-award text-warning fs-1"></i>
                                </div>
                                <h5 class="card-title">{{ $winner->candidate->name }}</h5>
                                <p class="text-muted mb-1">{{ $winner->position->name }}</p>
                                <p class="text-primary mb-2">{{ $winner->candidate->party->name ?? 'Independent' }}</p>
                                <div class="badge bg-success fs-6">
                                    <i class="bi bi-ballot me-1"></i>{{ $winner->vote_count }} votes
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Detailed Results by Position -->
        <div class="row">
            @foreach($election->positions->sortBy('order') as $position)
            <div class="col-xl-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-person-circle me-2"></i>{{ $position->name }}
                            <span class="badge bg-light text-dark ms-2">
                                {{ $position->votes->count() }} votes
                            </span>
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $positionVotes = $position->votes->groupBy('candidate_id');
                            $positionTotal = $position->votes->count();
                            $candidates = $position->candidates;
                        @endphp
                        
                        @if($candidates->count() > 0)
                            @foreach($candidates as $candidate)
                                @php
                                    $candidateVotes = $positionVotes->get($candidate->id, collect())->count();
                                    $percentage = $positionTotal > 0 ? ($candidateVotes / $positionTotal) * 100 : 0;
                                    $isWinner = $election->winners->where('candidate_id', $candidate->id)->first();
                                @endphp
                                
                                <div class="mb-3 {{ $isWinner ? 'winner-highlight' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            @if($isWinner)
                                                <i class="bi bi-trophy text-warning me-2"></i>
                                            @endif
                                            <strong>{{ $candidate->name }}</strong>
                                            <span class="text-muted ms-2">({{ $candidate->party->name ?? 'Independent' }})</span>
                                        </div>
                                        <div class="text-end">
                                            <strong>{{ $candidateVotes }} votes</strong>
                                            <small class="text-muted d-block">{{ number_format($percentage, 1) }}%</small>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar {{ $isWinner ? 'bg-warning' : 'bg-primary' }}" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Chart Container -->
                            <div class="mt-4">
                                <canvas id="chart-{{ $position->id }}" width="400" height="200"></canvas>
                            </div>
                        @else
                            <p class="text-muted text-center py-3">No candidates for this position</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Party Performance Summary -->
        @if($partyStats->count() > 0)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Party Performance</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($partyStats as $party => $stats)
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h6 class="mb-1">{{ $party }}</h6>
                            <div class="text-muted small mb-2">{{ $stats['candidates'] }} candidates</div>
                            <div class="h4 text-primary">{{ $stats['total_votes'] }}</div>
                            <div class="text-muted small">total votes</div>
                            <div class="badge bg-success mt-1">{{ $stats['winners'] }} winners</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="text-center mt-4">
            <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i>Back to Elections
            </a>
            <button class="btn btn-success ms-2" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>Print Results
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.winner-highlight {
    background: linear-gradient(45deg, #fff3cd, #ffffff);
    border-radius: 8px;
    padding: 10px;
    border-left: 4px solid #ffc107;
}

.winner-card {
    background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
    border: 2px solid #ffc107 !important;
}

@media print {
    .btn, .navbar, .card-footer {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Generate charts for each position
@foreach($election->positions as $position)
    @php
        $positionVotes = $position->votes->groupBy('candidate_id');
        $chartData = [];
        $chartLabels = [];
        $chartColors = [];
        
        foreach($position->candidates as $candidate) {
            $votes = $positionVotes->get($candidate->id, collect())->count();
            $chartData[] = $votes;
            $chartLabels[] = $candidate->name;
            $chartColors[] = $election->winners->where('candidate_id', $candidate->id)->first() ? '#ffc107' : '#0d6efd';
        }
    @endphp
    
    {
        const ctx{{ $position->id }} = document.getElementById('chart-{{ $position->id }}').getContext('2d');
        new Chart(ctx{{ $position->id }}, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: {!! json_encode($chartColors) !!},
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' votes (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
@endforeach
</script>
@endpush