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
                            <input type="hidden" name="quantity" id="hiddenQuantity" value="1">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="updateQuantity()">
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateQuantity() {
            var quantityInput = document.getElementById('quantity');
            var hiddenQuantity = document.getElementById('hiddenQuantity');
            hiddenQuantity.value = quantityInput.value;
        }
    </script>
@endsection
