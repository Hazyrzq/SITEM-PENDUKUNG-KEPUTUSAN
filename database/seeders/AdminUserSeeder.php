<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@apis.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        
        $this->command->info('Admin user created!');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: admin123');
    }
}