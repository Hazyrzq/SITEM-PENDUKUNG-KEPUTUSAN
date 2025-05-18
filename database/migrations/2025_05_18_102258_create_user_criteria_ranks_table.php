<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek apakah tabel user_criteria_ranks ada
        if (Schema::hasTable('user_criteria_ranks')) {
            // Cek apakah tabel memiliki kolom yang diperlukan
            $missingColumns = [];
            $requiredColumns = ['user_id', 'criteria_id', 'rank', 'created_at', 'updated_at'];
            
            foreach ($requiredColumns as $column) {
                if (!Schema::hasColumn('user_criteria_ranks', $column)) {
                    $missingColumns[] = $column;
                }
            }
            
            // Jika ada kolom yang hilang, tambahkan kolom tersebut
            if (!empty($missingColumns)) {
                Schema::table('user_criteria_ranks', function (Blueprint $table) use ($missingColumns) {
                    if (in_array('user_id', $missingColumns)) {
                        $table->unsignedBigInteger('user_id')->after('id');
                    }
                    
                    if (in_array('criteria_id', $missingColumns)) {
                        $table->unsignedBigInteger('criteria_id')->after('user_id');
                    }
                    
                    if (in_array('rank', $missingColumns)) {
                        $table->integer('rank')->after('criteria_id');
                    }
                    
                    if (in_array('created_at', $missingColumns)) {
                        $table->timestamp('created_at')->nullable();
                    }
                    
                    if (in_array('updated_at', $missingColumns)) {
                        $table->timestamp('updated_at')->nullable();
                    }
                });
            }
            
            // Cek apakah sudah ada indeks unik dan tambahkan jika belum
            try {
                Schema::table('user_criteria_ranks', function (Blueprint $table) {
                    $table->unique(['user_id', 'criteria_id'], 'user_criteria_ranks_user_criteria_unique');
                });
            } catch (\Exception $e) {
                // Indeks mungkin sudah ada, abaikan error
                DB::statement("/* INFO: Unique index untuk user_id dan criteria_id mungkin sudah ada */");
            }
            
            // Cek apakah sudah ada indeks biasa dan tambahkan jika belum
            try {
                Schema::table('user_criteria_ranks', function (Blueprint $table) {
                    $table->index('user_id', 'user_criteria_ranks_user_id_index');
                });
            } catch (\Exception $e) {
                // Indeks mungkin sudah ada, abaikan error
            }
            
            try {
                Schema::table('user_criteria_ranks', function (Blueprint $table) {
                    $table->index('criteria_id', 'user_criteria_ranks_criteria_id_index');
                });
            } catch (\Exception $e) {
                // Indeks mungkin sudah ada, abaikan error
            }
            
            // Log info
            DB::statement("/* INFO: Tabel user_criteria_ranks sudah ada dan telah diperbarui sesuai kebutuhan */");
        } else {
            // Jika tabel belum ada, buat baru
            Schema::create('user_criteria_ranks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('criteria_id');
                $table->integer('rank');
                $table->timestamps();
                
                // Menambahkan index
                $table->unique(['user_id', 'criteria_id']);
                $table->index('user_id');
                $table->index('criteria_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak melakukan drop tabel untuk menghindari kehilangan data
        // Jika ingin menghapus tabel, silakan gunakan kode berikut:
        // Schema::dropIfExists('user_criteria_ranks');
    }
};