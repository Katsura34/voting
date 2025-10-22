@extends('layouts.app')

@section('title', 'Edit Candidate - ' . $election->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Election Header -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><i class="bi bi-calendar-event me-2"></i>{{ $election->name }}</h5>
                        <p class="mb-0 opacity-75">{{ $election->description }}</p>
                    </div>
                    <div class="text-end">
                        @if($election->status === 'active')
                            <span class="badge bg-success fs-6"><i class="bi bi-circle-fill me-1"></i>Active</span>
                        @elseif($election->status === 'closed')
                            <span class="badge bg-secondary fs-6"><i class="bi bi-check-circle me-1"></i>Closed</span>
                        @else
                            <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i>Pending</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.elections.index') }}" class="text-decoration-none">Elections</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.elections.show', $election) }}" class="text-decoration-none">{{ $election->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.elections.elections.candidates.index', $election) }}" class="text-decoration-none">Candidates</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit {{ $candidate->name }}</li>
            </ol>
        </nav>

        <!-- Edit Candidate Card -->
        <div class="card border-0 shadow-sm" data-aos="fade-up">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-person-badge text-success me-2"></i>Edit Candidate
                    </h4>
                    <div class="text-muted small">
                        Position: <strong>{{ $candidate->position->name }}</strong>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('admin.elections.elections.candidates.update', [$election, $candidate]) }}" 
                      enctype="multipart/form-data" id="editCandidateForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Position Selection -->
                    <div class="mb-4">
                        <label for="position_id" class="form-label fw-semibold">
                            <i class="bi bi-people me-1"></i>Position <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('position_id') is-invalid @enderror" 
                                id="position_id" name="position_id" required>
                            <option value="">Select Position</option>
                            @foreach($election->positions->sortBy('order') as $position)
                                <option value="{{ $position->id }}" 
                                        {{ old('position_id', $candidate->position_id) == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                    @php
                                        $candidateCount = $position->candidates->where('id', '!=', $candidate->id)->count();
                                    @endphp
                                    @if($candidateCount >= 2)
                                        (Full - 2/2 candidates)
                                    @else
                                        ({{ $candidateCount }}/2 candidates)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Each position can have a maximum of 2 candidates.
                        </div>
                    </div>
                    
                    <!-- Party Selection -->
                    <div class="mb-4">
                        <label for="party_id" class="form-label fw-semibold">
                            <i class="bi bi-flag me-1"></i>Political Party
                        </label>
                        <select class="form-select @error('party_id') is-invalid @enderror" 
                                id="party_id" name="party_id">
                            <option value="">Independent (No Party)</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->id }}" 
                                        {{ old('party_id', $candidate->party_id) == $party->id ? 'selected' : '' }}
                                        data-color="{{ $party->color }}">
                                    {{ $party->name }} - {{ $party->slogan }}
                                </option>
                            @endforeach
                        </select>
                        @error('party_id')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Candidates can run independently or represent a political party.
                        </div>
                    </div>
                    
                    <!-- Candidate Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Candidate Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $candidate->name) }}"
                               placeholder="Enter candidate's full name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Candidate Bio -->
                    <div class="mb-4">
                        <label for="bio" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-1"></i>Candidate Biography
                        </label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" 
                                  name="bio" 
                                  rows="4"
                                  placeholder="Brief biography highlighting qualifications, experience, and goals...">{{ old('bio', $candidate->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Provide a brief description of the candidate's qualifications and campaign platform.
                        </div>
                    </div>
                    
                    <!-- Current Photo Display -->
                    @if($candidate->photo)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-image me-1"></i>Current Photo
                            </label>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                     alt="{{ $candidate->name }}" 
                                     class="rounded-circle me-3"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <p class="mb-1 fw-medium">{{ $candidate->name }}</p>
                                    <small class="text-muted">Current candidate photo</small>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label for="photo" class="form-label fw-semibold">
                            <i class="bi bi-camera me-1"></i>{{ $candidate->photo ? 'Update Photo' : 'Candidate Photo' }}
                        </label>
                        <input type="file" 
                               class="form-control @error('photo') is-invalid @enderror" 
                               id="photo" 
                               name="photo"
                               accept="image/*">
                        @error('photo')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            {{ $candidate->photo ? 'Upload a new photo to replace the current one. ' : '' }}
                            Recommended: Square image (300x300px or higher), JPG/PNG format, max 2MB.
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-circle me-2"></i>Update Candidate
                        </button>
                        <a href="{{ route('admin.elections.elections.candidates.index', $election) }}" 
                           class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-2"></i>Cancel
                        </a>
                        @if($election->status === 'pending')
                            <button type="button" 
                                    class="btn btn-outline-danger px-4 ms-auto" 
                                    onclick="confirmDelete('{{ $candidate->name }}')">
                                <i class="bi bi-trash me-2"></i>Delete Candidate
                            </button>
                        @endif
                    </div>
                </form>
                
                <!-- Hidden Delete Form -->
                @if($election->status === 'pending')
                    <form method="POST" 
                          action="{{ route('admin.elections.elections.candidates.destroy', [$election, $candidate]) }}" 
                          id="deleteForm" 
                          style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-outline-secondary:hover {
    background: #6c757d;
    border-color: #6c757d;
    transform: translateY(-1px);
}

.btn-outline-danger:hover {
    background: #dc3545;
    border-color: #dc3545;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.card {
    transition: all 0.3s ease;
}

.breadcrumb-item a {
    color: #667eea;
    font-weight: 500;
}

.breadcrumb-item a:hover {
    color: #5a67d8;
}
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(candidateName) {
        if (confirm(`Are you sure you want to delete candidate "${candidateName}"? This action cannot be undone.`)) {
            document.getElementById('deleteForm').submit();
        }
    }
    
    // Preview selected image
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview functionality here if needed
                console.log('Image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Form validation
    document.getElementById('editCandidateForm').addEventListener('submit', function(e) {
        const positionSelect = document.getElementById('position_id');
        const nameInput = document.getElementById('name');
        
        // Check if position is selected
        if (!positionSelect.value) {
            e.preventDefault();
            positionSelect.focus();
            alert('Please select a position for this candidate.');
            return false;
        }
        
        // Check if name is provided
        if (!nameInput.value.trim()) {
            e.preventDefault();
            nameInput.focus();
            alert('Please enter the candidate\'s name.');
            return false;
        }
        
        return true;
    });
</script>
@endpush