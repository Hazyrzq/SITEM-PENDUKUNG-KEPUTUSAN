{{-- resources/views/user/calculations/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Hasil Perhitungan MAIRCA')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.calculations.index') }}">Perhitungan</a></li>
            <li class="breadcrumb-item active">Hasil</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-calculator me-2"></i>Hasil Perhitungan: {{ $calculation->name }}</h4>
                <div>
                    <span class="badge bg-light text-dark">
                        <i class="far fa-calendar-alt me-1"></i>{{ $calculation->calculated_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-success mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Perhitungan Selesai</h6>
                        <p class="mb-0">Perhitungan berhasil dilakukan menggunakan metode MAIRCA dengan {{ count($alternatives) }} alternatif dan {{ $criteria->count() }} kriteria.</p>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Peringkat Alternatif</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">Rank</th>
                            <th width="15%">Kode</th>
                            <th width="40%">Nama Alternatif</th>
                            <th width="20%">Nilai Akhir</th>
                            <th width="20%">Normalisasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $results = json_decode($calculation->results, true) ?: [];
                            $finalValues = isset($results['final_values']) ? $results['final_values'] : [];
                            
                            // Urutkan final values jika belum terurut
                            usort($finalValues, function($a, $b) {
                                return $b['final_value'] <=> $a['final_value'];
                            });
                            
                            $maxValue = !empty($finalValues) ? max(array_column($finalValues, 'final_value')) : 1;
                        @endphp
                        
                        @foreach($finalValues as $index => $result)
                            <tr @if($index === 0) class="table-success" @endif>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-secondary">{{ $result['code'] ?? '-' }}</span></td>
                                <td class="fw-semibold">{{ $result['name'] ?? '-' }}</td>
                                <td>{{ number_format($result['final_value'] ?? 0, 4) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 me-2">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar {{ $index === 0 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ ($result['final_value'] ?? 0) / $maxValue * 100 }}%"></div>
                                            </div>
                                        </div>
                                        <div>
                                            {{ number_format((($result['final_value'] ?? 0) / $maxValue) * 100, 2) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- LANGKAH-LANGKAH PERHITUNGAN MAIRCA -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <h5 class="fw-bold mb-3">Langkah-langkah Perhitungan MAIRCA</h5>
                    
                    <div class="accordion" id="accordionMairca">
                        <!-- Langkah 1: Matriks Keputusan -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingMatriks">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMatriks" aria-expanded="true" aria-controls="collapseMatriks">
                                    <i class="fas fa-table me-2"></i>Langkah 1: Membuat Matriks Keputusan
                                </button>
                            </h2>
                            <div id="collapseMatriks" class="accordion-collapse collapse show" aria-labelledby="headingMatriks" data-bs-parent="#accordionMairca">
                                <div class="accordion-body">
                                    <p>Matriks keputusan adalah representasi dari nilai setiap alternatif untuk setiap kriteria.</p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Alternatif</th>
                                                    @foreach($criteria as $criterion)
                                                        <th>{{ $criterion->code }} ({{ $criterion->type == 'benefit' ? 'B' : 'C' }})</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $decisionMatrix = $results['decision_matrix'] ?? [];
                                                @endphp
                                                
                                                @foreach($alternatives as $alternative)
                                                    <tr>
                                                        <td>{{ $alternative->code }}</td>
                                                        @foreach($criteria as $criterion)
                                                            <td>
                                                                {{ isset($decisionMatrix[$alternative->id][$criterion->id]) ? $decisionMatrix[$alternative->id][$criterion->id] : '-' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="small text-muted mt-2">Keterangan: B = Benefit, C = Cost</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Langkah 2: Nilai Preferensi Alternatif -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPreferensi">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePreferensi" aria-expanded="false" aria-controls="collapsePreferensi">
                                    <i class="fas fa-percentage me-2"></i>Langkah 2: Menentukan Nilai Preferensi Alternatif
                                </button>
                            </h2>
                            <div id="collapsePreferensi" class="accordion-collapse collapse" aria-labelledby="headingPreferensi" data-bs-parent="#accordionMairca">
                                <div class="accordion-body">
                                    <p>Nilai preferensi alternatif ditentukan dengan persamaan: </p>
                                    <div class="alert alert-light">
                                        <strong>P<sub>ai</sub> = 1/m</strong>, di mana m adalah jumlah alternatif
                                    </div>
                                    
                                    <p>Dengan jumlah alternatif sebanyak {{ count($alternatives) }}, maka nilai preferensi alternatif adalah:</p>
                                    <div class="alert alert-success">
                                        P<sub>ai</sub> = 1/{{ count($alternatives) }} = {{ number_format(1/count($alternatives), 4) }}
                                    </div>
                                    
                                    <p>Nilai preferensi ini sama untuk semua alternatif karena pengambil keputusan bersikap netral.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Langkah 3: Matriks Evaluasi Teoritis -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTeoritis">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTeoritis" aria-expanded="false" aria-controls="collapseTeoritis">
                                    <i class="fas fa-calculator me-2"></i>Langkah 3: Menghitung Nilai Matriks Evaluasi Teoritis
                                </button>
                            </h2>
                            <div id="collapseTeoritis" class="accordion-collapse collapse" aria-labelledby="headingTeoritis" data-bs-parent="#accordionMairca">
                                <div class="accordion-body">
                                    <p>Matriks evaluasi teoritis dihitung dengan mengalikan nilai preferensi alternatif dengan bobot kriteria.</p>
                                    
                                    <p>T<sub>p</sub> = P<sub>ai</sub> * W<sub>j</sub></p>
                                    
                                    @php
                                        $preferensi = 1 / count($alternatives);
                                        $theoreticalMatrix = $results['theoretical_matrix'] ?? [];
                                    @endphp
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
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
                                                                @if(isset($theoreticalMatrix[$alternative->id][$criterion->id]))
                                                                    {{ number_format($theoreticalMatrix[$alternative->id][$criterion->id], 4) }}
                                                                @else
                                                                    {{ number_format($preferensi * $criterion->weight, 4) }}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Langkah 4: Matriks Evaluasi Realistis -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingRealistis">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRealistis" aria-expanded="false" aria-controls="collapseRealistis">
                                    <i class="fas fa-balance-scale me-2"></i>Langkah 4: Menghitung Nilai Matriks Evaluasi Realistis
                                </button>
                            </h2>
                            <div id="collapseRealistis" class="accordion-collapse collapse" aria-labelledby="headingRealistis" data-bs-parent="#accordionMairca">
                                <div class="accordion-body">
                                    <p>Matriks evaluasi realistis dihitung berdasarkan nilai normalisasi dan matriks evaluasi teoritis.</p>
                                    
                                    @php
                                        $normalizedMatrix = $results['normalized_matrix'] ?? [];
                                        $weightedMatrix = $results['weighted_matrix'] ?? [];
                                    @endphp
                                    
                                    <h6 class="mt-3">Matriks Normalisasi</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
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
                                                                {{ isset($normalizedMatrix[$alternative->id][$criterion->id]) ? number_format($normalizedMatrix[$alternative->id][$criterion->id], 4) : '-' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <h6 class="mt-3">Matriks Evaluasi Realistis (Hasil Pembobotan)</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
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
                                                                {{ isset($weightedMatrix[$alternative->id][$criterion->id]) ? number_format($weightedMatrix[$alternative->id][$criterion->id], 4) : '-' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Langkah 5: Matriks Total Gap -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingGap">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGap" aria-expanded="false" aria-controls="collapseGap">
                                    <i class="fas fa-arrows-alt-h me-2"></i>Langkah 5: Menghitung Matriks Total Gap
                                </button>
                            </h2>
                            <div id="collapseGap" class="accordion-collapse collapse" aria-labelledby="headingGap" data-bs-parent="#accordionMairca">
                                <div class="accordion-body">
                                    <p>Matriks total gap menghitung selisih antara nilai teoritis dan nilai realistis untuk setiap alternatif dan kriteria.</p>
                                    
                                    @php
                                        // Gunakan gap_matrix jika ada, jika tidak gunakan distance_matrix
                                        $gapMatrix = $results['gap_matrix'] ?? ($results['distance_matrix'] ?? []);
                                        $preferensi = 1 / count($alternatives);
                                    @endphp
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
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
                                                            @if(isset($gapMatrix[$alternative->id][$criterion->id]))
                                                                <td>{{ number_format($gapMatrix[$alternative->id][$criterion->id], 4) }}</td>
                                                            @else
                                                                @php
                                                                    $teoritis = $preferensi * $criterion->weight;
                                                                    $realistis = $weightedMatrix[$alternative->id][$criterion->id] ?? 0;
                                                                    $gap = $teoritis - $realistis;
                                                                @endphp
                                                                <td>{{ number_format($gap, 4) }}</td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Langkah 6: Nilai Akhir Fungsi -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingNilaiAkhir">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNilaiAkhir" aria-expanded="false" aria-controls="collapseNilaiAkhir">
                                    <i class="fas fa-trophy me-2"></i>Langkah 6: Menghitung Nilai Akhir Fungsi
                                </button>
                            </h2>
                            <div id="collapseNilaiAkhir" class="accordion-collapse collapse" aria-labelledby="headingNilaiAkhir" data-bs-parent="#accordionMairca">
                                <div class="accordion-body">
                                    <p>Nilai akhir fungsi dihitung dengan menjumlahkan elemen-elemen dari matriks gap untuk setiap alternatif. Alternatif dengan nilai terbesar adalah yang terbaik.</p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Kode</th>
                                                    <th>Nama Alternatif</th>
                                                    <th>Nilai Total Gap</th>
                                                    <th>Nilai Akhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    // Ulang pengurutan untuk memastikan urutan yang benar
                                                    usort($finalValues, function($a, $b) {
                                                        return $b['final_value'] <=> $a['final_value'];
                                                    });
                                                @endphp
                                                
                                                @foreach($finalValues as $index => $result)
                                                    <tr @if($index === 0) class="table-success" @endif>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $result['code'] ?? '-' }}</td>
                                                        <td>{{ $result['name'] ?? '-' }}</td>
                                                        <td>{{ isset($result['gap_sum']) ? number_format($result['gap_sum'], 4) : number_format($result['final_value'] ?? 0, 4) }}</td>
                                                        <td>{{ number_format($result['final_value'] ?? 0, 4) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    @if(!empty($finalValues))
                                        <div class="alert alert-success mt-3">
                                            <p class="mb-0">Berdasarkan perhitungan MAIRCA, alternatif <strong>{{ $finalValues[0]['name'] ?? '-' }}</strong> ({{ $finalValues[0]['code'] ?? '-' }}) merupakan alternatif terbaik dengan nilai akhir <strong>{{ number_format($finalValues[0]['final_value'] ?? 0, 4) }}</strong>.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <h5 class="fw-bold mb-3">Detail Alternatif dan Kriteria</h5>
                    
                    <div class="accordion" id="accordionCalculation">
                        <!-- Alternatif yang Digunakan -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <i class="fas fa-cubes me-2"></i>Alternatif yang Digunakan
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionCalculation">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="15%">Kode</th>
                                                    <th width="40%">Nama Alternatif</th>
                                                    <th width="40%">Deskripsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($alternatives as $index => $alternative)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td><span class="badge bg-secondary">{{ $alternative->code }}</span></td>
                                                        <td class="fw-semibold">{{ $alternative->name }}</td>
                                                        <td>{{ $alternative->description ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kriteria yang Digunakan -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="fas fa-list-check me-2"></i>Kriteria yang Digunakan
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionCalculation">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="15%">Kode</th>
                                                    <th width="25%">Nama Kriteria</th>
                                                    <th width="15%">Tipe</th>
                                                    <th width="20%">Bobot</th>
                                                    <th width="20%">Normalisasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($criteria as $index => $criterion)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td><span class="badge bg-secondary">{{ $criterion->code }}</span></td>
                                                        <td class="fw-semibold">{{ $criterion->name }}</td>
                                                        <td>
                                                            @if($criterion->type == 'benefit')
                                                                <span class="badge bg-success">Benefit</span>
                                                            @else
                                                                <span class="badge bg-danger">Cost</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($criterion->weight, 4) }}</td>
                                                        <td>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $criterion->weight * 100 }}%"></div>
                                                            </div>
                                                            <div class="small text-end mt-1">{{ number_format($criterion->weight * 100, 2) }}%</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('user.calculations.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <form action="{{ route('user.calculations.destroy', $calculation) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perhitungan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Hapus Perhitungan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart
        const results = {!! json_encode($finalValues ?? []) !!};
        
        // Persiapkan data untuk chart
        const labels = results.map(result => result.code || '-');
        const data = results.map(result => result.final_value || 0);
        const backgroundColors = results.map((result, index) => index === 0 ? 'rgba(40, 167, 69, 0.7)' : 'rgba(67, 97, 238, 0.7)');
        const borderColors = results.map((result, index) => index === 0 ? 'rgb(40, 167, 69)' : 'rgb(67, 97, 238)');
        
        // Buat chart
        const ctx = document.getElementById('resultChart').getContext('2d');
        const resultChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai Akhir',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                return `${results[index]?.name || '-'}: ${(results[index]?.final_value || 0).toFixed(4)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nilai Akhir'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Alternatif'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection