<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code','discount_amount','discount_percent','valid_from','valid_until','usage_limit','used_count','status'];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date'
    ];
}
