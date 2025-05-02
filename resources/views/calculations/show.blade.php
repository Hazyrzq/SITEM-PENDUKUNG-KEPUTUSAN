<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Perhitungan - SPK KELOMPOK 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f8fa;
            padding: 20px;
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
        .table-responsive {
            margin-bottom: 1.5rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Detail Perhitungan: {{ $calculation->name }}</h5>
                <div>
                    <a href="{{ route('calculations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p><strong>Tanggal Perhitungan:</strong> {{ $calculation->calculated_at->format('d M Y H:i') }}</p>
                    <p><strong>Deskripsi:</strong> {{ $calculation->description ?? 'Tidak ada deskripsi' }}</p>
                </div>

               
                <!-- 1. Data Penelitian -->
                <h5 class="mt-4 mb-3">1. Data Penelitian</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>Server</th>
                                @foreach($criteria as $criterion)
                                    <th>{{ $criterion->code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $alternative)
                                <tr>
                                    <td>{{ $alternative->name }}</td>
                                    @foreach($criteria as $criterion)
                                        <td>
                                            @php
                                                $value = isset($calculation->results['decision_matrix'][$alternative->id][$criterion->id]) ? 
                                                    $calculation->results['decision_matrix'][$alternative->id][$criterion->id] : '-';
                                                
                                                // Format nilai berdasarkan jenis kriteria
                                                if ($criterion->code == 'C1') { // Harga
                                                    echo is_numeric($value) ? number_format($value, 0, ',', '.') : $value;
                                                } elseif ($criterion->code == 'C5') { // Jenis Memory
                                                    echo $value == 1 ? 'SSD' : ($value == 2 ? 'NVMe' : $value);
                                                } elseif ($criterion->code == 'C6') { // Bandwidth
                                                    echo $value == 1 ? 'unlimited' : ($value == 0 ? 'Tidak' : $value);
                                                } elseif ($criterion->code == 'C7') { // Multi OS
                                                    echo $value == 1 ? 'Ya' : ($value == 0 ? 'Tidak' : $value);
                                                } else {
                                                    echo $value;
                                                }
                                            @endphp
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mb-4">
                    <h6><i class="fas fa-info-circle me-2"></i> Standarisasi Data Kualitatif Menjadi Kuantitatif</h6>
                    <p class="mb-1">Untuk menjadikan data di atas sepenuhnya data kuantitatif, data tersebut distandarisasi sebagai berikut:</p>
                    <ul class="mb-0">
                        <li>Jenis memory: SSD = 1, NVMe = 2</li>
                        <li>Bandwidth: Unlimited = 1, Tidak = 0</li>
                        <li>Multi OS: Ya = 1, Tidak = 0</li>
                    </ul>
                </div>

                <!-- 2. Data Kriteria -->
                <h5 class="mt-4 mb-3">2. Data Kriteria</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Kriteria</th>
                                <th>Jenis</th>
                                <th>Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $criterion)
                                <tr>
                                    <td>{{ $criterion->code }}</td>
                                    <td>{{ $criterion->name }}</td>
                                    <td>{{ $criterion->type == 'benefit' ? 'Benefit' : 'Cost' }}</td>
                                    <td>{{ is_numeric($criterion->weight) ? number_format($criterion->weight, 4) : $criterion->weight }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 3. Matriks Keputusan (Decision Matrix) -->
                <h5 class="mt-4 mb-3">3. Matriks Keputusan</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
                                @foreach($criteria as $criterion)
                                    <th>{{ $criterion->code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $alternative)
                                <tr>
                                    <td>{{ $alternative->code }} - {{ $alternative->name }}</td>
                                    @foreach($criteria as $criterion)
                                        <td>
                                            @php
                                                $value = isset($calculation->results['decision_matrix'][$alternative->id][$criterion->id]) ? 
                                                    $calculation->results['decision_matrix'][$alternative->id][$criterion->id] : '-';
                                            @endphp
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 4. Bobot Kriteria (ROC) -->
                <h5 class="mt-4 mb-3">4. Bobot Kriteria (ROC)</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Kriteria</th>
                                <th>Peringkat</th>
                                <th>Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $criterion)
                                <tr>
                                    <td>{{ $criterion->code }}</td>
                                    <td>{{ $criterion->name }}</td>
                                    <td>{{ $criterion->rank }}</td>
                                    <td>{{ is_numeric($criterion->weight) ? number_format($criterion->weight, 4) : $criterion->weight }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 5. Nilai Preferensi Alternatif -->
                <h5 class="mt-4 mb-3">5. Nilai Preferensi Alternatif</h5>
                <div class="alert alert-info">
                    <p class="mb-0">Nilai preferensi untuk setiap alternatif adalah sama, yaitu PAi = 1/{{ count($alternatives) }} = {{ number_format(1/count($alternatives), 4) }}</p>
                </div>

                <!-- 6. Matriks Evaluasi Teoritis -->
                <h5 class="mt-4 mb-3">6. Matriks Evaluasi Teoritis (Theoretical Evaluation Matrix)</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
                                @foreach($criteria as $criterion)
                                    <th>{{ $criterion->code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $alternative)
                                <tr>
                                    <td>{{ $alternative->code }}</td>
                                    @foreach($criteria as $criterion)
                                        <td>
                                            @php
                                                $value = isset($calculation->results['theoretical_matrix'][$alternative->id][$criterion->id]) ? 
                                                    $calculation->results['theoretical_matrix'][$alternative->id][$criterion->id] : '-';
                                                echo is_numeric($value) ? number_format($value, 4) : $value;
                                            @endphp
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 7. Matriks Evaluasi Realistis -->
                <h5 class="mt-4 mb-3">7. Matriks Evaluasi Realistis (Real Evaluation Matrix)</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
                                @foreach($criteria as $criterion)
                                    <th>{{ $criterion->code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $alternative)
                                <tr>
                                    <td>{{ $alternative->code }}</td>
                                    @foreach($criteria as $criterion)
                                        <td>
                                            @php
                                                $value = isset($calculation->results['realistic_matrix'][$alternative->id][$criterion->id]) ? 
                                                    $calculation->results['realistic_matrix'][$alternative->id][$criterion->id] : '-';
                                                echo is_numeric($value) ? number_format($value, 4) : $value;
                                            @endphp
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 8. Matriks Total Gap -->
                <h5 class="mt-4 mb-3">8. Matriks Total Gap</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
                                @foreach($criteria as $criterion)
                                    <th>{{ $criterion->code }}</th>
                                @endforeach
                                <th>Total Gap</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $alternative)
                                <tr>
                                    <td>{{ $alternative->code }}</td>
                                    @foreach($criteria as $criterion)
                                        <td>
                                            @php
                                                $value = isset($calculation->results['gap_matrix'][$alternative->id][$criterion->id]) ? 
                                                    $calculation->results['gap_matrix'][$alternative->id][$criterion->id] : '-';
                                                echo is_numeric($value) ? number_format($value, 4) : $value;
                                            @endphp
                                        </td>
                                    @endforeach
                                    <td>
                                        @php
                                            $total = array_sum($calculation->results['gap_matrix'][$alternative->id] ?? []);
                                            echo is_numeric($total) ? number_format($total, 4) : '-';
                                        @endphp
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 9. Nilai Akhir dan Peringkat -->
                <h5 class="mt-4 mb-3">9. Nilai Akhir dan Peringkat</h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Alternatif</th>
                                <th>Total Gap</th>
                                <th>Nilai Akhir (Q)</th>
                                <th>Peringkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($calculation->results['final_values']))
                                @foreach($calculation->results['final_values'] as $result)
                                    <tr>
                                        <td>{{ $result['code'] }}</td>
                                        <td>{{ $result['name'] }}</td>
                                        <td>
                                            @php
                                                $total = array_sum($calculation->results['gap_matrix'][$result['id']] ?? []);
                                                echo is_numeric($total) ? number_format($total, 4) : '-';
                                            @endphp
                                        </td>
                                        <td>{{ is_numeric($result['value']) ? number_format($result['value'], 4) : $result['value'] }}</td>
                                        <td>{{ $result['rank'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">Data nilai akhir tidak tersedia</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Penjelasan Tahapan Metode MAIRCA -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Tahapan Metode MAIRCA</h5>
            </div>
            <div class="card-body">
                <ol>
                    <li>
                        <strong>Membuat matriks keputusan</strong>
                        <p>Pada tahap ini, data alternatif dan kriteria disusun dalam bentuk matriks keputusan yang berisi nilai dari setiap alternatif untuk setiap kriteria.</p>
                    </li>
                    <li>
                        <strong>Menentukan nilai preferensi alternatif</strong>
                        <p>Setiap alternatif diberikan nilai preferensi yang sama, yaitu PAi = 1/m, dimana m adalah jumlah alternatif.</p>
                    </li>
                    <li>
                        <strong>Menghitung nilai matriks evaluasi teoritis</strong>
                        <p>Matriks evaluasi teoritis dihitung dengan mengalikan nilai preferensi alternatif dengan bobot kriteria.</p>
                    </li>
                    <li>
                        <strong>Menghitung nilai matriks evaluasi realistis</strong>
                        <p>Matriks evaluasi realistis dihitung dengan mengalikan matriks evaluasi teoritis dengan nilai normalisasi dari matriks keputusan.</p>
                    </li>
                    <li>
                        <strong>Menghitung matriks total gap</strong>
                        <p>Gap dihitung dari selisih antara matriks evaluasi teoritis dan matriks evaluasi realistis.</p>
                    </li>
                    <li>
                        <strong>Menghitung nilai akhir fungsi dan peringkat alternatif</strong>
                        <p>Nilai akhir dihitung berdasarkan total gap. Semakin kecil nilai total gap, semakin baik alternatif tersebut.</p>
                    </li>
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