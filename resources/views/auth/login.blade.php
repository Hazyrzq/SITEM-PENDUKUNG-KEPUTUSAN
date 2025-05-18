@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Login card with enhanced styling -->
            <div class="card border-0 shadow-lg">
                <div class="card-header py-3" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sign-in-alt fa-2x me-3"></i>
                        <h4 class="mb-0 fw-bold">Login</h4>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show animate-in mb-4" role="alert">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading mb-1">Berhasil!</h5>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <!-- Login form with visual improvements -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- Decorative element -->
                        <div class="text-center mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px">
                                <i class="fas fa-user-circle fa-3x" style="color: var(--primary-color);"></i>
                            </div>
                            <h5 class="mt-3 fw-bold text-secondary">Masuk ke Akun Anda</h5>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">
                                <i class="fas fa-envelope me-2" style="color: var(--primary-color);"></i>Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input id="email" type="email" 
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" 
                                    required autofocus 
                                    placeholder="Masukkan email Anda">
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label fw-medium">
                                    <i class="fas fa-lock me-2" style="color: var(--primary-color);"></i>Password
                                </label>
                                <!-- Password reset link added -->
                                <a href="#" class="small text-decoration-none" style="color: var(--primary-color);">Lupa password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" type="password" 
                                    class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                    name="password" required 
                                    placeholder="Masukkan password Anda">
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg py-3 fw-medium" 
                                style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none;">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>

                        <!-- Separator with text -->
                        <div class="position-relative text-center my-4">
                            <hr class="text-muted">
                            <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted">atau</span>
                        </div>
                        
                        <!-- Registration link with improved styling -->
                        <div class="text-center">
                            <p class="mb-0">Belum punya akun? 
                                <a href="{{ route('register') }}" class="fw-medium text-decoration-none" style="color: var(--primary-color);">
                                    Daftar di sini <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
                
                <!-- Optional: Footer with security message -->
                <div class="card-footer bg-white py-3 text-center border-0">
                    <div class="d-flex align-items-center justify-content-center text-muted small">
                        <i class="fas fa-shield-alt me-2"></i>
                        <span>Keamanan login terjamin dengan enkripsi 256-bit</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection