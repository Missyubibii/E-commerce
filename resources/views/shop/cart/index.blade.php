@extends('layouts.app')

@section('title', 'Giỏ Hàng')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden p-6">
        <div class="flex items-center mb-6">
            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-3xl font-bold text-gray-900">Giỏ Hàng</h2>
        </div>

        @if($cartItems->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg mb-4">Giỏ hàng của bạn đang trống</p>
                <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4 hover:bg-gray-50 transition-colors duration-200 p-4 rounded-lg">
                        <div class="flex items-center">
                            @if($item->product->image)
                                <img src="{{ asset('storage/products/' . $item->product->image) }}"
                                    alt="{{ $item->product->name }}"
                                    class="w-20 h-20 object-contain rounded-lg shadow-sm transform transition-transform duration-300 hover:scale-105">
                            @endif
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors duration-150">{{ $item->product->name }}</h3>
                                <p class="text-blue-600 font-medium">{{ number_format($item->product->price, 0, ',', '.') }}đ</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <select name="quantity" onchange="this.form.submit()"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-700 hover:border-blue-500 transition-colors duration-150">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </form>
                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-all duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div class="mt-8">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 shadow-inner">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-medium text-gray-900">Tổng cộng</span>
                            <span class="text-3xl font-bold text-blue-600">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            Tiếp tục mua sắm
                        </a>
                        <a href="{{ route('checkout.show') }}"
                            class="inline-flex items-center justify-center w-full md:w-auto mt-4 px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-150 hover:-translate-y-0.5 hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12l-4 4m0 0l-4-4m4 4V3" />
                            </svg>
                            Tiến hành thanh toán
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
