@extends('layouts.admin')

@section('title', 'Manajemen Alternatif')

@section('content')
<div class="content-wrapper">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0 fw-bold"><i class="fas fa-cubes me-2"></i>Manajemen Alternatif</h3>
            <p class="text-muted">Kelola alternatif untuk sistem pendukung keputusan</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.alternatives.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Tambah Alternatif Baru
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @if($alternatives->isEmpty())
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Perhatian</h6>
                            <p class="mb-0">Belum ada alternatif yang tersedia. Silakan tambahkan alternatif baru.</p>
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
                                <th width="10%">User</th>
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
                                        <span class="badge bg-info">
                                            {{ $alternative->userValueCount ?? 0 }} user
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.alternatives.show', $alternative) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.alternatives.edit', $alternative) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $alternative->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $alternative->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $alternative->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $alternative->id }}">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus alternatif <strong>{{ $alternative->name }}</strong>?</p>
                                                        <p class="text-danger"><small>Tindakan ini akan menghapus semua nilai yang terkait dengan alternatif ini dan tidak dapat dibatalkan.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('admin.alternatives.destroy', $alternative) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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