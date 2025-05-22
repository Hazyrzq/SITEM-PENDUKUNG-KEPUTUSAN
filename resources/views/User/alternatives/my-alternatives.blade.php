@extends('layouts.app')

@section('title', 'Alternatif Saya')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-cubes me-2"></i>Alternatif Saya</h4>
                <a href="{{ route('user.alternatives.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-1"></i>Tambah Alternatif
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
                        <p class="mb-0">Berikut adalah daftar alternatif yang Anda buat. Anda dapat menambah, mengedit, atau menghapus alternatif Anda sendiri.</p>
                    </div>
                </div>
            </div>

            @if($alternatives->isEmpty())
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Anda belum membuat alternatif apapun. Silakan klik tombol "Tambah Alternatif" untuk menambahkan alternatif baru.</p>
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
                                <th width="25%">Nama Alternatif</th>
                                <th width="30%">Deskripsi</th>
                                <th width="10%">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $index => $alternative)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-secondary">{{ $alternative->code }}</span></td>
                                    <td class="fw-semibold">{{ $alternative->name }}</td>
                                    <td>{{ $alternative->description ?? '-' }}</td>
                                    <td>
                                        @if($alternative->has_values)
                                            <span class="badge bg-success">Sudah Dinilai</span>
                                        @else
                                            <span class="badge bg-warning">Belum Dinilai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('user.alternatives.show', $alternative) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>
                                            <a href="{{ route('user.alternatives.edit', $alternative) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $alternative->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal{{ $alternative->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $alternative->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $alternative->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus alternatif <strong>{{ $alternative->name }}</strong>?</p>
                                                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('user.alternatives.destroy', $alternative) }}" method="POST" class="d-inline">
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
            <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tentang Alternatif</h4>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Apa itu Alternatif?</h5>
                    <p>Alternatif adalah pilihan atau opsi yang akan dibandingkan dalam proses pengambilan keputusan menggunakan metode MAIRCA. Setiap alternatif akan dinilai berdasarkan kriteria-kriteria yang telah ditentukan.</p>
                    <p>Langkah-langkah dalam mengelola alternatif:</p>
                    <ol>
                        <li>Buat alternatif baru dengan nama dan deskripsi yang jelas</li>
                        <li>Berikan nilai untuk setiap kriteria pada alternatif</li>
                        <li>Pastikan semua alternatif sudah dinilai sebelum perhitungan</li>
                        <li>Lakukan perhitungan MAIRCA untuk mendapatkan peringkat</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Tips Mengelola Alternatif</h5>
                    <ul>
                        <li>Berikan nama alternatif yang mudah dikenali dan spesifik</li>
                        <li>Tulis deskripsi yang menjelaskan karakteristik alternatif</li>
                        <li>Pastikan semua alternatif memiliki kode unik untuk identifikasi</li>
                        <li>Berikan nilai yang objektif untuk setiap kriteria</li>
                        <li>Minimal buat 2 alternatif untuk dapat melakukan perbandingan</li>
                    </ul>
                    <div class="alert alert-warning mt-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-circle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Perhatian</h6>
                                <p class="mb-0">Pastikan Anda telah memberikan nilai untuk semua kriteria pada setiap alternatif sebelum melakukan perhitungan MAIRCA agar hasil peringkat akurat.</p>
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