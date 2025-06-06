@extends('layouts.app')

@section('title', 'Danh sách đơn hàng')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-bold text-gray-900">Danh sách đơn hàng</h1>
        </div>

        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->user->name }}<br>
                                <span class="text-xs text-gray-400">{{ $order->user->email }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($order->status === 'completed')
                                        Hoàn thành
                                    @elseif($order->status === 'processing')
                                        Đang xử lý
                                    @elseif($order->status === 'cancelled')
                                        Đã hủy
                                    @else
                                        Đang chờ xử lý
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Xem</a>
                                @if($order->status === 'pending')
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="processing">
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                            Xử lý
                                        </button>
                                    </form>
                                @endif
                                @if($order->status === 'processing')
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                            Hoàn thành
                                        </button>
                                    </form>
                                @endif
                                @if($order->canBeCancelled())
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                            Hủy bỏ
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
