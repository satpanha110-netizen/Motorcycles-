<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'telegram_username',
        'password',
        'role',
        'profile_image',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function motorcycles()
    {
        return $this->hasMany(Motorcycle::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Motorcycle::class, 'favorites')->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Full t.me link for this user's Telegram username, or null when unset.
     * The stored value never contains "@" or URL parts, so this is always safe.
     */
    public function getTelegramUrlAttribute(): ?string
    {
        $username = $this->telegram_username;

        if (!$username) {
            return null;
        }

        return 'https://t.me/' . ltrim($username, '@');
    }
}
