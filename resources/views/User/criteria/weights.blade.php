@extends('layouts.app')

@section('title', 'Bobot Kriteria')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.criteria.index') }}">Kriteria</a></li>
            <li class="breadcrumb-item active">Bobot</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-balance-scale me-2"></i>Bobot Kriteria (Metode ROC)</h4>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Informasi Metode ROC</h6>
                        <p class="mb-0">Bobot kriteria dihitung menggunakan metode Rank Order Centroid (ROC) berdasarkan peringkat kepentingan yang telah ditentukan oleh admin.</p>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode</th>
                            <th width="35%">Nama Kriteria</th>
                            <th width="10%">Ranking</th>
                            <th width="35%">Bobot</th>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="4" class="text-end">Total Bobot:</th>
                            <th>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        {{ number_format($totalWeight, 4) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalWeight * 100 }}%"></div>
                                        </div>
                                        <div class="small text-end mt-1">{{ number_format($totalWeight * 100, 2) }}%</div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0">Rumus Perhitungan Bobot ROC</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="fw-bold mb-2">Rumus ROC:</p>
                            <p class="mb-3">W<sub>k</sub> = (1/n) × Σ<sub>i=k</sub><sup>n</sup> (1/i)</p>
                            <p class="mb-2">di mana:</p>
                            <ul>
                                <li>W<sub>k</sub> = bobot kriteria pada peringkat ke-k</li>
                                <li>n = jumlah kriteria</li>
                                <li>i = posisi peringkat</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <p class="fw-bold mb-2">Contoh Perhitungan:</p>
                            <p>Untuk 4 kriteria dengan peringkat 1, 2, 3, dan 4:</p>
                            <ul>
                                <li>W<sub>1</sub> = (1/4) × (1/1 + 1/2 + 1/3 + 1/4) = 0.5208</li>
                                <li>W<sub>2</sub> = (1/4) × (1/2 + 1/3 + 1/4) = 0.2708</li>
                                <li>W<sub>3</sub> = (1/4) × (1/3 + 1/4) = 0.1458</li>
                                <li>W<sub>4</sub> = (1/4) × (1/4) = 0.0625</li>
                            </ul>
                            <p>Total bobot = 1.0000</p>
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