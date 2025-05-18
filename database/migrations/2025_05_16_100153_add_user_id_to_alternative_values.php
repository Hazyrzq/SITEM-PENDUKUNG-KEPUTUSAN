<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alternative_values', function (Blueprint $table) {
            // Tambahkan kolom user_id setelah kolom criteria_id
            $table->unsignedBigInteger('user_id')->after('criteria_id')->nullable();
            
            // Tambahkan foreign key constraint ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alternative_values', function (Blueprint $table) {
            // Hapus foreign key constraint dan kolom
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};