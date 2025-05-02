<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kriteria - SPK KELOMPOK 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f8fa;
        }
        .container {
            max-width: 1140px;
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
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/criteria">Kriteria</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Kriteria</h2>
            <a href="{{ route('criteria.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Kriteria</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('criteria.update', $criterion) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Kriteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $criterion->code) }}" required>
                        <div class="form-text">Contoh: C1, C2, KRIT01, dll.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $criterion->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Kriteria <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="benefit" {{ old('type', $criterion->type) == 'benefit' ? 'selected' : '' }}>Benefit (makin tinggi makin baik)</option>
                            <option value="cost" {{ old('type', $criterion->type) == 'cost' ? 'selected' : '' }}>Cost (makin rendah makin baik)</option>
                        </select>
                        <div class="form-text">
                            <ul class="mb-0">
                                <li><strong>Benefit</strong>: semakin tinggi nilai, semakin baik (misal: kualitas, keuntungan)</li>
                                <li><strong>Cost</strong>: semakin rendah nilai, semakin baik (misal: biaya, risiko)</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rank" class="form-label">Prioritas <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="rank" name="rank" value="{{ old('rank', $criterion->rank) }}" min="1" required>
                        <div class="form-text">
                            Urutan kepentingan kriteria. Nilai 1 berarti prioritas tertinggi.
                            Prioritas ini digunakan untuk menghitung bobot dengan metode ROC.
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Tentang Prioritas Kriteria</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>Prioritas kriteria</strong> menentukan tingkat kepentingan suatu kriteria dibandingkan dengan kriteria lainnya.
                    Prioritas ini digunakan dalam perhitungan bobot dengan metode ROC (Rank Order Centroid).
                </p>
                <div class="alert alert-info">
                    <strong>Catatan penting:</strong>
                    <ul class="mb-0">
                        <li>Kriteria dengan prioritas 1 adalah yang paling penting</li>
                        <li>Tidak boleh ada dua kriteria dengan prioritas yang sama</li>
                        <li>Setelah mengubah prioritas, Anda perlu menghitung ulang bobot dengan mengklik tombol "Hitung Bobot ROC" pada halaman daftar kriteria</li>
                    </ul>
                </div>
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