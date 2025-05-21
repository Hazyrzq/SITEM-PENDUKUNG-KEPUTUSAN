<!-- resources/views/admin/alternatives/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Tambah Alternatif Baru')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Alternatif Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.alternatives.store') }}" method="POST">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label fw-bold">Kode Alternatif <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" maxlength="10" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kode harus unik dan maksimal 10 karakter.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama Alternatif <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" maxlength="255" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Deskripsi singkat tentang alternatif (opsional).</small>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi</h6>
                            <p class="mb-0">Alternatif yang dibuat oleh admin akan tersedia untuk semua user. Pastikan data yang dimasukkan sudah benar.</p>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.alternatives.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection