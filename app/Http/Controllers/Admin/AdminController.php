<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Calculation;
use App\Models\Criteria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Ganti method middleware() dengan properti $middleware
    protected array $middleware = ['admin'];
    
    /**
     * Menampilkan dashboard admin
     */
    public function dashboard()
    {
        $userCount = User::where('role', '!=', 'admin')->count();
        $alternativeCount = Alternative::count();
        $criteriaCount = Criteria::count();
        $calculationCount = Calculation::count();
        
        // Jumlah nilai alternatif yang diinput per user
        $userValueCounts = DB::table('alternative_values')
            ->select('user_id', DB::raw('count(*) as value_count'))
            ->groupBy('user_id')
            ->get();
            
        // Perhitungan terbaru
        $recentCalculations = Calculation::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Kriteria tanpa bobot
        $criteriaWithoutWeight = Criteria::whereNull('weight')->count();
        
        return view('admin.dashboard', compact(
            'userCount', 
            'alternativeCount', 
            'criteriaCount', 
            'calculationCount',
            'userValueCounts',
            'recentCalculations',
            'criteriaWithoutWeight'
        ));
    }
    
    /**
     * Menampilkan daftar user
     */
    public function users()
    {
        $users = User::where('role', '!=', 'admin')->get();
        
        // Tambahkan info jumlah perhitungan dan nilai alternatif untuk setiap user
        foreach ($users as $user) {
            $user->calculation_count = Calculation::where('user_id', $user->id)->count();
            $user->value_count = AlternativeValue::where('user_id', $user->id)->count();
        }
        
        return view('admin.users', compact('users'));
    }
    
    /**
     * Menampilkan detail user tertentu
     */
    public function showUser(User $user)
    {
        $calculationCount = Calculation::where('user_id', $user->id)->count();
        $valueCount = AlternativeValue::where('user_id', $user->id)->count();
        $recentCalculations = Calculation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $alternativesWithValues = Alternative::withValuesForUser($user->id)->count();
        
        return view('admin.user-detail', compact(
            'user',
            'calculationCount',
            'valueCount',
            'recentCalculations',
            'alternativesWithValues'
        ));
    }
    
    /**
     * Menghapus user
     */
    public function destroyUser(User $user)
    {
        // Pastikan user yang akan dihapus bukan admin
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Admin tidak dapat dihapus.');
        }
        
        // Hapus perhitungan user
        Calculation::where('user_id', $user->id)->delete();
        
        // Hapus nilai alternatif yang diinput user
        AlternativeValue::where('user_id', $user->id)->delete();
        
        // Hapus user
        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus beserta semua data terkait.');
    }
    
    /**
     * Menampilkan laporan perhitungan
     */
    public function reports()
    {
        $calculations = Calculation::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.reports', compact('calculations'));
    }
}