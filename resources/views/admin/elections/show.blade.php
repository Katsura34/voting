@extends('layouts.app')

@section('title', 'Election Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h3"><i class="bi bi-list-check me-2"></i>{{ $election->name }}</h1>
  <div class="btn-group">
    @if($election->status==='draft')
      <a href="{{ route('admin.elections.candidates.create', $election) }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i>Add Candidate</a>
      <a href="{{ route('admin.elections.positions.create', $election) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-plus-square me-1"></i>Add Position</a>
      <form action="{{ route('admin.elections.activate', $election) }}" method="POST" class="ms-2" onsubmit="return confirm('Activate election?')">@csrf<button class="btn btn-success btn-sm"><i class="bi bi-play-fill"></i> Activate</button></form>
    @elseif($election->status==='active')
      <form action="{{ route('admin.elections.close', $election) }}" method="POST" onsubmit="return confirm('Close election and declare winners?')">@csrf<button class="btn btn-danger btn-sm"><i class="bi bi-stop-fill"></i> Close</button></form>
    @endif
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card mb-3">
      <div class="card-header fw-semibold">Description</div>
      <div class="card-body">{{ $election->description }}</div>
    </div>

    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Positions</span>
        @if($election->status==='draft')
          <a href="{{ route('admin.elections.positions.create', $election) }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Add Position</a>
        @endif
      </div>
      <div class="card-body">
        @forelse($election->positions as $position)
          <div class="border rounded p-3 mb-2">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <strong>{{ $position->name }}</strong>
                @if($position->description)<div class="text-muted small">{{ $position->description }}</div>@endif
              </div>
              <div class="text-end small text-muted">Candidates: {{ $position->candidates()->count() }}</div>
            </div>
          </div>
        @empty
          <p class="text-muted mb-0">No positions yet.</p>
        @endforelse
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Candidates</span>
        @if($election->status==='draft')
          <a href="{{ route('admin.elections.candidates.create', $election) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i> Add Candidate
          </a>
        @endif
      </div>
      <div class="card-body">
        <a href="{{ route('admin.elections.candidates.index', $election) }}" class="btn btn-outline-info">
          <i class="bi bi-people me-1"></i> View All Candidates
        </a>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header fw-semibold">Meta</div>
      <div class="card-body small">
        <div class="d-flex justify-content-between"><span>Status</span><span class="badge {{ $election->status==='active'?'bg-success':($election->status==='closed'?'bg-secondary':'bg-warning text-dark') }}">{{ ucfirst($election->status) }}</span></div>
        <div class="d-flex justify-content-between mt-2"><span>Start</span><span>{{ $election->start_date->format('M d, Y H:i') }}</span></div>
        <div class="d-flex justify-content-between mt-1"><span>End</span><span>{{ $election->end_date->format('M d, Y H:i') }}</span></div>
        <div class="d-flex justify-content-between mt-1"><span>Total Votes</span><span>{{ $election->votes()->count() }}</span></div>
      </div>
    </div>
  </div>
</div>
@endsection