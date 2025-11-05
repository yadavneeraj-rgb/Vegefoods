<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CreateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
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

// Public Routes
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

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get("/dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');
    
    // Category Routes
    Route::get('/category', [CategoryController::class, 'category'])->name('category');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    
    // Add these new routes for edit/update
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('/subcategories/{id}', [CategoryController::class, 'subcategories'])->name('category.subcategories');
});