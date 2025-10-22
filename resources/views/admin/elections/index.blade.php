@extends('layouts.admin')

@section('title', 'Elections')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Elections Management</h1>
            <p class="page-subtitle mb-0">Manage all elections and their configurations</p>
        </div>
        <div>
            <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>New Election
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="stats-number">{{ $elections->count() ?? 0 }}</div>
                            <div class="stats-label">Total Elections</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-calendar-event" style="font-size: 2rem; opacity: 0.7;"></i>
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
                            <div class="stats-number">{{ $activeElections ?? 0 }}</div>
                            <div class="stats-label">Active Elections</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-lightning" style="font-size: 2rem; opacity: 0.7;"></i>
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
                            <div class="stats-number">{{ $completedElections ?? 0 }}</div>
                            <div class="stats-label">Completed</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-check-circle" style="font-size: 2rem; opacity: 0.7;"></i>
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
                            <div class="stats-number">{{ $pendingElections ?? 0 }}</div>
                            <div class="stats-label">Pending</div>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-clock" style="font-size: 2rem; opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Elections Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-calendar-event me-2"></i>
                All Elections
            </h5>
            <div class="d-flex gap-2">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm active" data-filter="all">All</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-filter="active">Active</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-filter="pending">Pending</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-filter="completed">Completed</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(isset($elections) && $elections->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Election</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Candidates</th>
                                <th>Votes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($elections as $election)
                                <tr data-status="{{ $election->status ?? 'pending' }}">
                                    <td>
                                        <div>
                                            <strong>{{ $election->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($election->description ?? 'No description', 60) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if(($election->status ?? 'pending') === 'active')
                                            <span class="badge bg-success"><i class="bi bi-lightning me-1"></i>Active</span>
                                        @elseif(($election->status ?? 'pending') === 'pending')
                                            <span class="badge bg-warning"><i class="bi bi-clock me-1"></i>Pending</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-check me-1"></i>Completed</span>
                                        @endif
                                    </td>
                                    <td>{{ $election->start_date ? $election->start_date->format('M d, Y H:i') : 'Not set' }}</td>
                                    <td>{{ $election->end_date ? $election->end_date->format('M d, Y H:i') : 'Not set' }}</td>
                                    <td>{{ $election->candidates_count ?? 0 }}</td>
                                    <td>{{ $election->votes_count ?? 0 }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.elections.show', $election) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.elections.edit', $election) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.elections.destroy', $election) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">No Elections Found</h4>
                    <p class="text-muted mb-4">You haven't created any elections yet. Get started by creating your first election.</p>
                    <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus me-2"></i>Create First Election
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('[data-filter]');
        const tableRows = document.querySelectorAll('tbody tr');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter rows
                tableRows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    if (filter === 'all' || status === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush