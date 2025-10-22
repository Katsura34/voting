@extends('layouts.app')

@section('title', 'Edit Election')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Election</h3>
            </div>
            <form action="{{ route('admin.elections.update', $election) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    {{-- Status Warning --}}
                    @if($election->status !== 'draft')
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Notice:</strong> Only draft elections can be edited. This election is currently <strong>{{ ucfirst($election->status) }}</strong>.
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" value="{{ old('name', $election->name) }}" 
                               class="form-control @error('name') is-invalid @enderror" 
                               {{ $election->status !== 'draft' ? 'readonly' : 'required' }}>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="4" 
                                  class="form-control @error('description') is-invalid @enderror"
                                  {{ $election->status !== 'draft' ? 'readonly' : 'required' }}>{{ old('description', $election->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Start Date & Time</label>
                            <input type="datetime-local" name="start_date" 
                                   value="{{ old('start_date', $election->start_date->format('Y-m-d\\TH:i')) }}" 
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   {{ $election->status !== 'draft' ? 'readonly' : 'required' }}>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">End Date & Time</label>
                            <input type="datetime-local" name="end_date" 
                                   value="{{ old('end_date', $election->end_date->format('Y-m-d\\TH:i')) }}" 
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   {{ $election->status !== 'draft' ? 'readonly' : 'required' }}>
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    {{-- Current Status Info --}}
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Current Status</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge {{ $election->status === 'active' ? 'bg-success' : ($election->status === 'closed' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                                        {{ ucfirst($election->status) }}
                                    </span>
                                </div>
                                <div class="text-end small text-muted">
                                    <div>Positions: {{ $election->positions()->count() }}</div>
                                    <div>Candidates: {{ $election->candidates()->count() }}</div>
                                    <div>Votes: {{ $election->votes()->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back
                    </a>
                    @if($election->status === 'draft')
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-1"></i>Update Election
                        </button>
                    @else
                        <span class="text-muted">Cannot edit {{ $election->status }} election</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
