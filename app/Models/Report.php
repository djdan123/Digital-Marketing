<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['advertiser_id','campaign_id','title','description','filters','results','type'];

    protected $casts = [
        'filters' => 'array',
        'results' => 'array'
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
