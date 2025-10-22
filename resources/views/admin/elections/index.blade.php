@extends('layouts.app')

@section('title', 'Elections')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2"><i class="bi bi-calendar-event me-2"></i>Elections</h1>
    <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>New Election
    </a>
</div>

@if($elections->count() === 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted mb-0">No elections found. Create one to begin.</p>
        </div>
    </div>
@else
<div class="row">
    @foreach($elections as $election)
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <strong>{{ $election->name }}</strong>
                    @if($election->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($election->status === 'closed')
                        <span class="badge bg-secondary">Closed</span>
                    @else
                        <span class="badge bg-warning text-dark">Draft</span>
                    @endif
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">{{ Str::limit($election->description, 120) }}</p>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Positions: {{ $election->positions()->count() }}</span>
                        <span>Candidates: {{ $election->candidates()->count() }}</span>
                        <span>Votes: {{ $election->votes()->count() }}</span>
                    </div>
                    <div class="mt-3 small">
                        <div><strong>Start:</strong> {{ $election->start_date->format('M d, Y H:i') }}</div>
                        <div><strong>End:</strong> {{ $election->end_date->format('M d, Y H:i') }}</div>
                    </div>
                </div>
                <div class="card-footer d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('admin.elections.candidates.index', $election) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-people me-1"></i> Candidates</a>
                    @if($election->status === 'draft')
                        <a href="{{ route('admin.elections.candidates.create', $election) }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i> Add</a>
                        <a href="{{ route('admin.elections.positions.create', $election) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-plus-square me-1"></i> Position</a>
                        <form action="{{ route('admin.elections.activate', $election) }}" method="POST" class="ms-auto" onsubmit="return confirm('Activate this election? This will close other active elections.')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Activate</button>
                        </form>
                    @elseif($election->status === 'active')
                        <a href="{{ route('admin.elections.results', $election) }}" class="btn btn-info btn-sm"><i class="bi bi-bar-chart"></i></a>
                        <form action="{{ route('admin.elections.close', $election) }}" method="POST" class="ms-auto" onsubmit="return confirm('Close this election and declare winners?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-stop-fill me-1"></i>Close</button>
                        </form>
                    @else
                        <a href="{{ route('admin.elections.results', $election) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-trophy"></i></a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
@endsection