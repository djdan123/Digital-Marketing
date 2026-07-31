<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoredFile extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = ['path','disk','mime_type','size','original_name','type'];
}
