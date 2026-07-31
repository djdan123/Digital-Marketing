<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * Représente un annonceur (profil utilisateur métier)
 */
class Advertiser extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id','company_id','first_name','last_name','phone','email','role','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Nom complet pour affichage.
     */
    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    /**
     * Mutateur pour forcer l'email en minuscules.
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value ? Str::lower($value) : null;
    }

    /**
     * Scope pour annonceurs actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Canal email pour les notifications.
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->email;
    }
}
