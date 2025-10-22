@extends('layouts.app')

@section('title', $party->name . ' - Party Details')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="sidebar p-3">
                <h6 class="text-muted mb-3">ADMIN PANEL</h6>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.parties.index') }}">
                            <i class="bi bi-people"></i> Parties
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.elections.index') }}">
                            <i class="bi bi-calendar-event"></i> Elections
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <!-- Navigation Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.parties.index') }}" class="text-decoration-none">Parties</a></li>
                    <li class="breadcrumb-item active">{{ $party->name }}</li>
                </ol>
            </nav>

            <!-- Party Header -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body" style="background: linear-gradient(135deg, {{ $party->color }}22, {{ $party->color }}44); border-radius: 0.5rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                @if($party->logo)
                                    <img src="{{ Storage::url($party->logo) }}" 
                                         alt="{{ $party->name }} Logo" 
                                         class="rounded-circle me-4" 
                                         style="width: 80px; height: 80px; object-fit: cover; border: 3px solid {{ $party->color }};">
                                @else
                                    <div class="rounded-circle me-4 d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px; background-color: {{ $party->color }}; color: white; font-size: 2rem;">
                                        <i class="bi bi-flag-fill"></i>
                                    </div>
                                @endif
                                <div>
                                    <h1 class="h2 mb-1" style="color: {{ $party->color }};">{{ $party->name }}</h1>
                                    <p class="lead fst-italic mb-2 text-muted">"{{ $party->slogan }}"</p>
                                    <span class="badge {{ $party->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                                        <i class="bi bi-{{ $party->is_active ? 'check-circle' : 'pause-circle' }} me-1"></i>
                                        {{ $party->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.parties.edit', $party) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil-square me-1"></i>Edit Party
                                </a>
                                <a href="{{ route('admin.parties.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Back to Parties
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Party Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="bi bi-person-badge text-primary" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $party->candidates()->count() }}</h4>
                            <p class="text-muted mb-0">Total Candidates</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="bi bi-calendar-check text-success" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $party->candidates()->whereHas('election', function($q) { $q->where('status', 'active'); })->count() }}</h4>
                            <p class="text-muted mb-0">Active Campaigns</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="bi bi-trophy text-warning" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">0</h4>
                            <p class="text-muted mb-0">Victories</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="bi bi-calendar3 text-info" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $party->created_at->format('M Y') }}</h4>
                            <p class="text-muted mb-0">Founded</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Party Candidates -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Party Candidates</h5>
                    @if($party->candidates()->count() > 0)
                        <span class="badge bg-info">{{ $party->candidates()->count() }} {{ Str::plural('Candidate', $party->candidates()->count()) }}</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($party->candidates()->count() > 0)
                        <div class="row">
                            @foreach($party->candidates as $candidate)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border">
                                        <div class="card-body text-center">
                                            @if($candidate->photo)
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" 
                                                     alt="{{ $candidate->name }}" 
                                                     class="rounded-circle mb-3" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px; background-color: {{ $party->color }}; color: white; font-size: 1.5rem;">
                                                    {{ substr($candidate->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <h6 class="mb-1">{{ $candidate->name }}</h6>
                                            <p class="text-muted small mb-2">{{ $candidate->position->name ?? 'No Position' }}</p>
                                            @if($candidate->election)
                                                <span class="badge bg-{{ $candidate->election->status === 'active' ? 'success' : ($candidate->election->status === 'closed' ? 'secondary' : 'warning') }} mb-2">
                                                    {{ $candidate->election->name }}
                                                </span>
                                            @endif
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.elections.candidates.show', [$candidate->election, $candidate]) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.elections.candidates.edit', [$candidate->election, $candidate]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-person-plus text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">No Candidates Yet</h5>
                            <p class="text-muted">This party doesn't have any candidates registered for elections.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Party Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Party Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Details</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Party Name:</strong></td>
                                    <td>{{ $party->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Slogan:</strong></td>
                                    <td class="fst-italic">"{{ $party->slogan }}"</td>
                                </tr>
                                <tr>
                                    <td><strong>Party Color:</strong></td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $party->color }}; color: white;">{{ $party->color }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $party->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $party->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Timeline</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $party->created_at->format('M d, Y \a\t H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $party->updated_at->format('M d, Y \a\t H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Elections:</strong></td>
                                    <td>{{ $party->candidates()->distinct('election_id')->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Can be Deleted:</strong></td>
                                    <td>
                                        @if($party->candidates()->count() == 0)
                                            <span class="text-success"><i class="bi bi-check-circle"></i> Yes</span>
                                        @else
                                            <span class="text-danger"><i class="bi bi-x-circle"></i> No (Has candidates)</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.sidebar {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    min-height: 600px;
}

.nav-pills .nav-link {
    color: #6c757d;
    margin-bottom: 0.25rem;
    border-radius: 0.375rem;
}

.nav-pills .nav-link:hover {
    background-color: #e9ecef;
    color: #495057;
}

.nav-pills .nav-link.active {
    background-color: #0d6efd;
    color: white;
}

.card {
    transition: transform 0.2s ease-in-out;
    border-radius: 0.5rem;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.breadcrumb-item a {
    color: #0d6efd;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: #0a58ca;
    text-decoration: underline;
}
</style>
@endpush