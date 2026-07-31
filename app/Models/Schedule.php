<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id','advertisement_id','media_id','scheduled_at','spots','price','status'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'price' => 'decimal:4'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
