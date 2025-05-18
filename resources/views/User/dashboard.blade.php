@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h4>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Selamat Datang!</h6>
                        <p class="mb-0">Halo, {{ Auth::user()->name }}! Ini adalah panel dashboard Anda untuk Sistem Pendukung Keputusan berbasis MAIRCA.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stats-card-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0">Alternatif</h5>
                                    <small class="text-muted">Alternatif yang telah dinilai</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bold mb-0 me-2">{{ $alternativeCount }}</h3>
                                <span class="text-muted">dari {{ $totalAlternatives }} alternatif</span>
                            </div>
                            <div class="progress mt-3" style="height: 8px;">
                                @php
                                    // Hitung persentase dengan benar dan batasi maksimal 100%
                                    $alternativePercentage = $totalAlternatives > 0 ? min(100, ($alternativeCount / $totalAlternatives) * 100) : 0;
                                @endphp
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $alternativePercentage }}%;"></div>
                            </div>
                            <div class="small text-end mt-2">{{ number_format($alternativePercentage, 1) }}% dinilai</div>
                            <div class="mt-auto pt-3">
                                <a href="{{ route('user.alternatives.index') }}" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-pencil-alt me-1"></i>Nilai Alternatif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stats-card-icon bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0">Perhitungan</h5>
                                    <small class="text-muted">Perhitungan yang telah dilakukan</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bold mb-0">{{ $calculationCount }}</h3>
                            </div>
                            @if($criteriaWithoutWeight)
                                <div class="alert alert-warning mt-3 mb-0">
                                    <small><i class="fas fa-exclamation-triangle me-1"></i>Admin belum menghitung bobot kriteria</small>
                                </div>
                            @elseif($alternativeCount < 2)
                                <div class="alert alert-info mt-3 mb-0">
                                    <small><i class="fas fa-info-circle me-1"></i>Nilai minimal 2 alternatif untuk melakukan perhitungan</small>
                                </div>
                            @endif
                            <div class="mt-auto pt-3">
                                <a href="{{ route('user.calculations.create') }}" class="btn btn-sm btn-success w-100 {{ ($criteriaWithoutWeight || $alternativeCount < 2) ? 'disabled' : '' }}">
                                    <i class="fas fa-plus-circle me-1"></i>Buat Perhitungan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stats-card-icon bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-list-check"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0">Kriteria</h5>
                                    <small class="text-muted">Kriteria yang digunakan</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bold mb-0 me-2">{{ $criteriaWithWeight }}</h3>
                                <span class="text-muted">dari {{ $totalCriteria }} kriteria memiliki bobot</span>
                            </div>
                            <div class="progress mt-3" style="height: 8px;">
                                @php
                                    $criteriaPercentage = $totalCriteria > 0 ? ($criteriaWithWeight / $totalCriteria) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $criteriaPercentage }}%;"></div>
                            </div>
                            <div class="small text-end mt-2">{{ number_format($criteriaPercentage, 1) }}% dengan bobot</div>
                            <div class="mt-auto pt-3">
                                <a href="{{ route('user.criteria.index') }}" class="btn btn-sm btn-info w-100">
                                    <i class="fas fa-eye me-1"></i>Lihat Kriteria
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
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
                                    <p class="mb-0">Anda belum melakukan perhitungan apapun.</p>
                                    <a href="{{ route('user.calculations.create') }}" class="btn btn-sm btn-primary mt-2 {{ ($criteriaWithoutWeight || $alternativeCount < 2) ? 'disabled' : '' }}">
                                        <i class="fas fa-plus-circle me-1"></i>Buat Perhitungan
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Perhitungan</th>
                                        <th>Tanggal</th>
                                        <th>Alternatif Terbaik</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCalculations as $calculation)
                                        <tr>
                                            <td class="fw-semibold">{{ $calculation->name }}</td>
                                            <td>{{ $calculation->calculated_at ? $calculation->calculated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $results = is_string($calculation->results) ? json_decode($calculation->results, true) : [];
                                                    $finalValues = isset($results['final_values']) ? $results['final_values'] : [];
                                                    
                                                    // PERBAIKAN: Menggunakan nilai tertinggi sebagai alternatif terbaik
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
                                                    <span class="text-muted">Tidak ada data</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('user.calculations.show', $calculation) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Lihat</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('user.calculations.index') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-list me-1"></i>Lihat Semua Perhitungan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h4>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Tentang SPK MAIRCA</h5>
                        <p>Sistem Pendukung Keputusan ini menggunakan metode MAIRCA dan pembobotan ROC untuk membantu Anda dalam memilih alternatif terbaik berdasarkan kriteria yang telah ditentukan.</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">Langkah Penggunaan</h5>
                        <ol class="ps-3">
                            <li class="mb-2">Lihat kriteria yang telah ditentukan oleh admin</li>
                            <li class="mb-2">Berikan nilai untuk alternatif yang tersedia</li>
                            <li class="mb-2">Buat perhitungan baru menggunakan metode MAIRCA</li>
                            <li class="mb-2">Analisis hasil perhitungan dan ambil keputusan</li>
                        </ol>
                    </div>

                    <div class="alert alert-light border-primary border-start border-4">
                        <div class="d-flex">
                            <div class="me-3 text-primary">
                                <i class="fas fa-lightbulb fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Tips</h6>
                                <p class="mb-0">Semakin banyak alternatif yang Anda nilai, semakin akurat hasil perbandingan yang akan didapatkan.</p>
                            </div>
                        </div>
                    </div>
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