<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Criteria;
use App\Models\UserCriteriaRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AlternativeController extends Controller
{
    /**
     * Middleware untuk controller ini
     */
    protected array $middleware = ['auth'];
    
    /**
     * Menampilkan daftar alternatif yang tersedia untuk user
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Tampilkan alternatif dari admin (user_id = null) dan alternatif user sendiri
        $alternatives = Alternative::where(function($query) use ($userId) {
            $query->whereNull('user_id')
                  ->orWhere('user_id', $userId);
        })->get();
        
        foreach ($alternatives as $alternative) {
            $valueCount = AlternativeValue::where('alternative_id', $alternative->id)
                ->where('user_id', $userId)
                ->count();
                
            $alternative->has_values = $valueCount > 0;
            $alternative->is_own = $alternative->user_id === $userId;
        }
        
        return view('user.alternatives.index', compact('alternatives'));
    }
    
    /**
     * Menampilkan form untuk membuat alternatif baru oleh user
     */
    public function create()
    {
        return view('user.alternatives.create');
    }
    
    /**
     * Menyimpan alternatif baru yang dibuat oleh user
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        
        // Validasi kustom untuk memastikan kode unik per user
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('alternatives')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
            ],
            'description' => 'nullable|string',
        ], [
            'code.unique' => 'Kode alternatif sudah digunakan dalam daftar alternatif Anda.',
            'code.required' => 'Kode alternatif harus diisi.',
            'code.max' => 'Kode alternatif maksimal 10 karakter.',
            'name.required' => 'Nama alternatif harus diisi.',
            'name.max' => 'Nama alternatif maksimal 255 karakter.'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Alternative::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'user_id' => $userId,
        ]);
        
        return redirect()->route('user.alternatives.my-alternatives')
            ->with('success', 'Alternatif berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan detail alternatif
     */
    public function show(Alternative $alternative)
    {
        $userId = Auth::id();
        
        // Cek kepemilikan atau alternatif admin
        if ($alternative->user_id !== null && $alternative->user_id !== $userId) {
            return redirect()->route('user.alternatives.index')
                ->with('error', 'Anda tidak memiliki akses ke alternatif ini.');
        }
        
        // Ambil nilai alternatif yang telah diinput oleh user
        $values = AlternativeValue::where('alternative_id', $alternative->id)
            ->where('user_id', $userId)
            ->with('criteria')
            ->get();
            
        return view('user.alternatives.show', compact('alternative', 'values'));
    }
    
    /**
     * Menampilkan form untuk mengedit alternatif
     */
    public function edit(Alternative $alternative)
    {
        $userId = Auth::id();
        
        // Cek kepemilikan
        if ($alternative->user_id !== $userId) {
            return redirect()->route('user.alternatives.index')
                ->with('error', 'Anda hanya dapat mengedit alternatif yang Anda buat.');
        }
        
        return view('user.alternatives.edit', compact('alternative'));
    }
    
    /**
     * Mengupdate alternatif di database
     */
    public function update(Request $request, Alternative $alternative)
    {
        $userId = Auth::id();
        
        // Cek kepemilikan
        if ($alternative->user_id !== $userId) {
            return redirect()->route('user.alternatives.index')
                ->with('error', 'Anda hanya dapat mengedit alternatif yang Anda buat.');
        }
        
        // Validasi kustom untuk memastikan kode unik per user
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('alternatives')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })->ignore($alternative->id)
            ],
            'description' => 'nullable|string',
        ], [
            'code.unique' => 'Kode alternatif sudah digunakan dalam daftar alternatif Anda.',
            'code.required' => 'Kode alternatif harus diisi.',
            'code.max' => 'Kode alternatif maksimal 10 karakter.',
            'name.required' => 'Nama alternatif harus diisi.',
            'name.max' => 'Nama alternatif maksimal 255 karakter.'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $alternative->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);
        
        return redirect()->route('user.alternatives.my-alternatives')
            ->with('success', 'Alternatif berhasil diperbarui.');
    }
    
    /**
     * Menghapus alternatif dari database
     */
    public function destroy(Alternative $alternative)
    {
        $userId = Auth::id();
        
        // Cek kepemilikan
        if ($alternative->user_id !== $userId) {
            return redirect()->route('user.alternatives.index')
                ->with('error', 'Anda hanya dapat menghapus alternatif yang Anda buat.');
        }
        
        // Hapus semua nilai alternatif
        AlternativeValue::where('alternative_id', $alternative->id)->delete();
        
        // Hapus alternatif
        $alternative->delete();
        
        return redirect()->route('user.alternatives.my-alternatives')
            ->with('success', 'Alternatif berhasil dihapus.');
    }

    /**
     * Menampilkan form untuk mengatur nilai alternatif
     */
    public function editValues(Alternative $alternative)
    {
        $userId = Auth::id();
        
        // Cek alternatif milik user atau alternatif admin
        if ($alternative->user_id !== null && $alternative->user_id !== $userId) {
            return redirect()->route('user.alternatives.index')
                ->with('error', 'Anda tidak memiliki akses ke alternatif ini.');
        }
        
        // Verifikasi apakah tabel user_criteria_ranks tersedia dan berfungsi
        $userRankTableFunctional = false;
        $userHasCustomRanking = false;
        
        try {
            if (Schema::hasTable('user_criteria_ranks')) {
                // Coba akses tabel untuk memastikan struktur sesuai
                $userHasCustomRanking = DB::table('user_criteria_ranks')
                    ->where('user_id', $userId)
                    ->exists();
                    
                $userRankTableFunctional = true;
            }
        } catch (\Exception $e) {
            // Tabel mungkin ada tapi struktur tidak sesuai
            Log::warning('Error saat mengakses tabel user_criteria_ranks: ' . $e->getMessage());
            $userRankTableFunctional = false;
        }
        
        // Ambil kriteria berdasarkan ada tidaknya custom ranking
        if ($userRankTableFunctional && $userHasCustomRanking) {
            // Gunakan join dengan tabel rank user
            $criteria = Criteria::select('criteria.*', DB::raw('COALESCE(ucr.rank, criteria.rank) as display_rank'))
                ->leftJoin('user_criteria_ranks as ucr', function($join) use ($userId) {
                    $join->on('criteria.id', '=', 'ucr.criteria_id')
                        ->where('ucr.user_id', '=', $userId);
                })
                ->orderBy('display_rank')
                ->get();
        } else {
            // Gunakan urutan default
            $criteria = Criteria::orderBy('rank')->get();
        }
        
        // Cek apakah kriteria sudah memiliki bobot
        $needWeightCalculation = Criteria::whereNull('weight')->count() > 0;
        if ($needWeightCalculation) {
            return redirect()->route('user.alternatives.index')
                ->with('error', 'Admin belum menghitung bobot kriteria. Nilai alternatif tidak dapat diinput.');
        }
        
        // Ambil nilai alternatif yang sudah ada untuk user ini
        $values = AlternativeValue::where('alternative_id', $alternative->id)
            ->where('user_id', $userId)
            ->pluck('value', 'criteria_id')
            ->toArray();
        
        return view('user.alternatives.values', compact('alternative', 'criteria', 'values', 'userHasCustomRanking', 'userRankTableFunctional'));
    }
    
    /**
     * Menyimpan nilai alternatif dengan pendekatan delete-insert yang aman
     */
    public function storeValues(Request $request, Alternative $alternative)
    {
        try {
            $criteria = Criteria::all();
            $userId = Auth::id();
            
            // Validasi input
            $rules = [];
            foreach ($criteria as $criterion) {
                $rules["values.{$criterion->id}"] = 'required|numeric';
            }
            
            $request->validate($rules);
            
            // Gunakan transaksi database
            DB::beginTransaction();
            
            try {
                // Hapus nilai lama
                AlternativeValue::where('alternative_id', $alternative->id)
                    ->where('user_id', $userId)
                    ->delete();
                
                // Siapkan data baru
                $values = [];
                $now = now();
                
                foreach ($request->values as $criteriaId => $value) {
                    // Pastikan kriteria valid
                    if (!$criteria->contains('id', $criteriaId)) {
                        continue;
                    }
                    
                    $values[] = [
                        'alternative_id' => $alternative->id,
                        'criteria_id' => $criteriaId,
                        'user_id' => $userId,
                        'value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }
                
                // Insert data baru secara batch
                if (!empty($values)) {
                    DB::table('alternative_values')->insert($values);
                }
                
                DB::commit();
                
                return redirect()->route('user.alternatives.my-values')
                    ->with('success', 'Nilai alternatif berhasil disimpan.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan nilai alternatif: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Menampilkan daftar nilai alternatif yang sudah diisi user
     */
    public function myValues()
    {
        $userId = Auth::id();
        
        // Cari alternatif yang sudah memiliki nilai dari user ini
        $alternatives = Alternative::whereHas('values', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        
        foreach ($alternatives as $alternative) {
            $alternative->criteria_count = AlternativeValue::where('alternative_id', $alternative->id)
                ->where('user_id', $userId)
                ->count();
                
            $alternative->total_criteria = Criteria::count();
        }
        
        return view('user.alternatives.my-values', compact('alternatives'));
    }
    
    /**
     * Menampilkan daftar alternatif yang dibuat oleh user
     */
    public function myAlternatives()
    {
        $userId = Auth::id();
        
        // Ambil alternatif yang dibuat oleh user ini
        $alternatives = Alternative::where('user_id', $userId)->get();
        
        foreach ($alternatives as $alternative) {
            $valueCount = AlternativeValue::where('alternative_id', $alternative->id)
                ->where('user_id', $userId)
                ->count();
                
            $alternative->has_values = $valueCount > 0;
        }
        
        return view('user.alternatives.my-alternatives', compact('alternatives'));
    }
    
    /**
     * Menghapus nilai alternatif yang diinput user
     */
    public function destroyValues(Alternative $alternative)
    {
        $userId = Auth::id();
        
        try {
            $deleted = AlternativeValue::where('alternative_id', $alternative->id)
                ->where('user_id', $userId)
                ->delete();
                
            return redirect()->route('user.alternatives.my-values')
                ->with('success', 'Nilai alternatif berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error saat menghapus nilai alternatif: ' . $e->getMessage());
            
            return redirect()->route('user.alternatives.my-values')
                ->with('error', 'Terjadi kesalahan saat menghapus nilai alternatif.');
        }
    }
}