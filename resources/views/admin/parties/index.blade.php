@extends('layouts.app')

@section('title', 'Manage Parties')

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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-people text-primary"></i> Political Parties</h2>
                @if($parties->count() < 2)
                    <a href="{{ route('admin.parties.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add Party
                    </a>
                @else
                    <span class="badge bg-success fs-6">Maximum parties reached (2/2)</span>
                @endif
            </div>
            
            @if($parties->count() > 0)
                <div class="row">
                    @foreach($parties as $party)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100" style="border-color: {{ $party->color }}; border-width: 3px;">
                            <div class="card-header text-center" style="background-color: {{ $party->color }}; color: white;">
                                <h4 class="mb-0">{{ $party->name }}</h4>
                            </div>
                            <div class="card-body text-center">
                                @if($party->logo)
                                    <img src="{{ Storage::url($party->logo) }}" alt="{{ $party->name }} Logo" class="img-fluid mb-3" style="max-height: 100px;">
                                @else
                                    <i class="bi bi-people-fill" style="font-size: 4rem; color: {{ $party->color }};"></i>
                                @endif
                                
                                <h5 class="text-muted mb-3">{{ $party->slogan }}</h5>
                                
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.parties.show', $party->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.parties.edit', $party->id) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    @if($party->candidates()->count() == 0)
                                        <form action="{{ route('admin.parties.destroy', $party->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this party?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="btn btn-outline-secondary btn-sm disabled" title="Cannot delete party with candidates">
                                            <i class="bi bi-lock"></i> Protected
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <small class="text-muted">
                                    <i class="bi bi-person-check"></i> {{ $party->candidates()->count() }} {{ Str::plural('Candidate', $party->candidates()->count()) }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-3 text-muted">No Political Parties</h3>
                        <p class="text-muted">Create political parties to organize candidates for elections.</p>
                        <a href="{{ route('admin.parties.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Create First Party
                        </a>
                    </div>
                </div>
            @endif
            
            <!-- Party Guidelines -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Party Management Guidelines</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Party Creation Rules:</h6>
                            <ul class="mb-0">
                                <li>Maximum of 2 political parties allowed</li>
                                <li>Each party must have a unique name and slogan</li>
                                <li>Party colors help differentiate candidates</li>
                                <li>Logo upload is optional but recommended</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Deletion Policy:</h6>
                            <ul class="mb-0">
                                <li>Parties with candidates cannot be deleted</li>
                                <li>Remove all candidates first before deletion</li>
                                <li>Deleted parties cannot be recovered</li>
                                <li>Active elections prevent party changes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection