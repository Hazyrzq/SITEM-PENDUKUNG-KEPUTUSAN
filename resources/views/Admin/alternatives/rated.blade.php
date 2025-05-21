@extends('layouts.admin')

@section('title', 'Alternatif yang Sudah Dinilai')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-check-square me-2"></i>Alternatif yang Sudah Dinilai</h5>
        </div>
        <div class="card-body">
            @if($alternatives->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Belum ada alternatif yang telah dinilai oleh user.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>
                                <th width="20%">Nama</th>
                                <th width="20%">Deskripsi</th>
                                <th width="15%">Asal</th>
                                <th width="15%">Jumlah Penilaian</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $index => $alternative)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-secondary">{{ $alternative->code }}</span></td>
                                    <td>{{ $alternative->name }}</td>
                                    <td>{{ $alternative->description ?? '-' }}</td>
                                    <td>
                                        @if($alternative->user_id)
                                            <a href="{{ route('admin.users.show', $alternative->user_id) }}" class="text-decoration-none">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-user me-1"></i>{{ $alternative->creator->name }}
                                                </span>
                                            </a>
                                        @else
                                            <span class="badge bg-primary">
                                                <i class="fas fa-user-shield me-1"></i>Admin
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $alternative->user_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.alternatives.show', $alternative) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
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
        <div class="card-footer">
            <a href="{{ route('admin.alternatives.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection