<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = ['statisticable_type','statisticable_id','impressions','clicks','conversions','cost','metadata','date'];

    protected $casts = [
        'metadata' => 'array',
        'cost' => 'decimal:4',
        'date' => 'date'
    ];

    public function statisticable()
    {
        return $this->morphTo();
    }
}
