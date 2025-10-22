@extends('layouts.admin')

@section('title', 'Party Details')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ $party->name }}</h1>
            <p class="page-subtitle mb-0">{{ $party->abbreviation ? $party->abbreviation . ' - ' : '' }}Party details and management</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.parties.edit', $party) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Party Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $party->candidates_count ?? 0 }}</div>
                            <div class="stats-label">Total Candidates</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-person-badge" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $party->active_candidates ?? 0 }}</div>
                            <div class="stats-label">Active Candidates</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-person-check" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $party->total_votes ?? 0 }}</div>
                            <div class="stats-label">Total Votes</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-check-square" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $party->elections_participated ?? 0 }}</div>
                            <div class="stats-label">Elections</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-calendar-event" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Party Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Party Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Party Name</h6>
                                <p class="mb-0">{{ $party->name }}</p>
                            </div>
                            @if($party->abbreviation)
                                <div class="mb-3">
                                    <h6 class="text-muted mb-1">Abbreviation</h6>
                                    <p class="mb-0">{{ $party->abbreviation }}</p>
                                </div>
                            @endif
                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Description</h6>
                                <p class="mb-0">{{ $party->description ?? 'No description provided' }}</p>
                            </div>
                            @if($party->founded_date)
                                <div class="mb-3">
                                    <h6 class="text-muted mb-1">Founded</h6>
                                    <p class="mb-0">{{ $party->founded_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-center">
                            @if($party->logo)
                                <img src="{{ $party->logo }}" alt="{{ $party->name }}" class="img-fluid rounded" style="max-width: 120px; max-height: 120px;">
                            @else
                                <div class="bg-primary rounded d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; background-color: {{ $party->color ?? '#3b82f6' }} !important;">
                                    <i class="bi bi-people text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="mt-3">
                                @if($party->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidates List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>
                        Party Candidates
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($candidates) && $candidates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Candidate</th>
                                        <th>Election</th>
                                        <th>Position</th>
                                        <th>Votes</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($candidates as $candidate)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if($candidate->photo)
                                                            <img src="{{ $candidate->photo }}" alt="{{ $candidate->name }}" class="rounded-circle" width="32" height="32">
                                                        @else
                                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                <i class="bi bi-person text-white"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong>{{ $candidate->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $candidate->student_id ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $candidate->election->name ?? 'N/A' }}</td>
                                            <td>{{ $candidate->position->name ?? 'N/A' }}</td>
                                            <td><strong class="text-primary">{{ $candidate->votes_count ?? 0 }}</strong></td>
                                            <td>
                                                @if($candidate->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-person-x" style="font-size: 3rem; color: #dee2e6;"></i>
                            <p class="text-muted mt-2 mb-0">No candidates registered for this party</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.parties.edit', $party) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Party
                        </a>
                        <a href="{{ route('admin.candidates.index') }}?party={{ $party->id }}" class="btn btn-outline-success">
                            <i class="bi bi-people me-2"></i>View All Candidates
                        </a>
                        <button class="btn btn-outline-info" onclick="exportPartyData()">
                            <i class="bi bi-download me-2"></i>Export Data
                        </button>
                        <form method="POST" action="{{ route('admin.parties.destroy', $party) }}" class="d-inline" onsubmit="return confirm('Are you sure? This will remove the party from all candidates.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-2"></i>Delete Party
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Party Timeline -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Party Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Party Created</h6>
                                <small class="text-muted">{{ $party->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        @if($party->founded_date && $party->founded_date != $party->created_at->toDateString())
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Party Founded</h6>
                                    <small class="text-muted">{{ $party->founded_date->format('M d, Y') }}</small>
                                </div>
                            </div>
                        @endif
                        @if($party->updated_at != $party->created_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Last Updated</h6>
                                    <small class="text-muted">{{ $party->updated_at->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: -1.3rem;
        top: 1.5rem;
        width: 2px;
        height: calc(100% - 1rem);
        background-color: #dee2e6;
    }
    
    .timeline-marker {
        position: absolute;
        left: -1.75rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
    function exportPartyData() {
        alert('Export functionality will be implemented here.');
    }
</script>
@endpush