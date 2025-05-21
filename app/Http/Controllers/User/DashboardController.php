<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Calculation;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard pengguna dengan statistik dan perhitungan terbaru
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Total alternatif yang tersedia
        $totalAlternatives = Alternative::count();
        
        // PERBAIKAN: Jumlah alternatif yang telah dinilai (unik)
        $alternativeCount = AlternativeValue::where('user_id', $userId)
            ->select('alternative_id')
            ->distinct()
            ->count();
        
        // Jumlah perhitungan yang telah dilakukan
        $calculationCount = Calculation::where('user_id', $userId)->count();
        
        // Jumlah kriteria dengan bobot
        $criteriaWithWeight = Criteria::whereNotNull('weight')->count();
        $totalCriteria = Criteria::count();
        
        // Perhitungan terbaru
        $recentCalculations = Calculation::where('user_id', $userId)
            ->orderBy('calculated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Cek kriteria tanpa bobot
        $criteriaWithoutWeight = ($totalCriteria - $criteriaWithWeight) > 0;
        
        return view('user.dashboard', compact(
            'alternativeCount', 
            'totalAlternatives', 
            'calculationCount', 
            'criteriaWithWeight',
            'totalCriteria',
            'recentCalculations',
            'criteriaWithoutWeight'
        ));
    }

    /**
     * Menampilkan perhitungan terbaru di homepage
     */
    public function home()
    {
        // Untuk homepage publik
        return view('home');
    }
    
    /**
     * Menampilkan halaman bantuan
     */
    public function help()
    {
        return view('user.help');
    }
    
    /**
     * Menampilkan halaman tentang aplikasi
     */
    public function about()
    {
        return view('user.about');
    }
    
    /**
     * Menampilkan statistik sistem untuk laporan
     */
    public function stats()
    {
        $userId = Auth::id();
        
        // Jumlah alternatif yang telah dinilai (unik)
        $alternativeCount = AlternativeValue::where('user_id', $userId)
            ->select('alternative_id')
            ->distinct()
            ->count();
        
        // Total alternatif yang tersedia
        $totalAlternatives = Alternative::count();
        
        // Persentase alternatif yang telah dinilai
        $alternativePercentage = $totalAlternatives > 0 
            ? round(($alternativeCount / $totalAlternatives) * 100, 1) 
            : 0;
        
        // Jumlah kriteria yang digunakan
        $criteriaCount = Criteria::count();
        
        // Jumlah kriteria dengan bobot
        $criteriaWithWeight = Criteria::whereNotNull('weight')->count();
        
        // Persentase kriteria dengan bobot
        $criteriaPercentage = $criteriaCount > 0 
            ? round(($criteriaWithWeight / $criteriaCount) * 100, 1) 
            : 0;
        
        // Jumlah perhitungan yang telah dilakukan
        $calculationCount = Calculation::where('user_id', $userId)->count();
        
        // Perhitungan terbaru
        $recentCalculations = Calculation::where('user_id', $userId)
            ->orderBy('calculated_at', 'desc')
            ->limit(10)
            ->get();
        
        // Alternatif terbaik dari semua perhitungan
        $topAlternatives = [];
        
        foreach ($recentCalculations as $calculation) {
            if (!empty($calculation->results)) {
                $results = is_string($calculation->results) ? json_decode($calculation->results, true) : [];
                $finalValues = isset($results['final_values']) ? $results['final_values'] : [];
                
                if (!empty($finalValues)) {
                    // Urutkan berdasarkan nilai final_value
                    usort($finalValues, function($a, $b) {
                        return $b['final_value'] <=> $a['final_value'];
                    });
                    
                    $topResult = $finalValues[0];
                    
                    if (isset($topResult['code'])) {
                        $code = $topResult['code'];
                        
                        if (!isset($topAlternatives[$code])) {
                            $topAlternatives[$code] = [
                                'code' => $code,
                                'name' => $topResult['name'] ?? '',
                                'count' => 0
                            ];
                        }
                        
                        $topAlternatives[$code]['count']++;
                    }
                }
            }
        }
        
        // Urutkan berdasarkan jumlah terbanyak
        usort($topAlternatives, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        
        // Ambil 5 teratas
        $topAlternatives = array_slice($topAlternatives, 0, 5);
        
        return view('user.stats', compact(
            'alternativeCount',
            'totalAlternatives',
            'alternativePercentage',
            'criteriaCount',
            'criteriaWithWeight',
            'criteriaPercentage',
            'calculationCount',
            'recentCalculations',
            'topAlternatives'
        ));
    }

    /**
     * Menampilkan halaman profil pengguna
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Memperbarui profil pengguna
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        // Jika ada password, validasi dan update
        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            // Verifikasi password saat ini
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Password saat ini tidak sesuai!');
            }
            
            // Update password
            $user->password = Hash::make($request->password);
        }
        
        // Update data lainnya
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->save();
        
        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman hasil
     */
    public function results()
    {
        // Kode untuk halaman hasil
        $userId = Auth::id();
        
        // Perhitungan terbaru
        $calculations = Calculation::where('user_id', $userId)
            ->orderBy('calculated_at', 'desc')
            ->paginate(10);
            
        return view('user.results', compact('calculations'));
    }
}