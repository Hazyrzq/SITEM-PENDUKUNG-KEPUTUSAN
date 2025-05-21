<?php

namespace Database\Seeders;

use App\Models\Alternative;
use Illuminate\Database\Seeder;

class AlternativeSeeder extends Seeder
{
    public function run(): void
    {
        $alternatives = [
            [
                'name' => 'Qwords',
                'code' => 'A1',
                'description' => 'Penyedia layanan VPS Cloud Qwords',
                'user_id' => null, // Menandakan alternatif dari admin
            ],
            [
                'name' => 'Domainesia',
                'code' => 'A2',
                'description' => 'Penyedia layanan VPS Cloud Domainesia',
                'user_id' => null,
            ],
            [
                'name' => 'Dewaweb',
                'code' => 'A3',
                'description' => 'Penyedia layanan VPS Cloud Dewaweb',
                'user_id' => null,
            ],
            [
                'name' => 'Idwebhost',
                'code' => 'A4',
                'description' => 'Penyedia layanan VPS Cloud Idwebhost',
                'user_id' => null,
            ],
            [
                'name' => 'Dhyhost',
                'code' => 'A5',
                'description' => 'Penyedia layanan VPS Cloud Dhyhost',
                'user_id' => null,
            ],
            [
                'name' => 'Rumahweb',
                'code' => 'A6',
                'description' => 'Penyedia layanan VPS Cloud Rumahweb',
                'user_id' => null,
            ],
            [
                'name' => 'NevaCloud',
                'code' => 'A7',
                'description' => 'Penyedia layanan VPS Cloud NevaCloud',
                'user_id' => null,
            ],
            [
                'name' => 'Cloudmatika',
                'code' => 'A8',
                'description' => 'Penyedia layanan VPS Cloud Cloudmatika',
                'user_id' => null,
            ],
            [
                'name' => 'Dihostingin',
                'code' => 'A9',
                'description' => 'Penyedia layanan VPS Cloud Dihostingin',
                'user_id' => null,
            ],
            [
                'name' => 'Cloudaja',
                'code' => 'A10',
                'description' => 'Penyedia layanan VPS Cloud Cloudaja',
                'user_id' => null,
            ],
        ];
        
        foreach ($alternatives as $item) {
            Alternative::create($item);
        }
    }
}