<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlternativeValue extends Model
{
    use HasFactory;

    protected $table = 'alternative_values';

    // Pastikan semua kolom yang dibutuhkan ada di sini
    protected $fillable = [
        'alternative_id',
        'criteria_id',
        'user_id',
        'value',
    ];

    /**
     * Relasi ke model Alternative
     */
    public function alternative(): BelongsTo
    {
        return $this->belongsTo(Alternative::class);
    }

    /**
     * Relasi ke model Criteria
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
    
    /**
     * Relasi ke model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}