<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name','category_id','company_id','type','pricing_type','base_price','description','status'
    ];

    protected $casts = [
        'base_price' => 'decimal:4',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    /**
     * Prix formaté pour affichage.
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->base_price, 2);
    }

    /**
     * Scope pour médias actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
