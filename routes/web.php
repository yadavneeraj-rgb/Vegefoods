<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductSingle;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\WhislistController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('', 'home')->name('home');
});

Route::get('/shop', [ShopController::class, 'shop'])->name('shop');

Route::get('/wishlist', [WhislistController::class, 'wishlist'])->name('wishlist');

Route::get('/product-single', [ProductSingle::class, 'productSingle'])->name('productSingle');

Route::get('/cart', [CartController::class, 'cart'])->name('cart');

Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

Route::get('/about', [AboutController::class, 'about'])->name('about');

Route::get('/blog', [BlogController::class, 'blog'])->name('blog');

Route::get('/contact', [ContactController::class, 'contact'])->name('contact');

Route::get("/dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');