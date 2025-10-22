@extends('layouts.app')

@section('title', 'Create Party')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-people-fill text-primary"></i> Create Political Party</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.parties.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Party Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-tag"></i> Party Name *
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="e.g., Progressive Party"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Party Color -->
                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">
                                    <i class="bi bi-palette"></i> Party Color *
                                </label>
                                <div class="input-group">
                                    <input type="color" 
                                           class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           id="color" 
                                           name="color" 
                                           value="{{ old('color', '#0d6efd') }}"
                                           title="Choose party color">
                                    <input type="text" 
                                           class="form-control" 
                                           id="color_text" 
                                           value="{{ old('color', '#0d6efd') }}"
                                           readonly>
                                </div>
                                @error('color')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Party Slogan -->
                        <div class="mb-3">
                            <label for="slogan" class="form-label">
                                <i class="bi bi-chat-quote"></i> Party Slogan *
                            </label>
                            <input type="text" 
                                   class="form-control @error('slogan') is-invalid @enderror" 
                                   id="slogan" 
                                   name="slogan" 
                                   value="{{ old('slogan') }}" 
                                   placeholder="e.g., Progress Through Unity"
                                   required>
                            @error('slogan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">A catchy phrase that represents your party's vision</div>
                        </div>
                        
                        <!-- Party Logo -->
                        <div class="mb-3">
                            <label for="logo" class="form-label">
                                <i class="bi bi-image"></i> Party Logo (Optional)
                            </label>
                            <input type="file" 
                                   class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" 
                                   name="logo" 
                                   accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Upload an image file (JPG, PNG, GIF). Maximum size: 2MB</div>
                        </div>
                        
                        <!-- Preview Section -->
                        <div class="mb-4">
                            <h5><i class="bi bi-eye"></i> Party Preview</h5>
                            <div class="card" id="party-preview">
                                <div class="card-header text-center text-white" style="background-color: #0d6efd;">
                                    <h5 class="mb-0" id="preview-name">Party Name</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div id="preview-logo">
                                        <i class="bi bi-people-fill" style="font-size: 3rem; color: #0d6efd;"></i>
                                    </div>
                                    <h6 class="text-muted mt-2" id="preview-slogan">Party Slogan</h6>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Parties
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Create Party
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Real-time preview updates
    document.getElementById('name').addEventListener('input', function() {
        document.getElementById('preview-name').textContent = this.value || 'Party Name';
    });
    
    document.getElementById('slogan').addEventListener('input', function() {
        document.getElementById('preview-slogan').textContent = this.value || 'Party Slogan';
    });
    
    document.getElementById('color').addEventListener('input', function() {
        const color = this.value;
        document.getElementById('color_text').value = color;
        document.querySelector('.card-header').style.backgroundColor = color;
        document.querySelector('#preview-logo i').style.color = color;
    });
    
    document.getElementById('logo').addEventListener('change', function() {
        const file = this.files[0];
        const previewLogo = document.getElementById('preview-logo');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewLogo.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="img-fluid" style="max-height: 80px;">`;
            };
            reader.readAsDataURL(file);
        } else {
            const color = document.getElementById('color').value;
            previewLogo.innerHTML = `<i class="bi bi-people-fill" style="font-size: 3rem; color: ${color};"></i>`;
        }
    });
</script>
@endsection