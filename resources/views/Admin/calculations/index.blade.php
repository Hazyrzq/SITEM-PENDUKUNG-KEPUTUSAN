@extends('layouts.admin')

@section('title', 'Daftar Perhitungan MAIRCA')

@section('content')
<div class="content-wrapper">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0 fw-bold"><i class="fas fa-calculator me-2"></i>Daftar Perhitungan MAIRCA</h3>
            <p class="text-muted">Lihat dan kelola perhitungan yang dilakukan oleh pengguna</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-list me-2"></i>Semua Perhitungan</h4>
                <div class="input-group" style="width: 300px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari perhitungan..." aria-label="Cari perhitungan">
                    <button class="btn btn-light" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($calculations->isEmpty())
                <div class="alert alert-info m-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi</h6>
                            <p class="mb-0">Belum ada perhitungan yang dilakukan oleh pengguna.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover" id="calculationsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama Perhitungan</th>
                                <th width="15%">Pengguna</th>
                                <th width="15%">Tanggal</th>
                                <th width="30%">Alternatif Terbaik</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calculations as $index => $calculation)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $calculation->name }}</td>
                                    <td>{{ $calculation->user->name ?? 'User tidak ditemukan' }}</td>
                                    <td>{{ $calculation->calculated_at ? $calculation->calculated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        @php
                                            // PERBAIKAN: Pastikan $calculation->results adalah string sebelum di-decode
                                            $results = is_string($calculation->results) ? json_decode($calculation->results, true) : [];
                                            $finalValues = isset($results['final_values']) ? $results['final_values'] : [];
                                            
                                            // PERBAIKAN: Menggunakan nilai tertinggi sebagai alternatif terbaik
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
                                            <a href="{{ route('admin.calculations.show', $calculation) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $calculation->id }}"
                                                    title="Hapus Perhitungan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal Konfirmasi Hapus -->
                                        <div class="modal fade" id="deleteModal{{ $calculation->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $calculation->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $calculation->id }}">Konfirmasi Hapus</h5>
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginasi -->
                <div class="d-flex justify-content-center mt-4 pb-4">
                    {{ $calculations->links() }}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Statistik Perhitungan -->
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik Perhitungan per Pengguna</h4>
                </div>
                <div class="card-body p-4">
                    @if($userCalculationStats->isEmpty())
                        <div class="alert alert-info mb-0">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Informasi</h6>
                                    <p class="mb-0">Belum ada statistik perhitungan yang dapat ditampilkan.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pengguna</th>
                                        <th>Jumlah Perhitungan</th>
                                        <th>Perhitungan Terakhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userCalculationStats as $stat)
                                        <tr>
                                            <td>{{ $stat->user->name ?? 'User tidak ditemukan' }}</td>
                                            <td><span class="badge bg-primary">{{ $stat->calculation_count }}</span></td>
                                            <td>{{ $stat->last_calculation ? $stat->last_calculation->format('d/m/Y H:i') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-trophy me-2"></i>Alternatif Terpopuler</h4>
                </div>
                <div class="card-body p-4">
                    @if(empty($popularAlternatives))
                        <div class="alert alert-info mb-0">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Informasi</h6>
                                    <p class="mb-0">Belum ada alternatif terpopuler yang dapat ditampilkan.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Alternatif</th>
                                        <th>Kode</th>
                                        <th>Jumlah Terpilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($popularAlternatives as $alternative)
                                        <tr>
                                            <td>{{ $alternative['name'] }}</td>
                                            <td><span class="badge bg-secondary">{{ $alternative['code'] }}</span></td>
                                            <td><span class="badge bg-success">{{ $alternative['count'] }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fitur pencarian perhitungan
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('calculationsTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    
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
@endsection