<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Alternative extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AlternativeValue::class);
    }
    
    /**
     * Mengambil nilai alternatif untuk kriteria tertentu berdasarkan user
     */
    public function getValueForCriteria($criteriaId, $userId)
    {
        return $this->values()
            ->where('criteria_id', $criteriaId)
            ->where('user_id', $userId)
            ->value('value');
    }

    /**
     * Mengambil semua nilai alternatif untuk user tertentu
     */
    public function getValuesForUser($userId)
    {
        return $this->values()
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * Scope untuk filter alternatif berdasarkan user yang memiliki nilai
     */
    public function scopeWithValuesForUser($query, $userId)
    {
        return $query->whereHas('values', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}