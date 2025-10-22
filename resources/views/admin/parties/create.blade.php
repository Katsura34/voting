@extends('layouts.app')

@section('title', 'Create Party')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header"><h3 class="mb-0"><i class="bi bi-flag me-2"></i>Create Party</h3></div>
      <form action="{{ route('admin.parties.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Slogan</label>
            <input name="slogan" value="{{ old('slogan') }}" class="form-control @error('slogan') is-invalid @enderror" required>
            @error('slogan')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Color</label>
              <div class="input-group">
                <input type="color" name="color" value="{{ old('color','#3B82F6') }}" class="form-control form-control-color @error('color') is-invalid @enderror" required>
                <input type="text" class="form-control" value="{{ old('color','#3B82F6') }}" readonly>
              </div>
              @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Logo (optional)</label>
              <input type="file" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
              @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <a href="{{ route('admin.parties.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
          <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection