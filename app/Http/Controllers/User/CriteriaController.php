<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriteriaController extends Controller
{
    protected array $middleware = ['auth'];
    
    /**
     * Menampilkan daftar kriteria untuk user (hanya untuk melihat)
     */
    public function index()
    {
        $criteria = Criteria::orderBy('rank')->get();
        
        // Cek apakah semua kriteria sudah memiliki bobot
        $allHaveWeights = $criteria->every(function ($criterion) {
            return $criterion->weight !== null;
        });
        
        return view('user.criteria.index', compact('criteria', 'allHaveWeights'));
    }
    
    /**
     * Menampilkan detail kriteria
     */
    public function show(Criteria $criterion)
    {
        $userId = Auth::id();
        
        // Hitung jumlah alternatif yang sudah diinput nilainya oleh user untuk kriteria ini
        $alternativeCount = $criterion->alternativeValues()
            ->where('user_id', $userId)
            ->distinct('alternative_id')
            ->count('alternative_id');
            
        return view('user.criteria.show', compact('criterion', 'alternativeCount'));
    }
    
    /**
     * Menampilkan informasi tentang bobot kriteria
     */
    public function weights()
    {
        $criteria = Criteria::orderBy('rank')->get();
        
        // Cek apakah semua kriteria sudah memiliki bobot
        $allHaveWeights = $criteria->every(function ($criterion) {
            return $criterion->weight !== null;
        });
        
        // Jika belum semua memiliki bobot, redirect ke halaman kriteria
        if (!$allHaveWeights) {
            return redirect()->route('user.criteria.index')
                ->with('info', 'Admin belum menghitung bobot untuk semua kriteria.');
        }
        
        // Hitung total bobot (seharusnya mendekati 1.0)
        $totalWeight = $criteria->sum('weight');
        
        return view('user.criteria.weights', compact('criteria', 'totalWeight'));
    }
}