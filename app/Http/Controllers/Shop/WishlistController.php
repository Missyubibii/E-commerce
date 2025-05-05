<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlists = auth()->user()->wishlists()
            ->with(['product' => function($query) {
                $query->withAvg('reviews', 'rating')
                    ->withCount('reviews');
            }])
            ->latest()
            ->paginate(12);

        return view('shop.wishlist.index', compact('wishlists'));
    }

    public function toggle(Product $product)
    {
        $userId = auth()->id();

        if ($product->isWishlistedBy($userId)) {
            Wishlist::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->delete();

            return back()->with('success', 'Product removed from wishlist');
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $product->id
            ]);

            return back()->with('success', 'Product added to wishlist');
        }
    }

    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with('success', 'Product removed from wishlist');
    }

    public function clear()
    {
        auth()->user()->wishlists()->delete();

        return back()->with('success', 'Wishlist cleared successfully');
    }

    public function moveAllToCart()
    {
        $wishlists = auth()->user()->wishlists;

        foreach ($wishlists as $wishlist) {
            // Check if product is already in cart
            $cartItem = auth()->user()->cartItems()
                ->where('product_id', $wishlist->product_id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                auth()->user()->cartItems()->create([
                    'product_id' => $wishlist->product_id,
                    'quantity' => 1
                ]);
            }
        }

        // Clear wishlist after moving items to cart
        auth()->user()->wishlists()->delete();

        return redirect()->route('cart.index')
            ->with('success', 'All wishlist items moved to cart');
    }
}
