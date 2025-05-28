@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 sm:p-8">
            <!-- Order Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 pb-6 border-b border-blue-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Đơn hàng số {{ $order->id }}</h1>
                    <p class="text-sm text-gray-600">Đặt vào ngày {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @endif">
                        @if($order->status === 'pending')
                            Đang chờ xử lý
                        @elseif($order->status === 'processing')
                            Đang xử lý
                        @elseif($order->status === 'completed')
                            Hoàn thành
                        @elseif($order->status === 'cancelled')
                            Đã hủy
                        @endif
                    </span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Sản phẩm trong đơn hàng</h2>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    @if(isset($order->orderItems) && count($order->orderItems) > 0)
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                <div class="flex items-center flex-1">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/products/' . $item->product->image) }}"
                                        alt="{{ $item->product->name }}"
                                        class="w-16 h-16 object-contain rounded-lg mr-4">
                                @endif
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h3>
                                        <p class="text-gray-600">Số lượng: {{ $item->quantity }} × {{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-4 text-gray-500">Không có sản phẩm nào trong đơn hàng này.</div>
                    @endif
                </div>
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Shipping Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin giao hàng</h2>
                    <div class="space-y-3">
                        <p class="text-gray-700">
                            <span class="font-medium">Tên:</span> {{ $order->user->name }}
                        </p>
                        <p class="text-gray-700">
                            <span class="font-medium">Số điện thoại:</span> {{ $order->phone_number }}
                        </p>
                        <p class="text-gray-700">
                            <span class="font-medium">Địa chỉ:</span><br>
                            {{ $order->shipping_address }}
                        </p>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin thanh toán</h2>
                    <div class="space-y-3">
                        <p class="text-gray-700">
                            <span class="font-medium">Phương thức thanh toán:</span><br>
                            Thanh toán khi giao hàng (COD)
                        </p>
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Tổng phụ:</span>
                                <span class="text-gray-900">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Phí giao hàng:</span>
                                <span class="text-gray-900">0 VNĐ</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-4">
                                <span>Tổng cộng:</span>
                                <span class="text-blue-600">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Quay lại danh sách đơn hàng
                </a>
                @if($order->status === 'pending')
                    <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Hủy đơn hàng
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
