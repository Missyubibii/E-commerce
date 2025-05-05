<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comparison extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Add a product to comparison list
     */
    public static function addProduct($userId, $productId): bool
    {
        // Check if product is already in comparison
        if (self::where('user_id', $userId)->where('product_id', $productId)->exists()) {
            return false;
        }

        // Check if comparison list is full (max 4 products)
        if (self::where('user_id', $userId)->count() >= 4) {
            return false;
        }

        self::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return true;
    }

    /**
     * Remove a product from comparison list
     */
    public static function removeProduct($userId, $productId): bool
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete() > 0;
    }

    /**
     * Clear comparison list for a user
     */
    public static function clearList($userId): bool
    {
        return self::where('user_id', $userId)->delete() > 0;
    }

    /**
     * Get common specifications of compared products
     */
    public static function getCommonSpecs($products)
    {
        $specs = [];
        foreach ($products as $product) {
            $productSpecs = [
                'name' => $product->name,
                'price' => number_format($product->price, 0, ',', '.') . ' đ',
                'stock_quantity' => $product->stock_quantity,
                'category' => $product->category->name,
                'rating' => number_format($product->average_rating, 1),
                'reviews' => $product->review_count . ' đánh giá',
            ];
            $specs[] = $productSpecs;
        }
        return $specs;
    }
}
