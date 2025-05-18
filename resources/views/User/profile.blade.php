@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-0 fw-bold"><i class="fas fa-user-cog me-2"></i>Profil Pengguna</h3>
            <p class="text-muted">Kelola informasi akun Anda</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="user-avatar mx-auto mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit me-1"></i>Edit Profil
                        </button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0">Aktivitas</h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $alternativeCount = \App\Models\AlternativeValue::where('user_id', Auth::id())
                            ->select('alternative_id')
                            ->distinct()
                            ->count();
                        $calculationCount = \App\Models\Calculation::where('user_id', Auth::id())->count();
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="mb-0">Alternatif Dinilai</p>
                        </div>
                        <div>
                            <span class="badge bg-primary">{{ $alternativeCount }}</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0">Perhitungan Dibuat</p>
                        </div>
                        <div>
                            <span class="badge bg-success">{{ $calculationCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Keamanan Akun</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Informasi</h6>
                                <p class="mb-0">Pastikan untuk selalu menjaga kerahasiaan akun Anda dan menggunakan password yang kuat.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Ubah Password</h5>
                        <p>Untuk meningkatkan keamanan akun, sebaiknya ubah password Anda secara berkala.</p>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fas fa-key me-1"></i>Ubah Password
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h5 class="fw-bold mb-3">Detail Akun</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%" class="bg-light">Nama</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Terdaftar pada</th>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Password minimal 8 karakter.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4361ee;
    }
</style>
@endsection