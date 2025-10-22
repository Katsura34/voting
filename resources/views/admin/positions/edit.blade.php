@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    {{-- Election Context --}}
    <div class="card mb-3">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1">{{ $election->name }}</h5>
          <div class="text-muted small">{{ $election->description }}</div>
        </div>
        <span class="badge {{ $election->status === 'active' ? 'bg-success' : ($election->status === 'closed' ? 'bg-secondary' : 'bg-warning text-dark') }}">
          {{ ucfirst($election->status) }}
        </span>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Position</h3></div>
      <form action="{{ route('admin.elections.positions.update', [$election, $position]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          @if($election->status !== 'draft')
            <div class="alert alert-warning">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Only draft elections allow editing positions.
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label fw-semibold">Position Name</label>
            <input type="text" name="name" value="{{ old('name', $position->name) }}" class="form-control @error('name') is-invalid @enderror" {{ $election->status !== 'draft' ? 'readonly' : 'required' }}>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Description <span class="text-muted">(Optional)</span></label>
            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" {{ $election->status !== 'draft' ? 'readonly' : '' }}>{{ old('description', $position->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Display Order</label>
            <input type="number" name="order" value="{{ old('order', $position->order) }}" class="form-control @error('order') is-invalid @enderror" min="0" step="1" {{ $election->status !== 'draft' ? 'readonly' : '' }}>
            @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">Lower numbers appear first (0 = highest priority)</div>
          </div>

          {{-- Info: Candidate count for this position --}}
          <div class="card bg-light">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div class="fw-semibold">Current Candidates</div>
              <div class="badge bg-primary">{{ $position->candidates()->count() }}</div>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Election</a>
          @if($election->status === 'draft')
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Update Position</button>
          @else
            <span class="text-muted">Cannot edit in {{ $election->status }} status</span>
          @endif
        </div>
      </form>
    </div>
  </div>
</div>
@endsection