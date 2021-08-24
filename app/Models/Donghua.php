<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donghua extends Model
{
    use HasFactory;

    protected $table = 'donghuas';
    protected $fillable = [
        'slug', 'title', 'synopsis', 'status', 'network', 'studio', 'release_date', 'duration', 'graphic', 'country', 'type', 'translated_by', 'user_id',
        'image', 'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function genre()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function episode()
    {
        return $this->hasMany(Episode::class, 'donghua_id');
    }
}
