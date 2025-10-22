@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-pencil text-warning"></i> Edit Position</h4>
                </div>
                <div class="card-body">
                    <!-- Election Info -->
                    <div class="alert alert-info">
                        <strong><i class="bi bi-calendar-event"></i> Election:</strong> {{ $election->name }}<br>
                        <strong><i class="bi bi-award"></i> Position:</strong> {{ $position->name }}
                    </div>
                    
                    <form action="{{ route('admin.elections.positions.update', [$election->id, $position->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Position Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-tag"></i> Position Name *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $position->name) }}" 
                                   placeholder="e.g., President, Vice President"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Position Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-file-text"></i> Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Brief description of the position's responsibilities">{{ old('description', $position->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Position Order -->
                        <div class="mb-3">
                            <label for="order" class="form-label">
                                <i class="bi bi-sort-numeric-up"></i> Display Order *
                            </label>
                            <input type="number" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', $position->order) }}" 
                                   min="1"
                                   required>
                            @error('order')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Lower numbers appear first (1, 2, 3...)</div>
                        </div>
                        
                        <!-- Position Status -->
                        @if($position->candidates()->count() > 0)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> 
                                This position has {{ $position->candidates()->count() }} candidate(s). 
                                Changes may affect the election structure.
                            </div>
                        @endif
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Election
                            </a>
                            <div>
                                <a href="{{ route('admin.elections.positions.show', [$election->id, $position->id]) }}" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i> View Position
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check"></i> Update Position
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection