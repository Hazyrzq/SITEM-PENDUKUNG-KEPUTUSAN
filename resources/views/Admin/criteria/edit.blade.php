@extends('layouts.admin')

@section('title', 'Edit Kriteria')

@section('content')
<div class="content-wrapper">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.criteria.index') }}">Kriteria</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Kriteria: {{ $criterion->name }}</h4>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-warning mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Perhatian</h6>
                        <p class="mb-0">Mengubah rank kriteria akan memengaruhi bobot. Jika melakukan perubahan, Anda perlu menghitung ulang bobot menggunakan metode ROC.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.criteria.update', $criterion) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label fw-bold">Kode Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $criterion->code) }}" required>
                            <div class="form-text">Masukkan kode unik untuk kriteria (maks. 10 karakter).</div>
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $criterion->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rank" class="form-label fw-bold">Ranking <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('rank') is-invalid @enderror" id="rank" name="rank" value="{{ old('rank', $criterion->rank) }}" min="1" required>
                            <div class="form-text">Masukkan ranking kepentingan kriteria (1 = paling penting).</div>
                            @error('rank')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">Tipe Kriteria <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="benefit" {{ old('type', $criterion->type) == 'benefit' ? 'selected' : '' }}>Benefit (Nilai lebih besar lebih baik)</option>
                                <option value="cost" {{ old('type', $criterion->type) == 'cost' ? 'selected' : '' }}>Cost (Nilai lebih kecil lebih baik)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.criteria.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection