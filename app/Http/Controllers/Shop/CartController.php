<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Access\AuthorizationException;

class CartController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    /**
     * Display a listing of the cart items.
     */
    public function index(): View
    {
        // Lấy tất cả các sản phẩm trong giỏ hàng của người dùng hiện tại
        $cartItems = Auth::user()->cartItems()->with('product')->get();

        // Kiểm tra tổng giá trị giỏ hàng
        $total = $cartItems->sum(function ($item) {
            return $item->getSubtotal(); // Tính tổng giá trị của sản phẩm trong giỏ
        });

        return view('shop.cart.index', compact('cartItems', 'total'));
    }

    /**
     * Store a newly created cart item.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem = Auth::user()->cartItems()
            ->firstOrCreate(
                ['product_id' => $product->id],
                ['quantity' => 0]
            );

        $cartItem->increment('quantity', $validated['quantity']);

        return redirect()->route('cart.index')
            ->with('success', 'Sản phẩm đã được thêm vào giỏ hàng thành công');
    }

    /**
     * Update the specified cart item.
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorize('update', $cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->update($validated);

        return redirect()->route('cart.index')
            ->with('success', 'Giỏ hàng đã được cập nhật thành công');
    }

    /**
     * Remove the specified cart item.
     */
    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng thành công');
    }
}
