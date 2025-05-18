@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="content-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Pengguna</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-user me-2"></i>Profil Pengguna</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="user-avatar mx-auto mb-3">
                                <i class="fas fa-user-circle fa-5x text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-3">{{ $user->email }}</p>
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th width="40%" class="bg-light">ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Terdaftar Pada</th>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Terakhir Update</th>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>

                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteUserModal">
                                <i class="fas fa-trash me-1"></i>Hapus Pengguna
                            </button>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus pengguna <strong>{{ $user->name }}</strong>?
                                        </p>
                                        <p class="text-danger"><small>Tindakan ini akan menghapus semua data terkait user
                                                ini termasuk nilai alternatif dan perhitungan yang telah dilakukan. Tindakan
                                                ini tidak dapat dibatalkan.</small></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Aktivitas</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="stats-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Perhitungan</h6>
                                <span class="badge bg-primary">{{ $calculationCount }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;"></div>
                            </div>
                        </div>

                        <div class="stats-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Nilai Alternatif</h6>
                                <span class="badge bg-success">{{ $valueCount }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                            </div>
                        </div>

                        <div class="stats-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Alternatif Dinilai</h6>
                                <span class="badge bg-info">{{ $alternativesWithValues }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-history me-2"></i>Perhitungan Terbaru</h4>
                    </div>
                    <div class="card-body p-4">
                        @if($recentCalculations->isEmpty())
                            <div class="alert alert-info mb-0">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Informasi</h6>
                                        <p class="mb-0">Pengguna ini belum melakukan perhitungan apapun.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Perhitungan</th>
                                            <th>Tanggal</th>
                                            <th>Alternatif Terbaik</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentCalculations as $calculation)
                                            <tr>
                                                <td class="fw-semibold">{{ $calculation->name }}</td>
                                                <td>{{ $calculation->calculated_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    @if(!empty($calculation->results))
                                                        @php
                                                            // PERBAIKAN: Gunakan json_decode untuk membaca results
                                                            $results = is_string($calculation->results) ? json_decode($calculation->results, true) : [];
                                                            $finalValues = isset($results['final_values']) ? $results['final_values'] : [];
                                                            
                                                            // Urutkan berdasarkan nilai terbesar
                                                            usort($finalValues, function($a, $b) {
                                                                return $b['final_value'] <=> $a['final_value'];
                                                            });
                                                            
                                                            $topResult = !empty($finalValues) ? $finalValues[0] : null;
                                                        @endphp
                                                        
                                                        @if($topResult)
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-success me-1">{{ $topResult['code'] ?? 'N/A' }}</span>
                                                                {{ $topResult['name'] ?? 'Tidak ada nama' }}
                                                            </div>
                                                        @else
                                                            <span class="text-muted">Format hasil tidak valid</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Tidak ada data</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.calculations.show', $calculation) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-end mt-3">
                                <a href="{{ route('admin.calculations.user', $user->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-list me-1"></i>Lihat Semua Perhitungan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Nilai Alternatif</h4>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $alternativeValues = \App\Models\AlternativeValue::where('user_id', $user->id)
                                ->with(['alternative', 'criteria'])
                                ->get()
                                ->groupBy('alternative_id');
                        @endphp

                        @if($alternativeValues->isEmpty())
                            <div class="alert alert-info mb-0">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Informasi</h6>
                                        <p class="mb-0">Pengguna ini belum memberikan nilai untuk alternatif apapun.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="accordion" id="alternativeAccordion">
                                @foreach($alternativeValues as $alternativeId => $values)
                                    @php
                                        $alternative = $values->first()->alternative;
                                    @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $alternativeId }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $alternativeId }}" aria-expanded="false"
                                                aria-controls="collapse{{ $alternativeId }}">
                                                <span class="badge bg-secondary me-2">{{ $alternative->code }}</span>
                                                <span class="fw-bold">{{ $alternative->name }}</span>
                                                <span class="ms-2 badge bg-primary">{{ $values->count() }} kriteria</span>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $alternativeId }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ $alternativeId }}" data-bs-parent="#alternativeAccordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th width="15%">Kode</th>
                                                                <th width="40%">Kriteria</th>
                                                                <th width="15%">Tipe</th>
                                                                <th width="25%">Nilai</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($values as $index => $value)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td><span class="badge bg-secondary">{{ $value->criteria->code }}</span>
                                                                    </td>
                                                                    <td>{{ $value->criteria->name }}</td>
                                                                    <td>
                                                                        @if($value->criteria->type == 'benefit')
                                                                            <span class="badge bg-success">Benefit</span>
                                                                        @else
                                                                            <span class="badge bg-danger">Cost</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="fw-bold">{{ number_format($value->value, 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Pengguna
            </a>
        </div>
    </div>

    <style>
        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4361ee;
        }
    </style>
@endsection