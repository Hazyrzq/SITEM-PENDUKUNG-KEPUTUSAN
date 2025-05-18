@extends('layouts.app')

@section('title', 'Buat Perhitungan Baru')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.calculations.index') }}">Perhitungan</a></li>
            <li class="breadcrumb-item active">Buat Baru</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Buat Perhitungan Baru</h4>
        </div>
        <div class="card-body p-4">
            @if($needsWeightCalculation)
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Admin belum menghitung bobot kriteria. Perhitungan tidak dapat dilakukan sampai bobot kriteria dihitung.</p>
                        </div>
                    </div>
                </div>
            @elseif(!$hasValues)
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Anda belum memberikan nilai untuk alternatif manapun. Silakan beri nilai minimal 2 alternatif terlebih dahulu.</p>
                            <a href="{{ route('user.alternatives.index') }}" class="btn btn-sm btn-warning mt-2">
                                <i class="fas fa-pencil-alt me-1"></i>Beri Nilai Alternatif
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi</h6>
                            <p class="mb-0">Perhitungan MAIRCA akan menggunakan alternatif yang telah Anda berikan nilai dan bobot kriteria yang telah dihitung dengan metode ROC.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('user.calculations.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Perhitungan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        <div class="form-text">Masukkan nama untuk perhitungan ini (contoh: "Perhitungan VPS Cloud Mei 2025")</div>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0">Alternatif yang Akan Dihitung</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $alternatives = \App\Models\Alternative::withValuesForUser(Auth::id())->get();
                            @endphp
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="15%">Kode</th>
                                            <th width="40%">Nama Alternatif</th>
                                            <th width="40%">Jumlah Kriteria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($alternatives as $index => $alternative)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><span class="badge bg-secondary">{{ $alternative->code }}</span></td>
                                                <td class="fw-semibold">{{ $alternative->name }}</td>
                                                <td>
                                                    @php
                                                        $valueCount = \App\Models\AlternativeValue::where('alternative_id', $alternative->id)
                                                            ->where('user_id', Auth::id())
                                                            ->count();
                                                    @endphp
                                                    <span class="badge bg-primary">{{ $valueCount }}</span> kriteria
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('user.calculations.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calculator me-1"></i>Mulai Perhitungan
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Langkah-langkah Perhitungan MAIRCA</h4>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-12">
                    <ol>
                        <li class="mb-4">
                            <h5 class="fw-bold">Membentuk Matriks Keputusan</h5>
                            <p>Langkah pertama adalah membentuk matriks keputusan dari nilai-nilai yang telah Anda berikan untuk setiap alternatif pada setiap kriteria.</p>
                        </li>
                        <li class="mb-4">
                            <h5 class="fw-bold">Normalisasi Matriks Keputusan</h5>
                            <p>Matriks keputusan dinormalisasi sesuai dengan tipe kriteria:</p>
                            <ul>
                                <li><strong>Benefit:</strong> (x - min) / (max - min)</li>
                                <li><strong>Cost:</strong> (max - x) / (max - min)</li>
                            </ul>
                        </li>
                        <li class="mb-4">
                            <h5 class="fw-bold">Perhitungan Matriks Pembobotan</h5>
                            <p>Matriks normalisasi dikalikan dengan bobot kriteria yang telah dihitung dengan metode ROC.</p>
                        </li>
                        <li class="mb-4">
                            <h5 class="fw-bold">Perhitungan Matriks Jarak dari Solusi Ideal</h5>
                            <p>Menghitung jarak setiap alternatif dari solusi ideal untuk setiap kriteria.</p>
                        </li>
                        <li class="mb-4">
                            <h5 class="fw-bold">Menghitung Nilai Akhir dan Peringkat</h5>
                            <p>Menghitung nilai akhir setiap alternatif dan mengurutkan berdasarkan nilai tersebut untuk mendapatkan peringkat keputusan.</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection