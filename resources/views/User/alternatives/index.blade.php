@extends('layouts.app')

@section('title', 'Daftar Alternatif')

@section('content')
<div class="container">
    <!-- Pesan Sukses atau Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-cubes me-2"></i>Daftar Alternatif</h4>
                <div>
                    <a href="{{ route('user.alternatives.my-alternatives') }}" class="btn btn-info me-2">
                        <i class="fas fa-list me-1"></i>Alternatif Saya
                    </a>
                    <a href="{{ route('user.alternatives.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>Tambah Alternatif
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Informasi</h6>
                        <p class="mb-0">Alternatif dibawah ini adalah alternatif yang tersedia untuk penilaian. Alternatif dengan tanda <span class="badge bg-primary">Milik Saya</span> adalah alternatif yang Anda buat. Silakan klik "Beri Nilai" untuk memberikan nilai pada setiap alternatif berdasarkan kriteria yang telah ditetapkan admin.</p>
                    </div>
                </div>
            </div>

            @if($alternatives->isEmpty())
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Belum ada alternatif yang tersedia. Silakan klik tombol "Tambah Alternatif" untuk menambahkan alternatif baru.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Kode</th>
                                <th width="25%">Nama Alternatif</th>
                                <th width="30%">Deskripsi</th>
                                <th width="13%">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $index => $alternative)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $alternative->code }}</span>
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $alternative->name }}
                                        @if($alternative->is_own)
                                            <span class="badge bg-primary ms-1">Milik Saya</span>
                                        @endif
                                    </td>
                                    <td>{{ $alternative->description ?? '-' }}</td>
                                    <td>
                                        @if($alternative->has_values)
                                            <span class="badge bg-success">Sudah Dinilai</span>
                                        @else
                                            <span class="badge bg-warning">Belum Dinilai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('user.alternatives.values', $alternative) }}" class="btn btn-sm btn-primary {{ $alternative->has_values ? 'disabled' : '' }}">
                                                <i class="fas fa-pencil-alt me-1"></i>Beri Nilai
                                            </a>
                                            @if($alternative->is_own)
                                                <a href="{{ route('user.alternatives.edit', $alternative) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Alternatif yang Sudah Dinilai</h4>
        </div>
        <div class="card-body p-4">
            @php
                $ratedAlternatives = $alternatives->filter(function($alt) {
                    return $alt->has_values;
                });
            @endphp

            @if($ratedAlternatives->isEmpty())
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Anda belum memberikan nilai untuk alternatif manapun.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Kode</th>
                                <th width="25%">Nama Alternatif</th>
                                <th width="30%">Deskripsi</th>
                                <th width="13%">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ratedAlternatives as $index => $alternative)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-success">{{ $alternative->code }}</span></td>
                                    <td class="fw-semibold">
                                        {{ $alternative->name }}
                                        @if($alternative->is_own)
                                            <span class="badge bg-primary ms-1">Milik Saya</span>
                                        @endif
                                    </td>
                                    <td>{{ $alternative->description ?? '-' }}</td>
                                    <td><span class="badge bg-success">Sudah Dinilai</span></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('user.alternatives.show', $alternative) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Lihat
                                            </a>
                                            <a href="{{ route('user.alternatives.values', $alternative) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                        </div>
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
@endsection