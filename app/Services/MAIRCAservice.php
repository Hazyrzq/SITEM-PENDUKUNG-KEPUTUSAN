<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Calculation;
use App\Models\Criteria;
use Illuminate\Support\Collection;

class MAIRCAService
{
    /**
     * Menghitung peringkat alternatif menggunakan metode MAIRCA
     *
     * @param string $calculationName Nama perhitungan
     * @return array Hasil perhitungan MAIRCA
     */
    public function calculate(string $calculationName): array
    {
        // 1. Membuat matriks keputusan
        $matrix = $this->createDecisionMatrix();
        
        // 2. Menentukan nilai preferensi alternatif
        $alternatives = Alternative::all();
        $alternativeCount = $alternatives->count();
        $preferenceValue = 1 / $alternativeCount;
        
        // 3. Mendapatkan bobot kriteria (dari ROC)
        $criteria = Criteria::orderBy('rank')->get();
        $weights = $criteria->pluck('weight', 'id')->toArray();
        
        // 4. Menghitung nilai matriks evaluasi teoritis
        $theoreticalMatrix = $this->calculateTheoreticalMatrix($preferenceValue, $weights);
        
        // 5. Menghitung nilai matriks evaluasi realistis
        $realisticMatrix = $this->calculateRealisticMatrix($matrix, $theoreticalMatrix, $criteria);
        
        // 6. Menghitung matriks total gap
        $gapMatrix = $this->calculateGapMatrix($theoreticalMatrix, $realisticMatrix);
        
        // 7. Menghitung nilai akhir fungsi
        $finalValues = $this->calculateFinalValues($gapMatrix);
        
        // Simpan hasil ke database
        $calculation = Calculation::create([
            'name' => $calculationName,
            'calculated_at' => now(),
            'results' => [
                'decision_matrix' => $matrix,
                'preference_value' => $preferenceValue,
                'theoretical_matrix' => $theoreticalMatrix,
                'realistic_matrix' => $realisticMatrix,
                'gap_matrix' => $gapMatrix,
                'final_values' => $finalValues,
            ],
        ]);
        
        return $calculation->results;
    }
    
    /**
     * Membuat matriks keputusan berdasarkan data alternatif dan kriteria
     */
    private function createDecisionMatrix(): array
    {
        $alternatives = Alternative::with('values.criteria')->get();
        $criteria = Criteria::orderBy('rank')->get();
        
        $matrix = [];
        
        foreach ($alternatives as $alternative) {
            $row = [];
            
            foreach ($criteria as $criterion) {
                $value = $alternative->values->where('criteria_id', $criterion->id)->first();
                $row[$criterion->id] = $value ? $value->value : 0;
            }
            
            $matrix[$alternative->id] = $row;
        }
        
        return $matrix;
    }
    
    /**
     * Menghitung matriks evaluasi teoritis
     */
    private function calculateTheoreticalMatrix(float $preferenceValue, array $weights): array
    {
        $theoreticalMatrix = [];
        
        foreach (Alternative::all() as $alternative) {
            $row = [];
            
            foreach ($weights as $criteriaId => $weight) {
                $row[$criteriaId] = $preferenceValue * $weight;
            }
            
            $theoreticalMatrix[$alternative->id] = $row;
        }
        
        return $theoreticalMatrix;
    }
    
    /**
     * Menghitung matriks evaluasi realistis
     */
    private function calculateRealisticMatrix(array $decisionMatrix, array $theoreticalMatrix, Collection $criteria): array
    {
        $realisticMatrix = [];
        
        foreach ($decisionMatrix as $alternativeId => $alternativeValues) {
            $row = [];
            
            foreach ($alternativeValues as $criteriaId => $value) {
                $criterion = $criteria->where('id', $criteriaId)->first();
                
                // Mencari nilai minimum dan maksimum untuk kriteria ini
                $criteriaValues = array_column($decisionMatrix, $criteriaId);
                $min = min($criteriaValues);
                $max = max($criteriaValues);
                
                // Normalisasi berdasarkan tipe kriteria (benefit atau cost)
                if ($criterion->type === 'benefit') {
                    // Untuk kriteria benefit
                    $normalizedValue = ($max - $min) != 0 
                        ? ($value - $min) / ($max - $min)
                        : 0;
                } else {
                    // Untuk kriteria cost
                    $normalizedValue = ($max - $min) != 0 
                        ? ($max - $value) / ($max - $min)
                        : 0;
                }
                
                $theoreticalValue = $theoreticalMatrix[$alternativeId][$criteriaId];
                $row[$criteriaId] = $theoreticalValue * $normalizedValue;
            }
            
            $realisticMatrix[$alternativeId] = $row;
        }
        
        return $realisticMatrix;
    }
    
    /**
     * Menghitung matriks total gap
     */
    private function calculateGapMatrix(array $theoreticalMatrix, array $realisticMatrix): array
    {
        $gapMatrix = [];
        
        foreach ($theoreticalMatrix as $alternativeId => $theoreticalValues) {
            $row = [];
            
            foreach ($theoreticalValues as $criteriaId => $theoreticalValue) {
                $realisticValue = $realisticMatrix[$alternativeId][$criteriaId];
                $row[$criteriaId] = $theoreticalValue - $realisticValue;
            }
            
            $gapMatrix[$alternativeId] = $row;
        }
        
        return $gapMatrix;
    }
    
    /**
     * Menghitung nilai akhir fungsi
     */
    private function calculateFinalValues(array $gapMatrix): array
    {
        $finalValues = [];
        
        foreach ($gapMatrix as $alternativeId => $gapValues) {
            $finalValues[$alternativeId] = array_sum($gapValues);
        }
        
        // Urutkan alternatif berdasarkan nilai (nilai terendah = peringkat tertinggi)
        asort($finalValues);
        
        // Tambahkan data nama alternatif
        $alternativesData = [];
        $rank = 1;
        
        foreach ($finalValues as $alternativeId => $value) {
            $alternative = Alternative::find($alternativeId);
            $alternativesData[] = [
                'rank' => $rank++,
                'id' => $alternativeId,
                'code' => $alternative->code,
                'name' => $alternative->name,
                'value' => $value
            ];
        }
        
        return $alternativesData;
    }
}