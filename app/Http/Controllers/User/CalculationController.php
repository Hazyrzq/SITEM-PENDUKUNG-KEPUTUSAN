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
use Illuminate\Support\Facades\Log;

class CalculationController extends Controller
{
    protected array $middleware = ['auth'];

    /**
     * Menampilkan daftar perhitungan yang dilakukan user
     */
    public function index()
    {
        $calculations = Calculation::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.calculations.index', compact('calculations'));
    }

    /**
     * Menampilkan form untuk membuat perhitungan baru
     */
    public function create()
    {
        $userId = Auth::id();

        // Periksa apakah bobot kriteria sudah dihitung
        $needsWeightCalculation = Criteria::whereNull('weight')->count() > 0;

        // Periksa apakah user sudah memberi nilai untuk minimal 2 alternatif
        $alternativesWithValues = Alternative::withValuesForUser($userId)->count();
        $hasValues = $alternativesWithValues >= 2;

        return view('user.calculations.create', compact('needsWeightCalculation', 'hasValues'));
    }

    /**
     * Menyimpan perhitungan baru dan melakukan perhitungan MAIRCA
     * dengan urutan langkah yang jelas dan sesuai dengan jurnal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $userId = Auth::id();

        // Periksa apakah bobot kriteria sudah dihitung
        if (Criteria::whereNull('weight')->count() > 0) {
            return redirect()->route('user.calculations.create')
                ->with('error', 'Admin belum menghitung bobot kriteria. Perhitungan tidak dapat dilakukan.');
        }

        // Periksa apakah user sudah memberi nilai untuk minimal 2 alternatif
        $alternatives = Alternative::withValuesForUser($userId)->get();
        if ($alternatives->count() < 2) {
            return redirect()->route('user.calculations.create')
                ->with('error', 'Anda harus memberikan nilai untuk minimal 2 alternatif terlebih dahulu.');
        }

        try {
            DB::beginTransaction();
            
            // Buat perhitungan baru
            $calculation = new Calculation();
            $calculation->name = $validated['name'];
            $calculation->user_id = $userId;
            $calculation->calculated_at = now();
            
            // Ambil semua kriteria
            $criteria = Criteria::orderBy('rank')->get();
            
            // ========= LANGKAH 1: MEMBUAT MATRIKS KEPUTUSAN =========
            $decisionMatrix = [];
            
            foreach ($alternatives as $alternative) {
                $decisionMatrix[$alternative->id] = [];
                
                foreach ($criteria as $criterion) {
                    $value = AlternativeValue::where('alternative_id', $alternative->id)
                        ->where('criteria_id', $criterion->id)
                        ->where('user_id', $userId)
                        ->value('value');
                    
                    $decisionMatrix[$alternative->id][$criterion->id] = $value ?? 0;
                }
            }
            
            // Cari nilai min dan max untuk setiap kriteria (untuk normalisasi nanti)
            $minValues = [];
            $maxValues = [];
            
            foreach ($criteria as $criterion) {
                $criteriaValues = [];
                
                foreach ($alternatives as $alternative) {
                    $criteriaValues[] = $decisionMatrix[$alternative->id][$criterion->id];
                }
                
                $minValues[$criterion->id] = min($criteriaValues);
                $maxValues[$criterion->id] = max($criteriaValues);
            }
            
            // ========= LANGKAH 2: MENENTUKAN NILAI PREFERENSI ALTERNATIF =========
            $preferenceValue = 1 / count($alternatives);
            
            // ========= LANGKAH 3: MENGHITUNG NILAI MATRIKS EVALUASI TEORITIS =========
            $theoreticalMatrix = [];
            
            foreach ($alternatives as $alternative) {
                $theoreticalMatrix[$alternative->id] = [];
                
                foreach ($criteria as $criterion) {
                    $theoreticalMatrix[$alternative->id][$criterion->id] = $preferenceValue * $criterion->weight;
                }
            }
            
            // ========= LANGKAH 4: MENGHITUNG NILAI MATRIKS EVALUASI REALISTIS =========
            // Normalisasi matriks keputusan (sesuai jurnal)
            $normalizedMatrix = [];
            
            foreach ($alternatives as $alternative) {
                $normalizedMatrix[$alternative->id] = [];
                
                foreach ($criteria as $criterion) {
                    $value = $decisionMatrix[$alternative->id][$criterion->id];
                    $min = $minValues[$criterion->id];
                    $max = $maxValues[$criterion->id];
                    
                    // Hindari division by zero
                    if ($max == $min) {
                        $normalizedMatrix[$alternative->id][$criterion->id] = 1;
                    } else {
                        if ($criterion->type == 'benefit') {
                            $normalizedMatrix[$alternative->id][$criterion->id] = ($value - $min) / ($max - $min);
                        } else { // cost
                            $normalizedMatrix[$alternative->id][$criterion->id] = ($max - $value) / ($max - $min);
                        }
                    }
                }
            }
            
            // Matriks evaluasi realistis (pembobotan)
            $weightedMatrix = [];
            
            foreach ($alternatives as $alternative) {
                $weightedMatrix[$alternative->id] = [];
                
                foreach ($criteria as $criterion) {
                    $normalizedValue = $normalizedMatrix[$alternative->id][$criterion->id];
                    $weightedMatrix[$alternative->id][$criterion->id] = $normalizedValue * $criterion->weight;
                }
            }
            
            // ========= LANGKAH 5: MENGHITUNG MATRIKS TOTAL GAP =========
            $gapMatrix = [];
            
            foreach ($alternatives as $alternative) {
                $gapMatrix[$alternative->id] = [];
                
                foreach ($criteria as $criterion) {
                    $theoretical = $theoreticalMatrix[$alternative->id][$criterion->id];
                    $realistic = $weightedMatrix[$alternative->id][$criterion->id];
                    
                    // PERBAIKAN: Sesuai dengan jurnal MAIRCA, nilai gap yang harus diambil adalah
                    // nilai realistic - nilai theoretical atau theoretical - (theoretical - realistic)
                    // atau mengubah formula menjadi Qi = ∑(tpij - |tpij-trij|)
                    // namun untuk menyederhanakan, kita dapat mengambil nilai absolut gap
                    // dan mengubah pengurutan akhir dari nilai gap
                    $gapMatrix[$alternative->id][$criterion->id] = abs($theoretical - $realistic);
                }
            }
            
            // ========= LANGKAH 6: MENGHITUNG NILAI AKHIR FUNGSI =========
            $finalValues = [];
            
            foreach ($alternatives as $alternative) {
                $gapSum = 0;
                
                foreach ($criteria as $criterion) {
                    $gapSum += $gapMatrix[$alternative->id][$criterion->id];
                }
                
                $finalValues[] = [
                    'alternative_id' => $alternative->id,
                    'name' => $alternative->name,
                    'code' => $alternative->code,
                    'description' => $alternative->description,
                    'gap_sum' => $gapSum,
                    'final_value' => $gapSum, // Nilai akhir adalah total gap
                    'rank' => 0 // Akan diisi nanti
                ];
            }
            
            // PERBAIKAN: Dalam jurnal MAIRCA, alternatif terbaik adalah yang memiliki nilai Gap TERKECIL
            // karena gap kecil berarti alternatif lebih mendekati nilai ideal
            usort($finalValues, function($a, $b) {
                return $a['final_value'] <=> $b['final_value'];
            });
            
            // Tetapkan peringkat
            $rank = 1;
            foreach ($finalValues as &$value) {
                $value['rank'] = $rank++;
            }
            
            // Simpan hasil semua tahapan dengan format yang benar
            $calculation->results = json_encode([
                'decision_matrix' => $decisionMatrix,
                'min_values' => $minValues,
                'max_values' => $maxValues,
                'preference_value' => $preferenceValue,
                'theoretical_matrix' => $theoreticalMatrix,
                'normalized_matrix' => $normalizedMatrix,
                'weighted_matrix' => $weightedMatrix,
                'gap_matrix' => $gapMatrix,
                'final_values' => $finalValues
            ], JSON_PRETTY_PRINT);
            
            $calculation->save();
            
            DB::commit();

            return redirect()->route('user.calculations.show', $calculation)
                ->with('success', 'Perhitungan MAIRCA berhasil dilakukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat melakukan perhitungan: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('user.calculations.create')
                ->with('error', 'Terjadi kesalahan saat melakukan perhitungan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail perhitungan dengan langkah-langkah MAIRCA
     */
    public function show(Calculation $calculation)
    {
        // Pastikan user hanya bisa melihat perhitungannya sendiri
        if ($calculation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        try {
            // Ambil alternatif yang digunakan pada perhitungan
            $results = json_decode($calculation->results, true);
            
            if (!$results) {
                throw new \Exception("Format hasil perhitungan tidak valid.");
            }

            // Ambil alternatif ID yang digunakan dalam perhitungan
            $alternativeIds = [];
            if (isset($results['decision_matrix']) && is_array($results['decision_matrix'])) {
                $alternativeIds = array_keys($results['decision_matrix']);
            }
            
            // Ambil data alternatif dari database
            $alternatives = Alternative::whereIn('id', $alternativeIds)->get();
            
            // Ambil semua kriteria berdasarkan urutan rank
            $criteria = Criteria::orderBy('rank')->get();
            
            return view('user.calculations.show', compact('calculation', 'alternatives', 'criteria'));
            
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan hasil perhitungan: ' . $e->getMessage());
            
            return redirect()->route('user.calculations.index')
                ->with('error', 'Terjadi kesalahan saat menampilkan hasil perhitungan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus perhitungan
     */
    public function destroy(Calculation $calculation)
    {
        // Pastikan user hanya bisa menghapus perhitungannya sendiri
        if ($calculation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $calculation->delete();

            return redirect()->route('user.calculations.index')
                ->with('success', 'Perhitungan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error saat menghapus perhitungan: ' . $e->getMessage());

            return redirect()->route('user.calculations.index')
                ->with('error', 'Terjadi kesalahan saat menghapus perhitungan.');
        }
    }
}