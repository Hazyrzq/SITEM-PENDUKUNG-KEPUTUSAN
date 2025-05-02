<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kriteria - SPK KELOMPOK 5</title>
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
        .btn-action {
            margin-right: 5px;
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
                        <a class="nav-link active" href="/criteria">Kriteria</a>
                    </li>
               
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Kriteria</h2>
            <a href="{{ route('criteria.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Kriteria
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kriteria</h5>
                <a href="{{ route('criteria.calculate-weights') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-calculator me-1"></i> Hitung Bobot ROC
                </a>
            </div>
            <div class="card-body">
                @if($criteria->isEmpty())
                    <div class="alert alert-info mb-0">
                        Belum ada kriteria yang ditambahkan. Klik tombol "Tambah Kriteria" untuk memulai.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th>Jenis</th>
                                    <th>Prioritas</th>
                                    <th>Bobot</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($criteria->sortBy('rank') as $criterion)
                                    <tr>
                                        <td>{{ $criterion->code }}</td>
                                        <td>{{ $criterion->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $criterion->type }} badge-type">
                                                {{ $criterion->type == 'benefit' ? 'Benefit' : 'Cost' }}
                                            </span>
                                        </td>
                                        <td>{{ $criterion->rank }}</td>
                                        <td>
                                            @if($criterion->weight)
                                                {{ number_format($criterion->weight, 4) }}
                                            @else
                                                <span class="text-muted">Belum dihitung</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('criteria.edit', $criterion) }}" class="btn btn-sm btn-info btn-action">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('criteria.destroy', $criterion) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Yakin ingin menghapus kriteria ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Tentang Metode ROC (Rank Order Centroid)</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>Rank Order Centroid (ROC)</strong> adalah metode pembobotan yang menentukan bobot kriteria berdasarkan urutan prioritas atau kepentingan kriteria.
                </p>
                <p>
                    Metode ini dikembangkan oleh <strong>Barron dan Barrett (1996)</strong> dengan rumus sebagai berikut:
                </p>
                <div class="alert alert-light border">
                    <p>$$ W_k = \frac{1}{K} \sum_{i=k}^K \frac{1}{i} $$</p>
                    <p>di mana:</p>
                    <ul>
                        <li>$W_k$ = bobot kriteria ke-k</li>
                        <li>$K$ = jumlah kriteria</li>
                        <li>$i$ = posisi urutan kriteria</li>
                    </ul>
                </div>
                <p>
                    Dalam metode ROC, kriteria harus diurutkan berdasarkan tingkat kepentingannya, dari yang paling penting (peringkat 1) hingga yang paling tidak penting (peringkat terakhir).
                </p>
                <p>
                    <strong>Contoh:</strong> Jika ada 3 kriteria, maka bobot untuk:
                </p>
                <ul>
                    <li>Kriteria 1: $W_1 = \frac{1}{3}(1 + \frac{1}{2} + \frac{1}{3}) = 0.611$</li>
                    <li>Kriteria 2: $W_2 = \frac{1}{3}(\frac{1}{2} + \frac{1}{3}) = 0.278$</li>
                    <li>Kriteria 3: $W_3 = \frac{1}{3}(\frac{1}{3}) = 0.111$</li>
                </ul>
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