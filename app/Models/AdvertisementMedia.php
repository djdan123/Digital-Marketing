<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementMedia extends Model
{
    use HasFactory;

    protected $table = 'advertisement_media';

    protected $fillable = ['advertisement_id','media_id','file_path','type','mime_type','size'];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
