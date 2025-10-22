@extends('layouts.admin')

@section('title', 'Create Election')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Create New Election</h1>
            <p class="page-subtitle mb-0">Set up a new election with all necessary details</p>
        </div>
        <div>
            <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Elections
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Election Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.elections.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Election Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="e.g., Student Council Elections 2025"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Provide a detailed description of this election...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date & Time</label>
                                <input type="datetime-local" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">When voting should begin</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date & Time</label>
                                <input type="datetime-local" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">When voting should end</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Initial Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Election Type</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                    <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General Election</option>
                                    <option value="student_council" {{ old('type') === 'student_council' ? 'selected' : '' }}>Student Council</option>
                                    <option value="departmental" {{ old('type') === 'departmental' ? 'selected' : '' }}>Departmental</option>
                                    <option value="special" {{ old('type') === 'special' ? 'selected' : '' }}>Special Election</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Election
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Help Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Quick Tips
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Election Name</h6>
                        <small class="text-muted">Choose a clear, descriptive name that voters will easily recognize.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Timing</h6>
                        <small class="text-muted">Set appropriate start and end times. Consider time zones and student schedules.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Status</h6>
                        <small class="text-muted">Start with 'Pending' to set up positions and candidates before going 'Active'.</small>
                    </div>
                    
                    <div>
                        <h6 class="text-primary">Next Steps</h6>
                        <small class="text-muted">After creation, you'll need to add positions and candidates before activation.</small>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-eye me-2"></i>
                        Election Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div id="election-preview">
                        <h6 id="preview-name" class="text-muted">Election name will appear here</h6>
                        <p id="preview-description" class="text-muted small">Description will appear here</p>
                        <div class="small text-muted">
                            <div><strong>Start:</strong> <span id="preview-start">Not set</span></div>
                            <div><strong>End:</strong> <span id="preview-end">Not set</span></div>
                            <div><strong>Status:</strong> <span id="preview-status" class="badge bg-secondary">Pending</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Real-time preview
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const descInput = document.getElementById('description');
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const statusInput = document.getElementById('status');

        function updatePreview() {
            document.getElementById('preview-name').textContent = nameInput.value || 'Election name will appear here';
            document.getElementById('preview-description').textContent = descInput.value || 'Description will appear here';
            document.getElementById('preview-start').textContent = startInput.value ? new Date(startInput.value).toLocaleString() : 'Not set';
            document.getElementById('preview-end').textContent = endInput.value ? new Date(endInput.value).toLocaleString() : 'Not set';
            
            const statusBadge = document.getElementById('preview-status');
            statusBadge.textContent = statusInput.value;
            statusBadge.className = `badge ${statusInput.value === 'active' ? 'bg-success' : 'bg-secondary'}`;
        }

        [nameInput, descInput, startInput, endInput, statusInput].forEach(input => {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        });
    });
</script>
@endpush