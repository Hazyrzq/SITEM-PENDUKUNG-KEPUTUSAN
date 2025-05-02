<?php

use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\CriteriaController;
use Illuminate\Support\Facades\Route;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\Calculation;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', function () {
    return view('home');
})->name('home');

// Dashboard
Route::get('/dashboard', function () {
    $criteriaCount = Criteria::count();
    $alternativeCount = Alternative::count();
    $calculationCount = Calculation::count();
    
    return view('dashboard', compact('criteriaCount', 'alternativeCount', 'calculationCount'));
})->name('dashboard');

// Kriteria
Route::get('/criteria/calculate-weights', [CriteriaController::class, 'calculateWeights'])
    ->name('criteria.calculate-weights');
Route::resource('criteria', CriteriaController::class);

// Alternatif
Route::get('/alternatives/{alternative}/values', [AlternativeController::class, 'editValues'])
    ->name('alternatives.values');
Route::post('/alternatives/{alternative}/values', [AlternativeController::class, 'storeValues'])
    ->name('alternatives.store-values');
Route::resource('alternatives', AlternativeController::class);

// Perhitungan
Route::resource('calculations', CalculationController::class);