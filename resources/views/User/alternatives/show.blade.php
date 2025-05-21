<!-- resources/views/user/alternatives/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Alternatif')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-cube me-2"></i>Detail Alternatif: {{ $alternative->name }}</h4>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Informasi Alternatif</h5>
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
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Ringkasan Nilai</h5>
                        </div>
                        <div class="card-body">
                            @if($values->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Belum ada nilai yang diberikan untuk alternatif ini.
                                </div>
                            @else
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="fw-bold">Total Kriteria yang Dinilai:</span>
                                    <span class="badge bg-success">{{ $values->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="fw-bold">Terakhir Diperbarui:</span>
                                    <span>{{ $values->max('updated_at')->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('user.alternatives.values', $alternative) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit Nilai
                                    </a>
                                    <form action="{{ route('user.alternatives.destroy-values', $alternative) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus nilai?')">
                                            <i class="fas fa-trash me-1"></i>Hapus Nilai
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Detail Nilai Kriteria</h5>
                </div>
                <div class="card-body">
                    @if($values->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Belum ada nilai yang diberikan untuk alternatif ini.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Kode</th>
                                        <th width="40%">Nama Kriteria</th>
                                        <th width="20%">Bobot</th>
                                        <th width="20%">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($values as $index => $value)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="badge bg-secondary">{{ $value->criteria->code }}</span></td>
                                            <td>{{ $value->criteria->name }}</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $value->criteria->weight * 100 }}%;" aria-valuenow="{{ $value->criteria->weight * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="d-block mt-1">{{ number_format($value->criteria->weight, 4) }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $value->value }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('user.alternatives.my-values') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <div>
                    <a href="{{ route('user.alternatives.values', $alternative) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit Nilai
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection