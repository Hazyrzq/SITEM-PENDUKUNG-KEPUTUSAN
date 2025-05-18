@extends('layouts.app')

@section('content')
<!-- Hero Section with Enhanced Design -->
<div class="hero-section position-relative">
    <div class="container text-center py-5">
        <h1 class="display-3 fw-bold mb-2 animate-in">Sistem Pendukung Keputusan</h1>
        <h2 class="h2 fw-semibold mb-4 animate-in">Pemilihan VPS Cloud dengan Metode ROC dan MAIRCA</h2>
        <p class="lead mt-4 mb-5 animate-in" style="max-width: 800px; margin-left: auto; margin-right: auto">
            Temukan VPS Cloud terbaik untuk kebutuhan Anda dengan pendekatan yang objektif dan terukur melalui kombinasi metode ROC dan MAIRCA.
        </p>
        <div class="mt-5 animate-in">
            @guest
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3 me-md-3 shadow-sm">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5 py-3 shadow-sm">
                    <i class="fas fa-user-plus me-2"></i>Register
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-5 py-3 me-md-3 shadow-sm">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="{{ route('calculations.create') }}" class="btn btn-outline-light btn-lg px-5 py-3 shadow-sm">
                    <i class="fas fa-calculator me-2"></i>Mulai Perhitungan
                </a>
            @endguest
        </div>
    </div>
    <!-- Decorative elements -->
    <div class="position-absolute bottom-0 end-0 d-none d-lg-block" style="opacity: 0.1">
        <i class="fas fa-server fa-10x"></i>
    </div>
    <div class="position-absolute top-50 start-0 d-none d-lg-block" style="opacity: 0.05">
        <i class="fas fa-cloud fa-8x"></i>
    </div>
</div>

<!-- Features Section -->
<div class="container py-5 mt-4">
    <h2 class="text-center mb-2 fw-bold">Fitur Utama</h2>
    <p class="text-center text-muted mb-5">Kenali keunggulan sistem pendukung keputusan kami</p>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-box text-center">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-sort-amount-down"></i>
                </div>
                <h4 class="fw-bold mb-3">Metode ROC</h4>
                <p class="text-muted">Rank Order Centroid untuk menentukan bobot kriteria berdasarkan urutan prioritas yang Anda tetapkan.</p>
                <div class="mt-4">
                    <span class="badge bg-primary rounded-pill px-3 py-2">Pembobotan Objektif</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="feature-box text-center">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 class="fw-bold mb-3">Metode MAIRCA</h4>
                <p class="text-muted">Multi-Attribute Ideal-Real Comparative Analysis untuk evaluasi dan peringkat alternatif secara komprehensif.</p>
                <div class="mt-4">
                    <span class="badge bg-primary rounded-pill px-3 py-2">Analisis Komparatif</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="feature-box text-center">
                <div class="feature-icon mx-auto">
                    <i class="fas fa-server"></i>
                </div>
                <h4 class="fw-bold mb-3">Rekomendasi VPS</h4>
                <p class="text-muted">Hasil analisis objektif untuk membantu individu dan pelaku usaha mikro memilih VPS Cloud terbaik.</p>
                <div class="mt-4">
                    <span class="badge bg-primary rounded-pill px-3 py-2">Keputusan Terukur</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Section with Cards -->
<div class="container py-5 mt-3">
    <div class="row">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title fw-bold mb-4">Tentang Sistem Pendukung Keputusan</h2>
                    <p class="lead fw-normal mb-3">SPK Pemilihan VPS Cloud menggunakan pendekatan ilmiah untuk membantu Anda memilih VPS terbaik.</p>
                    <p class="text-muted">Sistem Pendukung Keputusan (SPK) ini mengkombinasikan metode ROC untuk pembobotan kriteria dan MAIRCA untuk evaluasi alternatif, menghasilkan rekomendasi yang objektif dan terukur berdasarkan preferensi Anda.</p>
                    
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3">Keunggulan Menggunakan SPK:</h5>
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3 text-primary">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0">Membandingkan berbagai alternatif VPS Cloud secara objektif</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3 text-primary">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0">Mendapatkan rekomendasi berdasarkan kriteria yang relevan</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3 text-primary">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0">Menghemat waktu dalam proses pengambilan keputusan</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="me-3 text-primary">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0">Memastikan pilihan yang tepat sesuai kebutuhan Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Proses Pengambilan Keputusan</h4>
                    
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="fw-bold">1</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-semibold">Tentukan Kriteria</h5>
                            <p class="text-muted mb-0">Definisikan kriteria yang relevan untuk kebutuhan VPS Cloud Anda</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="fw-bold">2</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-semibold">Urutkan Prioritas</h5>
                            <p class="text-muted mb-0">Urutkan kriteria berdasarkan tingkat kepentingan untuk metode ROC</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="fw-bold">3</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-semibold">Tentukan Alternatif</h5>
                            <p class="text-muted mb-0">Masukkan alternatif VPS Cloud yang akan dibandingkan</p>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="me-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <span class="fw-bold">4</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-semibold">Dapatkan Hasil</h5>
                            <p class="text-muted mb-0">Lihat analisis MAIRCA dan peringkat rekomendasi VPS Cloud</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Methods Section with Explanation -->
<div class="bg-light py-5 mt-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Metode yang Digunakan</h2>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-sort-amount-down me-2"></i>Metode ROC</h4>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Rank Order Centroid</h5>
                        <p>Metode pembobotan kriteria yang menghitung bobot berdasarkan peringkat kepentingan kriteria. Metode ini memberikan bobot objektif sesuai dengan urutan prioritas Anda.</p>
                        
                        <div class="alert alert-light border-primary border-start border-4 mt-4">
                            <div class="d-flex">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Keunggulan ROC</h6>
                                    <p class="mb-0 small">Metode ini ideal untuk keadaan di mana pengguna dapat dengan mudah mengurutkan kriteria berdasarkan kepentingan tetapi sulit untuk memberikan nilai bobot secara langsung.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>Metode MAIRCA</h4>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Multi-Attribute Ideal-Real Comparative Analysis</h5>
                        <p>Metode untuk mengevaluasi alternatif-alternatif terhadap solusi ideal. MAIRCA membandingkan nilai ideal dengan nilai sebenarnya untuk setiap alternatif dan kriteria.</p>
                        
                        <div class="alert alert-light border-primary border-start border-4 mt-4">
                            <div class="d-flex">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Keunggulan MAIRCA</h6>
                                    <p class="mb-0 small">Metode ini memberikan peringkat yang stabil dan mempertimbangkan hubungan antara nilai ideal dan nilai aktual dari setiap alternatif.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="bg-primary text-white py-5 mt-5">
    <div class="container text-center py-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3">Mulai Gunakan SPK Pemilihan VPS Cloud Sekarang</h2>
                <p class="lead mb-4">Buat akun gratis dan temukan VPS Cloud terbaik untuk kebutuhan Anda dengan metode ROC dan MAIRCA</p>
                <div class="mt-4">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3 me-3 shadow">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5 py-3 shadow">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </a>
                    @else
                        <a href="{{ route('calculations.create') }}" class="btn btn-light btn-lg px-5 py-3 shadow">
                            <i class="fas fa-calculator me-2"></i>Mulai Perhitungan
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Section - Optional, only if you have team info -->
<div class="container py-5 mt-4">
    <h2 class="text-center fw-bold mb-2">Tim Pengembang</h2>
    <p class="text-center text-muted mb-5">Kelompok 5 - Sistem Pendukung Keputusan</p>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-4">Kelompok 5</h4>
                    <p class="lead">SPK Pemilihan VPS Cloud dengan metode ROC dan MAIRCA</p>
                    <p class="text-muted mb-0">Dikembangkan sebagai project Sistem Pendukung Keputusan</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection