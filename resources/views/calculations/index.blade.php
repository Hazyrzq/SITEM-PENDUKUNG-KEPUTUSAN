<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Perhitungan - SPK KELOMPOK 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="mb-4">Daftar Perhitungan</h2>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Daftar Perhitungan</h5>
                <a href="{{ route('calculations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Buat Perhitungan Baru
                </a>
            </div>
            <div class="card-body">
                @if(isset($calculations) && $calculations->isEmpty())
                    <div class="alert alert-info">
                        Belum ada perhitungan yang dilakukan. Klik tombol "Buat Perhitungan Baru" untuk memulai.
                    </div>
                @elseif(isset($calculations))
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Perhitungan</th>
                                    <th>Tanggal Perhitungan</th>
                                    <th>Jumlah Alternatif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calculations as $index => $calculation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $calculation->name }}</td>
                                        <td>{{ $calculation->calculated_at->format('d M Y H:i') }}</td>
                                        <td>{{ count($calculation->results['final_values'] ?? []) }}</td>
                                        <td>
                                            <a href="{{ route('calculations.show', $calculation) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                            <form action="{{ route('calculations.destroy', $calculation) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus perhitungan ini?')">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Tidak dapat mengambil data perhitungan.
                    </div>
                @endif
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