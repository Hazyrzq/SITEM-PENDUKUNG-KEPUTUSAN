<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlternativeValueSeeder extends Seeder
{
    public function run(): void
    {
        // Cek tabel yang tersedia
        $this->command->info("Memeriksa tabel di database...");
        
        // Data alternatif dan kriteria
        $data = [
            // Format: [kode_alternatif, kode_kriteria, nilai]
            // Qwords (A1)
            ['A1', 'C1', 135000], // Harga/Bulan
            ['A1', 'C2', 1],      // RAM (GB)
            ['A1', 'C3', 1],      // CPU (Core)
            ['A1', 'C4', 25],     // Penyimpanan (GB)
            ['A1', 'C5', 1],      // Jenis Penyimpanan (1=SSD, 2=NVMe)
            ['A1', 'C6', 0],      // Unlimited Bandwidth (1=Ya, 0=Tidak)
            ['A1', 'C7', 1],      // Multi OS (1=Ya, 0=Tidak)
            
            // Domainesia (A2)
            ['A2', 'C1', 80000],
            ['A2', 'C2', 1],
            ['A2', 'C3', 1],
            ['A2', 'C4', 20],
            ['A2', 'C5', 2],
            ['A2', 'C6', 1],
            ['A2', 'C7', 0],
            
            // Dewaweb (A3)
            ['A3', 'C1', 300000],
            ['A3', 'C2', 1],
            ['A3', 'C3', 1],
            ['A3', 'C4', 20],
            ['A3', 'C5', 2],
            ['A3', 'C6', 1],
            ['A3', 'C7', 0],
            
            // Idwebhost (A4)
            ['A4', 'C1', 129000],
            ['A4', 'C2', 1],
            ['A4', 'C3', 1],
            ['A4', 'C4', 30],
            ['A4', 'C5', 1],
            ['A4', 'C6', 1],
            ['A4', 'C7', 0],
            
            // Dhyhost (A5)
            ['A5', 'C1', 99750],
            ['A5', 'C2', 1],
            ['A5', 'C3', 1],
            ['A5', 'C4', 25],
            ['A5', 'C5', 1],
            ['A5', 'C6', 0],
            ['A5', 'C7', 0],
            
            // Rumahweb (A6)
            ['A6', 'C1', 60000],
            ['A6', 'C2', 1],
            ['A6', 'C3', 1],
            ['A6', 'C4', 20],
            ['A6', 'C5', 1],
            ['A6', 'C6', 1],
            ['A6', 'C7', 0],
            
            // NevaCloud (A7)
            ['A7', 'C1', 90000],
            ['A7', 'C2', 1],
            ['A7', 'C3', 1],
            ['A7', 'C4', 20],
            ['A7', 'C5', 2],
            ['A7', 'C6', 1],
            ['A7', 'C7', 0],
            
            // Cloudmatika (A8)
            ['A8', 'C1', 140000],
            ['A8', 'C2', 1],
            ['A8', 'C3', 2],
            ['A8', 'C4', 50],
            ['A8', 'C5', 2],
            ['A8', 'C6', 1],
            ['A8', 'C7', 0],
            
            // Dihostingin (A9)
            ['A9', 'C1', 25000],
            ['A9', 'C2', 2],
            ['A9', 'C3', 1],
            ['A9', 'C4', 25],
            ['A9', 'C5', 1],
            ['A9', 'C6', 1],
            ['A9', 'C7', 0],
            
            // Cloudaja (A10)
            ['A10', 'C1', 75000],
            ['A10', 'C2', 1],
            ['A10', 'C3', 1],
            ['A10', 'C4', 20],
            ['A10', 'C5', 1],
            ['A10', 'C6', 0],
            ['A10', 'C7', 0],
        ];

        foreach ($data as [$alternativeCode, $criteriaCode, $value]) {
            try {
                // Dapatkan ID alternatif
                $alternativeId = DB::table('alternatives')
                    ->where('code', $alternativeCode)
                    ->value('id');
                
                // Dapatkan ID kriteria
                $criteriaId = DB::table('criteria')
                    ->where('code', $criteriaCode)
                    ->value('id');
                
                if (!$alternativeId) {
                    $this->command->error("Alternatif dengan kode {$alternativeCode} tidak ditemukan!");
                    continue;
                }
                
                if (!$criteriaId) {
                    $this->command->error("Kriteria dengan kode {$criteriaCode} tidak ditemukan!");
                    continue;
                }
                
                // Inserting using Query Builder
                DB::table('alternative_values')->insert([
                    'alternative_id' => $alternativeId,
                    'criteria_id' => $criteriaId,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Berhasil menambahkan nilai {$value} untuk alternatif {$alternativeCode}, kriteria {$criteriaCode}");
            } catch (\Exception $e) {
                $this->command->error("Error: " . $e->getMessage());
            }
        }
    }
}