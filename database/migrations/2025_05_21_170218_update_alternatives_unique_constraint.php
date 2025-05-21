<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateAlternativesUniqueConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Hapus constraint unik yang sekarang pada kolom code
        Schema::table('alternatives', function (Blueprint $table) {
            DB::statement('ALTER TABLE alternatives DROP INDEX alternatives_code_unique');
        });

        // Tambahkan constraint unik baru pada kombinasi code dan user_id
        Schema::table('alternatives', function (Blueprint $table) {
            $table->unique(['code', 'user_id'], 'alternatives_code_user_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Hapus constraint unik pada kombinasi code dan user_id
        Schema::table('alternatives', function (Blueprint $table) {
            $table->dropUnique('alternatives_code_user_id_unique');
        });

        // Kembalikan constraint unik pada kolom code
        Schema::table('alternatives', function (Blueprint $table) {
            $table->unique('code', 'alternatives_code_unique');
        });
    }
}