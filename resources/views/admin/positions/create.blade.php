@extends('layouts.admin')

@section('title', 'Create Position')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Create New Position</h1>
            <p class="page-subtitle mb-0">Add a new position for {{ $election->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.elections.positions.index', $election) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Positions
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
                        Position Details
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.elections.positions.store', $election) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Position Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g., President, Vice President, Secretary"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Describe the responsibilities and requirements for this position...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_selections" class="form-label">Maximum Selections</label>
                                <input type="number" 
                                       class="form-control @error('max_selections') is-invalid @enderror" 
                                       id="max_selections" 
                                       name="max_selections" 
                                       value="{{ old('max_selections', 1) }}"
                                       min="1"
                                       max="10">
                                @error('max_selections')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">How many candidates voters can select for this position</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="order" class="form-label">Display Order</label>
                                <input type="number" 
                                       class="form-control @error('order') is-invalid @enderror" 
                                       id="order" 
                                       name="order" 
                                       value="{{ old('order', 1) }}"
                                       min="1">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Order in which this position appears on the ballot</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="required" class="form-label">Voting Required</label>
                                <select class="form-select @error('required') is-invalid @enderror" id="required" name="required">
                                    <option value="0" {{ old('required') == '0' ? 'selected' : '' }}>Optional</option>
                                    <option value="1" {{ old('required') == '1' ? 'selected' : '' }}>Required</option>
                                </select>
                                @error('required')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Whether voters must make a selection for this position</small>
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
                            <a href="{{ route('admin.elections.positions.index', $election) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create Position
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
                        <div class="mt-2"><strong>Existing Positions:</strong> {{ $election->positions_count ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Position Guidelines -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>
                        Position Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Position Name</h6>
                        <small class="text-muted">Use clear, official titles that voters will recognize (e.g., President, Secretary).</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Max Selections</h6>
                        <small class="text-muted">Set to 1 for single-winner positions, or higher for committee positions.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Display Order</h6>
                        <small class="text-muted">Higher-ranked positions (President, VP) typically appear first on ballots.</small>
                    </div>
                    
                    <div>
                        <h6 class="text-primary">Required Voting</h6>
                        <small class="text-muted">Mark as required for essential positions, optional for supplementary roles.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection