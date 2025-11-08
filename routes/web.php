<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductSingle;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\WebAuthController;
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

//Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get("/dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/module', [ModuleController::class, 'module'])->name('module');
    Route::get('/module/edit/{id}', [ModuleController::class, 'edit_module'])->name("module.edit");
    Route::post('/module/{id}', [ModuleController::class, 'update'])->name('module.update');


    // Category Routes
    Route::get('/category', [CategoryController::class, 'category'])->name('category');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('/subcategories/{id}', [CategoryController::class, 'subcategories'])->name('category.subcategories');

    // Product Routes
    Route::get('/product', [ProductController::class, 'product'])->name('product');
    Route::get('/product/create', [ProductController::class, 'createProduct'])->name('product.create');
    Route::post('/product', [ProductController::class, 'storeProduct'])->name('product.store');
    Route::get('/product/{id}/edit', [ProductController::class, 'editProduct'])->name('product.edit');
    Route::put('/product/{id}', [ProductController::class, 'updateProduct'])->name('product.update');
    Route::delete('/product/{id}', [ProductController::class, 'destroyProduct'])->name('product.destroy');
    Route::put('/product/{id}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('product.toggle-featured');

    //Product Category Routes
    Route::get('/productCategory', [ProductCategoryController::class, 'productCategory'])->name('productCategory');
    Route::post('/productCategory/assign', [ProductCategoryController::class, 'assignCategory'])->name('productCategory.assign');
    Route::get('/product-categories/{productId}', [ProductCategoryController::class, 'getProductCategories'])->name('productCategory.get');
    Route::delete('/productCategory/{id}', [ProductCategoryController::class, 'removeAssignment'])->name('productCategory.remove');

});


// User Authentication Routes
Route::post('/user/login', [WebAuthController::class, 'login'])->name('user.login');
Route::post('/user/register', [WebAuthController::class, 'register'])->name('user.register');
Route::post('/user/logout', [WebAuthController::class, 'logout'])->name('user.logout');
