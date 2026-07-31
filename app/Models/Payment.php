<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['advertiser_id','campaign_id','amount','currency','status','payment_method','reference','metadata'];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'decimal:4'
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
