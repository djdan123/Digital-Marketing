<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'campaign_id','media_id','title','description','format','status','meta','cost'
    ];

    protected $casts = [
        'meta' => 'array',
        'cost' => 'decimal:4',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function files()
    {
        return $this->hasMany(AdvertisementMedia::class);
    }

    /**
     * Indique si l'annonce est approuvée.
     */
    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Scope pour annonces en attente.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Mutateur simple pour nettoyer le titre.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value ? Str::of($value)->trim()->__toString() : $value;
    }
}
