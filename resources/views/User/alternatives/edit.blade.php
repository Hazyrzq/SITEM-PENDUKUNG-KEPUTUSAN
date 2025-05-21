@extends('layouts.app')

@section('title', 'Edit Alternatif')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Alternatif</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('user.alternatives.update', $alternative) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="code" class="form-label fw-bold">Kode Alternatif <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $alternative->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Kode harus unik dan maksimal 10 karakter.</small>
                </div>
                
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Nama Alternatif <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $alternative->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $alternative->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('user.alternatives.my-alternatives') }}" class="btn btn-secondary">
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