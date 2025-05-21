@extends('layouts.admin')

@section('title', 'Detail Alternatif')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-cube me-2"></i>Detail Alternatif</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Kode</th>
                            <td>
                                <span class="badge bg-secondary">{{ $alternative->code }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $alternative->name }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $alternative->description ?? '-' }}</td>
                        </tr>
                        @if($creator)
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>
                                <a href="{{ route('admin.users.show', $creator->id) }}" class="text-decoration-none">
                                    {{ $creator->name }}
                                </a>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>Jumlah Penilaian</th>
                            <td>
                                <span class="badge bg-{{ $userValueCount > 0 ? 'success' : 'warning' }}">
                                    {{ $userValueCount }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Statistik Penilaian</h6>
                        </div>
                        <div class="card-body">
                            @if($userValueCount > 0)
                                <p>Alternatif ini telah dinilai oleh <strong>{{ $userValueCount }}</strong> user.</p>
                                
                                <div class="mt-3">
                                    <h6>User yang Telah Menilai:</h6>
                                    <ul class="list-group">
                                        @php
                                            $userValues = \App\Models\AlternativeValue::where('alternative_id', $alternative->id)
                                                ->select('user_id')
                                                ->distinct()
                                                ->with('user')
                                                ->get();
                                        @endphp
                                        
                                        @foreach($userValues as $userValue)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $userValue->user->name }}
                                                <a href="{{ route('admin.alternatives.user-values', [$alternative->id, $userValue->user_id]) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Lihat Nilai
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Alternatif ini belum dinilai oleh user manapun.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.alternatives.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                
                <div>
                    @if($alternative->user_id === null)
                        <a href="{{ route('admin.alternatives.edit', $alternative) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                    @endif
                    
                    <form action="{{ route('admin.alternatives.destroy', $alternative) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini? Semua nilai terkait juga akan dihapus.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection