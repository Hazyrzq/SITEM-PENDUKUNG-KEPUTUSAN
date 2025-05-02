<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternative_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alternative_id');
            $table->unsignedBigInteger('criteria_id');
            $table->float('value')->comment('Nilai alternatif untuk kriteria tertentu');
            $table->timestamps();
            
            $table->unique(['alternative_id', 'criteria_id']);
            
            // Menggunakan syntax foreign key yang lebih eksplisit
            $table->foreign('alternative_id')
                  ->references('id')
                  ->on('alternatives')
                  ->onDelete('cascade');
                  
            $table->foreign('criteria_id')
                  ->references('id')
                  ->on('criteria')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternative_values');
    }
};