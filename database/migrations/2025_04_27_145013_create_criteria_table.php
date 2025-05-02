<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('rank')->comment('Urutan prioritas untuk perhitungan ROC');
            $table->enum('type', ['benefit', 'cost']);
            $table->float('weight')->nullable()->comment('Bobot hasil perhitungan ROC');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};