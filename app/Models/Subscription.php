<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['advertiser_id','plan_name','price','currency','starts_at','ends_at','status'];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'price' => 'decimal:4'
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }
}
