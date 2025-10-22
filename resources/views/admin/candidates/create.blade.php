@extends('layouts.admin')

@section('title', 'Add Candidate')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Add New Candidate</h1>
            <p class="page-subtitle mb-0">Add a candidate to {{ $election->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.elections.candidates.index', $election) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Candidates
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
                        Candidate Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.elections.candidates.store', $election) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Enter candidate's full name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" 
                                       class="form-control @error('student_id') is-invalid @enderror" 
                                       id="student_id" 
                                       name="student_id" 
                                       value="{{ old('student_id') }}" 
                                       placeholder="Enter student ID">
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="position_id" class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-select @error('position_id') is-invalid @enderror" id="position_id" name="position_id" required>
                                    <option value="">Select a position</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->id }}" {{ old('position_id', request('position')) == $position->id ? 'selected' : '' }}>
                                            {{ $position->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('position_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="party_id" class="form-label">Political Party</label>
                                <select class="form-select @error('party_id') is-invalid @enderror" id="party_id" name="party_id">
                                    <option value="">Independent</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->id }}" {{ old('party_id') == $party->id ? 'selected' : '' }}>
                                            {{ $party->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('party_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Biography</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="4" 
                                      placeholder="Tell voters about this candidate's background, experience, and platform...">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Candidate Photo</label>
                                <input type="file" 
                                       class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" 
                                       name="photo"
                                       accept="image/*">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload a professional photo (max 2MB)</small>
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

                        <div class="mb-3">
                            <label for="platform" class="form-label">Campaign Platform</label>
                            <textarea class="form-control @error('platform') is-invalid @enderror" 
                                      id="platform" 
                                      name="platform" 
                                      rows="4" 
                                      placeholder="Outline the candidate's campaign promises and goals...">{{ old('platform') }}</textarea>
                            @error('platform')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.elections.candidates.index', $election) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Add Candidate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Election Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Election Information
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">{{ $election->name }}</h6>
                    <p class="text-muted small mb-2">{{ $election->description ?? 'No description' }}</p>
                    <div class="small text-muted">
                        <div><strong>Status:</strong> 
                            @if($election->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($election->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-secondary">Completed</span>
                            @endif
                        </div>
                        <div class="mt-2"><strong>Total Candidates:</strong> {{ $election->candidates_count ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Candidate Preview -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-eye me-2"></i>
                        Candidate Preview
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div id="candidate-preview">
                        <div id="preview-photo" class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h6 id="preview-name" class="text-muted">Candidate name will appear here</h6>
                        <small id="preview-student-id" class="text-muted d-block mb-2">Student ID</small>
                        <div class="mb-2">
                            <span id="preview-position" class="badge bg-primary">Select Position</span>
                            <span id="preview-party" class="badge bg-secondary">Independent</span>
                        </div>
                        <p id="preview-bio" class="text-muted small">Bio will appear here</p>
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
        const studentIdInput = document.getElementById('student_id');
        const positionSelect = document.getElementById('position_id');
        const partySelect = document.getElementById('party_id');
        const bioInput = document.getElementById('bio');
        const statusSelect = document.getElementById('status');
        const photoInput = document.getElementById('photo');

        function updatePreview() {
            document.getElementById('preview-name').textContent = nameInput.value || 'Candidate name will appear here';
            document.getElementById('preview-student-id').textContent = studentIdInput.value || 'Student ID';
            document.getElementById('preview-bio').textContent = bioInput.value || 'Bio will appear here';
            
            const positionBadge = document.getElementById('preview-position');
            positionBadge.textContent = positionSelect.options[positionSelect.selectedIndex].text || 'Select Position';
            
            const partyBadge = document.getElementById('preview-party');
            partyBadge.textContent = partySelect.options[partySelect.selectedIndex].text || 'Independent';
            partyBadge.className = partySelect.value ? 'badge bg-primary' : 'badge bg-secondary';
            
            const statusBadge = document.getElementById('preview-status');
            statusBadge.textContent = statusSelect.value;
            statusBadge.className = `badge ${statusSelect.value === 'active' ? 'bg-success' : 'bg-secondary'}`;
        }

        // Photo preview
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewPhoto = document.getElementById('preview-photo');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewPhoto.innerHTML = `<img src="${e.target.result}" alt="Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">`;
                };
                reader.readAsDataURL(file);
            } else {
                previewPhoto.innerHTML = '<i class="bi bi-person text-white" style="font-size: 2rem;"></i>';
            }
        });

        [nameInput, studentIdInput, positionSelect, partySelect, bioInput, statusSelect].forEach(input => {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        });
    });
</script>
@endpush