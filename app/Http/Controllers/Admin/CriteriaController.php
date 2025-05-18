<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Services\ROCService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CriteriaController extends Controller
{
    protected $rocService;
    
    public function __construct(ROCService $rocService)
    {
        $this->rocService = $rocService;
        // Pastikan hanya admin yang bisa mengakses controller ini
        
    }
    protected array $middleware = ['admin'];

    /**
     * Menampilkan daftar semua kriteria
     */
    public function index()
    {
        $criteria = Criteria::orderBy('rank')->get();
        return view('admin.criteria.index', compact('criteria'));
    }
    
    /**
     * Menampilkan form untuk membuat kriteria baru
     */
    public function create()
    {
        return view('admin.criteria.create');
    }
    
    /**
     * Menyimpan kriteria baru ke database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:criteria',
            'rank' => 'required|integer|min:1',
            'type' => 'required|in:benefit,cost',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Criteria::create([
            'name' => $request->name,
            'code' => $request->code,
            'rank' => $request->rank,
            'type' => $request->type,
        ]);
        
        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan form untuk mengedit kriteria
     */
    public function edit(Criteria $criterion)
    {
        return view('admin.criteria.edit', compact('criterion'));
    }
    
    /**
     * Mengupdate kriteria di database
     */
    public function update(Request $request, Criteria $criterion)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:criteria,code,' . $criterion->id,
            'rank' => 'required|integer|min:1',
            'type' => 'required|in:benefit,cost',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $criterion->update([
            'name' => $request->name,
            'code' => $request->code,
            'rank' => $request->rank,
            'type' => $request->type,
        ]);
        
        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }
    
    /**
     * Menghapus kriteria dari database
     */
    public function destroy(Criteria $criterion)
    {
        // Hapus semua nilai alternatif untuk kriteria ini
        $criterion->alternativeValues()->delete();
        
        $criterion->delete();
        
        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }
    
    /**
     * Menghitung bobot kriteria menggunakan metode ROC
     */
    public function calculateWeights()
    {
        $criteria = $this->rocService->calculateWeights();
        
        return redirect()->route('admin.criteria.index')
            ->with('success', 'Bobot kriteria berhasil dihitung dengan metode ROC.');
    }
}