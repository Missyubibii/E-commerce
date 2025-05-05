<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Comparison;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')
            ->withTimestamps();
    }

    public function comparedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comparisons')
            ->withTimestamps();
    }

    public function isWishlistedBy($userId): bool
    {
        return $this->wishlistedBy()->where('user_id', $userId)->exists();
    }

    public function isInComparison($userId): bool
    {
        return Comparison::where('user_id', $userId)
            ->where('product_id', $this->id)
            ->exists();
    }

    public function scopeWithComparison($query, $userId)
    {
        return $query->withCount(['comparisons' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }]);
    }

    // Search scope
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // Filter by price range
    public function scopeFilterByPrice($query, $min, $max)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    // Filter by category
    public function scopeFilterByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    // Get average rating
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    // Get review count
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    // Check if user has reviewed
    public function hasBeenReviewedBy($userId)
    {
        return $this->reviews()->where('user_id', $userId)->exists();
    }
}
