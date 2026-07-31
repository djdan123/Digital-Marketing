<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Role du système
 *
 * Ce modèle représente un rôle attribuable aux utilisateurs/entités.
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    protected $casts = [];
}
