<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alternatif - SPK KELOMPOK 5</title>
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
                        <a class="nav-link active" href="/alternatives">Alternatif</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tambah Alternatif Baru</h2>
            <a href="{{ route('alternatives.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Alternatif Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('alternatives.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Alternatif</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="Contoh: A1, A2, dst" required>
                        <div class="form-text">Gunakan kode unik singkat seperti A1, A2, A3, dst.</div>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Alternatif</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Dewaweb, Rumahweb, dst" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Deskripsi singkat tentang alternatif">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Setelah menambahkan alternatif, Anda dapat mengisi nilai-nilai kriteria untuk alternatif ini melalui tombol "Nilai" pada daftar alternatif.
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Alternatif
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Contoh Alternatif Penyedia VPS Cloud</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Alternatif</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A1</td>
                                <td>Qwords</td>
                                <td>Penyedia layanan VPS Cloud Qwords</td>
                            </tr>
                            <tr>
                                <td>A2</td>
                                <td>Domainesia</td>
                                <td>Penyedia layanan VPS Cloud Domainesia</td>
                            </tr>
                            <tr>
                                <td>A3</td>
                                <td>Dewaweb</td>
                                <td>Penyedia layanan VPS Cloud Dewaweb</td>
                            </tr>
                            <tr>
                                <td>A4</td>
                                <td>Idwebhost</td>
                                <td>Penyedia layanan VPS Cloud Idwebhost</td>
                            </tr>
                            <tr>
                                <td>A5</td>
                                <td>Rumahweb</td>
                                <td>Penyedia layanan VPS Cloud Rumahweb</td>
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