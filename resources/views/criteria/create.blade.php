<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kriteria - SPK KELOMPOK 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f8fa;
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
            <h2>Tambah Kriteria Baru</h2>
            <a href="{{ route('criteria.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Kriteria Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('criteria.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Kriteria</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="Contoh: C1, C2, dst" required>
                        <div class="form-text">Gunakan kode unik singkat seperti C1, C2, C3, dst.</div>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Harga Perbulan" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Kriteria</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="" selected disabled>Pilih jenis kriteria</option>
                            <option value="benefit" {{ old('type') == 'benefit' ? 'selected' : '' }}>Benefit (Semakin tinggi nilai semakin baik)</option>
                            <option value="cost" {{ old('type') == 'cost' ? 'selected' : '' }}>Cost (Semakin rendah nilai semakin baik)</option>
                        </select>
                        <div class="form-text">
                            <ul>
                                <li><strong>Benefit</strong>: Nilai yang lebih tinggi lebih disukai (misal: kapasitas RAM, jumlah CPU)</li>
                                <li><strong>Cost</strong>: Nilai yang lebih rendah lebih disukai (misal: harga, konsumsi daya)</li>
                            </ul>
                        </div>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="rank" class="form-label">Peringkat Prioritas</label>
                        <input type="number" class="form-control @error('rank') is-invalid @enderror" id="rank" name="rank" value="{{ old('rank') }}" min="1" placeholder="Contoh: 1, 2, 3, dst" required>
                        <div class="form-text">
                            Urutan prioritas dari yang paling penting (1) ke yang kurang penting. Nilai ini digunakan untuk menghitung bobot dengan metode ROC.
                        </div>
                        @error('rank')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Bobot kriteria akan dihitung secara otomatis menggunakan metode ROC berdasarkan peringkat prioritas yang Anda tentukan.
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Kriteria
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Contoh Kriteria untuk Pemilihan VPS Cloud</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Kriteria</th>
                                <th>Jenis</th>
                                <th>Peringkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>C1</td>
                                <td>Harga Perbulan (Rupiah)</td>
                                <td>Cost</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>C2</td>
                                <td>Kapasitas RAM (GB)</td>
                                <td>Benefit</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>C3</td>
                                <td>Jumlah CPU (Core)</td>
                                <td>Benefit</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>C4</td>
                                <td>Kapasitas Penyimpanan (GB)</td>
                                <td>Benefit</td>
                                <td>4</td>
                            </tr>
                            <tr>
                                <td>C5</td>
                                <td>Jenis Penyimpanan</td>
                                <td>Benefit</td>
                                <td>5</td>
                            </tr>
                        </tbody>
                    </table>
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