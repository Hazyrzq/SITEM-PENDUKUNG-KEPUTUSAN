
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK KELOMPOK 5 - ROC dan MAIRCA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            padding: 6rem 0;
            background-color: #0d6efd;
            color: white;
        }
        .feature-box {
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s;
            background-color: white;
        }
        .feature-box:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">SPK KELOMPOK 5</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    
                   
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Sistem Pendukung Keputusan</h1>
            <h2 class="display-6">Pemilihan VPS Cloud dengan Metode ROC dan MAIRCA</h2>
            <p class="lead mt-4">
                Temukan Pemilihan Metode SPK untuk kebutuhan Anda dengan pendekatan yang objektif dan terukur.
            </p>
            <div class="mt-5">
                <a href="/dashboard" class="btn btn-light btn-lg px-4 me-md-2">Lihat Dashboard</a>
                <a href="/calculations" class="btn btn-outline-light btn-lg px-4">Mulai Perhitungan</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container py-5">
        <h2 class="text-center mb-5">Fitur Utama</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon">📊</div>
                    <h4>Metode ROC</h4>
                    <p>Rank Order Centroid untuk menentukan bobot kriteria berdasarkan urutan prioritas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon">📈</div>
                    <h4>Metode MAIRCA</h4>
                    <p>Multi-Attribute Ideal-Real Comparative Analysis untuk evaluasi dan peringkat alternatif.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon">💻</div>
                    <h4>Rekomendasi Metode</h4>
                    <p>Hasil analisis objektif untuk membantu individu dan pelaku usaha mikro memilih VPS Cloud terbaik.</p>
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