<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Cek tabel yang tersedia
        $this->command->info("Memeriksa tabel yang tersedia...");
        $tables = DB::select('SHOW TABLES');
        
        $tableFound = false;
        $tableName = '';
        
        foreach ($tables as $table) {
            $currentTable = reset($table);
            $this->command->info("Tabel: " . $currentTable);
            
            // Cek jika tabel criteria atau criterias
            if ($currentTable === 'criteria') {
                $tableName = 'criteria';
                $tableFound = true;
                break;
            } else if ($currentTable === 'criterias') {
                $tableName = 'criterias';
                $tableFound = true;
                break;
            }
        }
        
        if (!$tableFound) {
            $this->command->error("Tabel criteria atau criterias tidak ditemukan!");
            return;
        }
        
        $this->command->info("Menggunakan tabel: " . $tableName);
        
        $criteria = [
            [
                'name' => 'Harga Perbulan (Rupiah)',
                'code' => 'C1',
                'rank' => 1, // Prioritas tertinggi
                'type' => 'cost',
                'weight' => null // Akan dihitung dengan ROC
            ],
            [
                'name' => 'Kapasitas RAM (GB)',
                'code' => 'C2',
                'rank' => 2,
                'type' => 'benefit',
                'weight' => null
            ],
            [
                'name' => 'Jumlah CPU (Core)',
                'code' => 'C3',
                'rank' => 3,
                'type' => 'benefit',
                'weight' => null
            ],
            [
                'name' => 'Kapasitas Penyimpanan (GB)',
                'code' => 'C4',
                'rank' => 4,
                'type' => 'benefit',
                'weight' => null
            ],
            [
                'name' => 'Jenis Penyimpanan',
                'code' => 'C5',
                'rank' => 5,
                'type' => 'benefit',
                'weight' => null
            ],
            [
                'name' => 'Unlimited Bandwidth',
                'code' => 'C6',
                'rank' => 6,
                'type' => 'benefit',
                'weight' => null
            ],
            [
                'name' => 'Multi OS',
                'code' => 'C7',
                'rank' => 7, // Prioritas terendah
                'type' => 'benefit',
                'weight' => null
            ],
        ];

        foreach ($criteria as $item) {
            try {
                // Menggunakan Query Builder langsung daripada Eloquent
                DB::table($tableName)->insert([
                    'name' => $item['name'],
                    'code' => $item['code'],
                    'rank' => $item['rank'],
                    'type' => $item['type'],
                    'weight' => $item['weight'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Berhasil menambahkan kriteria: " . $item['name']);
            } catch (\Exception $e) {
                $this->command->error("Error menambahkan kriteria: " . $e->getMessage());
            }
        }
    }
}