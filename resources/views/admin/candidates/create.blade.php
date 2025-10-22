@extends('layouts.app')

@section('title', 'Add Candidate')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-person-plus text-success"></i> Add Election Candidate</h4>
                </div>
                <div class="card-body">
                    <!-- Election Info -->
                    <div class="alert alert-info">
                        <strong><i class="bi bi-calendar-event"></i> Election:</strong> {{ $election->name }}<br>
                        <strong><i class="bi bi-people"></i> Current Candidates:</strong> {{ $election->candidates()->count() }}
                    </div>
                    
                    @if($parties->count() == 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            No political parties available. <a href="{{ route('admin.parties.create') }}">Create parties first</a>.
                        </div>
                    @endif
                    
                    @if($availablePositions->count() == 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            No positions available or all positions are full (2 candidates each).
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.elections.candidates.store', $election->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Position Selection -->
                        <div class="mb-3">
                            <label for="position_id" class="form-label">
                                <i class="bi bi-award"></i> Position *
                            </label>
                            <select class="form-select @error('position_id') is-invalid @enderror" 
                                    id="position_id" 
                                    name="position_id" 
                                    required
                                    {{ $availablePositions->count() == 0 ? 'disabled' : '' }}>
                                <option value="">Select Position</option>
                                @foreach($availablePositions as $position)
                                    <option value="{{ $position->id }}" 
                                            {{ old('position_id', request('position_id')) == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }} ({{ $position->candidates()->count() }}/2 candidates)
                                    </option>
                                @endforeach
                            </select>
                            @error('position_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Party Selection -->
                        <div class="mb-3">
                            <label for="party_id" class="form-label">
                                <i class="bi bi-people"></i> Political Party *
                            </label>
                            <select class="form-select @error('party_id') is-invalid @enderror" 
                                    id="party_id" 
                                    name="party_id" 
                                    required
                                    {{ $parties->count() == 0 ? 'disabled' : '' }}>
                                <option value="">Select Party</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->id }}" 
                                            {{ old('party_id') == $party->id ? 'selected' : '' }}
                                            data-color="{{ $party->color }}">
                                        {{ $party->name }} - {{ $party->slogan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('party_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Candidate Name -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">
                                    <i class="bi bi-person"></i> First Name *
                                </label>
                                <input type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name') }}" 
                                       placeholder="Enter first name"
                                       required>
                                @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">
                                    <i class="bi bi-person"></i> Last Name *
                                </label>
                                <input type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name') }}" 
                                       placeholder="Enter last name"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- USN -->
                        <div class="mb-3">
                            <label for="usn" class="form-label">
                                <i class="bi bi-card-text"></i> USN (University Serial Number) *
                            </label>
                            <input type="text" 
                                   class="form-control @error('usn') is-invalid @enderror" 
                                   id="usn" 
                                   name="usn" 
                                   value="{{ old('usn') }}" 
                                   placeholder="e.g., 1AB21CS001"
                                   required>
                            @error('usn')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Bio -->
                        <div class="mb-3">
                            <label for="bio" class="form-label">
                                <i class="bi bi-file-person"></i> Biography
                            </label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="4"
                                      placeholder="Brief biography, achievements, and campaign message">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Photo Upload -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">
                                <i class="bi bi-camera"></i> Candidate Photo (Optional)
                            </label>
                            <input type="file" 
                                   class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Upload an image file (JPG, PNG, GIF). Maximum size: 2MB</div>
                        </div>
                        
                        <!-- Preview Section -->
                        <div class="mb-4">
                            <h5><i class="bi bi-eye"></i> Candidate Preview</h5>
                            <div class="card" id="candidate-preview">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                                <i class="bi bi-person-fill text-muted"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1" id="preview-name">Candidate Name</h6>
                                            <p class="text-muted mb-1" id="preview-usn">USN</p>
                                            <span class="badge bg-secondary" id="preview-party">Party</span>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-2 mb-0" id="preview-position">Position: Not selected</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Election
                            </a>
                            <button type="submit" class="btn btn-success" {{ $availablePositions->count() == 0 || $parties->count() == 0 ? 'disabled' : '' }}>
                                <i class="bi bi-person-plus"></i> Add Candidate
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
    // Real-time preview updates
    function updatePreview() {
        const firstName = document.getElementById('first_name').value || 'First';
        const lastName = document.getElementById('last_name').value || 'Last';
        const usn = document.getElementById('usn').value || 'USN';
        
        document.getElementById('preview-name').textContent = `${firstName} ${lastName}`;
        document.getElementById('preview-usn').textContent = usn;
    }
    
    // Name and USN updates
    ['first_name', 'last_name', 'usn'].forEach(id => {
        document.getElementById(id).addEventListener('input', updatePreview);
    });
    
    // Party selection
    document.getElementById('party_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const color = selectedOption.getAttribute('data-color');
        const partyName = selectedOption.text.split(' - ')[0] || 'Party';
        
        const previewParty = document.getElementById('preview-party');
        previewParty.textContent = partyName;
        
        if (color) {
            previewParty.style.backgroundColor = color;
            previewParty.style.color = 'white';
            this.style.borderLeft = `4px solid ${color}`;
        } else {
            previewParty.style.backgroundColor = '#6c757d';
            previewParty.style.color = 'white';
            this.style.borderLeft = 'none';
        }
    });
    
    // Position selection
    document.getElementById('position_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const positionName = selectedOption.text.split(' (')[0] || 'Not selected';
        
        document.getElementById('preview-position').textContent = `Position: ${positionName}`;
    });
    
    // Initialize preview if values exist
    updatePreview();
    if (document.getElementById('party_id').value) {
        document.getElementById('party_id').dispatchEvent(new Event('change'));
    }
    if (document.getElementById('position_id').value) {
        document.getElementById('position_id').dispatchEvent(new Event('change'));
    }
</script>
@endsection