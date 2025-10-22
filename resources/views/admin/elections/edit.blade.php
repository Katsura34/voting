@extends('layouts.admin')

@section('title', 'Edit Election')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Election</h1>
            <p class="page-subtitle mb-0">Update election details and configuration</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-2"></i>View
                </a>
                <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Update Election Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.elections.update', $election) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Election Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $election->name) }}" 
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
                                      rows="4">{{ old('description', $election->description) }}</textarea>
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
                                       value="{{ old('start_date', $election->start_date ? $election->start_date->format('Y-m-d\TH:i') : '') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date & Time</label>
                                <input type="datetime-local" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date', $election->end_date ? $election->end_date->format('Y-m-d\TH:i') : '') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="pending" {{ old('status', $election->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ old('status', $election->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ old('status', $election->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Election Type</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                    <option value="general" {{ old('type', $election->type) === 'general' ? 'selected' : '' }}>General Election</option>
                                    <option value="student_council" {{ old('type', $election->type) === 'student_council' ? 'selected' : '' }}>Student Council</option>
                                    <option value="departmental" {{ old('type', $election->type) === 'departmental' ? 'selected' : '' }}>Departmental</option>
                                    <option value="special" {{ old('type', $election->type) === 'special' ? 'selected' : '' }}>Special Election</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Election
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Election Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Current Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Current Status</h6>
                        @if($election->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($election->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-secondary">Completed</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Created</h6>
                        <small>{{ $election->created_at->format('M d, Y H:i') }}</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Last Updated</h6>
                        <small>{{ $election->updated_at->format('M d, Y H:i') }}</small>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="mb-1 text-primary">{{ $election->positions_count ?? 0 }}</h6>
                                <small class="text-muted">Positions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-1 text-success">{{ $election->candidates_count ?? 0 }}</h6>
                            <small class="text-muted">Candidates</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning Card -->
            @if($election->status === 'active')
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h6 class="mb-0 text-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Active Election Warning
                        </h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            This election is currently active. Making changes to active elections may affect ongoing voting. 
                            Consider the impact on voters before making changes.
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection