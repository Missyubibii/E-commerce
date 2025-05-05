@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="flex items-center mb-6">
                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <h2 class="text-3xl font-bold text-gray-900">Checkout</h2>
            </div>

            <!-- Cart Summary -->
            <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Order Summary
            </h3>
            <div class="space-y-4">
                @foreach($cartItems as $item)
                <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                alt="{{ $item->product->name }}"
                                class="w-16 h-16 object-cover rounded-lg mr-4">
                        @endif
                        <div>
                            <span class="font-medium text-gray-900">{{ $item->product->name }}</span>
                            <span class="block text-sm text-gray-500">Quantity: {{ $item->quantity }}</span>
                            <span class="block text-sm text-blue-600">${{ number_format($item->product->price, 2) }} each</span>
                        </div>
                    </div>
                    <span class="font-medium text-lg text-gray-900">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-gray-200 mt-6 pt-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="text-gray-900">${{ number_format($total, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Shipping</span>
                    <span class="text-gray-900">$0.00</span>
                </div>
                <div class="flex justify-between items-center font-bold text-lg border-t border-gray-200 mt-2 pt-2">
                    <span>Total</span>
                    <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.process') }}" class="space-y-6">
            @csrf
            <!-- Shipping Information -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Shipping Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="full_name" id="full_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" id="phone" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="address" id="address" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" id="city" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Payment Method
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="radio" name="payment_method" id="cod" value="cod" checked
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="cod" class="ml-3 block text-sm font-medium text-gray-700">
                            Cash on Delivery (COD)
                            <span class="block text-sm text-gray-500">Pay when you receive the package</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="bank_transfer" class="ml-3 block text-sm font-medium text-gray-700">
                            Bank Transfer
                            <span class="block text-sm text-gray-500">Bank details will be provided after order confirmation</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Place Order Button -->
            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-150 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Complete Order
                </button>
                <p class="text-center text-sm text-gray-500 mt-4">
                    By placing this order, you agree to our
                    <a href="#" class="text-blue-600 hover:text-blue-700">Terms of Service</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
