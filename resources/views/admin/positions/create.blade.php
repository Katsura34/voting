@extends('layouts.app')

@section('title', 'Add Position')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        {{-- Election Header --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $election->name }}</h5>
                        <p class="text-muted mb-0">{{ $election->description }}</p>
                    </div>
                    <span class="badge {{ $election->status === 'active' ? 'bg-success' : ($election->status === 'closed' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                        {{ ucfirst($election->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        {{-- Add Position Form --}}
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add Position</h3>
            </div>
            <form action="{{ route('admin.elections.positions.store', $election) }}" method="POST">
                @csrf
                <div class="card-body">
                    {{-- Status Check --}}
                    @if($election->status !== 'draft')
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Notice:</strong> Positions can only be added to draft elections.
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Position Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="e.g., President, Vice President, Secretary" 
                               {{ $election->status !== 'draft' ? 'readonly' : 'required' }}>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Enter a unique position name for this election</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description <span class="text-muted">(Optional)</span></label>
                        <textarea name="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe the responsibilities of this position..."
                                  {{ $election->status !== 'draft' ? 'readonly' : '' }}>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Display Order</label>
                        <input type="number" name="order" value="{{ old('order', ($election->positions()->max('order') ?? 0) + 1) }}" 
                               class="form-control @error('order') is-invalid @enderror" 
                               min="0" step="1"
                               {{ $election->status !== 'draft' ? 'readonly' : '' }}>
                        @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Lower numbers appear first (0 = highest priority)</div>
                    </div>
                    
                    {{-- Current Positions List --}}
                    @if($election->positions()->count() > 0)
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Existing Positions ({{ $election->positions()->count() }})</h6>
                            <div class="row g-2">
                                @foreach($election->positions()->orderBy('order')->get() as $pos)
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center p-2 border rounded bg-white">
                                        <div>
                                            <strong>{{ $pos->name }}</strong>
                                            @if($pos->description)
                                                <div class="small text-muted">{{ Str::limit($pos->description, 50) }}</div>
                                            @endif
                                        </div>
                                        <div class="text-end small text-muted">
                                            <div>Order: {{ $pos->order }}</div>
                                            <div>Candidates: {{ $pos->candidates()->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Election
                    </a>
                    @if($election->status === 'draft')
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-1"></i>Add Position
                        </button>
                    @else
                        <span class="text-muted">Cannot add to {{ $election->status }} election</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
