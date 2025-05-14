@extends('layouts.admin')

@section('header', 'Thêm Sản Phẩm Mới')

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700">Tên Sản Phẩm</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                @error('name') border-red-500 @enderror" required>
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Mô Tả</label>
            <textarea name="description" id="description" rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700">Giá (VNĐ)</label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                    @error('price') border-red-500 @enderror" required>
                @error('price')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Số Lượng Tồn Kho</label>
                <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                    @error('stock_quantity') border-red-500 @enderror" required>
                @error('stock_quantity')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Danh Mục</label>
            <select name="category_id" id="category_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                @error('category_id') border-red-500 @enderror" required>
                <option value="">Chọn danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="brand_id" class="block text-sm font-medium text-gray-700">Thương Hiệu</label>
            <select name="brand_id" id="brand_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                @error('brand_id') border-red-500 @enderror">
                <option value="">Chọn thương hiệu</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700">Hình Ảnh Sản Phẩm</label>
            <input type="file" name="image" id="image"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0 file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100
                @error('image') border-red-500 @enderror" required>
            <p class="mt-1 text-sm text-gray-500">Tải lên hình ảnh sản phẩm (JPEG, PNG, JPG, GIF tối đa 2MB)</p>
            @error('image')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-900">
                Quay Lại Danh Sách
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                Tạo Sản Phẩm
            </button>
        </div>
    </form>
</div>
@endsection
