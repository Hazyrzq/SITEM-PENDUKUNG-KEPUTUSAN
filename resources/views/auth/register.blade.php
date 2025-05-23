@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Register card with enhanced styling -->
            <div class="card border-0 shadow-lg">
                <div class="card-header py-3" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-plus fa-2x me-3"></i>
                        <h4 class="mb-0 fw-bold">Registrasi</h4>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Register form with visual improvements -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- Decorative element -->
                        <div class="text-center mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px">
                                <i class="fas fa-user-edit fa-3x" style="color: var(--primary-color);"></i>
                            </div>
                            <h5 class="mt-3 fw-bold text-secondary">Buat Akun Baru</h5>
                        </div>

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">
                                <i class="fas fa-user me-2" style="color: var(--primary-color);"></i>Nama
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input id="name" type="text" 
                                    class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                    name="name" value="{{ old('name') }}" 
                                    required autofocus 
                                    placeholder="Masukkan nama lengkap Anda">
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
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
                                    required 
                                    placeholder="Masukkan alamat email Anda">
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">
                                <i class="fas fa-lock me-2" style="color: var(--primary-color);"></i>Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" type="password" 
                                    class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                    name="password" required 
                                    placeholder="Buat password Anda">
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-medium">
                                <i class="fas fa-check-circle me-2" style="color: var(--primary-color);"></i>Konfirmasi Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <input id="password-confirm" type="password" 
                                    class="form-control form-control-lg" 
                                    name="password_confirmation" required 
                                    placeholder="Ulangi password Anda">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg py-3 fw-medium" 
                                style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none;">
                                <i class="fas fa-user-plus me-2"></i>Daftar
                            </button>
                        </div>

                        <!-- Separator with text -->
                        <div class="position-relative text-center my-4">
                            <hr class="text-muted">
                            <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted">atau</span>
                        </div>
                        
                        <!-- Login link with improved styling -->
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun? 
                                <a href="{{ route('login') }}" class="fw-medium text-decoration-none" style="color: var(--primary-color);">
                                    Login di sini <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
                
                <!-- Optional: Footer with security message -->
                <div class="card-footer bg-white py-3 text-center border-0">
                    <div class="d-flex align-items-center justify-content-center text-muted small">
                        <i class="fas fa-shield-alt me-2"></i>
                        <span>Data Anda dilindungi dengan enkripsi 256-bit</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection