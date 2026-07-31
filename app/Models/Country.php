<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name','iso_code','currency_id'];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
