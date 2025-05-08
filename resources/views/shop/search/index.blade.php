@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Bộ lọc</h2>
                <form action="{{ route('search') }}" method="GET" class="space-y-6">
                    @if($query)
                        <input type="hidden" name="q" value="{{ $query }}">
                    @endif

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input type="radio" name="category" value="{{ $category->id }}"
                                        {{ $categoryId == $category->id ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <label class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Khoảng giá</label>
                        <div class="flex items-center space-x-2">
                            <input type="number" name="min_price" value="{{ $minPrice }}" min="0"
                                placeholder="Tối thiểu"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <span class="text-gray-500">đến</span>
                            <input type="number" name="max_price" value="{{ $maxPrice }}" min="0"
                                placeholder="Tối đa"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sắp xếp theo</label>
                        <select name="sort"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="default" {{ $sort == 'default' ? 'selected' : '' }}>Mặc định</option>
                            <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>Đánh giá trung bình</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-150">
                        Áp dụng bộ lọc
                    </button>
                </form>
            </div>
        </div>

        <!-- Results -->
        <div class="lg:w-3/4">
            @if($query)
                <h2 class="text-2xl font-bold mb-6">Kết quả tìm kiếm cho "{{ $query }}"</h2>
            @endif

            @if($products->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Không tìm thấy sản phẩm</h3>
                    <p class="mt-2 text-gray-500">Hãy thử thay đổi từ khóa hoặc bộ lọc để tìm sản phẩm phù hợp hơn.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            @if($product->image)
                                <img src="{{ Storage::url('products/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover">
                            @endif
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</p>

                                <!-- Rating -->
                                <div class="flex items-center mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="text-sm text-gray-500 ml-1">({{ $product->review_count }})</span>
                                </div>

                                <div class="mt-4 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold text-gray-900">{{ number_format($product->price, 2) }}₫</span>
                                        <div class="flex space-x-2">
                                            <!-- Comparison Toggle -->
                                            <form action="{{ route('comparison.toggle', $product) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 {{ auth()->check() && $product->isInComparison(auth()->id()) ? 'text-blue-600 hover:text-blue-700' : 'text-gray-400 hover:text-blue-600' }} transition-colors duration-150">
                                                    <svg class="w-6 h-6" fill="{{ auth()->check() && $product->isInComparison(auth()->id()) ? 'currentColor' : 'none' }}"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Wishlist Toggle -->
                                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 {{ auth()->check() && auth()->user()->hasWishlisted($product) ? 'text-red-600 hover:text-red-700' : 'text-gray-400 hover:text-red-600' }} transition-colors duration-150">
                                                    <svg class="w-6 h-6" fill="{{ auth()->check() && auth()->user()->hasWishlisted($product) ? 'currentColor' : 'none' }}"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 21c-4.973 0-9-4.029-9-9s4.027-9 9-9 9 4.029 9 9-4.027 9-9 9zm0-16a7 7 0 100 14 7 7 0 000-14z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-150">
                                        Thêm vào giỏ hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
