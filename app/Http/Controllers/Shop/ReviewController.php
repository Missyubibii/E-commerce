<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi. Vui lòng cập nhật đánh giá của bạn thay vì tạo mới.');
        }

        // Create review
        $review = new Review([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Check if user has purchased the product
        $review->verified_purchase = $review->verifyPurchase();
        $review->save();

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function update(Request $request, Review $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được cập nhật!');
    }

    public function destroy(Review $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Đánh giá của bạn đã được xóa!');
    }

    public function index(Product $product)
    {
        $reviews = $product->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $averageRating = Review::getAverageRating($product->id);
        $ratingDistribution = Review::getRatingDistribution($product->id);

        // Check if current user has purchased the product
        $hasPurchased = auth()->check() ?
            Review::query()->where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists() : false;

        return view('shop.reviews.index', compact(
            'product',
            'reviews',
            'averageRating',
            'ratingDistribution',
            'hasPurchased'
        ));
    }
}
