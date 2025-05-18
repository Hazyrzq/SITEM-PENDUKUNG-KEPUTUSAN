@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="content-wrapper">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Manajemen Pengguna</h3>
            <p class="text-muted">Kelola pengguna sistem pendukung keputusan</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @if($users->isEmpty())
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi</h6>
                            <p class="mb-0">Belum ada pengguna yang terdaftar.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nama</th>
                                <th width="25%">Email</th>
                                <th width="15%">Terdaftar</th>
                                <th width="15%">Aktivitas</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="mb-1">
                                                <span class="badge bg-primary me-1">{{ $user->calculation_count }}</span> Perhitungan
                                            </div>
                                            <div>
                                                <span class="badge bg-success me-1">{{ $user->value_count }}</span> Nilai Alternatif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus pengguna <strong>{{ $user->name }}</strong>?</p>
                                                        <p class="text-danger"><small>Tindakan ini akan menghapus semua data terkait user ini termasuk nilai alternatif dan perhitungan yang telah dilakukan. Tindakan ini tidak dapat dibatalkan.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
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

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik Pengguna</h4>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Pengguna Baru Per Bulan</h5>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <canvas id="newUsersChart" width="100%" height="40"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Aktivitas Pengguna</h5>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <canvas id="userActivityChart" width="100%" height="40"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        $('#usersTable').DataTable({
            "order": [[0, "asc"]],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
        
        // Prepare data for new users chart
        @php
            $monthlyNewUsers = [];
            $currentYear = date('Y');
            
            // Initialize all months with 0
            for ($i = 1; $i <= 12; $i++) {
                $monthlyNewUsers[$i] = 0;
            }
            
            // Count new users per month for current year
            foreach ($users as $user) {
                if ($user->created_at->year == $currentYear) {
                    $month = $user->created_at->month;
                    $monthlyNewUsers[$month]++;
                }
            }
            
            $labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $newUsersData = array_values($monthlyNewUsers);
            
            // Prepare data for user activity chart
            $userNames = $users->take(5)->pluck('name')->toArray();
            $calculationCounts = $users->take(5)->pluck('calculation_count')->toArray();
            $valueCounts = $users->take(5)->pluck('value_count')->toArray();
        @endphp
        
        // Create new users chart
        const newUsersCtx = document.getElementById('newUsersChart').getContext('2d');
        const newUsersChart = new Chart(newUsersCtx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Pengguna Baru',
                    data: @json($newUsersData),
                    backgroundColor: 'rgba(67, 97, 238, 0.2)',
                    borderColor: 'rgb(67, 97, 238)',
                    borderWidth: 2,
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Pengguna Baru Per Bulan ({{ $currentYear }})'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Pengguna Baru'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });
        
        // Create user activity chart
        const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
        const userActivityChart = new Chart(userActivityCtx, {
            type: 'bar',
            data: {
                labels: @json($userNames),
                datasets: [
                    {
                        label: 'Perhitungan',
                        data: @json($calculationCounts),
                        backgroundColor: 'rgba(67, 97, 238, 0.7)',
                        borderColor: 'rgb(67, 97, 238)',
                        borderWidth: 1
                    },
                    {
                        label: 'Nilai Alternatif',
                        data: @json($valueCounts),
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgb(40, 167, 69)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Aktivitas 5 Pengguna Teratas'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Pengguna'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection