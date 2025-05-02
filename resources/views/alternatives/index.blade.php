<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alternatif - SPK KELOMPOK 5</title>
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
            <h2>Daftar Alternatif</h2>
            <a href="{{ route('alternatives.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Alternatif
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
                <h5 class="mb-0">Daftar Alternatif</h5>
            </div>
            <div class="card-body">
                @if($alternatives->isEmpty())
                    <div class="alert alert-info mb-0">
                        Belum ada alternatif yang ditambahkan. Klik tombol "Tambah Alternatif" untuk memulai.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Alternatif</th>
                                    <th>Deskripsi</th>
                                    <th>Status Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alternatives as $alternative)
                                    <tr>
                                        <td>{{ $alternative->code }}</td>
                                        <td>{{ $alternative->name }}</td>
                                        <td>{{ Str::limit($alternative->description, 50) }}</td>
                                        <td>
                                            @php
                                                $values = $alternative->values;
                                                $criteriaCount = App\Models\Criteria::count();
                                                $valueCount = $values->count();
                                                $percentage = $criteriaCount > 0 ? ($valueCount / $criteriaCount) * 100 : 0;
                                            @endphp
                                            
                                            @if($percentage == 0)
                                                <span class="badge bg-danger">Belum ada nilai</span>
                                            @elseif($percentage < 100)
                                                <span class="badge bg-warning">Nilai belum lengkap ({{ number_format($percentage) }}%)</span>
                                            @else
                                                <span class="badge bg-success">Nilai lengkap</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('alternatives.values', $alternative) }}" class="btn btn-sm btn-success btn-action" title="Input Nilai">
                                                <i class="fas fa-edit"></i> Nilai
                                            </a>
                                            <a href="{{ route('alternatives.show', $alternative) }}" class="btn btn-sm btn-primary btn-action" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('alternatives.edit', $alternative) }}" class="btn btn-sm btn-info btn-action" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('alternatives.destroy', $alternative) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-action" title="Hapus" onclick="return confirm('Yakin ingin menghapus alternatif ini?')">
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
                <h5 class="mb-0">Tentang Pengisian Nilai Alternatif</h5>
            </div>
            <div class="card-body">
                <p>
                    Setiap alternatif perlu memiliki nilai untuk setiap kriteria yang telah ditentukan.
                    Nilai-nilai ini akan digunakan dalam perhitungan metode MAIRCA untuk mendapatkan peringkat alternatif.
                </p>
                <p>
                    Langkah-langkah pengisian nilai alternatif:
                </p>
                <ol>
                    <li>Tambahkan alternatif baru dengan mengklik tombol "Tambah Alternatif"</li>
                    <li>Setelah alternatif ditambahkan, klik tombol "Nilai" untuk mengisi nilai kriteria</li>
                    <li>Isi nilai untuk setiap kriteria sesuai dengan karakteristik alternatif</li>
                    <li>Pastikan semua nilai kriteria telah diisi untuk setiap alternatif sebelum melakukan perhitungan</li>
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