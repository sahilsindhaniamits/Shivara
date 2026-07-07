<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\AccountController;
use App\Http\Controllers\Storefront\BlogController as StorefrontBlogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

/*
|--------------------------------------------------------------------------
| Storefront Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{slug}/review', [ProductController::class, 'storeReview'])->name('products.review')->middleware('auth');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/blog', [StorefrontBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [StorefrontBlogController::class, 'show'])->name('blog.show');

// Cart (works for guests and authenticated users)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Checkout & Payment
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/payment/verify', [CheckoutController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/order/success/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');

    // My Account
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{orderNumber}', [AccountController::class, 'orderDetail'])->name('orders.show');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');
        Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
        Route::delete('/addresses/{address}', [AccountController::class, 'deleteAddress'])->name('addresses.destroy');
        Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
        Route::post('/wishlist', [AccountController::class, 'addToWishlist'])->name('wishlist.add');
        Route::delete('/wishlist/{wishlistItem}', [AccountController::class, 'removeFromWishlist'])->name('wishlist.remove');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', AdminProductController::class);
    Route::post('products/bulk', [AdminProductController::class, 'bulk'])->name('products.bulk');
    Route::delete('products/{product}/image/{image}', [AdminProductController::class, 'deleteImage'])->name('products.deleteImage');
    Route::delete('products/{product}/banner/{index}', [AdminProductController::class, 'deleteBanner'])->name('products.deleteBanner');
    Route::delete('products/{product}/variant/{variant}', [AdminProductController::class, 'deleteVariant'])->name('products.deleteVariant');
    Route::post('products/{product}/attributes', [AdminProductController::class, 'storeAttribute'])->name('products.storeAttribute');
    Route::delete('products/{product}/attribute/{attribute}', [AdminProductController::class, 'deleteAttribute'])->name('products.deleteAttribute');
    Route::delete('products/{product}/attribute-value/{attributeValue}', [AdminProductController::class, 'deleteAttributeValue'])->name('products.deleteAttributeValue');

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/bulk-status', [AdminOrderController::class, 'bulkStatus'])->name('orders.bulkStatus');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);

    // Banners
    Route::resource('banners', AdminBannerController::class);

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggleStatus');

    // Free Gift
    Route::get('/free-gift', [\App\Http\Controllers\Admin\FreeGiftController::class, 'index'])->name('free-gift.index');
    Route::post('/free-gift', [\App\Http\Controllers\Admin\FreeGiftController::class, 'update'])->name('free-gift.update');

    // Reviews
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'decline'])->name('reviews.decline');

    // Blog
    Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);

    // Reports & Analytics
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue-chart', [AdminReportController::class, 'revenueChart'])->name('reports.revenueChart');
});
