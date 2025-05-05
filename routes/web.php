<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ComparisonController as AdminComparisonController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shop\ReviewController;
use App\Http\Controllers\Shop\SearchController;
use App\Http\Controllers\Shop\WishlistController;
use App\Http\Controllers\Shop\ComparisonController;

// Public routes
Route::get('/', function () {
    $categories = \App\Models\Category::all();
    $products = \App\Models\Product::when(request('category'), function($query, $category) {
        return $query->whereHas('category', function($q) use ($category) {
            $q->where('slug', $category);
        });
    })->paginate(12);

    return view('welcome', compact('products', 'categories'));
})->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
    });

    // Authenticated admin routes
    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        Route::middleware(['admin'])->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

            // Resource routes
            Route::resource('categories', CategoryController::class);
            Route::resource('products', ProductController::class);
            Route::resource('orders', AdminOrderController::class);
            Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
                ->name('orders.update-status');

            // Comparison management
            Route::get('/comparisons', [AdminComparisonController::class, 'index'])->name('comparisons.index');
            Route::delete('/comparisons/{comparison}', [AdminComparisonController::class, 'destroy'])
                ->name('comparisons.destroy');
            Route::delete('/comparisons', [AdminComparisonController::class, 'clearAll'])
                ->name('comparisons.clear-all');
        });
    });
});

// User routes
Route::middleware('auth')->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.add');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'destroy'])->name('cart.remove');

    // Order routes
    Route::get('/orders', [ShopOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [ShopOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [ShopOrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{order}', [ShopOrderController::class, 'destroy'])->name('orders.destroy');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Review routes
    Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Search routes
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

    // Wishlist routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/products/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::delete('/wishlist', [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::post('/wishlist/move-to-cart', [WishlistController::class, 'moveAllToCart'])->name('wishlist.move-to-cart');

    // Comparison routes
    Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison.index');
    Route::post('/comparison/products/{product}', [ComparisonController::class, 'toggle'])->name('comparison.toggle');
    Route::delete('/comparison/{comparison}', [ComparisonController::class, 'destroy'])->name('comparison.destroy');
    Route::delete('/comparison', [ComparisonController::class, 'clear'])->name('comparison.clear');
    Route::post('/comparison/compare-now', [ComparisonController::class, 'compareNow'])->name('comparison.compare-now');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});
