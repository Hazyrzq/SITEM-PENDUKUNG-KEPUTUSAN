<!-- resources/views/user/alternatives/my-values.blade.php -->
@extends('layouts.app')

@section('title', 'Alternatif Yang Sudah Dinilai')

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
                <h4 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Alternatif Yang Sudah Dinilai</h4>
                <div>
                    <a href="{{ route('user.alternatives.index') }}" class="btn btn-light">
                        <i class="fas fa-list me-1"></i>Daftar Alternatif
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
                        <p class="mb-0">Berikut adalah daftar alternatif yang telah Anda nilai. Anda dapat melihat detail nilai atau melakukan perubahan nilai melalui tombol aksi.</p>
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
                            <p class="mb-0">Anda belum memberikan nilai untuk alternatif manapun. Silakan kembali ke <a href="{{ route('user.alternatives.index') }}">Daftar Alternatif</a> untuk memberikan nilai.</p>
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
                                <th width="25%">Deskripsi</th>
                                <th width="15%">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $index => $alternative)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-success">{{ $alternative->code }}</span></td>
                                    <td class="fw-semibold">
                                        {{ $alternative->name }}
                                        @if($alternative->user_id === Auth::id())
                                            <span class="badge bg-primary ms-1">Milik Saya</span>
                                        @endif
                                    </td>
                                    <td>{{ $alternative->description ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <span class="badge bg-success">Dinilai</span>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">
                                                    {{ $alternative->criteria_count }}/{{ $alternative->total_criteria }} kriteria
                                                </small>
                                                <div class="progress" style="height: 5px; width: 80px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($alternative->criteria_count / $alternative->total_criteria) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('user.alternatives.show', $alternative) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Lihat
                                            </a>
                                            <a href="{{ route('user.alternatives.values', $alternative) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <form action="{{ route('user.alternatives.destroy-values', $alternative) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai untuk alternatif ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash me-1"></i>Hapus
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