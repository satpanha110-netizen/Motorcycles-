<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    /** @use HasFactory<\Database\Factories\BrandFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'logo', 'description', 'is_active'];

    protected static function booted(): void
    {
        static::creating(function (Brand $brand) {
            $brand->slug ??= Str::slug($brand->name);
        });
    }

    public function motorcycles(): HasMany
    {
        return $this->hasMany(Motorcycle::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeWithCounts(Builder $query): Builder
    {
        return $query->withCount(['motorcycles' => fn (Builder $q) => $q->where('status', 'approved')]);
    }
}
