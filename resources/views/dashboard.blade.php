<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SPK KELOMPOK 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #0d6efd;
            color: white;
            padding: 2rem 0;
        }
        .dashboard-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            height: 100%;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
        .progress-bar-section {
            padding: 1.5rem;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .summary-card {
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
        .summary-card h2 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 0;
        }
        .bg-blue-light {
            background-color: #e6f2ff;
        }
        .bg-green-light {
            background-color: #e6fff2;
        }
        .bg-purple-light {
            background-color: #f2e6ff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-server me-2"></i>
                SPK KELOMPOK 5
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/dashboard">Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <h1>Dashboard</h1>
            <p class="lead">Sistem Pendukung Keputusan dengan Metode ROC dan MAIRCA</p>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container py-5">
        <!-- Summary Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="summary-card bg-blue-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Total Kriteria</p>
                            <h2>{{ $criteriaCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-list-ol fa-3x text-primary opacity-25"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('criteria.index') }}" class="btn btn-sm btn-primary">Kelola Kriteria</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card bg-green-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Total Alternatif</p>
                            <h2>{{ $alternativeCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-server fa-3x text-success opacity-25"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('alternatives.index') }}" class="btn btn-sm btn-success">Kelola Alternatif</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card bg-purple-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Total Perhitungan</p>
                            <h2>{{ $calculationCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-calculator fa-3x text-purple opacity-25"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('calculations.index') }}" class="btn btn-sm btn-primary">Lihat Perhitungan</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-bar-section mb-5">
            <h4 class="mb-4">Kemajuan Sistem</h4>
            
            @php
                $criteriaProgress = $criteriaCount > 0 ? 100 : 0;
                $alternativeProgress = $alternativeCount > 0 ? 100 : 0;
                
                $criteriaWithWeights = App\Models\Criteria::whereNotNull('weight')->count();
                $weightProgress = $criteriaCount > 0 ? ($criteriaWithWeights / $criteriaCount) * 100 : 0;
                
                $alternativesWithCompleteValues = 0;
                if($alternativeCount > 0 && $criteriaCount > 0) {
                    foreach(App\Models\Alternative::all() as $alt) {
                        $valueCount = $alt->values->count();
                        if($valueCount == $criteriaCount) {
                            $alternativesWithCompleteValues++;
                        }
                    }
                    $valuesProgress = ($alternativesWithCompleteValues / $alternativeCount) * 100;
                } else {
                    $valuesProgress = 0;
                }
                
                $calculationProgress = $calculationCount > 0 ? 100 : 0;
                
                $overallProgress = ($criteriaProgress + $alternativeProgress + $weightProgress + $valuesProgress + $calculationProgress) / 5;
            @endphp
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Kriteria Ditentukan</span>
                    <span>{{ $criteriaProgress }}%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $criteriaProgress }}%" aria-valuenow="{{ $criteriaProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Bobot Kriteria Dihitung (ROC)</span>
                    <span>{{ number_format($weightProgress) }}%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $weightProgress }}%" aria-valuenow="{{ $weightProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Alternatif Ditentukan</span>
                    <span>{{ $alternativeProgress }}%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $alternativeProgress }}%" aria-valuenow="{{ $alternativeProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Nilai Alternatif Dilengkapi</span>
                    <span>{{ number_format($valuesProgress) }}%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $valuesProgress }}%" aria-valuenow="{{ $valuesProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Perhitungan MAIRCA Dilakukan</span>
                    <span>{{ $calculationProgress }}%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $calculationProgress }}%" aria-valuenow="{{ $calculationProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="d-flex justify-content-between mb-1">
                    <span><strong>Kemajuan Keseluruhan</strong></span>
                    <span><strong>{{ number_format($overallProgress) }}%</strong></span>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $overallProgress }}%" aria-valuenow="{{ $overallProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h4 class="mb-4">Tindakan Cepat</h4>
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-list-ol feature-icon"></i>
                        </div>
                        <h5 class="card-title text-center">Kelola Kriteria</h5>
                        <p class="card-text">
                            Tentukan kriteria yang akan digunakan dalam pengambilan keputusan. Setiap kriteria akan dihitung bobotnya menggunakan metode ROC.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('criteria.index') }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i> Lihat Kriteria
                            </a>
                            <a href="{{ route('criteria.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i> Tambah Kriteria
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-server feature-icon"></i>
                        </div>
                        <h5 class="card-title text-center">Kelola Alternatif</h5>
                        <p class="card-text">
                            Tambahkan alternatif penyedia VPS Cloud yang ingin dibandingkan. Masukkan data setiap alternatif sesuai dengan kriteria yang telah ditentukan.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('alternatives.index') }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i> Lihat Alternatif
                            </a>
                            <a href="{{ route('alternatives.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i> Tambah Alternatif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-balance-scale feature-icon"></i>
                        </div>
                        <h5 class="card-title text-center">Perhitungan Bobot ROC</h5>
                        <p class="card-text">
                            Hitung bobot kriteria menggunakan metode Rank Order Centroid (ROC) berdasarkan urutan prioritas yang telah ditentukan pada setiap kriteria.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('criteria.calculate-weights') }}" class="btn btn-success">
                                <i class="fas fa-calculator me-1"></i> Hitung Bobot
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-chart-bar feature-icon"></i>
                        </div>
                        <h5 class="card-title text-center">Perhitungan MAIRCA</h5>
                        <p class="card-text">
                            Lakukan perhitungan peringkat alternatif menggunakan metode MAIRCA berdasarkan bobot ROC dan nilai alternatif yang telah dimasukkan.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('calculations.create') }}" class="btn btn-success">
                                <i class="fas fa-calculator me-1"></i> Mulai Perhitungan
                            </a>
                            <a href="{{ route('calculations.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-history me-1"></i> Riwayat Perhitungan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Workflow Section -->
        <div class="card mb-5">
            <div class="card-header">
                <h5 class="mb-0">Alur Kerja Penggunaan Sistem</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <ol class="step-list">
                            <li class="mb-3">
                                <strong>Tentukan Kriteria:</strong> Tambahkan kriteria-kriteria yang akan digunakan dalam pengambilan keputusan. Pastikan untuk menentukan jenis kriteria (benefit/cost) dan peringkat prioritasnya.
                            </li>
                            <li class="mb-3">
                                <strong>Hitung Bobot ROC:</strong> Gunakan metode ROC untuk menghitung bobot setiap kriteria berdasarkan peringkat prioritas yang telah ditentukan.
                            </li>
                            <li class="mb-3">
                                <strong>Tambahkan Alternatif:</strong> Masukkan daftar alternatif penyedia VPS Cloud yang akan dibandingkan.
                            </li>
                            <li class="mb-3">
                                <strong>Input Nilai Alternatif:</strong> Masukkan nilai untuk setiap alternatif berdasarkan kriteria yang telah ditentukan.
                            </li>
                            <li class="mb-3">
                                <strong>Lakukan Perhitungan MAIRCA:</strong> Jalankan perhitungan metode MAIRCA untuk mendapatkan peringkat alternatif terbaik.
                            </li>
                            <li>
                                <strong>Analisis Hasil:</strong> Lihat detail hasil perhitungan dan peringkat alternatif untuk mendukung pengambilan keputusan.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Method Info Section -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Tentang Metode ROC</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Rank Order Centroid (ROC)</strong> adalah metode pembobotan yang menentukan bobot kriteria berdasarkan urutan prioritas atau kepentingan kriteria.
                        </p>
                        <p>
                            Formula perhitungan bobot kriteria dengan metode ROC:
                        </p>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-1">Wj = (1/K) × (1 + 1/2 + 1/3 + ... + 1/j)</p>
                            <p class="mb-0">
                                dimana:<br>
                                Wj = bobot kriteria ke-j<br>
                                K = jumlah kriteria<br>
                                j = urutan kriteria
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Tentang Metode MAIRCA</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Multi-Attribute Ideal-Real Comparative Analysis (MAIRCA)</strong> adalah metode pengambilan keputusan multi-kriteria yang membandingkan nilai ideal dan nilai sebenarnya dari alternatif.
                        </p>
                        <p>
                            Langkah-langkah metode MAIRCA:
                        </p>
                        <ol>
                            <li>Membuat matriks keputusan</li>
                            <li>Menentukan nilai preferensi alternatif</li>
                            <li>Menghitung nilai matriks evaluasi teoritis</li>
                            <li>Menghitung nilai matriks evaluasi realistis</li>
                            <li>Menghitung matriks total gap</li>
                            <li>Menghitung nilai akhir fungsi dan peringkat alternatif</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>
                &copy; {{ date('Y') }} SPK KELOMPOK 5 | Metode ROC dan MAIRCA
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>