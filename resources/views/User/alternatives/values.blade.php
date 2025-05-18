{{-- resources/views/user/alternatives/values.blade.php --}}

@extends('layouts.app')

@section('title', 'Beri Nilai Alternatif')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.alternatives.index') }}">Alternatif</a></li>
            <li class="breadcrumb-item active">Beri Nilai</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-pencil-alt me-2"></i>Beri Nilai Alternatif: {{ $alternative->name }}</h4>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Petunjuk Pengisian</h6>
                        <p class="mb-0">Berikan nilai untuk setiap kriteria pada alternatif <b>{{ $alternative->name }}</b>. Pastikan nilai yang diberikan sesuai dengan ketentuan kriteria.</p>
                    </div>
                </div>
            </div>

            @if (session('error'))
            <div class="alert alert-danger">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Error!</h6>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- PERBAIKAN: Pastikan form benar dan CSRF token disertakan --}}
            <form action="{{ route('user.alternatives.store-values', $alternative) }}" method="POST">
                @csrf
                
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Kode</th>
                            <th width="40%">Kriteria</th>
                            <th width="15%">Tipe</th>
                            <th width="20%">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criteria as $index => $criterion)
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
                                <td>
                                    {{-- PERBAIKAN: Pastikan name attribute benar dan konsisten --}}
                                    <input type="number" 
                                           step="0.01" 
                                           class="form-control @error('values.'.$criterion->id) is-invalid @enderror" 
                                           name="values[{{ $criterion->id }}]" 
                                           value="{{ old('values.'.$criterion->id, $values[$criterion->id] ?? '') }}" 
                                           required>
                                    @error('values.'.$criterion->id)
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('user.alternatives.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    {{-- PERBAIKAN: Pastikan button submit berfungsi dengan benar --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection