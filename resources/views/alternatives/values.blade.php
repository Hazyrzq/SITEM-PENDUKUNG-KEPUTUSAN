<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai Alternatif - SPK KELOMPOK 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f8fa;
        }
        .container {
            max-width: 900px;
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
        .badge-type {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
        .badge-benefit {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .badge-cost {
            background-color: #f8d7da;
            color: #842029;
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
                        <a class="nav-link" href="/criteria">Kriteria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/alternatives">Alternatif</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/calculations">Perhitungan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Input Nilai Alternatif: {{ $alternative->name }}</h2>
            <a href="{{ route('alternatives.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Input Nilai Kriteria</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('alternatives.store-values', $alternative) }}" method="POST">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th>Jenis</th>
                                    <th>Nilai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($criteria as $criterion)
                                    <tr>
                                        <td>{{ $criterion->code }}</td>
                                        <td>{{ $criterion->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $criterion->type }} badge-type">
                                                {{ $criterion->type == 'benefit' ? 'Benefit' : 'Cost' }}
                                            </span>
                                        </td>
                                        <td>
                                            <input 
                                                type="number" 
                                                class="form-control @error('values.' . $criterion->id) is-invalid @enderror" 
                                                name="values[{{ $criterion->id }}]" 
                                                step="any"
                                                value="{{ $values[$criterion->id] ?? old('values.' . $criterion->id) }}" 
                                                required
                                            >
                                            @error('values.' . $criterion->id)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            @if($criterion->code == 'C1')
                                                Harga dalam Rupiah
                                            @elseif($criterion->code == 'C2')
                                                Kapasitas RAM dalam GB
                                            @elseif($criterion->code == 'C3')
                                                Jumlah Core CPU
                                            @elseif($criterion->code == 'C4')
                                                Kapasitas dalam GB
                                            @elseif($criterion->code == 'C5')
                                                1=SSD, 2=NVMe
                                            @elseif($criterion->code == 'C6')
                                                1=Ya, 0=Tidak
                                            @elseif($criterion->code == 'C7')
                                                1=Ya, 0=Tidak
                                            @else
                                                Masukkan nilai sesuai kriteria
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Contoh Input Nilai Alternatif VPS Cloud</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
                                <th>C1 (Harga)</th>
                                <th>C2 (RAM)</th>
                                <th>C3 (CPU)</th>
                                <th>C4 (Storage)</th>
                                <th>C5 (Storage Type)</th>
                                <th>C6 (Bandwidth)</th>
                                <th>C7 (Multi OS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Qwords</td>
                                <td>135000</td>
                                <td>1</td>
                                <td>1</td>
                                <td>25</td>
                                <td>1</td>
                                <td>0</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>Domainesia</td>
                                <td>80000</td>
                                <td>1</td>
                                <td>1</td>
                                <td>20</td>
                                <td>2</td>
                                <td>1</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Dewaweb</td>
                                <td>300000</td>
                                <td>1</td>
                                <td>1</td>
                                <td>20</td>
                                <td>2</td>
                                <td>1</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <p><strong>Catatan:</strong></p>
                    <ul>
                        <li>Untuk kriteria <strong>C1 (Harga)</strong>: masukkan nilai dalam Rupiah</li>
                        <li>Untuk kriteria <strong>C2 (RAM)</strong>: masukkan nilai dalam GB</li>
                        <li>Untuk kriteria <strong>C3 (CPU)</strong>: masukkan jumlah core CPU</li>
                        <li>Untuk kriteria <strong>C4 (Storage)</strong>: masukkan nilai dalam GB</li>
                        <li>Untuk kriteria <strong>C5 (Storage Type)</strong>: masukkan 1 untuk SSD, 2 untuk NVMe</li>
                        <li>Untuk kriteria <strong>C6 (Bandwidth)</strong>: masukkan 1 untuk Unlimited, 0 untuk Terbatas</li>
                        <li>Untuk kriteria <strong>C7 (Multi OS)</strong>: masukkan 1 untuk Ya, 0 untuk Tidak</li>
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