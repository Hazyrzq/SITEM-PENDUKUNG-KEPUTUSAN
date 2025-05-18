@extends('layouts.admin')

@section('title', 'Detail Nilai Alternatif')

@section('content')
<div class="content-wrapper">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.alternatives.index') }}">Alternatif</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.alternatives.show', $alternative) }}">Detail</a></li>
            <li class="breadcrumb-item active">Nilai User</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Nilai Alternatif: {{ $alternative->name }} (User: {{ $user->name }})</h4>
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
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Informasi User</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%" class="bg-light">Nama</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Email</th>
                            <td>{{ $user->email }}</td>
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
                        @forelse($values as $index => $value)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-secondary">{{ $value->criteria->code }}</span></td>
                                <td>{{ $value->criteria->name }}</td>
                                <td>
                                    @if($value->criteria->type == 'benefit')
                                        <span class="badge bg-success">Benefit</span>
                                    @else
                                        <span class="badge bg-danger">Cost</span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ number_format($value->value, 2) }}</td>
                                <td>
                                    @if($value->criteria->type == 'benefit')
                                        <small class="text-muted">Semakin tinggi semakin baik</small>
                                    @else
                                        <small class="text-muted">Semakin rendah semakin baik</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada nilai yang ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.alternatives.show', $alternative) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection