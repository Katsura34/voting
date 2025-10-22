@extends('layouts.app')

@section('title', 'Parties')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2"><i class="bi bi-people-fill me-2"></i>Parties</h1>
    <a href="{{ route('admin.parties.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>New Party
    </a>
</div>

@if($parties->count() === 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-flag text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted mb-0">No parties yet. Create one to get started.</p>
        </div>
    </div>
@else
<div class="row">
    @foreach($parties as $party)
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center" style="background-color: {{ $party->color }}; color: #fff;">
                    @if($party->logo)
                        <img src="{{ Storage::url($party->logo) }}" class="rounded me-2" style="width:40px;height:40px;object-fit:cover" alt="{{ $party->name }}">
                    @else
                        <i class="bi bi-flag-fill me-2" style="font-size: 1.25rem;"></i>
                    @endif
                    <strong>{{ $party->name }}</strong>
                    <span class="ms-auto badge {{ $party->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $party->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="card-body">
                    <p class="fst-italic">"{{ $party->slogan }}"</p>
                    <div class="d-flex justify-content-between text-muted">
                        <small>Candidates: {{ $party->candidates()->count() }}</small>
                        <small>Created: {{ $party->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.parties.edit', $party) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @if($party->canBeDeleted())
                        <form action="{{ route('admin.parties.destroy', $party) }}" method="POST" onsubmit="return confirm('Delete this party?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
@endsection