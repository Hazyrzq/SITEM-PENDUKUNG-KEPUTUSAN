@extends('layouts.admin')

@section('title', 'Alternatif dari ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Pesan Sukses/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-cubes me-2"></i>Alternatif dari {{ $user->name }}
                </h5>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-light">
                    <i class="fas fa-user me-1"></i>Lihat Profil User
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($alternatives->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>User ini belum membuat alternatif apapun.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>
                                <th width="25%">Nama</th>
                                <th width="30%">Deskripsi</th>
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
                                        @php
                                            $valueCount = \App\Models\AlternativeValue::where('alternative_id', $alternative->id)
                                                ->distinct('user_id')
                                                ->count('user_id');
                                        @endphp
                                        <span class="badge bg-{{ $valueCount > 0 ? 'success' : 'warning' }}">{{ $valueCount }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.alternatives.show', $alternative) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.alternatives.destroy', $alternative) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini? Semua nilai yang terkait juga akan dihapus.');">
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
        <div class="card-footer">
            <a href="{{ route('admin.alternatives.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection