@extends('layouts.app')

@section('title', 'Daftar Kriteria')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-list-check me-2"></i>Daftar Kriteria</h4>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Informasi</h6>
                        <p class="mb-0">Berikut adalah daftar kriteria yang digunakan dalam sistem pendukung keputusan. Kriteria ini telah ditetapkan oleh admin dan digunakan untuk perhitungan dengan metode MAIRCA.</p>
                    </div>
                </div>
            </div>

            @if($criteria->isEmpty())
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Belum ada kriteria yang tersedia. Silakan hubungi admin untuk menambahkan kriteria.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode</th>
                                <th width="30%">Nama Kriteria</th>
                                <th width="10%">Ranking</th>
                                <th width="15%">Tipe</th>
                                <th width="25%">Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $index => $criterion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-secondary">{{ $criterion->code }}</span></td>
                                    <td class="fw-semibold">{{ $criterion->name }}</td>
                                    <td><span class="badge bg-primary">{{ $criterion->rank }}</span></td>
                                    <td>
                                        @if($criterion->type == 'benefit')
                                            <span class="badge bg-success">Benefit</span>
                                            <div class="small text-muted mt-1">Nilai lebih besar lebih baik</div>
                                        @else
                                            <span class="badge bg-danger">Cost</span>
                                            <div class="small text-muted mt-1">Nilai lebih kecil lebih baik</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($criterion->weight !== null)
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    {{ number_format($criterion->weight, 4) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $criterion->weight * 100 }}%"></div>
                                                    </div>
                                                    <div class="small text-end mt-1">{{ number_format($criterion->weight * 100, 2) }}%</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum dihitung</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="5" class="text-end">Total Bobot:</th>
                                <th>
                                    @if($criteria->whereNotNull('weight')->count() > 0)
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                {{ number_format($criteria->sum('weight'), 4) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $criteria->sum('weight') * 100 }}%"></div>
                                                </div>
                                                <div class="small text-end mt-1">{{ number_format($criteria->sum('weight') * 100, 2) }}%</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum dihitung</span>
                                    @endif
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if(!$allHaveWeights)
                    <div class="alert alert-warning mt-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Perhatian</h6>
                                <p class="mb-0">Beberapa kriteria belum memiliki bobot. Perhitungan belum dapat dilakukan sampai admin menghitung bobot kriteria menggunakan metode ROC.</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if(!$criteria->isEmpty() && $allHaveWeights)
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Metode Pembobotan</h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">Tentang Metode ROC</h5>
                        <p>Rank Order Centroid (ROC) adalah metode pembobotan kriteria yang menghitung bobot berdasarkan peringkat kepentingan kriteria. Metode ini memberikan bobot objektif sesuai dengan urutan prioritas yang telah ditetapkan oleh admin.</p>
                        <p>Perhitungan bobot kriteria menggunakan metode ROC memastikan bahwa:</p>
                        <ul>
                            <li>Kriteria dengan peringkat lebih tinggi mendapatkan bobot lebih besar</li>
                            <li>Total bobot seluruh kriteria adalah 1 (atau 100%)</li>
                            <li>Bobot dihitung secara objektif berdasarkan rumus matematis</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">Penerapan dalam Perhitungan</h5>
                        <p>Bobot kriteria yang telah dihitung dengan metode ROC akan digunakan dalam perhitungan MAIRCA untuk:</p>
                        <ul>
                            <li>Memperhatikan kepentingan relatif dari masing-masing kriteria</li>
                            <li>Menghitung matriks keputusan terbobot</li>
                            <li>Menghasilkan peringkat alternatif yang mencerminkan preferensi berdasarkan kriteria yang telah ditentukan</li>
                        </ul>
                        <div class="alert alert-success mt-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Semua Kriteria Memiliki Bobot</h6><p class="mb-0">Semua kriteria sudah memiliki bobot yang valid. Anda dapat melanjutkan untuk memberikan nilai alternatif dan melakukan perhitungan MAIRCA.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection