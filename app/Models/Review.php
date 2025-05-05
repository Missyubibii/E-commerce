<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'verified_purchase',
    ];

    protected $casts = [
        'rating' => 'integer',
        'verified_purchase' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Helper method to check if user has purchased the product
    public function verifyPurchase(): bool
    {
        return OrderItem::query()
            ->whereHas('order', function ($query) {
                $query->where('user_id', $this->user_id)
                    ->where('status', 'completed');
            })
            ->where('product_id', $this->product_id)
            ->exists();
    }

    // Get average rating for a product
    public static function getAverageRating($productId): float
    {
        return static::where('product_id', $productId)
            ->avg('rating') ?? 0;
    }

    // Get rating distribution for a product
    public static function getRatingDistribution($productId): array
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = static::where('product_id', $productId)
                ->where('rating', $i)
                ->count();
            $distribution[$i] = $count;
        }
        return $distribution;
    }
}
