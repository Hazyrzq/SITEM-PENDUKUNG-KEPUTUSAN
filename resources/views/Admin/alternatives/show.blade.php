@extends('layouts.admin')

@section('title', 'Detail Alternatif')

@section('content')
<div class="content-wrapper">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.alternatives.index') }}">Alternatif</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-cube me-2"></i>Detail Alternatif: {{ $alternative->name }}</h4>
                <div>
                    <a href="{{ route('admin.alternatives.edit', $alternative) }}" class="btn btn-sm btn-light">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Informasi Alternatif</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%" class="bg-light">Kode</th>
                            <td><span class="badge bg-secondary">{{ $alternative->code }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama</th>
                            <td>{{ $alternative->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Deskripsi</th>
                            <td>{{ $alternative->description ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Statistik Penilaian User</h5>
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">Jumlah User yang Menilai</h6>
                                </div>
                                <div>
                                    <span class="badge bg-primary fs-5">{{ $userValueCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Daftar User yang Menilai</h5>
            @if($userValues->isEmpty())
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi</h6>
                            <p class="mb-0">Belum ada user yang memberikan nilai untuk alternatif ini.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama User</th>
                                <th width="25%">Email</th>
                                <th width="20%">Jumlah Kriteria</th>
                                <th width="15%">Tanggal Penilaian</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userValues as $index => $userValue)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $userValue->user->name }}</td>
                                    <td>{{ $userValue->user->email }}</td>
                                    <td>{{ $userValue->criteria_count }} kriteria</td>
                                    <td>{{ $userValue->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.alternatives.user-values', ['alternative' => $alternative, 'user' => $userValue->user_id]) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i>Lihat Nilai
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.alternatives.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection