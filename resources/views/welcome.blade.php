@extends('layouts.app')

@section('content')
<!-- Banner -->
<div class="mb-8">
    <div class="swiper mySwiper rounded-lg overflow-hidden">
        <div class="swiper-wrapper">
            @foreach(\App\Models\Banner::where('is_active', true)->orderBy('position')->get() as $banner)
                <div class="swiper-slide">
                    <a href="{{ $banner->link }}" class="block">
                        <img src="{{ asset('img/banner/' . $banner->image) }}"
                             alt="{{ $banner->title }}"
                             class="w-full h-48 md:h-[30rem] object-cover">
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Navigation + Pagination -->
        <div class="swiper-button-prev text-white"></div>
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-pagination"></div>
    </div>
</div>

    <!-- Bộ lọc + Sản phẩm -->
    <div class="w-full px-4 md:px-12 pb-12">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Bộ lọc -->
            <form action="/" method="GET" class="w-full md:w-1/4 bg-white p-6 rounded-lg border shadow-sm">
                <h2 class="text-xl font-semibold mb-4">Lọc sản phẩm</h2>
                <div class="space-y-4">
                    <!-- Danh mục -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Danh mục:</label>
                        <select name="category" id="category" class="w-full border rounded px-3 py-2 mt-1">
                            <option value="">Tất cả</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Thương hiệu -->
                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700">Thương hiệu:</label>
                        <select name="brand_id" id="brand_id" class="w-full border rounded px-3 py-2 mt-1">
                            <option value="">Tất cả</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Giá -->
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-gray-700">Giá từ:</label>
                        <input type="number" name="min_price" id="min_price" class="w-full border rounded px-3 py-2 mt-1" value="{{ request('min_price') }}">
                    </div>
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-gray-700">Đến:</label>
                        <input type="number" name="max_price" id="max_price" class="w-full border rounded px-3 py-2 mt-1" value="{{ request('max_price') }}">
                    </div>

                    <!-- Tình trạng -->
                    <div>
                        <label for="stock_status" class="block text-sm font-medium text-gray-700">Tình trạng:</label>
                        <select name="stock_status" id="stock_status" class="w-full border rounded px-3 py-2 mt-1">
                            <option value="">Tất cả</option>
                            <option value="instock" {{ request('stock_status') == 'instock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="outofstock" {{ request('stock_status') == 'outofstock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded">Lọc</button>
                    </div>
                </div>
            </form>

            <!-- Danh sách sản phẩm -->
            <div class="w-full md:w-3/4">
                <h1 class="text-2xl font-bold mb-4">Sản phẩm</h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($products as $product)
                        <div class="border rounded-lg overflow-hidden shadow-sm bg-white">
                            <a href="/products/{{ $product->slug }}" class="block">
                                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-contain" />
                            </a>
                            <div class="p-4">
                                <a href="/products/{{ $product->slug }}" class="block">
                                    <h2 class="text-lg font-semibold mb-1">{{ $product->name }}</h2>
                                </a>
                                <p class="text-sm text-gray-600 mb-2">{{ \Str::limit($product->description, 60) }}</p>
                                 <p class="text-blue-600 font-bold mb-3">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                                <div class="flex items-center justify-between">
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
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.115 1.152-.52 2.233-1.677 2.233H5.054a1.125 1.125 0 01-1.125-1.125l1.256-12c.115-1.152.52-2.233 1.677-2.233h9.292m0 0l4.263 14.212M16.5 6.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Không có sản phẩm nào phù hợp.</p>
                    @endforelse
                </div>

                <!-- Phân trang -->
                <div class="mt-6">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
        const swiper = new Swiper('.mySwiper', {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
@endsection
