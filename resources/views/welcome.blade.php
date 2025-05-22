@extends('layouts.app')

@section('content')
    <!-- Banner -->
    <div class="mb-8 relative w-full max-w-6xl mx-auto">
        <div id="default-carousel" class="relative w-full" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-64 md:h-[30rem] overflow-hidden rounded-lg flex items-center justify-center">
                @php
                    $banners = \App\Models\Banner::where('is_active', true)->orderBy('position')->get();
                @endphp

                @if($banners->count() > 0)
                    @foreach($banners as $banner)
                        <div class="{{ $loop->first ? 'block' : 'hidden' }} duration-700 ease-in-out absolute inset-0 flex items-center justify-center" data-carousel-item>
                            <a href="{{ $banner->link ?? '#' }}" class="block">
                                <img
                                    src="{{ asset('img/banner/' . $banner->image) }}"
                                    alt="{{ $banner->title }}"
                                    class="w-full h-full object-cover rounded-lg"
                                />
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="text-gray-500 text-center">Không có banner nào</div>
                @endif
            </div>

            <!-- Slider indicators -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-3 z-30">
                @foreach($banners as $key => $banner)
                    <button
                        type="button"
                        class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600"
                        aria-current="{{ $key === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $key + 1 }}"
                        data-carousel-slide-to="{{ $key }}"
                    ></button>
                @endforeach
            </div>

            <!-- Slider controls -->
            <button
                type="button"
                class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev
            >
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 group-hover:bg-white/50 dark:bg-gray-800/30 dark:group-hover:bg-gray-800/60"
                >
                    <svg
                        class="w-5 h-5 text-white dark:text-gray-800"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </span>
                <span class="sr-only">Trước</span>
            </button>

            <button
                type="button"
                class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next
            >
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 group-hover:bg-white/50 dark:bg-gray-800/30 dark:group-hover:bg-gray-800/60"
                >
                    <svg
                        class="w-5 h-5 text-white dark:text-gray-800"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
                <span class="sr-only">Sau</span>
            </button>
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($products as $product)
                        <div class="border rounded-lg overflow-hidden shadow-md bg-white">
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover transition-transform duration-200 transform hover:scale-105" />
                            </a>
                            <div class="p-4">
                                <a href="{{ route('products.show', $product->slug) }}" class="block">
                                    <h2 class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors duration-200 mb-1">{{ $product->name }}</h2>
                                </a>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $product->description }}</p>
                                <p class="text-blue-600 font-bold mb-3">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('shop.comparison.add', $product->id) }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200" title="So sánh sản phẩm">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </a>
                                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors duration-200" title="Thêm/Xóa yêu thích">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        </button>
                                    </form>
                                    </div>
                                     <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                         @csrf
                                         <input type="hidden" name="quantity" value="1">
                                         <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition-colors duration-200">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"></circle>
                                                <circle cx="19" cy="21" r="1"></circle>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
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
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
