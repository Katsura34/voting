@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm mt-5">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-ballot text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-2">School Voting System</h3>
                        <p class="text-muted">Sign in to your account</p>
                    </div>
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- USN Field -->
                        <div class="mb-3">
                            <label for="usn" class="form-label">
                                <i class="bi bi-person"></i> USN (University Serial Number)
                            </label>
                            <input type="text" 
                                   class="form-control @error('usn') is-invalid @enderror" 
                                   id="usn" 
                                   name="usn" 
                                   value="{{ old('usn') }}" 
                                   placeholder="Enter your USN"
                                   required 
                                   autofocus>
                            @error('usn')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Password
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Sign In
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p class="text-muted">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                Register here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection