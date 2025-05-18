@extends('layouts.admin')

@section('title', 'Detail Perhitungan MAIRCA')

@section('content')
<div class="content-wrapper">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0 fw-bold"><i class="fas fa-calculator me-2"></i>Detail Perhitungan MAIRCA</h3>
            <p class="text-muted">Hasil perhitungan: {{ $calculation->name }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.calculations.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Perhitungan
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Perhitungan</h4>
                    <span class="badge bg-light text-dark">
                        <i class="far fa-calendar-alt me-1"></i>{{ $calculation->calculated_at->format('d/m/Y H:i') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%" class="bg-light">Nama Perhitungan</th>
                                    <td>{{ $calculation->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Dibuat Oleh</th>
                                    <td>{{ $calculation->user->name ?? 'User tidak ditemukan' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Tanggal Perhitungan</th>
                                    <td>{{ $calculation->calculated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Jumlah Alternatif</th>
                                    <td>{{ count($alternatives) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Jumlah Kriteria</th>
                                    <td>{{ $criteria->count() }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @php
                                $results = is_string($calculation->results) ? json_decode($calculation->results, true) : [];
                                $finalValues = isset($results['final_values']) ? $results['final_values'] : [];
                                
                                // PERBAIKAN: Urutkan berdasarkan nilai TERBESAR (kebalikan dari implementasi asli)
                                // untuk konsistensi visual dengan halaman index
                                usort($finalValues, function($a, $b) {
                                    return $b['final_value'] <=> $a['final_value'];
                                });
                                
                                $topResult = !empty($finalValues) ? $finalValues[0] : null;
                            @endphp
                            
                            @if($topResult)
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">Alternatif Terbaik</h5>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <span class="badge bg-success p-2" style="font-size: 1.2rem;">{{ $topResult['code'] ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $topResult['name'] ?? 'Tidak ada data' }}</h5>
                                            <p class="mb-0 text-muted">Nilai: {{ number_format($topResult['final_value'] ?? 0, 4) }}</p>
                                        </div>
                                    </div>
                                    @if(!empty($topResult['description']))
                                        <div class="mt-2">
                                            <p class="mb-0"><strong>Deskripsi:</strong> {{ $topResult['description'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-sort-amount-down me-2"></i>Peringkat Alternatif</h4>
        </div>
        <div class="card-body p-4">
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
                            // PERBAIKAN: Untuk normalisasi, nilai terbesar adalah 100%
                            $maxValue = !empty($finalValues) ? $finalValues[0]['final_value'] : 0;
                            // Hindari division by zero
                            $maxValue = ($maxValue > 0) ? $maxValue : 1;
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
                                                <div class="progress-bar {{ $index === 0 ? 'bg-success' : 'bg-primary' }}" role="progressbar" 
                                                     style="width: {{ (($result['final_value'] ?? 0) / $maxValue) * 100 }}%"></div>
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
        </div>
    </div>

    <!-- LANGKAH-LANGKAH PERHITUNGAN MAIRCA -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-tasks me-2"></i>Langkah-langkah Perhitungan MAIRCA</h4>
        </div>
        <div class="card-body p-4">
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
                                                            $gap = abs($teoritis - $realistis);
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
                            <p class="mb-3">Nilai akhir fungsi dihitung dengan menjumlahkan elemen-elemen dari matriks gap untuk setiap alternatif. Alternatif dengan nilai tertinggi adalah yang terbaik.</p>
                            
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
                                            // Urutkan berdasarkan nilai tertinggi
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

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detail Alternatif dan Kriteria</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h5 class="fw-bold mb-3">Alternatif yang Digunakan</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Alternatif</th>
                                            <th>Deskripsi</th>
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
                        
                        <div class="col-md-6 mb-4">
                            <h5 class="fw-bold mb-3">Kriteria yang Digunakan</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Kriteria</th>
                                            <th>Tipe</th>
                                            <th>Bobot</th>
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

    <div class="row mb-4">
        <div class="col-12 text-end">
            <a href="{{ route('admin.calculations.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-1"></i>Hapus Perhitungan
            </button>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus perhitungan <strong>{{ $calculation->name }}</strong> oleh user <strong>{{ $calculation->user->name ?? 'tidak diketahui' }}</strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.calculations.destroy', $calculation) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aktifkan tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush
@endsection