@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Danh sách yêu thích của tôi</h1>

    @if($wishlists->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <p class="mt-4 text-lg text-gray-600">Danh sách yêu thích của bạn đang trống.</p>
            <a href="{{ route('home') }}" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($wishlists as $wishlist)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <a href="{{ route('products.show', $wishlist->product->slug) }}">
                        <img src="{{ asset('storage/products/' . $wishlist->product->image) }}" alt="{{ $wishlist->product->name }}" class="w-full h-48 object-contain">
                    </a>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('products.show', $wishlist->product->slug) }}" class="hover:text-blue-600 transition-colors duration-200">
                                 {{ $wishlist->product->name }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">{{ \Str::limit($wishlist->product->description, 60) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 font-bold">{{ number_format($wishlist->product->price, 0, ',', '.') }} VNĐ</span>
                            <form action="{{ route('wishlist.toggle', $wishlist->product) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $wishlists->links() }}
        </div>
    @endif
</div>
@endsection
