<?php

namespace App\Services;

use App\Models\Criteria;
use Illuminate\Support\Collection;

class ROCService
{
    /**
     * Menghitung bobot kriteria menggunakan metode Rank Order Centroid (ROC)
     *
     * @return Collection
     */
    public function calculateWeights(): Collection
    {
        // Ambil semua kriteria dan urutkan berdasarkan ranking
        $criteria = Criteria::orderBy('rank')->get();
        $totalCriteria = $criteria->count();
        
        // Hitung bobot ROC untuk setiap kriteria
        foreach ($criteria as $key => $criterion) {
            $weight = 0;
            $rank = $criterion->rank;
            
            // Implementasi rumus ROC: W_j = (1/m) * Σ(1/i) dari i=j sampai i=m
            // dimana m adalah jumlah kriteria dan j adalah peringkat kriteria
            for ($i = $rank; $i <= $totalCriteria; $i++) {
                $weight += (1 / $i);
            }
            
            $weight = $weight / $totalCriteria;
            
            // Update bobot di database
            $criterion->weight = $weight;
            $criterion->save();
        }
        
        return $criteria;
    }
}