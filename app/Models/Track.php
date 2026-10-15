<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'artist',
        'album',
        'release_year',
        'duration_seconds',
        'access_type',
        'audio_url',
        'cover_url',
        'genre',
        'lyrics',
        'description',
        'plays_count',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_tracks')
            ->withPivot('order')
            ->withTimestamps();
    }

    public function getDurationStringAttribute(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getAccessBadgeAttribute(): array
    {
        return match ($this->access_type) {
            'free' => ['label' => 'GRATIS', 'class' => 'badge-free'],
            'vip' => ['label' => 'VIP', 'class' => 'badge-vip'],
            default => ['label' => 'FREE', 'class' => 'badge-free'],
        };
    }
}
