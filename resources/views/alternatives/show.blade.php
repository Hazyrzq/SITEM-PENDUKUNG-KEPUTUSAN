<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Alternatif - SPK KELOMPOK 5</title>
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
            <h2>Detail Alternatif</h2>
            <div>
                <a href="{{ route('alternatives.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('alternatives.edit', $alternative) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('alternatives.values', $alternative) }}" class="btn btn-success">
                    <i class="fas fa-pencil-alt me-1"></i> Edit Nilai
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Alternatif</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th style="width: 30%">Kode</th>
                                <td>{{ $alternative->code }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $alternative->name }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $alternative->description ?: 'Tidak ada deskripsi' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Status Nilai</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $values = $alternative->values;
                            $criteriaCount = App\Models\Criteria::count();
                            $valueCount = $values->count();
                            $percentage = $criteriaCount > 0 ? ($valueCount / $criteriaCount) * 100 : 0;
                        @endphp
                        
                        <div class="mb-3">
                            @if($percentage == 0)
                                <span class="badge bg-danger">Belum ada nilai</span>
                            @elseif($percentage < 100)
                                <span class="badge bg-warning">Nilai belum lengkap ({{ number_format($percentage) }}%)</span>
                            @else
                                <span class="badge bg-success">Nilai lengkap</span>
                            @endif
                        </div>
                        
                        <div class="progress mb-3">
                            <div class="progress-bar {{ $percentage < 100 ? 'bg-warning' : 'bg-success' }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                        </div>
                        
                        <p>{{ $valueCount }} dari {{ $criteriaCount }} kriteria telah dinilai.</p>
                        
                        @if($percentage < 100)
                            <a href="{{ route('alternatives.values', $alternative) }}" class="btn btn-primary">
                                <i class="fas fa-pencil-alt me-1"></i> Lengkapi Nilai
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Nilai Kriteria</h5>
            </div>
            <div class="card-body">
                @if($values->isEmpty())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Alternatif ini belum memiliki nilai kriteria. 
                        <a href="{{ route('alternatives.values', $alternative) }}" class="alert-link">Klik di sini untuk mengisi nilai</a>.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Kriteria</th>
                                    <th>Nama Kriteria</th>
                                    <th>Jenis</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alternative->values->sortBy('criteria.rank') as $value)
                                    <tr>
                                        <td>{{ $value->criteria->code }}</td>
                                        <td>{{ $value->criteria->name }}</td>
                                        <td>
                                            @if($value->criteria->type == 'benefit')
                                                <span class="badge bg-success">Benefit</span>
                                            @else
                                                <span class="badge bg-danger">Cost</span>
                                            @endif
                                        </td>
                                        <td>{{ $value->value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
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