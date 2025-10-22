@extends('layouts.app')

@section('title', 'Edit Party')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Party</h3>
            </div>
            <form action="{{ route('admin.parties.update', $party) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input name="name" value="{{ old('name', $party->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slogan</label>
                        <input name="slogan" value="{{ old('slogan', $party->slogan) }}" class="form-control @error('slogan') is-invalid @enderror" required>
                        @error('slogan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Color</label>
                            <div class="input-group">
                                <input type="color" name="color" value="{{ old('color', $party->color) }}" class="form-control form-control-color @error('color') is-invalid @enderror" id="colorPicker" required>
                                <input type="text" class="form-control" id="colorHex" value="{{ old('color', $party->color) }}" readonly>
                            </div>
                            @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Logo</label>
                            <input type="file" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
                            @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Leave empty to keep current logo</div>
                        </div>
                    </div>
                    
                    {{-- Current Logo Display --}}
                    @if($party->logo)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Logo</label>
                        <div class="border rounded p-3 bg-light">
                            <img src="{{ Storage::url($party->logo) }}" alt="{{ $party->name }}" 
                                 class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_logo" id="removeLogo">
                                <label class="form-check-label text-danger" for="removeLogo">
                                    Remove current logo
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Live Preview Card --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Preview</label>
                        <div class="card border-secondary">
                            <div class="card-header d-flex align-items-center" id="previewHeader" 
                                 style="background-color: {{ $party->color }}; color: white;">
                                @if($party->logo)
                                    <img src="{{ Storage::url($party->logo) }}" id="previewLogo" 
                                         class="rounded me-2" style="width:30px;height:30px;object-fit:cover">
                                @else
                                    <i class="bi bi-flag-fill me-2" id="previewIcon" style="font-size: 1.25rem;"></i>
                                @endif
                                <span id="previewName">{{ $party->name }}</span>
                                <span class="ms-auto badge bg-success">Active</span>
                            </div>
                            <div class="card-body">
                                <p class="fst-italic mb-0">"<span id="previewSlogan">{{ $party->slogan }}</span>"</p>
                                <small class="text-muted">Live Preview</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.parties.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2 me-1"></i>Update Party
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const sloganInput = document.querySelector('input[name="slogan"]');
    const colorPicker = document.getElementById('colorPicker');
    const colorHex = document.getElementById('colorHex');
    const logoInput = document.querySelector('input[name="logo"]');
    const removeLogo = document.getElementById('removeLogo');
    
    const previewName = document.getElementById('previewName');
    const previewSlogan = document.getElementById('previewSlogan');
    const previewHeader = document.getElementById('previewHeader');
    const previewIcon = document.getElementById('previewIcon');
    const previewLogo = document.getElementById('previewLogo');
    
    // Update name preview
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || '{{ $party->name }}';
        });
    }
    
    // Update slogan preview
    if (sloganInput) {
        sloganInput.addEventListener('input', function() {
            previewSlogan.textContent = this.value || '{{ $party->slogan }}';
        });
    }
    
    // Update color preview
    if (colorPicker) {
        colorPicker.addEventListener('input', function() {
            const color = this.value;
            colorHex.value = color;
            previewHeader.style.backgroundColor = color;
            
            // Calculate contrast for text color
            const r = parseInt(color.substr(1,2),16);
            const g = parseInt(color.substr(3,2),16);
            const b = parseInt(color.substr(5,2),16);
            const brightness = ((r * 299) + (g * 587) + (b * 114)) / 1000;
            previewHeader.style.color = brightness > 128 ? 'black' : 'white';
        });
    }
    
    // Handle logo preview
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewLogo) {
                        previewLogo.src = e.target.result;
                        previewLogo.style.display = 'inline';
                    } else {
                        // Create new logo element
                        const newLogo = document.createElement('img');
                        newLogo.id = 'previewLogo';
                        newLogo.src = e.target.result;
                        newLogo.className = 'rounded me-2';
                        newLogo.style.cssText = 'width:30px;height:30px;object-fit:cover';
                        
                        if (previewIcon) {
                            previewIcon.replaceWith(newLogo);
                        } else {
                            previewHeader.querySelector('.d-flex').insertBefore(newLogo, previewName);
                        }
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Handle remove logo checkbox
    if (removeLogo) {
        removeLogo.addEventListener('change', function() {
            if (this.checked && previewLogo) {
                const icon = document.createElement('i');
                icon.id = 'previewIcon';
                icon.className = 'bi bi-flag-fill me-2';
                icon.style.fontSize = '1.25rem';
                previewLogo.replaceWith(icon);
            }
        });
    }
});
</script>
@endpush
