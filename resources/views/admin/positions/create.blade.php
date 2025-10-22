@extends('layouts.app')

@section('title', 'Add Position')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-plus text-success"></i> Add Election Position</h4>
                </div>
                <div class="card-body">
                    <!-- Election Info -->
                    <div class="alert alert-info">
                        <strong><i class="bi bi-calendar-event"></i> Election:</strong> {{ $election->name }}<br>
                        <strong><i class="bi bi-award"></i> Current Positions:</strong> {{ $election->positions()->count() }}
                    </div>
                    
                    <form action="{{ route('admin.elections.positions.store', $election->id) }}" method="POST">
                        @csrf
                        
                        <!-- Position Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-tag"></i> Position Name *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g., President, Vice President, Secretary"
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
                                      placeholder="Brief description of the position's responsibilities">{{ old('description') }}</textarea>
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
                                   value="{{ old('order', $election->positions()->max('order') + 1) }}" 
                                   min="1"
                                   required>
                            @error('order')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Lower numbers appear first (1, 2, 3...)</div>
                        </div>
                        
                        <!-- Common Position Templates -->
                        <div class="mb-4">
                            <h5><i class="bi bi-lightbulb"></i> Quick Templates</h5>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="fillPosition('President', 'Lead the student body and represent their interests')">President</button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="fillPosition('Vice President', 'Assist the President and oversee student activities')">Vice President</button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="fillPosition('Secretary', 'Maintain records and manage communications')">Secretary</button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="fillPosition('Treasurer', 'Manage student funds and financial planning')">Treasurer</button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="fillPosition('Cultural Secretary', 'Organize cultural events and activities')">Cultural Secretary</button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="fillPosition('Sports Secretary', 'Coordinate sports and physical activities')">Sports Secretary</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Election
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus"></i> Add Position
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
    function fillPosition(name, description) {
        document.getElementById('name').value = name;
        document.getElementById('description').value = description;
        
        // Update order to next available
        const currentPositions = {{ $election->positions()->count() }};
        document.getElementById('order').value = currentPositions + 1;
    }
</script>
@endsection