<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'advertiser_id','name','objective','status','starts_at','ends_at','budget','spent','targeting'
    ];

    protected $casts = [
        'budget' => 'decimal:4',
        'spent' => 'decimal:4',
        'targeting' => 'array',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Durée de la campagne en jours.
     */
    public function getDurationDaysAttribute(): ?int
    {
        if ($this->starts_at && $this->ends_at) {
            return Carbon::parse($this->starts_at)->diffInDays(Carbon::parse($this->ends_at));
        }

        return null;
    }

    /**
     * Scope pour campagnes actives.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Mutateur pour normaliser le budget en décimal.
     */
    public function setBudgetAttribute($value)
    {
        $this->attributes['budget'] = is_null($value) ? 0 : number_format((float)$value, 4, '.', '');
    }
}
