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
        $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('shop.checkout', compact('cartItems', 'total'));
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
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            // Validate stock availability
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception("Sorry, '{$item->product->name}' only has {$item->product->stock} items in stock.");
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
                'status' => 'pending'
            ]);

            // Add items and update stock
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                // Reduce product stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart after order is created
            CartItem::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully! We will contact you to confirm the delivery.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order processing error: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'stock')) {
                return back()->with('error', $e->getMessage());
            }

            return back()->with('error', 'Sorry, there was a problem processing your order. Our team has been notified. Please try again or contact support.');
        }
    }
}
