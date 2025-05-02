<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'criteria';

    protected $fillable = [
        'name',
        'code',
        'rank',
        'type',
        'weight',
    ];

    public function alternativeValues(): HasMany
    {
        return $this->hasMany(AlternativeValue::class);
    }
}