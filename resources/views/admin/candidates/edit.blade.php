@extends('layouts.app')

@section('title', 'Edit Candidate')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-pencil text-warning"></i> Edit Candidate</h4>
                </div>
                <div class="card-body">
                    <!-- Election & Position Info -->
                    <div class="alert alert-info">
                        <strong><i class="bi bi-calendar-event"></i> Election:</strong> {{ $election->name }}<br>
                        <strong><i class="bi bi-award"></i> Position:</strong> {{ $candidate->position->name }}<br>
                        <strong><i class="bi bi-person"></i> Candidate:</strong> {{ $candidate->first_name }} {{ $candidate->last_name }}
                    </div>
                    
                    <form action="{{ route('admin.elections.candidates.update', [$election->id, $candidate->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Position Selection -->
                        <div class="mb-3">
                            <label for="position_id" class="form-label">
                                <i class="bi bi-award"></i> Position *
                            </label>
                            <select class="form-select @error('position_id') is-invalid @enderror" 
                                    id="position_id" 
                                    name="position_id" 
                                    required>
                                <option value="">Select Position</option>
                                @foreach($election->positions as $position)
                                    <option value="{{ $position->id }}" 
                                            {{ old('position_id', $candidate->position_id) == $position->id ? 'selected' : '' }}
                                            {{ $position->candidates()->where('id', '!=', $candidate->id)->count() >= 2 ? 'disabled' : '' }}>
                                        {{ $position->name }} 
                                        @if($position->candidates()->where('id', '!=', $candidate->id)->count() >= 2)
                                            (Full - 2/2 candidates)
                                        @else
                                            ({{ $position->candidates()->where('id', '!=', $candidate->id)->count() }}/2 candidates)
                                        @endif
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
                                    required>
                                <option value="">Select Party</option>
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
                                       value="{{ old('first_name', $candidate->first_name) }}" 
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
                                       value="{{ old('last_name', $candidate->last_name) }}" 
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
                                   value="{{ old('usn', $candidate->usn) }}" 
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
                                      placeholder="Brief biography, achievements, and campaign message">{{ old('bio', $candidate->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Current Photo -->
                        @if($candidate->photo)
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-image"></i> Current Photo</label>
                                <div>
                                    <img src="{{ Storage::url($candidate->photo) }}" alt="Current photo" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>
                        @endif
                        
                        <!-- Photo Upload -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">
                                <i class="bi bi-camera"></i> {{ $candidate->photo ? 'Update Photo' : 'Upload Photo' }} (Optional)
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
                        
                        <!-- Candidate Status -->
                        @if($candidate->votes()->count() > 0)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> 
                                This candidate has received {{ $candidate->votes()->count() }} vote(s). 
                                Changes may affect election results.
                            </div>
                        @endif
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.elections.show', $election->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Election
                            </a>
                            <div>
                                <a href="{{ route('admin.elections.candidates.show', [$election->id, $candidate->id]) }}" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i> View Candidate
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check"></i> Update Candidate
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

@section('scripts')
<script>
    // Party color preview
    document.getElementById('party_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const color = selectedOption.getAttribute('data-color');
        
        if (color) {
            this.style.borderLeft = `4px solid ${color}`;
        } else {
            this.style.borderLeft = 'none';
        }
    });
    
    // Set initial color if party is selected
    const initialParty = document.getElementById('party_id');
    if (initialParty.value) {
        const selectedOption = initialParty.options[initialParty.selectedIndex];
        const color = selectedOption.getAttribute('data-color');
        if (color) {
            initialParty.style.borderLeft = `4px solid ${color}`;
        }
    }
</script>
@endsection