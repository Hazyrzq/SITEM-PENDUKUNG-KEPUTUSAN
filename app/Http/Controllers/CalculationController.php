<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Calculation;
use App\Models\Criteria;
use App\Services\MAIRCAService;
use App\Services\ROCService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalculationController extends Controller
{
    protected $rocService;
    protected $maircaService;
    
    public function __construct(ROCService $rocService, MAIRCAService $maircaService)
    {
        $this->rocService = $rocService;
        $this->maircaService = $maircaService;
    }
    
    /**
     * Menampilkan daftar perhitungan yang telah dilakukan
     */
    public function index()
    {
        $calculations = Calculation::orderBy('created_at', 'desc')->get();
        return view('calculations.index', compact('calculations'));
    }
    
    /**
     * Menampilkan form untuk membuat perhitungan baru
     */
    public function create()
    {
        // Cek apakah bobot sudah dihitung
        $criteria = Criteria::whereNull('weight')->count();
        $needsWeightCalculation = $criteria > 0;
        
        return view('calculations.create', compact('needsWeightCalculation'));
    }
    
    /**
     * Melakukan perhitungan baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Jika bobot belum dihitung, hitung terlebih dahulu
        $criteria = Criteria::whereNull('weight')->count();
        if ($criteria > 0) {
            $this->rocService->calculateWeights();
        }
        
        // Lakukan perhitungan MAIRCA
        $results = $this->maircaService->calculate($request->name);
        
        // Ambil objek calculation yang baru dibuat
        $calculation = Calculation::latest()->first();
        
        return redirect()->route('calculations.show', $calculation)
            ->with('success', 'Perhitungan berhasil dilakukan.');
    }
    
    /**
     * Menampilkan hasil perhitungan
     */
    public function show(Calculation $calculation)
    {
        $criteria = Criteria::orderBy('rank')->get();
        $alternatives = Alternative::all();
        
        return view('calculations.show', compact('calculation', 'criteria', 'alternatives'));
    }
    
    /**
     * Menghapus hasil perhitungan
     */
    public function destroy(Calculation $calculation)
    {
        $calculation->delete();
        
        return redirect()->route('calculations.index')
            ->with('success', 'Hasil perhitungan berhasil dihapus.');
    }
}