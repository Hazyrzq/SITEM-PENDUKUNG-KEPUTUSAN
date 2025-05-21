<!-- layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SPK KELOMPOK 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            color: #495057;
        }
        
        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            position: fixed;
            width: var(--sidebar-width);
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.85rem 1.5rem;
            border-radius: 0.5rem;
            margin: 0.3rem 0.8rem;
            transition: all 0.2s;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        /* Dropdown Styles in Sidebar */
        .sidebar .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            margin-left: 2.5rem;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .dropdown-item {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.6rem 1.2rem;
            border-radius: 0.3rem;
            transition: all 0.2s;
        }
        
        .sidebar .dropdown-item:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .sidebar .dropdown-item.active {
            color: var(--primary-color);
            background-color: #ffffff;
        }
        
        .sidebar .dropdown-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }
        
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 0.8rem 1.5rem;
        }
        
        .navbar .user-info {
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .navbar .user-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 18px;
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
            padding: 1rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .alert {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar-header h4, 
            .sidebar-header p, 
            .sidebar .nav-link span {
                display: none;
            }
            
            .sidebar .nav-link {
                padding: 0.85rem;
                justify-content: center;
                margin: 0.3rem;
            }
            
            .sidebar .nav-link i {
                margin-right: 0;
                font-size: 1.2rem;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .sidebar .logout-text {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
                margin-bottom: 1rem;
            }
            
            .sidebar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .sidebar-header h4, 
            .sidebar-header p {
                display: block;
                margin: 0;
            }
            
            .sidebar ul {
                display: flex;
                justify-content: space-around;
            }
            
            .sidebar .nav-link {
                flex-direction: column;
                text-align: center;
                padding: 0.5rem;
            }
            
            .sidebar .nav-link i {
                margin-right: 0;
                margin-bottom: 0.2rem;
            }
            
            .sidebar .nav-link span {
                display: block;
                font-size: 0.7rem;
            }
            
            .sidebar .logout-btn {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h4 class="mb-1">SPK KELOMPOK 5</h4>
                <p class="text-white-50 mb-0 small">Panel Admin</p>
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.criteria.*') ? 'active' : '' }}" href="{{ route('admin.criteria.index') }}">
                        <i class="fas fa-list-check"></i>
                        <span>Kriteria</span>
                    </a>
                </li>
                
                <!-- Dropdown untuk menu Alternatif -->
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.alternatives.*') || request()->routeIs('admin.user-alternatives') ? 'active' : '' }}" 
                       href="#alternativesDropdown" 
                       data-bs-toggle="collapse" 
                       role="button" 
                       aria-expanded="{{ request()->routeIs('admin.alternatives.*') || request()->routeIs('admin.user-alternatives') ? 'true' : 'false' }}" 
                       aria-controls="alternativesDropdown">
                        <i class="fas fa-cubes"></i>
                        <span>Alternatif</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.alternatives.*') || request()->routeIs('admin.user-alternatives') ? 'show' : '' }}" id="alternativesDropdown">
                        <div class="ps-4">
                            <a class="nav-link {{ request()->routeIs('admin.alternatives.index') ? 'active' : '' }}" href="{{ route('admin.alternatives.index') }}">
                                <i class="fas fa-list"></i>
                                <span>Semua Alternatif</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.alternatives.create') ? 'active' : '' }}" href="{{ route('admin.alternatives.create') }}">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Alternatif</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.alternatives.rated') ? 'active' : '' }}" href="{{ route('admin.alternatives.rated') }}">
                                <i class="fas fa-star"></i>
                                <span>Alternatif Dinilai</span>
                            </a>
                        </div>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.calculations.*') ? 'active' : '' }}" href="{{ route('admin.calculations.index') }}">
                        <i class="fas fa-calculator"></i>
                        <span>Perhitungan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                        <i class="fas fa-users"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-white border-0 bg-transparent w-100 logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="logout-text">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light sticky-top">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <h5 class="mb-0">Welcome Back!</h5>
                            <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
                        </div>
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <p class="mb-0">{{ Auth::user()->name }}</p>
                                <small class="text-muted">Administrator</small>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="content-wrapper">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>{{ session('success') }}</strong>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>{{ session('error') }}</strong>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script tambahan untuk semua halaman -->
    <script>
        // Verifikasi apakah Bootstrap JS sudah dimuat
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap JS tidak dimuat dengan benar');
            }
            
            // Auto close alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    
    <!-- Script khusus untuk masing-masing halaman -->
    @stack('scripts')
</body>
</html>