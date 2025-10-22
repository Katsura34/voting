@extends('layouts.app')

@section('title', 'Create Candidate')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header"><h3 class="mb-0"><i class="bi bi-person-plus me-2"></i>Create Candidate</h3></div>
      <form action="{{ route('admin.elections.candidates.store', $election) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Position</label>
            <select name="position_id" class="form-select @error('position_id') is-invalid @enderror" required>
              <option value="" disabled selected>Choose position</option>
              @foreach($positions as $position)
                <option value="{{ $position->id }}" {{ old('position_id')==$position->id?'selected':'' }}>{{ $position->name }}</option>
              @endforeach
            </select>
            @error('position_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Party</label>
            <select name="party_id" class="form-select @error('party_id') is-invalid @enderror" required>
              <option value="" disabled selected>Choose party</option>
              @foreach($parties as $party)
                <option value="{{ $party->id }}" {{ old('party_id')==$party->id?'selected':'' }}>{{ $party->name }}</option>
              @endforeach
            </select>
            @error('party_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Bio (optional)</label>
            <textarea name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror">{{ old('bio') }}</textarea>
            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Platform (optional)</label>
            <textarea name="platform" rows="3" class="form-control @error('platform') is-invalid @enderror">{{ old('platform') }}</textarea>
            @error('platform')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Photo (optional)</label>
            <input type="file" name="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror">
            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <a href="{{ route('admin.elections.candidates.index', $election) }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
          <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection