<!-- resources/views/user/alternatives/values.blade.php -->
@extends('layouts.app')

@section('title', 'Beri Nilai Alternatif')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-pencil-alt me-2"></i>Beri Nilai Alternatif: {{ $alternative->name }}</h4>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Panduan Penilaian</h6>
                        <p class="mb-0">Berikan nilai untuk alternatif "{{ $alternative->name }}" berdasarkan setiap kriteria dibawah ini. Nilai harus berupa angka sesuai ketentuan masing-masing kriteria.</p>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Detail Alternatif</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Kode</th>
                                    <td><span class="badge bg-secondary">{{ $alternative->code }}</span></td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $alternative->name }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ $alternative->description ?? '-' }}</td>
                                </tr>
                                @if($alternative->user_id === Auth::id())
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge bg-primary">Alternatif Saya</span></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informasi Penilaian</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    Total Kriteria
                                    <span class="badge bg-primary rounded-pill">{{ count($criteria) }}</span>
                                </li>
                                @if($userHasCustomRanking)
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    Mode Perangkingan Kriteria
                                    <span class="badge bg-success rounded-pill">Kustomisasi User</span>
                                </li>
                                @else
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    Mode Perangkingan Kriteria
                                    <span class="badge bg-info rounded-pill">Default</span>
                                </li>
                                @endif
                            </ul>
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Pastikan Anda memberikan nilai untuk semua kriteria
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('user.alternatives.store-values', $alternative) }}" method="POST">
                @csrf
                
                <div class="table-responsive mb-4">
                    <table class="table table-hover border">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode</th>
                                <th width="30%">Nama Kriteria</th>
                                <th width="15%">Bobot</th>
                                <th width="35%">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $index => $criterion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-secondary">{{ $criterion->code }}</span></td>
                                    <td>{{ $criterion->name }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $criterion->weight * 100 }}%;" aria-valuenow="{{ $criterion->weight * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="d-block mt-1">{{ number_format($criterion->weight, 4) }}</small>
                                    </td>
                                    <td>
                                        <input 
                                            type="number" 
                                            class="form-control @error('values.'.$criterion->id) is-invalid @enderror" 
                                            name="values[{{ $criterion->id }}]" 
                                            step="0.01"
                                            min="0"
                                            placeholder="Masukkan nilai"
                                            value="{{ old('values.'.$criterion->id, $values[$criterion->id] ?? '') }}" 
                                            required
                                        >
                                        @error('values.'.$criterion->id)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('user.alternatives.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection