<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalculationController extends Controller
{
    /**
     * Menampilkan daftar perhitungan
     */
    public function index()
    {
        // PERBAIKAN: Menggunakan paginate() bukan get()
        $calculations = Calculation::with('user')
            ->orderBy('calculated_at', 'desc')
            ->paginate(10); // Menggunakan paginate() bukan get()
        
        // Dapatkan statistik perhitungan per pengguna
        $userCalculationStats = DB::table('calculations')
            ->select('user_id', DB::raw('COUNT(*) as calculation_count'), DB::raw('MAX(calculated_at) as last_calculation'))
            ->groupBy('user_id')
            ->get()
            ->map(function($stat) {
                $user = User::find($stat->user_id);
                $stat->user = $user;
                $stat->last_calculation = $stat->last_calculation ? Carbon::parse($stat->last_calculation) : null;
                return $stat;
            });
        
        // Dapatkan alternatif terpopuler
        $popularAlternatives = [];
        $alternativeCounts = [];
        
        // Ambil semua perhitungan tanpa paginasi untuk menghitung statistik
        $allCalculations = Calculation::all();
        
        foreach ($allCalculations as $calculation) {
            if (empty($calculation->results)) continue;
            
            $results = is_string($calculation->results) ? json_decode($calculation->results, true) : [];
            $finalValues = $results['final_values'] ?? [];
            
            if (empty($finalValues)) continue;
            
            usort($finalValues, function($a, $b) {
                return $b['final_value'] <=> $a['final_value'];
            });
            
            $topResult = $finalValues[0] ?? null;
            
            if ($topResult && isset($topResult['code'])) {
                $code = $topResult['code'];
                
                if (!isset($alternativeCounts[$code])) {
                    $alternativeCounts[$code] = [
                        'code' => $code,
                        'name' => $topResult['name'] ?? '',
                        'count' => 0
                    ];
                }
                
                $alternativeCounts[$code]['count']++;
            }
        }
        
        // Urutkan berdasarkan jumlah terbanyak
        usort($alternativeCounts, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        
        // Ambil 5 teratas
        $popularAlternatives = array_slice($alternativeCounts, 0, 5);
        
        return view('admin.calculations.index', compact('calculations', 'userCalculationStats', 'popularAlternatives'));
    }

    /**
     * Menampilkan detail perhitungan
     */
    public function show(Calculation $calculation)
    {
        $results = is_string($calculation->results) ? json_decode($calculation->results, true) : [];
        
        if (!$results) {
            return redirect()->route('admin.calculations.index')
                ->with('error', 'Format hasil perhitungan tidak valid.');
        }
        
        // Ambil alternatif ID yang digunakan dalam perhitungan
        $alternativeIds = [];
        if (isset($results['decision_matrix']) && is_array($results['decision_matrix'])) {
            $alternativeIds = array_keys($results['decision_matrix']);
        }
        
        // Ambil data alternatif dari database
        $alternatives = \App\Models\Alternative::whereIn('id', $alternativeIds)->get();
        
        // Ambil semua kriteria berdasarkan urutan rank
        $criteria = \App\Models\Criteria::orderBy('rank')->get();
        
        return view('admin.calculations.show', compact('calculation', 'alternatives', 'criteria'));
    }

    /**
     * Menghapus perhitungan
     */
    public function destroy(Calculation $calculation)
    {
        try {
            $calculation->delete();
            
            return redirect()->route('admin.calculations.index')
                ->with('success', 'Perhitungan berhasil dihapus.');
                
        } catch (\Exception $e) {
            \Log::error('Error saat menghapus perhitungan: ' . $e->getMessage());
            
            return redirect()->route('admin.calculations.index')
                ->with('error', 'Terjadi kesalahan saat menghapus perhitungan.');
        }
    }
}