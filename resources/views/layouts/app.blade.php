<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel E-Commerce') }} - @yield('title')</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-1">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <span class="text-2xl font-bold text-white hover:text-blue-100 transition-all duration-150">{{ config('app.name', 'Laravel E-Commerce') }}</span>
                    </a>
                </div>

                <!-- Main Navigation -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                    <a href="{{ route('home') }}" class="text-blue-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 hover:bg-blue-500">Home</a>

                    <!-- Search Bar -->
                    <div class="relative ml-4" x-data="{ query: '' }">
                        <form action="{{ route('search') }}" method="GET" class="flex items-center">
                            <input type="text" name="q" x-model="query"
                                class="w-64 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900"
                                placeholder="Search products..."
                                @input.debounce.300ms="
                                    if (query.length >= 2) {
                                        fetch(`/search/suggestions?q=${query}`)
                                            .then(res => res.json())
                                            .then(data => {
                                                $refs.suggestions.innerHTML = data.map(item =>
                                                    `<a href='${item.url}' class='block px-4 py-2 hover:bg-gray-100'>${item.highlighted}</a>`
                                                ).join('');
                                                if (data.length > 0) {
                                                    $refs.suggestions.classList.remove('hidden');
                                                } else {
                                                    $refs.suggestions.classList.add('hidden');
                                                }
                                            });
                                    } else {
                                        $refs.suggestions.classList.add('hidden');
                                    }
                                "
                                @click.away="$refs.suggestions.classList.add('hidden')"
                            >
                            <button type="submit" class="ml-2 p-2 text-blue-100 hover:text-white rounded-full hover:bg-blue-500 transition duration-150">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                        <div x-ref="suggestions" class="absolute z-50 w-full mt-1 bg-white rounded-md shadow-lg hidden"></div>
                    </div>

                    <!-- Categories Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-blue-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 hover:bg-blue-500 inline-flex items-center">
                            Categories
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                @foreach($categories ?? [] as $category)
                                    <a href="{{ route('home', ['category' => $category->slug]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Navigation -->
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    @auth
                        <!-- Comparison -->
                        <a href="{{ route('comparison.index') }}" class="relative text-blue-100 hover:text-white p-2 rounded-full hover:bg-blue-500 transition duration-150">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            @if(auth()->check() && auth()->user()->comparisons()->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ auth()->user()->comparisons()->count() }}
                                </span>
                            @endif
                        </a>

                        <!-- Wishlist -->
                        <a href="{{ route('wishlist.index') }}" class="relative text-blue-100 hover:text-white p-2 rounded-full hover:bg-blue-500 transition duration-150">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            @if(auth()->check() && auth()->user()->wishlists()->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ auth()->user()->wishlists()->count() }}
                                </span>
                            @endif
                        </a>

                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" class="relative text-blue-100 hover:text-white p-2 rounded-full hover:bg-blue-500 transition duration-150">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if(isset($cartItemCount) && $cartItemCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $cartItemCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center">
                                <img class="h-8 w-8 rounded-full object-cover border-2 border-white hover:border-blue-300 transition duration-150"
                                    src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                    alt="{{ auth()->user()->name }}">
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-xs text-gray-400">{{ auth()->user()->name }}</div>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Account Settings</a>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-100 hover:text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 hover:bg-blue-500">Login</a>
                        <a href="{{ route('register') }}" class="text-blue-100 hover:text-white ml-4 px-4 py-2 rounded-md text-sm font-medium bg-blue-500 hover:bg-blue-600 transition duration-150">Register</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="sm:hidden flex items-center">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-blue-100 hover:text-white hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-300">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="pt-2 pb-3">
                <a href="{{ route('home') }}" class="block p-2 text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">Home</a>

                <!-- Mobile Categories -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex justify-between w-full p-2 text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">
                        <span>Categories</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="px-4 py-2 space-y-1 bg-blue-700">
                        @foreach($categories ?? [] as $category)
                            <a href="{{ route('home', ['category' => $category->slug]) }}"
                                class="block py-2 text-sm text-blue-100 hover:text-white hover:bg-blue-600 rounded">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @auth
                    <!-- Mobile User Menu -->
                    <div class="border-t border-blue-700 mt-2 pt-2">
                        <a href="{{ route('profile.edit') }}" class="block p-2 text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">
                            Account Settings
                        </a>
                        <a href="{{ route('orders.index') }}" class="block p-2 text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">
                            My Orders
                        </a>
                        <a href="{{ route('cart.index') }}" class="block p-2 text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">
                            Shopping Cart
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left p-2 text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-blue-700 mt-2 pt-2">
                        <a href="{{ route('login') }}" class="block p-2 text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">Login</a>
                        <a href="{{ route('register') }}" class="block p-2 text-base font-medium text-blue-100 hover:text-white hover:bg-blue-500">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 p-4 rounded-r-lg shadow-md transform transition-all duration-500 hover:scale-102" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm leading-5 text-green-700">
                            <strong class="font-bold">Success!</strong>
                            <span class="ml-1">{{ session('success') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 p-4 rounded-r-lg shadow-md transform transition-all duration-500 hover:scale-102" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm leading-5 text-red-700">
                            <strong class="font-bold">Error!</strong>
                            <span class="ml-1">{{ session('error') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="transition-all duration-300">
            @yield('content')
        </div>
    </main>
    @vite(['resources/js/app.js'])
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.querySelector('.mobile-menu-button');
            const menu = document.querySelector('#mobile-menu');

            button.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
