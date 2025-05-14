@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Sửa quảng cáo</h1>
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Tiêu đề:</label>
                <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $banner->title }}">
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Hình ảnh:</label>
                <select name="image" id="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @foreach(array_diff(scandir(public_path('img/banner')), array('.', '..')) as $image)
                        <option value="{{ $image }}" {{ $banner->image == $image ? 'selected' : '' }}>{{ $image }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="link" class="block text-gray-700 text-sm font-bold mb-2">Liên kết:</label>
                <input type="text" name="link" id="link" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $banner->link }}">
            </div>
            <div class="mb-4">
                <label for="is_active" class="block text-gray-700 text-sm font-bold mb-2">Trạng thái:</label>
                <select name="is_active" id="is_active" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>Không kích hoạt</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="position" class="block text-gray-700 text-sm font-bold mb-2">Vị trí:</label>
                <input type="number" name="position" id="position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $banner->position }}">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Cập nhật
                </button>
                <a href="{{ route('admin.banners.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Hủy
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function selectImage(image) {
            document.getElementById('selected_image').value = image;
        }
    </script>
@endsection
