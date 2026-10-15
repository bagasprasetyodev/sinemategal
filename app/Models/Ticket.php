<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'user_id',
        'movie_id',
        'price_paid',
        'payment_method',
        'status',
        'valid_until',
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
        'valid_until' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function isValid(): bool
    {
        return $this->status === 'active' && $this->valid_until->isFuture();
    }
}
