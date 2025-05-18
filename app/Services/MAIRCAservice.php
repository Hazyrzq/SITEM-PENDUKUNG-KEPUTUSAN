<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Calculation;
use App\Models\Criteria;
use Illuminate\Support\Facades\DB;

class MAIRCAService
{
    /**
     * Hitung untuk user tertentu
     */
    public function calculateForUser($name, $userId)
    {
        // Ambil semua kriteria
        $criteria = Criteria::orderBy('rank')->get();
        
        // Ambil alternatif yang memiliki nilai dari user tertentu
        $alternativeIds = AlternativeValue::where('user_id', $userId)
            ->select('alternative_id')
            ->distinct()
            ->pluck('alternative_id')
            ->toArray();
            
        $alternatives = Alternative::whereIn('id', $alternativeIds)->get();
        
        // Periksa minimal ada 2 alternatif untuk perbandingan
        if (count($alternativeIds) < 2) {
            throw new \Exception("Minimal diperlukan 2 alternatif dengan nilai untuk melakukan perhitungan.");
        }
        
        // Buat perhitungan baru
        $calculation = Calculation::create([
            'name' => $name,
            'user_id' => $userId,
            'calculated_at' => now(),
        ]);
        
        // Langkah 1: Membentuk matriks keputusan
        $decisionMatrix = [];
        foreach ($alternatives as $alternative) {
            foreach ($criteria as $criterion) {
                $value = AlternativeValue::where('alternative_id', $alternative->id)
                    ->where('criteria_id', $criterion->id)
                    ->where('user_id', $userId)
                    ->value('value');
                
                if ($value === null) {
                    // Jika tidak ada nilai, gunakan nilai default 0
                    $decisionMatrix[$alternative->id][$criterion->id] = 0;
                } else {
                    $decisionMatrix[$alternative->id][$criterion->id] = $value;
                }
            }
        }
        
        // Langkah 2: Normalisasi matriks keputusan
        $normalizedMatrix = $this->normalizeMatrix($decisionMatrix, $criteria, $userId);
        
        // Langkah 3: Perhitungan matriks pembobotan
        $weightedMatrix = $this->calculateWeightedMatrix($normalizedMatrix, $criteria);
        
        // Langkah 4: Menghitung matriks jarak alternatif dari solusi ideal
        $distanceMatrix = $this->calculateDistanceMatrix($weightedMatrix, $criteria);
        
        // Langkah 5: Menghitung nilai akhir alternatif
        $finalValues = $this->calculateFinalValues($distanceMatrix, $alternatives, $criteria);
        
        // Simpan hasil perhitungan
        $this->saveCalculationResults($calculation, $finalValues);
        
        return $finalValues;
    }
    
    /**
     * Normalisasi matriks keputusan
     */
    private function normalizeMatrix($matrix, $criteria, $userId)
    {
        $normalizedMatrix = [];
        
        foreach ($matrix as $alternativeId => $values) {
            foreach ($criteria as $criterion) {
                $criterionId = $criterion->id;
                $value = $values[$criterionId];
                
                // Cari nilai min dan max untuk kriteria ini dari alternatif dengan nilai
                $minMaxValues = DB::table('alternative_values')
                    ->where('criteria_id', $criterionId)
                    ->where('user_id', $userId)
                    ->selectRaw('MIN(value) as min_value, MAX(value) as max_value')
                    ->first();
                
                $min = $minMaxValues->min_value;
                $max = $minMaxValues->max_value;
                
                // Normalisasi berdasarkan tipe kriteria (benefit atau cost)
                if ($criterion->type === 'benefit') {
                    // Untuk kriteria benefit, nilai yang lebih tinggi lebih baik
                    if ($max == $min) {
                        $normalizedMatrix[$alternativeId][$criterionId] = 1;
                    } else {
                        $normalizedMatrix[$alternativeId][$criterionId] = ($value - $min) / ($max - $min);
                    }
                } else {
                    // Untuk kriteria cost, nilai yang lebih rendah lebih baik
                    if ($max == $min) {
                        $normalizedMatrix[$alternativeId][$criterionId] = 1;
                    } else {
                        $normalizedMatrix[$alternativeId][$criterionId] = ($max - $value) / ($max - $min);
                    }
                }
            }
        }
        
        return $normalizedMatrix;
    }
    
    /**
     * Perhitungan matriks pembobotan
     */
    private function calculateWeightedMatrix($normalizedMatrix, $criteria)
    {
        $weightedMatrix = [];
        
        foreach ($normalizedMatrix as $alternativeId => $values) {
            foreach ($criteria as $criterion) {
                $criterionId = $criterion->id;
                $normalizedValue = $values[$criterionId];
                $weight = $criterion->weight;
                
                $weightedMatrix[$alternativeId][$criterionId] = $normalizedValue * $weight;
            }
        }
        
        return $weightedMatrix;
    }
    
    /**
     * Perhitungan matriks jarak alternatif dari solusi ideal
     */
    private function calculateDistanceMatrix($weightedMatrix, $criteria)
    {
        $distanceMatrix = [];
        
        foreach ($weightedMatrix as $alternativeId => $values) {
            foreach ($criteria as $criterion) {
                $criterionId = $criterion->id;
                $weightedValue = $values[$criterionId];
                
                // Jarak dari solusi ideal (1 untuk normalized)
                $distanceMatrix[$alternativeId][$criterionId] = 1 - $weightedValue;
            }
        }
        
        return $distanceMatrix;
    }
    
    /**
     * Menghitung nilai akhir setiap alternatif
     */
    private function calculateFinalValues($distanceMatrix, $alternatives, $criteria)
    {
        $finalValues = [];
        
        foreach ($alternatives as $alternative) {
            $alternativeId = $alternative->id;
            $sum = 0;
            
            foreach ($criteria as $criterion) {
                $criterionId = $criterion->id;
                $sum += $distanceMatrix[$alternativeId][$criterionId];
            }
            
            $finalValues[$alternativeId] = [
                'alternative_id' => $alternativeId,
                'name' => $alternative->name,
                'code' => $alternative->code,
                'description' => $alternative->description,
                'distance_sum' => $sum,
                'final_value' => 1 - ($sum / count($criteria)), // Makin tinggi nilainya makin baik
                'rank' => 0 // Akan diisi nanti
            ];
        }
        
        // Urutkan berdasarkan nilai akhir dari tinggi ke rendah
        uasort($finalValues, function($a, $b) {
            return $b['final_value'] <=> $a['final_value'];
        });
        
        // Tetapkan peringkat
        $rank = 1;
        foreach ($finalValues as &$value) {
            $value['rank'] = $rank++;
        }
        
        return $finalValues;
    }
    
    /**
     * Simpan hasil perhitungan
     */
    private function saveCalculationResults($calculation, $finalValues)
    {
        // Simpan hasil perhitungan sebagai JSON di kolom results
        $calculation->results = $finalValues;
        $calculation->save();
    }
}