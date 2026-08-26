<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\MotorcycleController as AdminMotorcycleController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\MotorcycleController as SellerMotorcycleController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/motorcycles', [MotorcycleController::class, 'index'])->name('motorcycles.index');
Route::get('/motorcycles/{motorcycle}', [MotorcycleController::class, 'show'])
    ->whereNumber('motorcycle')->name('motorcycles.show');

Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brands.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated (all roles)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{motorcycle}/toggle', [FavoriteController::class, 'toggle'])
        ->name('favorites.toggle');

    /*
    |----------------------------------------------------------------------
    | Orders (creation disabled)
    |----------------------------------------------------------------------
    | Customers can no longer place orders; they contact sellers directly
    | via Telegram on the motorcycle detail page. Existing routes remain
    | so historical orders stay viewable/cancellable.
    */
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Contact a seller about a specific motorcycle
    Route::post('/motorcycles/{motorcycle}/contact-seller', [ContactController::class, 'contactSeller'])
        ->name('motorcycles.contact-seller');

    /*
    |----------------------------------------------------------------------
    | Seller area (sellers + admins)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:seller')->prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [SellerController::class, 'orders'])->name('orders');
        Route::patch('/orders/{order}/status', [SellerController::class, 'updateOrderStatus'])->name('orders.status');
        Route::get('/messages', [SellerController::class, 'messages'])->name('messages');
        Route::patch('/messages/{contact}/read', [SellerController::class, 'markMessageRead'])->name('messages.read');

        Route::get('/motorcycles', [SellerMotorcycleController::class, 'index'])->name('motorcycles.index');
        Route::get('/motorcycles/create', [SellerMotorcycleController::class, 'create'])->name('motorcycles.create');
        Route::post('/motorcycles', [SellerMotorcycleController::class, 'store'])->name('motorcycles.store');
        Route::get('/motorcycles/{motorcycle}/edit', [SellerMotorcycleController::class, 'edit'])
            ->name('motorcycles.edit');
        Route::put('/motorcycles/{motorcycle}', [SellerMotorcycleController::class, 'update'])
            ->name('motorcycles.update');
        Route::delete('/motorcycles/{motorcycle}', [SellerMotorcycleController::class, 'destroy'])
            ->name('motorcycles.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Admin area
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users management
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Motorcycles moderation
        Route::get('/motorcycles', [AdminMotorcycleController::class, 'index'])->name('motorcycles.index');
        Route::get('/motorcycles/{motorcycle}/edit', [AdminMotorcycleController::class, 'edit'])->name('motorcycles.edit');
        Route::put('/motorcycles/{motorcycle}', [AdminMotorcycleController::class, 'update'])->name('motorcycles.update');
        Route::patch('/motorcycles/{motorcycle}/status', [AdminMotorcycleController::class, 'setStatus'])
            ->name('motorcycles.status');
        Route::delete('/motorcycles/{motorcycle}', [AdminMotorcycleController::class, 'destroy'])
            ->name('motorcycles.destroy');

        // Brands CRUD
        Route::resource('brands', AdminBrandController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

        // Categories CRUD
        Route::resource('categories', AdminCategoryController::class)
            ->only(['index', 'store', 'edit', 'update', 'destroy']);

        // Orders management
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');

        // Contact messages
        Route::get('/messages', [AdminContactController::class, 'index'])->name('messages.index');
        Route::patch('/messages/{contact}/read', [AdminContactController::class, 'markRead'])->name('messages.read');
        Route::delete('/messages/{contact}', [AdminContactController::class, 'destroy'])->name('messages.destroy');
    });
});
