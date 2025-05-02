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
            ],
            [
                'name' => 'Domainesia',
                'code' => 'A2',
                'description' => 'Penyedia layanan VPS Cloud Domainesia',
            ],
            [
                'name' => 'Dewaweb',
                'code' => 'A3',
                'description' => 'Penyedia layanan VPS Cloud Dewaweb',
            ],
            [
                'name' => 'Idwebhost',
                'code' => 'A4',
                'description' => 'Penyedia layanan VPS Cloud Idwebhost',
            ],
            [
                'name' => 'Dhyhost',
                'code' => 'A5',
                'description' => 'Penyedia layanan VPS Cloud Dhyhost',
            ],
            [
                'name' => 'Rumahweb',
                'code' => 'A6',
                'description' => 'Penyedia layanan VPS Cloud Rumahweb',
            ],
            [
                'name' => 'NevaCloud',
                'code' => 'A7',
                'description' => 'Penyedia layanan VPS Cloud NevaCloud',
            ],
            [
                'name' => 'Cloudmatika',
                'code' => 'A8',
                'description' => 'Penyedia layanan VPS Cloud Cloudmatika',
            ],
            [
                'name' => 'Dihostingin',
                'code' => 'A9',
                'description' => 'Penyedia layanan VPS Cloud Dihostingin',
            ],
            [
                'name' => 'Cloudaja',
                'code' => 'A10',
                'description' => 'Penyedia layanan VPS Cloud Cloudaja',
            ],
        ];

        foreach ($alternatives as $item) {
            Alternative::create($item);
        }
    }
}