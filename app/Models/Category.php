<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'icon',
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
