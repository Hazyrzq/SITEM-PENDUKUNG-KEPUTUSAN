<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'calculated_at',
        'results',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'results' => 'json',  // Ubah 'array' menjadi 'json' untuk kompatibilitas lebih baik
    ];

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi "details" yang diambil dari kolom results
     * Ini adalah relasi virtual yang tidak memerlukan tabel baru
     */
    public function details()
    {
        // Mengembalikan collection kosong yang bisa diakses seperti relasi
        // tapi sebenarnya berasal dari kolom results
        return collect(json_decode($this->results, true) ?: []);
    }
}