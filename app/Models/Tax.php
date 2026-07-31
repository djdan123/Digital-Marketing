<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = ['name','rate','is_active','type'];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_active' => 'boolean'
    ];
}
