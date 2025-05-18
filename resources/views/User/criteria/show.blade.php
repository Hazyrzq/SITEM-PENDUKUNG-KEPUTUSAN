@extends('layouts.app')

@section('title', 'Detail Kriteria')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.criteria.index') }}">Kriteria</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-list-check me-2"></i>Detail Kriteria: {{ $criterion->name }}</h4>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Informasi Kriteria</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%" class="bg-light">Kode</th>
                            <td><span class="badge bg-secondary">{{ $criterion->code }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama</th>
                            <td>{{ $criterion->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Ranking</th>
                            <td><span class="badge bg-primary">{{ $criterion->rank }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Tipe</th>
                            <td>
                                @if($criterion->type == 'benefit')
                                    <span class="badge bg-success">Benefit</span>
                                    <div class="small text-muted mt-1">Nilai lebih besar lebih baik</div>
                                @else
                                    <span class="badge bg-danger">Cost</span>
                                    <div class="small text-muted mt-1">Nilai lebih kecil lebih baik</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Bobot</th>
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
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Statistik Penggunaan</h5>
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">Alternatif yang Dinilai</h6>
                                </div>
                                <div>
                                    <span class="badge bg-primary fs-5">{{ $alternativeCount }}</span>
                                </div>
                            </div>
                            <div class="alert alert-info mb-0">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0">Anda telah memberikan nilai untuk {{ $alternativeCount }} alternatif menggunakan kriteria ini.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('user.criteria.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection