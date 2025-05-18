<?php

namespace App\Services;

use App\Models\Criteria;

class ROCService
{
    /**
     * Menghitung bobot kriteria menggunakan metode ROC
     */
    public function calculateWeights()
    {
        // Ambil semua kriteria dan urutkan berdasarkan ranking
        $criteria = Criteria::orderBy('rank')->get();
        $n = $criteria->count();
        
        if ($n == 0) {
            return [];
        }
        
        // Hitung bobot untuk setiap kriteria menggunakan metode ROC
        $weights = [];
        $totalWeight = 0;
        
        // Rumus ROC: W_k = (1/n) * Sum(i=k to n)(1/i)
        foreach ($criteria as $index => $criterion) {
            $rank = $index + 1; // Rank dimulai dari 1
            $sum = 0;
            
            for ($i = $rank; $i <= $n; $i++) {
                $sum += 1 / $i;
            }
            
            $weight = (1 / $n) * $sum;
            $weights[$criterion->id] = $weight;
            $totalWeight += $weight;
        }
        
        // Normalisasi bobot agar total = 1
        foreach ($weights as $id => $weight) {
            $weights[$id] = $weight / $totalWeight;
        }
        
        // Simpan bobot ke database
        foreach ($criteria as $criterion) {
            $criterion->weight = $weights[$criterion->id];
            $criterion->save();
        }
        
        return $criteria;
    }
    
    /**
     * Reset semua bobot kriteria menjadi null
     */
    public function resetWeights()
    {
        Criteria::query()->update(['weight' => null]);
        return Criteria::orderBy('rank')->get();
    }
    
    /**
     * Update ranking kriteria
     */
    public function updateRanks(array $ranks)
    {
        foreach ($ranks as $id => $rank) {
            $criterion = Criteria::find($id);
            if ($criterion) {
                $criterion->rank = $rank;
                $criterion->save();
            }
        }
        
        return Criteria::orderBy('rank')->get();
    }
}