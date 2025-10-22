@extends('layouts.app')

@section('title', 'Create Election')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Create New Election</h3>
            </div>
            <form action="{{ route('admin.elections.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Start Date & Time</label>
                            <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="form-control @error('start_date') is-invalid @enderror" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">End Date & Time</label>
                            <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="form-control @error('end_date') is-invalid @enderror" required>
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.elections.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection