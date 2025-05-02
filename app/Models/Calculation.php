<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'calculated_at',
        'results',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'results' => 'array',
    ];
}