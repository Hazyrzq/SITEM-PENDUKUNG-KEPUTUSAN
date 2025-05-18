@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="content-wrapper">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0 fw-bold"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</h3>
            <p class="text-muted">Kelola sistem pendukung keputusan</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-card-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">Pengguna</h5>
                            <small class="text-muted">Total pengguna terdaftar</small>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $userCount }}</h3>
                    <div class="mt-auto pt-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-eye me-1"></i>Lihat Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-card-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">Alternatif</h5>
                            <small class="text-muted">Jumlah alternatif</small>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $alternativeCount }}</h3>
                    <div class="mt-auto pt-3">
                        <a href="{{ route('admin.alternatives.index') }}" class="btn btn-sm btn-success w-100">
                            <i class="fas fa-eye me-1"></i>Lihat Alternatif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-card-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">Kriteria</h5>
                            <small class="text-muted">Jumlah kriteria</small>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $criteriaCount }}</h3>
                    <div class="mt-auto pt-3">
                        <a href="{{ route('admin.criteria.index') }}" class="btn btn-sm btn-info w-100">
                            <i class="fas fa-eye me-1"></i>Lihat Kriteria
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-card-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">Perhitungan</h5>
                            <small class="text-muted">Total perhitungan</small>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $calculationCount }}</h3>
                    <div class="mt-auto pt-3">
                        <a href="{{ route('admin.calculations.index') }}" class="btn btn-sm btn-warning w-100">
                            <i class="fas fa-eye me-1"></i>Lihat Perhitungan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <div class="row">
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0"><i class="fas fa-history me-2"></i>Perhitungan Terbaru</h4>
            </div>
            <div class="card-body p-4">
                @if($recentCalculations->isEmpty())
                    <div class="alert alert-info mb-0">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Informasi</h6>
                                <p class="mb-0">Belum ada perhitungan yang dilakukan.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Perhitungan</th>
                                    <th>User</th>
                                    <th>Tanggal</th>
                                    <th>Alternatif Terbaik</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCalculations as $calculation)
                                    <tr>
                                        <td class="fw-semibold">{{ $calculation->name }}</td>
                                        <td>{{ $calculation->user->name ?? 'User tidak ditemukan' }}</td>
                                        <td>{{ $calculation->calculated_at ? $calculation->calculated_at->format('d/m/Y H:i') : 'Tanggal tidak tersedia' }}</td>
                                        <td>
                                            @if(!empty($calculation->results))
                                                @php
                                                    // PERBAIKAN: Pastikan $calculation->results adalah string sebelum di-decode
                                                    $results = is_string($calculation->results) ? json_decode($calculation->results, true) : [];
                                                    $finalValues = isset($results['final_values']) ? $results['final_values'] : [];
                                                    
                                                    // PERBAIKAN: Menggunakan nilai maksimum sebagai alternatif terbaik
                                                    usort($finalValues, function($a, $b) {
                                                        return $b['final_value'] <=> $a['final_value'];
                                                    });
                                                    
                                                    $topResult = !empty($finalValues) ? $finalValues[0] : null;
                                                @endphp
                                                @if($topResult)
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-success me-1">{{ $topResult['code'] ?? 'N/A' }}</span>
                                                        {{ $topResult['name'] ?? 'Tidak ada nama' }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">Format hasil tidak valid</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Tidak ada data</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.calculations.show', $calculation) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('admin.calculations.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list me-1"></i>Lihat Semua Perhitungan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Aktivitas User</h4>
                </div>
                <div class="card-body p-4">
                    @if($userValueCounts->isEmpty())
                        <div class="alert alert-info mb-0">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Informasi</h6>
                                    <p class="mb-0">Belum ada user yang memberikan nilai alternatif.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <h5 class="fw-bold mb-3">Jumlah Nilai Alternatif per User</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>User</th>
                                            <th>Jumlah Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userValueCounts as $userValue)
                                            @php
                                                $user = \App\Models\User::find($userValue->user_id);
                                            @endphp
                                            @if($user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td><span class="badge bg-primary">{{ $userValue->value_count }}</span></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($criteriaWithoutWeight > 0)
                        <div class="alert alert-warning mt-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Perhatian</h6>
                                    <p class="mb-0">{{ $criteriaWithoutWeight }} kriteria belum memiliki bobot. User tidak dapat melakukan perhitungan sampai semua kriteria memiliki bobot.</p>
                                    <a href="{{ route('admin.criteria.calculate-weights') }}" class="btn btn-sm btn-warning mt-2">
                                        <i class="fas fa-calculator me-1"></i>Hitung Bobot Kriteria
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
</style>
@endsection