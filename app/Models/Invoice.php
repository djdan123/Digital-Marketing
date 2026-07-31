<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['advertiser_id','invoice_number','issued_at','due_at','subtotal','tax','total','status','line_items','pdf_path'];

    protected $casts = [
        'line_items' => 'array',
        'issued_at' => 'date',
        'due_at' => 'date',
        'subtotal' => 'decimal:4',
        'tax' => 'decimal:4',
        'total' => 'decimal:4',
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }
}
