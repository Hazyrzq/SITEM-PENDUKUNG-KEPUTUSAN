@extends('layouts.admin')

@section('title', 'Manajemen Kriteria')

@section('content')
<div class="content-wrapper">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0 fw-bold"><i class="fas fa-list-check me-2"></i>Manajemen Kriteria</h3>
            <p class="text-muted">Kelola kriteria untuk sistem pendukung keputusan</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.criteria.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus-circle me-1"></i>Tambah Kriteria
            </a>
            <a href="{{ route('admin.criteria.calculate-weights') }}" class="btn btn-success">
                <i class="fas fa-calculator me-1"></i>Hitung Bobot ROC
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Berhasil!</h6>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Error!</h6>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($criteria->isEmpty())
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Belum ada kriteria yang tersedia. Silakan tambahkan kriteria baru.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>
                                <th width="20%">Nama Kriteria</th>
                                <th width="10%">Ranking</th>
                                <th width="10%">Tipe</th>
                                <th width="15%">Bobot</th>
                                <th width="15%" class="text-center">Normalisasi</th>
                                <th width="15%" class="text-center">Aksi</th>
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
                                        @else
                                            <span class="badge bg-danger">Cost</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($criterion->weight !== null)
                                            {{ number_format($criterion->weight, 4) }}
                                        @else
                                            <span class="badge bg-warning text-dark">Belum dihitung</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($criterion->weight !== null)
                                            {{ number_format($criterion->weight * 100, 2) }}%
                                        @else
                                            <span class="badge bg-warning text-dark">Belum dihitung</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.criteria.edit', $criterion) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Tombol hapus memakai form alih-alih modal untuk mencegah duplikasi -->
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                    data-id="{{ $criterion->id }}" 
                                                    data-name="{{ $criterion->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="5" class="text-end">Total Bobot:</th>
                                <th>
                                    @if($criteria->whereNotNull('weight')->count() > 0)
                                        {{ number_format($criteria->sum('weight'), 4) }}
                                    @else
                                        <span class="badge bg-warning text-dark">Belum dihitung</span>
                                    @endif
                                </th>
                                <th class="text-center">
                                    @if($criteria->whereNotNull('weight')->count() > 0)
                                        {{ number_format($criteria->sum('weight') * 100, 2) }}%
                                    @else
                                        <span class="badge bg-warning text-dark">Belum dihitung</span>
                                    @endif
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($criteria->whereNull('weight')->count() > 0)
                    <div class="alert alert-info mt-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Informasi</h6>
                                <p class="mb-0">Beberapa kriteria belum memiliki bobot. Klik tombol "Hitung Bobot ROC" untuk menghitung bobot secara otomatis menggunakan metode Rank Order Centroid.</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if(!$criteria->isEmpty())
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Metode ROC</h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">Tentang Metode ROC</h5>
                        <p>Rank Order Centroid (ROC) adalah metode pembobotan kriteria yang menghitung bobot berdasarkan peringkat kepentingan kriteria. Metode ini memberikan bobot objektif sesuai dengan urutan prioritas Anda.</p>
                        <p>Rumus perhitungan bobot ROC adalah:</p>
                        <div class="alert alert-light border-primary border-start border-4">
                            <p class="fw-bold mb-2">Rumus ROC:</p>
                            <p class="mb-0">W<sub>k</sub> = (1/n) × Σ<sub>i=k</sub><sup>n</sup> (1/i)</p>
                            <p class="mt-2 mb-0"><small>di mana:</small></p>
                            <ul class="mb-0">
                                <li><small>W<sub>k</sub> = bobot kriteria pada peringkat ke-k</small></li>
                                <li><small>n = jumlah kriteria</small></li>
                                <li><small>i = posisi peringkat</small></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">Petunjuk Penggunaan</h5>
                        <ol>
                            <li class="mb-2">Pastikan semua kriteria sudah memiliki peringkat (rank) yang sesuai.</li>
                            <li class="mb-2">Rank dimulai dari 1 (paling penting) hingga n (paling tidak penting).</li>
                            <li class="mb-2">Klik tombol "Hitung Bobot ROC" untuk melakukan perhitungan bobot otomatis.</li>
                            <li class="mb-2">Setelah bobot dihitung, Anda dapat mulai melakukan perhitungan MAIRCA.</li>
                        </ol>
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-exclamation-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Perhatian</h6>
                                    <p class="mb-0">Mengubah rank kriteria atau menambah/menghapus kriteria akan memengaruhi bobot. Jika melakukan perubahan, Anda perlu menghitung ulang bobot.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal konfirmasi hapus dipindahkan ke luar dari content-wrapper -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kriteria <strong id="criteriaName"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini akan menghapus semua nilai alternatif untuk kriteria ini dan tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan modal hanya dibuat dan ditambahkan sekali
    let modalInstance = null;
    
    // Hapus modal lama jika ada (untuk mencegah duplikasi)
    const oldModals = document.querySelectorAll('.modal-backdrop');
    oldModals.forEach(modal => modal.remove());
    
    // Event listener untuk tombol hapus
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            // Update konten modal
            document.getElementById('criteriaName').textContent = name;
            
            // Update form action
            const form = document.getElementById('deleteForm');
            form.action = "{{ route('admin.criteria.index') }}/" + id;
            
            // Buka modal
            const deleteModal = document.getElementById('deleteModal');
            
            // Hapus instance modal lama jika ada
            if (modalInstance) {
                modalInstance.dispose();
            }
            
            // Buat instance modal baru
            modalInstance = new bootstrap.Modal(deleteModal);
            modalInstance.show();
        });
    });
});
</script>
@endpush