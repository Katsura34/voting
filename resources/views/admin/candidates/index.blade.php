@extends('layouts.app')

@section('title', 'Candidates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h3"><i class="bi bi-people me-2"></i>Candidates</h1>
  <a href="{{ route('admin.elections.candidates.create', $election) }}" class="btn btn-primary"><i class="bi bi-plus"></i> Add Candidate</a>
</div>

@forelse($candidates as $positionName => $items)
  <div class="card mb-4">
    <div class="card-header fw-semibold">{{ $positionName }}</div>
    <div class="card-body">
      <div class="row">
        @foreach($items as $candidate)
          <div class="col-md-6 col-xl-4 mb-3">
            <div class="card h-100">
              <div class="card-body text-center">
                @if($candidate->photo)
                  <img src="{{ Storage::url($candidate->photo) }}" class="rounded-circle mb-3" width="90" height="90" style="object-fit:cover" alt="{{ $candidate->name }}">
                @else
                  <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:90px;height:90px"><i class="bi bi-person" style="font-size:1.5rem"></i></div>
                @endif
                <h6 class="mb-1">{{ $candidate->name }}</h6>
                <span class="badge" style="background-color: {{ $candidate->party->color }}">{{ $candidate->party->name }}</span>
                @if($candidate->bio)<p class="mt-2 small text-muted">{{ Str::limit($candidate->bio, 100) }}</p>@endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@empty
  <div class="card">
    <div class="card-body text-center py-5 text-muted">No candidates yet.</div>
  </div>
@endforelse
@endsection