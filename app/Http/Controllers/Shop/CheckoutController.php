<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show()
    {
        try {
            $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();
            $total = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            return view('shop.checkout', compact('cartItems', 'total'));
        } catch (\Exception $e) {
            \Log::error('Error retrieving cart items for checkout: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Xin lỗi, đã xảy ra lỗi khi lấy thông tin giỏ hàng của bạn. Vui lòng thử lại.');
        }
    }

    public function process(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,bank_transfer',
        ]);

        try {
            DB::beginTransaction();

            $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
            }

            // Validate stock availability
            foreach ($cartItems as $item) {
                \Log::info("Product: {$item->product->name}, Stock: {$item->product->stock_quantity}, Quantity: {$item->quantity}");
                if ($item->quantity > $item->product->stock_quantity) {
                    throw new \Exception("Sorry, '{$item->product->name}' only has {$item->product->stock_quantity} items in stock.");
                }
            }

            $total = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            // Create order first
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'shipping_address' => "{$request->address}, {$request->city}, {$request->postal_code}",
                'shipping_phone' => $request->phone,
                'shipping_name' => $request->full_name,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'phone_number' => $request->phone,
            ]);

            // Add items and update stock
            foreach ($cartItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                // Reduce product stock
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // Clear cart after order is created
            CartItem::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn để xác nhận giao hàng.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order processing error: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'stock')) {
                return back()->with('error', $e->getMessage());
            }

            return back()->with('error', 'Xin lỗi, đã xảy ra sự cố khi xử lý đơn hàng của bạn. Nhóm của chúng tôi đã được thông báo. Vui lòng thử lại hoặc liên hệ với bộ phận hỗ trợ.');
        }
    }
}
