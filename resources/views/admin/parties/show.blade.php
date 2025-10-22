@extends('layouts.app')

@section('title', $party->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <!-- Party Details -->
            <div class="card" style="border-color: {{ $party->color }}; border-width: 3px;">
                <div class="card-header text-center text-white" style="background-color: {{ $party->color }};">
                    <h2 class="mb-0">{{ $party->name }}</h2>
                </div>
                <div class="card-body text-center">
                    @if($party->logo)
                        <img src="{{ Storage::url($party->logo) }}" alt="{{ $party->name }} Logo" class="img-fluid mb-4" style="max-height: 150px;">
                    @else
                        <i class="bi bi-people-fill mb-4" style="font-size: 6rem; color: {{ $party->color }};"></i>
                    @endif
                    
                    <h3 class="text-muted mb-4">{{ $party->slogan }}</h3>
                    
                    @if($party->description)
                        <p class="lead">{{ $party->description }}</p>
                    @endif
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-primary mb-1">{{ $party->candidates()->count() }}</h4>
                                <small class="text-muted">Total Candidates</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <h4 class="text-success mb-1">{{ $party->votes()->count() }}</h4>
                                <small class="text-muted">Total Votes</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <div class="rounded-circle mx-auto" style="width: 30px; height: 30px; background-color: {{ $party->color }};"></div>
                                <small class="text-muted d-block mt-1">Party Color</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Party Candidates -->
            @if($party->candidates()->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Party Candidates</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($party->candidates as $candidate)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            @if($candidate->photo)
                                                <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->first_name }} {{ $candidate->last_name }}" class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: {{ $party->color }}20;">
                                                    <i class="bi bi-person-fill" style="color: {{ $party->color }};"></i>
                                                </div>
                                            @endif
                                            <h6 class="mb-1">{{ $candidate->first_name }} {{ $candidate->last_name }}</h6>
                                            <p class="text-muted mb-1">{{ $candidate->position->name ?? 'No Position' }}</p>
                                            <small class="text-muted">{{ $candidate->usn }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Party Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.parties.edit', $party->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Party
                        </a>
                        
                        @if($party->candidates()->count() == 0)
                            <form action="{{ route('admin.parties.destroy', $party->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this party? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Delete Party
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary" disabled title="Cannot delete party with candidates">
                                <i class="bi bi-lock"></i> Protected (Has Candidates)
                            </button>
                        @endif
                        
                        <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Back to Parties
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Party Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Party Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        <span class="text-muted">{{ $party->created_at->format('M d, Y H:i A') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <span class="text-muted">{{ $party->updated_at->format('M d, Y H:i A') }}</span>
                    </div>
                    <div class="mb-0">
                        <strong>Status:</strong><br>
                        @if($party->candidates()->count() > 0)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning">No Candidates</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection