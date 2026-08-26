<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorcycleImage extends Model
{
    /** @use HasFactory<\Database\Factories\MotorcycleImageFactory> */
    use HasFactory;

    protected $fillable = ['motorcycle_id', 'image'];

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function getUrlAttribute(): string
    {
        if (StorageHelper::exists($this->image)) {
            return StorageHelper::url($this->image);
        }

        return asset('images/placeholder-motorcycle.svg');
    }
}
