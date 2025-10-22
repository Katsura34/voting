@extends('layouts.admin')

@section('title', 'Create Party')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Create New Party</h1>
            <p class="page-subtitle mb-0">Add a new political party or organization</p>
        </div>
        <div>
            <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Parties
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
                        Party Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.parties.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="name" class="form-label">Party Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="e.g., Progressive Student Party"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="abbreviation" class="form-label">Abbreviation</label>
                                <input type="text" 
                                       class="form-control @error('abbreviation') is-invalid @enderror" 
                                       id="abbreviation" 
                                       name="abbreviation" 
                                       value="{{ old('abbreviation') }}" 
                                       placeholder="e.g., PSP">
                                @error('abbreviation')
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
                                      placeholder="Describe the party's mission, values, and goals...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="logo" class="form-label">Party Logo</label>
                                <input type="file" 
                                       class="form-control @error('logo') is-invalid @enderror" 
                                       id="logo" 
                                       name="logo"
                                       accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload a logo image (max 2MB)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">Party Color</label>
                                <input type="color" 
                                       class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" 
                                       name="color" 
                                       value="{{ old('color', '#3b82f6') }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Choose a representative color</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="founded_date" class="form-label">Founded Date</label>
                                <input type="date" 
                                       class="form-control @error('founded_date') is-invalid @enderror" 
                                       id="founded_date" 
                                       name="founded_date" 
                                       value="{{ old('founded_date') }}">
                                @error('founded_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Party
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
                        Party Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Party Name</h6>
                        <small class="text-muted">Choose a clear, memorable name that represents your party's identity.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Logo</h6>
                        <small class="text-muted">Upload a high-quality logo that will be displayed on ballots and campaign materials.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Description</h6>
                        <small class="text-muted">Provide a clear description of your party's mission and platform.</small>
                    </div>
                    
                    <div>
                        <h6 class="text-primary">Color</h6>
                        <small class="text-muted">Select a color that will be used to represent your party in charts and displays.</small>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-eye me-2"></i>
                        Party Preview
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div id="party-preview">
                        <div id="preview-logo" class="bg-primary rounded d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-people text-white"></i>
                        </div>
                        <h6 id="preview-name" class="text-muted">Party name will appear here</h6>
                        <small id="preview-abbreviation" class="text-muted d-block mb-2">Abbreviation</small>
                        <p id="preview-description" class="text-muted small">Description will appear here</p>
                        <span id="preview-status" class="badge bg-success">Active</span>
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
        const abbreviationInput = document.getElementById('abbreviation');
        const descriptionInput = document.getElementById('description');
        const colorInput = document.getElementById('color');
        const statusInput = document.getElementById('status');
        const logoInput = document.getElementById('logo');

        function updatePreview() {
            document.getElementById('preview-name').textContent = nameInput.value || 'Party name will appear here';
            document.getElementById('preview-abbreviation').textContent = abbreviationInput.value || 'Abbreviation';
            document.getElementById('preview-description').textContent = descriptionInput.value || 'Description will appear here';
            
            const previewLogo = document.getElementById('preview-logo');
            previewLogo.style.backgroundColor = colorInput.value;
            
            const statusBadge = document.getElementById('preview-status');
            statusBadge.textContent = statusInput.value;
            statusBadge.className = `badge ${statusInput.value === 'active' ? 'bg-success' : 'bg-secondary'}`;
        }

        // Logo preview
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewLogo = document.getElementById('preview-logo');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewLogo.innerHTML = `<img src="${e.target.result}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">`;
                };
                reader.readAsDataURL(file);
            } else {
                previewLogo.innerHTML = '<i class="bi bi-people text-white"></i>';
            }
        });

        [nameInput, abbreviationInput, descriptionInput, colorInput, statusInput].forEach(input => {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        });
    });
</script>
@endpush