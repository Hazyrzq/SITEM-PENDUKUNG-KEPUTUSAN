@extends('layouts.app')

@section('title', 'Nilai Alternatif Saya')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Nilai Alternatif Saya</h4>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Informasi</h6>
                        <p class="mb-0">Berikut adalah daftar alternatif yang telah Anda berikan nilai. Anda dapat melihat detail, mengedit, atau menghapus nilai yang sudah diberikan.</p>
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
                            <p class="mb-0">Anda belum memberikan nilai untuk alternatif manapun. <a href="{{ route('user.alternatives.index') }}" class="text-decoration-none">Klik di sini</a> untuk mulai memberikan nilai.</p>
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
                                <th width="30%">Nama Alternatif</th>
                                <th width="35%">Jumlah Kriteria</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatives as $index => $alternative)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-success">{{ $alternative->code }}</span></td>
                                    <td class="fw-semibold">{{ $alternative->name }}</td>
                                    <td>{{ $alternative->values->count() }} kriteria</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('user.alternatives.show', $alternative) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Lihat
                                            </a>
                                            <button type="button" class="btn btn-sm btn-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('user.alternatives.values', $alternative) }}">
                                                        <i class="fas fa-edit me-1"></i>Edit Nilai
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('user.alternatives.destroy-values', $alternative) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai alternatif ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash me-1"></i>Hapus Nilai
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('user.calculations.create') }}" class="btn btn-primary {{ $alternatives->isEmpty() ? 'disabled' : '' }}">
                    <i class="fas fa-calculator me-1"></i>Lakukan Perhitungan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection