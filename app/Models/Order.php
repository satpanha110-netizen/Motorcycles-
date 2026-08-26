<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    public const STATUSES = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];

    protected $fillable = [
        'user_id',
        'motorcycle_id',
        'seller_id',
        'price',
        'status',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format((float) $this->price);
    }
}
