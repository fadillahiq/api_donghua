<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $table = 'episodes';
    protected $fillable = [
        'title', 'slug', 'sub_title', 'resolutions', 'links', 'streaming', 'donghua_id'
    ];

    public function donghua()
    {
        return $this->belongsTo(Donghua::class, 'donghua_id');
    }
}
