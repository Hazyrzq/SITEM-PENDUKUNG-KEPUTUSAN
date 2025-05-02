<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Perhitungan Baru - SPK KELOMPOK 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f8fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 15px 20px;
        }
        .card-body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">SPK KELOMPOK 5</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calculations">Perhitungan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="card">
            <div class="card-header">
                <h5>Buat Perhitungan Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('calculations.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Perhitungan</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Perhitungan VPS Cloud April 2025" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Masukkan deskripsi atau catatan untuk perhitungan ini">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i> Informasi</h6>
                        <p class="mb-0">
                            Perhitungan akan menggunakan metode MAIRCA berdasarkan bobot ROC yang telah dihitung.
                            Pastikan bobot kriteria sudah dihitung dengan metode ROC sebelum melakukan perhitungan MAIRCA.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <a href="{{ route('calculations.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calculator me-1"></i> Hitung
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Tentang Metode MAIRCA</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>Multi-Attributive Ideal-Real Comparative Analysis (MAIRCA)</strong> adalah metode pengambilan keputusan multi-kriteria yang membandingkan nilai ideal dan nilai sebenarnya dari alternatif.
                </p>
                <p>
                    Metode ini menggunakan bobot kriteria yang didapatkan dari metode <strong>Rank Order Centroid (ROC)</strong> untuk menghitung nilai akhir dan peringkat alternatif.
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

    <footer class="bg-dark text-white py-3 text-center mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} SPK KELOMPOK 5 - Implementasi Metode ROC dan MAIRCA</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>