<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_id','media_id','broadcasted_at','status','notes'];

    protected $casts = [
        'broadcasted_at' => 'datetime'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
