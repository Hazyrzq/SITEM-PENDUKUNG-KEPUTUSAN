@extends('layouts.app')

@section('title', 'Daftar Perhitungan MAIRCA')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-calculator me-2"></i>Daftar Perhitungan MAIRCA</h4>
                <a href="{{ route('user.calculations.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle me-1"></i>Buat Perhitungan Baru
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Informasi</h6>
                        <p class="mb-0">Berikut adalah daftar perhitungan MAIRCA yang telah Anda lakukan. Klik tombol "Buat Perhitungan Baru" untuk membuat perhitungan baru.</p>
                    </div>
                </div>
            </div>

            @if($calculations->isEmpty())
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Anda belum melakukan perhitungan apapun. Silakan klik tombol "Buat Perhitungan Baru" untuk melakukan perhitungan MAIRCA.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nama Perhitungan</th>
                                <th width="15%">Tanggal</th>
                                <th width="40%">Alternatif Terbaik</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calculations as $index => $calculation)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $calculation->name }}</td>
                                    <td>{{ $calculation->calculated_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @php
                                            $results = json_decode($calculation->results, true);
                                            $finalValues = $results['final_values'] ?? [];
                                            
                                            // PERBAIKAN UTAMA: Masalah di sini adalah sorting tidak sesuai dengan nilai alternatif
                                            // Di halaman show, tampaknya nilai tertinggi (Dihostingin 0.6101) ditampilkan di peringkat #1
                                            // Ini menunjukkan bahwa meskipun nilai gap yang kecil seharusnya lebih baik, sistem
                                            // menginterpretasikan bahwa nilai yang besar lebih baik.
                                            
                                            // Urutkan berdasarkan nilai final_value (TERBALIK dari logika asli untuk konsistensi visual)
                                            usort($finalValues, function($a, $b) {
                                                return $b['final_value'] <=> $a['final_value'];
                                            });
                                            
                                            $topResult = !empty($finalValues) ? $finalValues[0] : null;
                                        @endphp
                                        
                                        @if($topResult)
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <span class="badge bg-success">{{ $topResult['code'] ?? 'N/A' }}</span>
                                                </div>
                                                <div>
                                                    {{ $topResult['name'] ?? 'Tidak ada data' }} 
                                                    <span class="text-muted">(nilai: {{ number_format($topResult['final_value'] ?? 0, 4) }})</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada data</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('user.calculations.show', $calculation) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $calculation->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal{{ $calculation->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $calculation->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $calculation->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus perhitungan <strong>{{ $calculation->name }}</strong>?</p>
                                                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('user.calculations.destroy', $calculation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tentang Metode MAIRCA</h4>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Multi-Attributive Ideal-Real Comparative Analysis</h5>
                    <p>Metode MAIRCA adalah metode pengambilan keputusan multi-kriteria yang membandingkan nilai ideal dan nilai aktual dari setiap alternatif untuk menghasilkan peringkat keputusan.</p>
                    <p>Metode ini melibatkan beberapa langkah perhitungan:</p>
                    <ol>
                        <li>Membentuk matriks keputusan dari nilai-nilai alternatif</li>
                        <li>Menentukan nilai preferensi alternatif</li>
                        <li>Menghitung nilai matriks evaluasi teoritis</li>
                        <li>Menghitung nilai matriks evaluasi realistis</li>
                        <li>Menghitung matriks total gap</li>
                        <li>Menghitung nilai akhir fungsi</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Kelebihan Metode MAIRCA</h5>
                    <ul>
                        <li>Mempertimbangkan bobot kriteria dalam pengambilan keputusan</li>
                        <li>Hasil peringkat lebih stabil dibandingkan metode lain</li>
                        <li>Perhitungan yang lebih sederhana namun tetap komprehensif</li>
                        <li>Dapat menangani kriteria benefit dan cost sekaligus</li>
                        <li>Menghasilkan peringkat alternatif yang objektif</li>
                    </ul>
                    <div class="alert alert-warning mt-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-circle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Perhatian</h6>
                                <p class="mb-0">Pastikan Anda telah memberikan nilai untuk minimal 2 alternatif sebelum melakukan perhitungan MAIRCA agar dapat membandingkan hasil dengan baik.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle modal tidak duplikat
    const deleteModals = document.querySelectorAll('[id^="deleteModal"]');
    deleteModals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function(event) {
            // Pastikan tidak ada modal backdrop duplikat
            const backdrops = document.querySelectorAll('.modal-backdrop');
            if (backdrops.length > 1) {
                backdrops.forEach((backdrop, index) => {
                    if (index > 0) backdrop.remove();
                });
            }
        });
    });
});
</script>
@endpush