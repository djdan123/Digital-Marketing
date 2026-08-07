<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * Représente un annonceur (profil utilisateur métier)
 */
class Advertiser extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'role',
        'status',
        'wallet_balance',
    ];

    protected $casts = [
        'wallet_balance' => 'decimal:2',
    ];

    /* ===================== Relations ===================== */

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

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /* ===================== Accessors ===================== */

    /**
     * Nom complet pour affichage.
     */
    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    /**
     * Alias pratique pour les listes (admin / API).
     */
    public function getNameAttribute(): string
    {
        $full = $this->full_name;

        if ($full !== '') {
            return $full;
        }

        return $this->email ?? ('Annonceur #' . $this->id);
    }

    /* ===================== Mutators ===================== */

    /**
     * Forcer l'email en minuscules.
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value ? Str::lower($value) : null;
    }

    /* ===================== Scopes ===================== */

    /**
     * Annonceurs actifs uniquement.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /* ===================== Helpers portefeuille ===================== */

    /**
     * Créditer le portefeuille.
     */
    public function creditWallet(float $amount): void
    {
        $this->wallet_balance = (float) ($this->wallet_balance ?? 0) + $amount;
        $this->save();
    }

    /**
     * Débiter le portefeuille (si solde suffisant).
     */
    public function debitWallet(float $amount): bool
    {
        $balance = (float) ($this->wallet_balance ?? 0);

        if ($balance < $amount) {
            return false;
        }

        $this->wallet_balance = $balance - $amount;
        $this->save();

        return true;
    }

    /* ===================== Notifications ===================== */

    /**
     * Canal email pour les notifications.
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->email;
    }
}