@extends('layouts.app')

@section('title', 'Add New Candidate')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="bi bi-person-plus text-success me-2"></i>Add New Candidate</h3>
                <p class="text-muted mb-0">Create a new candidate and assign them to an election position</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-form me-2"></i>Candidate Information</h5>
            </div>
            
            <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Election Selection -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event text-primary me-1"></i>Election
                            </label>
                            <select name="election_id" id="election_id" class="form-select @error('election_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Choose an election</option>
                                @foreach($elections as $election)
                                    <option value="{{ $election->id }}" {{ old('election_id') == $election->id ? 'selected' : '' }} 
                                            data-positions='@json($election->positions)'>
                                        {{ $election->name }}
                                        @if($election->status === 'draft')
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('election_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Only draft elections are available for adding candidates</div>
                        </div>

                        <!-- Position Selection -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-circle text-info me-1"></i>Position
                            </label>
                            <select name="position_id" id="position_id" class="form-select @error('position_id') is-invalid @enderror" required disabled>
                                <option value="" disabled selected>First select an election</option>
                            </select>
                            @error('position_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">The position this candidate will run for</div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Party Selection -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-flag text-warning me-1"></i>Party Affiliation
                            </label>
                            <select name="party_id" class="form-select @error('party_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Choose party or independent</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->id }}" {{ old('party_id') == $party->id ? 'selected' : '' }}>
                                        {{ $party->name }}
                                        @if($party->description)
                                            - {{ Str::limit($party->description, 50) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('party_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Select the party this candidate represents</div>
                        </div>

                        <!-- Candidate Name -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person text-success me-1"></i>Candidate Name
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Enter candidate's full name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Full name as it will appear on the ballot</div>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-camera text-secondary me-1"></i>Candidate Photo (Optional)
                        </label>
                        <input type="file" name="photo" accept="image/*" 
                               class="form-control @error('photo') is-invalid @enderror"
                               onchange="previewImage(this)">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Upload a professional photo (JPG, PNG, GIF - Max 2MB)</div>
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="preview" class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Biography -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-journal-text text-info me-1"></i>Biography (Optional)
                        </label>
                        <textarea name="bio" rows="4" 
                                  class="form-control @error('bio') is-invalid @enderror" 
                                  placeholder="Brief biography of the candidate...">{{ old('bio') }}</textarea>
                        @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">A brief background about the candidate</div>
                    </div>

                    <!-- Platform -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-megaphone text-primary me-1"></i>Platform/Agenda (Optional)
                        </label>
                        <textarea name="platform" rows="4" 
                                  class="form-control @error('platform') is-invalid @enderror" 
                                  placeholder="What does this candidate stand for? What are their goals?">{{ old('platform') }}</textarea>
                        @error('platform')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">The candidate's campaign promises and goals</div>
                    </div>
                </div>
                
                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Candidates
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check2-circle me-1"></i>Add Candidate
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Help Section -->
        <div class="card mt-4 border-info border-opacity-25">
            <div class="card-body">
                <h6 class="text-info mb-3"><i class="bi bi-info-circle me-2"></i>Candidate Management Tips</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Only draft elections allow new candidates</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Maximum 2 candidates per position</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Each party can only have one candidate per position</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-lightbulb text-warning me-2"></i>Upload professional photos for better presentation</li>
                            <li class="mb-2"><i class="bi bi-lightbulb text-warning me-2"></i>Clear platforms help voters make informed decisions</li>
                            <li class="mb-2"><i class="bi bi-lightbulb text-warning me-2"></i>Biography should highlight relevant experience</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border-radius: 1rem;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.form-control, .form-select {
    border-radius: 0.5rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 0.5rem;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
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

.btn-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    border: none;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-text {
    font-size: 0.875rem;
    color: #6b7280;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

.card-footer {
    border-radius: 0 0 1rem 1rem !important;
}
</style>
@endpush

@push('scripts')
<script>
    // Handle election selection and populate positions
    document.getElementById('election_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const positions = JSON.parse(selectedOption.getAttribute('data-positions') || '[]');
        const positionSelect = document.getElementById('position_id');
        
        // Clear previous options
        positionSelect.innerHTML = '<option value="" disabled selected>Choose a position</option>';
        
        // Add positions for selected election
        positions.forEach(position => {
            const option = document.createElement('option');
            option.value = position.id;
            option.textContent = position.name;
            if (position.description) {
                option.textContent += ` - ${position.description}`;
            }
            positionSelect.appendChild(option);
        });
        
        // Enable position selection
        positionSelect.disabled = false;
    });

    // Image preview function
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form validation enhancement
    document.querySelector('form').addEventListener('submit', function(e) {
        const electionId = document.getElementById('election_id').value;
        const positionId = document.getElementById('position_id').value;
        
        if (!electionId || !positionId) {
            e.preventDefault();
            alert('Please select both an election and a position.');
            return false;
        }
    });
</script>
@endpush