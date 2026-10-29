<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('cashier.dashboard'),
            'siswa' => redirect()->route('student.dashboard'),
            default => abort(403),
        };
    }

    return app(StudentController::class)->index(request());
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::get('/logout', function () {
    return redirect()->route('home')->with('warning', 'Gunakan tombol Keluar untuk mengakhiri sesi.');
})->middleware('auth')->name('logout.notice');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/menu', [AdminController::class, 'storeMenu'])->name('menu.store');
    Route::put('/menu/{menu}', [AdminController::class, 'updateMenu'])->name('menu.update');
    Route::delete('/menu/{menu}', [AdminController::class, 'destroyMenu'])->name('menu.destroy');
    Route::patch('/pesanan/{pesanan}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update');
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('student.')->group(function (): void {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
    Route::get('/pembayaran', [StudentController::class, 'payment'])->name('payment');
    Route::post('/keranjang', [StudentController::class, 'addToCart'])->name('cart.add');
    Route::patch('/keranjang/{menu}', [StudentController::class, 'updateCart'])->name('cart.update');
    Route::delete('/keranjang/{menu}', [StudentController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/checkout', [StudentController::class, 'checkout'])->name('checkout');
});

Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('cashier.')->group(function (): void {
    Route::get('/dashboard', [CashierController::class, 'index'])->name('dashboard');
    Route::patch('/pesanan/{pesanan}/status', [CashierController::class, 'updateOrderStatus'])->name('orders.update');
});
