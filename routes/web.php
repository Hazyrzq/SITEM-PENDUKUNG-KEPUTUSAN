<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AlternativeController as AdminAlternativeController;
use App\Http\Controllers\Admin\CalculationController as AdminCalculationController;
use App\Http\Controllers\Admin\CriteriaController as AdminCriteriaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\AlternativeController as UserAlternativeController;
use App\Http\Controllers\User\CalculationController as UserCalculationController;
use App\Http\Controllers\User\CriteriaController as UserCriteriaController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama (landing page)
Route::get('/', function () {
    return view('home');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    
    // Kriteria (hanya admin yang bisa mengatur kriteria)
    Route::get('/criteria/calculate-weights', [AdminCriteriaController::class, 'calculateWeights'])->name('criteria.calculate-weights');
    Route::get('/criteria/reset-weights', [AdminCriteriaController::class, 'resetWeights'])->name('criteria.reset-weights');
    Route::resource('criteria', AdminCriteriaController::class);
    
    // Alternatif (admin membuat dan mengelola alternatif)
    Route::resource('alternatives', AdminAlternativeController::class);
    Route::get('/alternatives/{alternative}/user-values/{user}', [AdminAlternativeController::class, 'viewUserValues'])->name('alternatives.user-values');
    
    // Perhitungan (admin bisa melihat semua perhitungan) - Perbaikan urutan route
    Route::get('/calculations', [AdminCalculationController::class, 'index'])->name('calculations.index');
    Route::get('/calculations/report', [AdminCalculationController::class, 'report'])->name('calculations.report'); // Pindahkan ke atas
    Route::get('/calculations/user/{user}', [AdminCalculationController::class, 'userCalculations'])->name('calculations.user'); // Pindahkan ke atas
    Route::get('/calculations/{calculation}', [AdminCalculationController::class, 'show'])->name('calculations.show');
    Route::delete('/calculations/{calculation}', [AdminCalculationController::class, 'destroy'])->name('calculations.destroy');
});

// User Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Dashboard User
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/results', [UserDashboardController::class, 'results'])->name('results');
    
    // Kriteria (user hanya bisa melihat)
    Route::get('/criteria', [UserCriteriaController::class, 'index'])->name('criteria.index');
    Route::get('/criteria/{criterion}', [UserCriteriaController::class, 'show'])->name('criteria.show');
    Route::get('/criteria-weights', [UserCriteriaController::class, 'weights'])->name('criteria.weights');
    
    // Alternatif (user hanya bisa memasukkan nilai)
    Route::get('/alternatives', [UserAlternativeController::class, 'index'])->name('alternatives.index');
    Route::get('/alternatives/{alternative}', [UserAlternativeController::class, 'show'])->name('alternatives.show');
    Route::get('/alternatives/{alternative}/values', [UserAlternativeController::class, 'editValues'])->name('alternatives.values');
    Route::post('/alternatives/{alternative}/values', [UserAlternativeController::class, 'storeValues'])->name('alternatives.store-values');
    Route::get('/my-values', [UserAlternativeController::class, 'myValues'])->name('alternatives.my-values');
    Route::delete('/alternatives/{alternative}/values', [UserAlternativeController::class, 'destroyValues'])->name('alternatives.destroy-values');
    
    // Perhitungan - Perbaikan urutan route
    Route::get('/calculations', [UserCalculationController::class, 'index'])->name('calculations.index');
    Route::get('/calculations/create', [UserCalculationController::class, 'create'])->name('calculations.create');
    Route::post('/calculations', [UserCalculationController::class, 'store'])->name('calculations.store');
    Route::get('/calculations/{calculation}', [UserCalculationController::class, 'show'])->name('calculations.show');
    Route::delete('/calculations/{calculation}', [UserCalculationController::class, 'destroy'])->name('calculations.destroy');
});

// Fallback route untuk dashboard berdasarkan role
Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    
    return redirect()->route('login');
})->name('dashboard');