<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pendekatan super sederhana untuk Laravel 12.
     */
    public function up(): void
    {
        // Gunakan try/catch untuk menangani kemungkinan error
        try {
            // Hapus index yang mungkin sudah ada
            DB::statement('ALTER TABLE alternative_values DROP INDEX IF EXISTS alternative_values_alternative_id_criteria_id_unique');
        } catch (\Exception $e) {
            // Abaikan error, mungkin index sudah tidak ada
        }

        try {
            // Tambahkan index baru
            DB::statement('ALTER TABLE alternative_values ADD UNIQUE INDEX alternative_values_alt_crit_user_unique (alternative_id, criteria_id, user_id)');
        } catch (\Exception $e) {
            // Jika gagal, coba pendekatan lain atau log error
            echo "Error menambahkan index baru: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Gunakan try/catch untuk menangani kemungkinan error
        try {
            // Hapus index baru
            DB::statement('ALTER TABLE alternative_values DROP INDEX IF EXISTS alternative_values_alt_crit_user_unique');
        } catch (\Exception $e) {
            // Abaikan error, mungkin index sudah tidak ada
        }

        try {
            // Kembalikan index lama
            DB::statement('ALTER TABLE alternative_values ADD UNIQUE INDEX alternative_values_alternative_id_criteria_id_unique (alternative_id, criteria_id)');
        } catch (\Exception $e) {
            // Jika gagal, log error
            echo "Error mengembalikan index lama: " . $e->getMessage() . "\n";
        }
    }
};