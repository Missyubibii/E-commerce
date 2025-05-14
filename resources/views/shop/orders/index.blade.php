@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

    @if($orders->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="mt-4 text-lg text-gray-600">You haven't placed any orders yet.</p>
            <a href="{{ route('home') }}" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Order #{{ $order->id }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Placed on {{ $order->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="text-lg font-bold text-gray-900">
                                    ${{ number_format($order->total_amount, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t pt-4">
    <div class="grid gap-4">
        @if(isset($order->items) && is_array($order->items) && count($order->items) > 0)
            @foreach($order->items as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if($item->product->image)
                            <img src="{{ asset('storage/products/' . $item->product->image) }}"
                                alt="{{ $item->product->name }}"
                                class="w-16 h-16 object-contain rounded">
                        @endif
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                            <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">
                        ${{ number_format($item->price * $item->quantity, 2) }}
                    </span>
                </div>
            @endforeach
        @else
            <p class="text-sm text-gray-500">Không có sản phẩm nào trong đơn hàng này.</p>
        @endif
    </div>
</div>

                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <p>{{ $order->shipping_name }}</p>
                                <p>{{ $order->shipping_phone }}</p>
                                <p>{{ $order->shipping_address }}</p>
                            </div>
                            <div class="flex space-x-4">
                                <a href="{{ route('orders.show', $order) }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Xem chi tiết
                                </a>
                                @if($order->status === 'pending')
                                    <form method="POST" action="{{ route('orders.destroy', $order) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Hủy đơn hàng
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
