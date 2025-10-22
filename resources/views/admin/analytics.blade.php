@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2"><i class="bi bi-graph-up-arrow me-2"></i>Analytics</h1>
</div>

@if(empty($analyticsData) || count($analyticsData) === 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-bar-chart text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted mb-0">No analytics available yet.</p>
        </div>
    </div>
@else
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Election</th>
                        <th>Status</th>
                        <th>Positions</th>
                        <th>Candidates</th>
                        <th>Total Votes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analyticsData as $row)
                        <tr>
                            <td class="fw-semibold">{{ $row['name'] }}</td>
                            <td>
                                @if($row['status'] === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($row['status'] === 'closed')
                                    <span class="badge bg-secondary">Closed</span>
                                @else
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @endif
                            </td>
                            <td>{{ $row['positions'] }}</td>
                            <td>{{ $row['candidates'] }}</td>
                            <td>{{ $row['total_votes'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection