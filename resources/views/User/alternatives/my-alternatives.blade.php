@extends('layouts.app')

@section('title', 'Alternatif Saya')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-cubes me-2"></i>Alternatif Saya</h4>
                <a href="{{ route('user.alternatives.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-1"></i>Tambah Alternatif
                </a>
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
                        <p class="mb-0">Berikut adalah daftar alternatif yang Anda buat. Anda dapat menambah, mengedit, atau menghapus alternatif Anda sendiri.</p>
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
                            <p class="mb-0">Anda belum membuat alternatif apapun. Silakan klik tombol "Tambah Alternatif" untuk menambahkan alternatif baru.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode</th>
                                <th width="25%">Nama Alternatif</th>
                                <th width="30%">Deskripsi</th>
                                <th width="10%">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $index => $alternative)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-secondary">{{ $alternative->code }}</span></td>
                                    <td class="fw-semibold">{{ $alternative->name }}</td>
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
                                            <a href="{{ route('user.alternatives.show', $alternative) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('user.alternatives.edit', $alternative) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('user.alternatives.destroy', $alternative) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
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