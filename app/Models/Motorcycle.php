<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Motorcycle extends Model
{
    /** @use HasFactory<\Database\Factories\MotorcycleFactory> */
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'category_id',
        'seller_id',
        'title',
        'slug',
        'model',
        'year',
        'price',
        'engine_cc',
        'mileage',
        'condition',
        'transmission',
        'fuel_type',
        'color',
        'location',
        'featured',
        'description',
        'features',
        'main_image',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
        'featured' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Motorcycle $motorcycle) {
            $motorcycle->slug = $motorcycle->slug ?? Str::slug($motorcycle->title . '-' . Str::random(6));
        });
    }

    /* Relationships */

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(MotorcycleImage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /* Accessors */

    public function getImageUrlAttribute(): string
    {
        if ($this->main_image && StorageHelper::exists($this->main_image)) {
            return StorageHelper::url($this->main_image);
        }

        return asset('images/placeholder-motorcycle.svg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format((float) $this->price);
    }

    public function getSpecsLineAttribute(): string
    {
        return sprintf('%d | %scc | %s', $this->year, $this->engine_cc ?? '—', ucfirst($this->transmission));
    }

    /**
     * Telegram deep-link URL that pre-fills a message with this motorcycle's details.
     *
     * Returns null when the seller has no telegram_username set.
     */
    public function getTelegramMessageUrlAttribute(): ?string
    {
        $username = $this->seller->telegram_username ?? null;

        if (!$username) {
            return null;
        }

        $imageUrl = url($this->image_url);
        $detailUrl = route('motorcycles.show', $this->slug);
        $priceFormatted = '$' . number_format((float) $this->price, 2);
        $brandName = $this->brand->name ?? '—';

        $message = "📸 រូបម៉ូតូ\n{$imageUrl}\n\n"
            . "🏍️ ម៉ូតូ:\n{$this->title}\n\n"
            . "🏷️ ម៉ាក:\n{$brandName}\n\n"
            . "💰 តម្លៃ:\n{$priceFormatted}\n\n"
            . "🔗 ព័ត៌មានម៉ូតូ:\n{$detailUrl}\n\n"
            . "សួស្តី ខ្ញុំចង់សួរព័ត៌មានអំពីម៉ូតូនេះ។\n"
            . "សូមជួយផ្តល់ព័ត៌មានបន្ថែម។ អរគុណ 🙏";

        return 'https://t.me/' . ltrim($username, '@') . '?text=' . urlencode($message);
    }

    /* Scopes */

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q) use ($keyword) {
            $q->where(function (Builder $inner) use ($keyword) {
                $inner->where('title', 'like', "%{$keyword}%")
                    ->orWhere('model', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%")
                    ->orWhereHas('brand', fn (Builder $b) => $b->where('name', 'like', "%{$keyword}%"));
            });
        });
    }

    /**
     * Apply listing filters from the request query string.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->search($filters['q'] ?? null)
            ->when($filters['brand'] ?? null, fn (Builder $q) => $q->whereHas('brand', fn (Builder $b) => $b->where('slug', $filters['brand'])))
            ->when($filters['category'] ?? null, fn (Builder $q) => $q->whereHas('category', fn (Builder $c) => $c->where('slug', $filters['category'])))
            ->when($filters['model'] ?? null, fn (Builder $q) => $q->where('model', 'like', '%' . $filters['model'] . '%'))
            ->when($filters['min_price'] ?? null, fn (Builder $q) => $q->where('price', '>=', $filters['min_price']))
            ->when($filters['max_price'] ?? null, fn (Builder $q) => $q->where('price', '<=', $filters['max_price']))
            ->when($filters['year'] ?? null, fn (Builder $q) => $q->where('year', $filters['year']))
            ->when($filters['min_cc'] ?? null, fn (Builder $q) => $q->where('engine_cc', '>=', $filters['min_cc']))
            ->when($filters['max_cc'] ?? null, fn (Builder $q) => $q->where('engine_cc', '<=', $filters['max_cc']))
            ->when($filters['condition'] ?? null, fn (Builder $q) => $q->where('condition', $filters['condition']))
            ->when($filters['transmission'] ?? null, fn (Builder $q) => $q->where('transmission', $filters['transmission']))
            ->when($filters['fuel_type'] ?? null, fn (Builder $q) => $q->where('fuel_type', $filters['fuel_type']))
            ->when($filters['location'] ?? null, fn (Builder $q) => $q->where('location', 'like', '%' . $filters['location'] . '%'));
    }

    public function scopeSort(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'price_low_high' => $query->orderBy('price'),
            'price_high_low' => $query->orderByDesc('price'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };
    }
}
