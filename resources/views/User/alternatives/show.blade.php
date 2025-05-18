@extends('layouts.app')

@section('title', 'Detail Alternatif')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.alternatives.index') }}">Alternatif</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-cube me-2"></i>Detail Alternatif: {{ $alternative->name }}</h4>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Informasi Alternatif</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%" class="bg-light">Kode</th>
                            <td><span class="badge bg-secondary">{{ $alternative->code }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama</th>
                            <td>{{ $alternative->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Deskripsi</th>
                            <td>{{ $alternative->description ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Nilai Kriteria</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode</th>
                            <th width="30%">Kriteria</th>
                            <th width="15%">Tipe</th>
                            <th width="15%">Nilai</th>
                            <th width="20%">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Mendapatkan semua kriteria yang diurutkan berdasarkan rank agar menampilkan C1, C2, C3 secara berurutan
                            $orderedCriteria = App\Models\Criteria::orderBy('rank')->get();
                            
                            // Mengubah koleksi nilai ke array asosiatif untuk akses cepat
                            $valueMap = [];
                            foreach ($alternative->values as $value) {
                                $valueMap[$value->criteria_id] = $value;
                            }
                        @endphp
                        
                        @forelse($orderedCriteria as $index => $criterion)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-secondary">{{ $criterion->code }}</span></td>
                                <td>{{ $criterion->name }}</td>
                                <td>
                                    @if($criterion->type == 'benefit')
                                        <span class="badge bg-success">Benefit</span>
                                    @else
                                        <span class="badge bg-danger">Cost</span>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    @if(isset($valueMap[$criterion->id]))
                                        {{ number_format($valueMap[$criterion->id]->value, 2) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($criterion->type == 'benefit')
                                        <small class="text-muted">Semakin tinggi semakin baik</small>
                                    @else
                                        <small class="text-muted">Semakin rendah semakin baik</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada kriteria yang tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('user.alternatives.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <a href="{{ route('user.alternatives.values', $alternative) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit Nilai
                </a>
                <form action="{{ route('user.alternatives.destroy-values', $alternative) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai alternatif ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Hapus Nilai
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection