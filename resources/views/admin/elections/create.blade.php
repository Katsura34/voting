@extends('layouts.app')

@section('title', 'Create Election')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-calendar-event text-primary"></i> Create New Election</h4>
                </div>
                <div class="card-body">
                    @if($parties->count() < 2)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            You need at least 2 political parties to create an election. 
                            <a href="{{ route('admin.parties.create') }}">Create parties first</a>.
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.elections.store') }}" method="POST">
                        @csrf
                        
                        <!-- Election Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-tag"></i> Election Name *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g., Student Council Election 2025"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Election Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-file-text"></i> Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Brief description of the election purpose and scope">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Election Period -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">
                                    <i class="bi bi-calendar-plus"></i> Start Date & Time *
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date') }}"
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">
                                    <i class="bi bi-calendar-x"></i> End Date & Time *
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}"
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Election Guidelines -->
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Election Setup Process</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">After creating the election, you'll need to:</p>
                                    <ol class="mb-0">
                                        <li>Add positions (President, Vice President, Secretary, etc.)</li>
                                        <li>Add candidates for each position (maximum 2 per position)</li>
                                        <li>Activate the election to open voting</li>
                                        <li>Monitor results in real-time</li>
                                        <li>Close election and declare winners</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Elections
                            </a>
                            <button type="submit" class="btn btn-primary" {{ $parties->count() < 2 ? 'disabled' : '' }}>
                                <i class="bi bi-plus"></i> Create Election
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-update end date when start date changes
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDate = new Date(startDate.getTime() + (24 * 60 * 60 * 1000)); // Add 24 hours
        
        const endDateInput = document.getElementById('end_date');
        endDateInput.min = this.value;
        
        if (!endDateInput.value || new Date(endDateInput.value) <= startDate) {
            endDateInput.value = endDate.toISOString().slice(0, 16);
        }
    });
</script>
@endsection