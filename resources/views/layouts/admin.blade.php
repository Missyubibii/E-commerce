<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - E-commerce</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 py-6 flex-shrink-0">
            <div class="px-6">
                <h2 class="text-2xl font-semibold">Bảng điều khiển Admin</h2>
            </div>
            <nav class="mt-6">
                <div class="px-6 py-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-400 hover:text-white">
                        Bảng điều khiển
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                       class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-400 hover:text-white">
                        Sản phẩm
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-400 hover:text-white">
                        Danh mục
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                       class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-400 hover:text-white">
                        Đơn hàng
                    </a>
                    <a href="{{ route('admin.comparisons.index') }}"
                       class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 text-gray-400 hover:text-white">
                        So sánh
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="container mx-auto px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-xl font-semibold text-gray-700">
                                @yield('header', 'Dashboard')
                            </span>
                        </div>
                        <div class="flex items-center">
                            <div class="relative">
                                <button class="flex items-center text-gray-600 focus:outline-none">
                                    <span class="text-sm mr-3">{{ Auth::user()->name }}</span>
                                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                                            Đăng xuất
                                        </button>
                                    </form>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
