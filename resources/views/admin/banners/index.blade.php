@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Quản lý quảng cáo</h1>
        <div class="mb-4">
            <a href="{{ route('admin.banners.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Thêm quảng cáo
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Tiêu đề</th>
                        <th class="px-4 py-2 text-left">Hình ảnh</th>
                        <th class="px-4 py-2 text-left">Liên kết</th>
                        <th class="px-4 py-2 text-left">Trạng thái</th>
                        <th class="px-4 py-2 text-left">Vị trí</th>
                        <th class="px-4 py-2 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                        <tr>
                            <td class="border px-4 py-2">{{ $banner->id }}</td>
                            <td class="border px-4 py-2">{{ $banner->title }}</td>
                            <td class="border px-4 py-2">
                                @if($banner->image)
                                    <img src="{{ asset('img/banner/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-20 h-20 object-fit-cover">
                                @else
                                    Không có hình ảnh
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $banner->link }}</td>
                            <td class="border px-4 py-2">{{ $banner->is_active ? 'Kích hoạt' : 'Không kích hoạt' }}</td>
                            <td class="border px-4 py-2">{{ $banner->position }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Sửa</a>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
