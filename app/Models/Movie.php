<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'tagline',
        'synopsis',
        'director',
        'cast',
        'release_year',
        'duration_minutes',
        'access_type',
        'ticket_price',
        'video_url',
        'trailer_url',
        'poster_url',
        'banner_url',
        'location_tag',
        'views_count',
        'rating',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'ticket_price' => 'decimal:2',
        'rating' => 'decimal:1',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->ticket_price, 0, ',', '.');
    }

    public function getDurationStringAttribute(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        if ($hours > 0) {
            return "{$hours}j {$minutes}m";
        }
        return "{$minutes} menit";
    }

    public function getAccessBadgeAttribute(): array
    {
        return match ($this->access_type) {
            'free' => [
                'label' => 'GRATIS',
                'class' => 'badge-free',
                'desc' => 'Bisa langsung ditonton'
            ],
            'vip' => [
                'label' => 'VIP SEBAYU',
                'class' => 'badge-vip',
                'desc' => 'Khusus Langganan VIP'
            ],
            'ticket' => [
                'label' => 'TIKET PREMIERE',
                'class' => 'badge-ticket',
                'desc' => 'Tiket ' . $this->formatted_price
            ],
            default => [
                'label' => 'UMUM',
                'class' => 'badge-free',
                'desc' => ''
            ],
        };
    }
}
