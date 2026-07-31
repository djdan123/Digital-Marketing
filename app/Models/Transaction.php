<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['payment_id','amount','currency','type','reference','details'];

    protected $casts = [
        'details' => 'array',
        'amount' => 'decimal:4'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
