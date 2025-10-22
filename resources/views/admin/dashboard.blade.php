@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h1>
  <div class="text-muted">
    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('M d, Y') }}
  </div>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card text-white bg-primary h-100">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <div class="fs-4 fw-bold">{{ $stats['total_students'] ?? 0 }}</div>
          <div class="small">Students</div>
        </div>
        <i class="bi bi-people-fill" style="font-size:2rem;opacity:.85;"></i>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-white bg-success h-100">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <div class="fs-4 fw-bold">{{ $stats['total_parties'] ?? 0 }}</div>
          <div class="small">Parties</div>
        </div>
        <i class="bi bi-flag-fill" style="font-size:2rem;opacity:.85;"></i>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-white bg-info h-100">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <div class="fs-4 fw-bold">{{ $stats['total_elections'] ?? 0 }}</div>
          <div class="small">Elections</div>
        </div>
        <i class="bi bi-calendar-event-fill" style="font-size:2rem;opacity:.85;"></i>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-white bg-danger h-100">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <div class="fs-4 fw-bold">{{ $stats['total_votes'] ?? 0 }}</div>
          <div class="small">Total Votes</div>
        </div>
        <i class="bi bi-ballot-fill" style="font-size:2rem;opacity:.85;"></i>
      </div>
    </div>
  </div>
</div>

{{-- Active Election + Live Results --}}
@if(isset($activeElection) && $activeElection)
<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-bar-chart me-2"></i>Live Results — {{ $activeElection->name }}</span>
        <div class="d-flex gap-2">
          <a href="{{ route('admin.elections.show', $activeElection) }}" class="btn btn-sm btn-outline-light">
            <i class="bi bi-eye me-1"></i>View
          </a>
          <a href="{{ route('admin.elections.results', $activeElection) }}" class="btn btn-sm btn-outline-light">
            <i class="bi bi-graph-up me-1"></i>Results
          </a>
        </div>
      </div>
      <div class="card-body">
        {{-- Hook for charts (Chart.js) --}}
        @includeWhen(isset($chartData) && count($chartData) > 0, 'admin.partials.live-results', [
          'chartData' => $chartData,
          'activeElection' => $activeElection
        ])

        @if(!isset($chartData) || count($chartData) === 0)
          <p class="text-center text-muted my-4">No votes cast yet.</p>
        @endif
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-success text-white fw-semibold">
        <i class="bi bi-lightning-charge me-2"></i>Quick Actions
      </div>
      <div class="card-body d-grid gap-2">
        <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-primary">
          <i class="bi bi-calendar-event me-1"></i> Manage Elections
        </a>
        <a href="{{ route('admin.elections.create') }}" class="btn btn-outline-success">
          <i class="bi bi-plus-circle me-1"></i> New Election
        </a>
        <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary">
          <i class="bi bi-flag me-1"></i> Manage Parties
        </a>
      </div>
      <div class="card-footer small text-muted">
        Voting ends: {{ $activeElection->end_date->format('M d, Y \\a\\t H:i') }}
      </div>
    </div>
  </div>
</div>
@else
<div class="alert alert-info d-flex align-items-center" role="alert">
  <i class="bi bi-info-circle me-2"></i>
  <div>No active election. You can create or activate one from the Elections page.</div>
  <a href="{{ route('admin.elections.create') }}" class="btn btn-sm btn-primary ms-auto"><i class="bi bi-plus-circle me-1"></i> New Election</a>
</div>
@endif

{{-- Recent Elections --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="fw-semibold"><i class="bi bi-clock-history me-2"></i>Recent Elections</span>
    <a href="{{ route('admin.elections.create') }}" class="btn btn-sm btn-primary">
      <i class="bi bi-plus me-1"></i>New Election
    </a>
  </div>
  <div class="card-body p-0">
    @if(isset($recentElections) && $recentElections->count())
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Status</th>
              <th>Start</th>
              <th>End</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
          @foreach($recentElections as $e)
            <tr>
              <td class="fw-semibold">{{ $e->name }}</td>
              <td>
                @if($e->status === 'active')
                  <span class="badge bg-success">Active</span>
                @elseif($e->status === 'closed')
                  <span class="badge bg-secondary">Closed</span>
                @else
                  <span class="badge bg-warning text-dark">Draft</span>
                @endif
              </td>
              <td>{{ $e->start_date->format('M d, Y H:i') }}</td>
              <td>{{ $e->end_date->format('M d, Y H:i') }}</td>
              <td class="text-end">
                <div class="btn-group">
                  <a href="{{ route('admin.elections.show', $e) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                  </a>
                  @if($e->status === 'draft')
                    <a href="{{ route('admin.elections.edit', $e) }}" class="btn btn-sm btn-outline-secondary">
                      <i class="bi bi-pencil"></i>
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="p-4 text-center text-muted">No recent elections.</div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
@if(isset($chartData) && is_array($chartData) && count($chartData) > 0)
<script>
  // Render each position’s chart using Chart.js if partial didn’t already render them
  // The partial 'admin.partials.live-results' can include more complex charts per position.
</script>
@endif
@endpush
