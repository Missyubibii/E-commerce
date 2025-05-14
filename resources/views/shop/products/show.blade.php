@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3">
                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-contain">
                </div>
                <div class="md:w-2/3 p-4">
                    <h1 class="text-2xl font-semibold mb-2">{{ $product->name }}</h1>
                    <p class="text-gray-700 mb-4">{{ $product->description }}</p>
                    <p class="text-xl font-bold text-blue-600 mb-4">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-700">
                            @if ($product->stock_quantity > 0)
                                Còn hàng ({{ $product->stock_quantity }})
                            @else
                                Hết hàng
                            @endif
                        </span>
                        <div class="flex items-center">
                            <label for="quantity" class="mr-2 text-gray-700">Số lượng:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-20 border rounded px-2 py-1">
                        </div>
                    </div>
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <div class="flex">
                                <a href="{{ route('shop.comparison.add', $product->id) }}" class="mr-2">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </a>
                                <a href="{{ route('shop.wishlist.add', $product->id) }}">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </a>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection
