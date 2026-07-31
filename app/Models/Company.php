<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Société (organisation) cliente ou média
 */
class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name','slug','description','address','country_id','province_id','city_id','phone','website','status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Retourne le nom affichable de la société.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Mutateur automatique pour `slug` à partir du nom.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    /**
     * Scope pour récupérer les sociétés actives.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
