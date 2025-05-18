<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK KELOMPOK 5 - ROC dan MAIRCA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background-color: #f8f9fC;
            color: #333;
        }
        
        /* Navbar styling */
        .navbar {
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s;
            margin: 0 5px;
            border-radius: 6px;
            padding: 8px 15px !important;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9);
        }
        
        /* Hero section */
        .hero-section {
            padding: 8rem 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            position: relative;
            overflow: hidden;
            border-radius: 0 0 50% 0;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.1);
        }
        
        .hero-section h1 {
            font-weight: 800;
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
        }
        
        .hero-section p {
            font-size: 1.25rem;
            opacity: 0.9;
        }
        
        /* Feature boxes */
        .feature-box {
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            height: 100%;
            transition: all 0.3s;
            background-color: white;
            border-top: 4px solid var(--primary-color);
        }
        
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.1);
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
        }
        
        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        /* Tables */
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .table thead {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Forms */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.7rem 1rem;
            border: 1px solid #e2e8f0;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.25);
            border-color: var(--primary-color);
        }
        
        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--dark-color), #000);
            padding: 3rem 0;
            margin-top: 6rem !important;
        }
        
        footer p {
            margin-bottom: 0;
        }
        
        /* Dashboard stats */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            height: 100%;
        }
        
        .stats-card-icon {
            background-color: rgba(67, 97, 238, 0.1);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 1rem;
        }
        
        .stats-card-icon i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        
        .stats-card h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-card p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 1rem 0;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: #6c757d;
        }
        
        /* Pagination */
        .pagination .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 3px;
            color: var(--dark-color);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="fas fa-chart-pie me-2"></i>
                SPK KELOMPOK 5
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">

                    
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard Admin
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('user/dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('user/calculations*') ? 'active' : '' }}" href="{{ route('user.calculations.index') }}">
                                    <i class="fas fa-calculator me-1"></i> Perhitungan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('user/criteria*') ? 'active' : '' }}" href="{{ route('user.criteria.index') }}">
                                    <i class="fas fa-list-check me-1"></i> Kriteria
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('user/alternatives*') ? 'active' : '' }}" href="{{ route('user.alternatives.index') }}">
                                    <i class="fas fa-sitemap me-1"></i> Alternatif
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="fas fa-user me-2"></i> Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show animate-in" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading mb-1">Berhasil!</h5>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-4">
            <div class="alert alert-danger alert-dismissible fade show animate-in" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading mb-1">Error!</h5>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i> SPK KELOMPOK 5</h5>
                    <p class="text-white-50">
                        Sistem Pendukung Keputusan menggunakan metode ROC dan MAIRCA.
                    </p>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Metode</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">ROC (Rank Order Centroid)</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">MAIRCA</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Tautan</h5>
                    <ul class="list-unstyled">
                        @auth
                            <li class="mb-2">
                                @if (Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-white-50 text-decoration-none">Dashboard</a>
                                @else
                                    <a href="{{ route('user.dashboard') }}" class="text-white-50 text-decoration-none">Dashboard</a>
                                @endif
                            </li>
                        @else
                            <li class="mb-2"><a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Login</a></li>
                            <li class="mb-2"><a href="{{ route('register') }}" class="text-white-50 text-decoration-none">Register</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} SPK KELOMPOK 5 | Metode ROC dan MAIRCA
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="d-flex justify-content-center justify-content-md-end">
                        <a href="#" class="text-white me-3"><i class="fab fa-github"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation class to elements when they come into view
        document.addEventListener('DOMContentLoaded', function() {
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
</body>
</html>