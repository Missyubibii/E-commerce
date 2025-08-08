<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('shop.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('shop.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $cartItems = CartItem::where('user_id', auth()->id())
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Giỏ hàng của bạn đang trống.');
            }

            $total = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'payment_method' => 'cod',
                'status' => 'pending'
            ]);

foreach ($cartItems as $item) {
    $order->orderItems()->create([
        'product_id' => $item->product_id,
        'quantity' => $item->quantity,
        'price' => $item->product->price
    ]);
}

            // Clear cart
            CartItem::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra vấn đề khi xử lý đơn hàng của bạn: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
            abort(403);
        }

        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Đơn hàng đã được hủy thành công.');
    }
}
