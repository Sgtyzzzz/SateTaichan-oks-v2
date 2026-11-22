<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PelangganController;
use App\Models\Menu;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. LANDING PAGE (PUBLIK)
// ==========================================
Route::get('/', function () {
    $favoriteMenus = Menu::where('is_available', true)->take(3)->get();
    return view('welcome', compact('favoriteMenus'));
})->name('home');


// ==========================================
// 2. AUTENTIKASI (LOGIN & REGISTER)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

    // Register Pelanggan (Email Real)
    Route::get('/register', fn () => view('auth.register'))->name('register');
    Route::post('/register/pelanggan', [AuthController::class, 'registerPelanggan'])->name('register.pelanggan');

    // Register Karyawan/Kasir (Email Bisnis)
    Route::get('/register/kasir', fn () => view('auth.register-kasir'))->name('register.kasir.form');
    Route::post('/register/kasir', [AuthController::class, 'registerKasir'])->name('register.kasir');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// ==========================================
// 3. VERIFIKASI EMAIL REAL / BISNIS
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return match ($request->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('kasir.dashboard'),
            default => redirect()->route('pelanggan.menu'),
        };
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi baru telah dikirim ke email Anda!');
    })->middleware('throttle:6,1')->name('verification.send');
});


// ==========================================
// 4. WEBHOOK MIDTRANS (ALL PAYMENT CALLBACK)
// ==========================================
Route::post('/midtrans/callback', [PelangganController::class, 'midtransCallback'])->name('midtrans.callback');


// ==========================================
// 5. ROLE: PELANGGAN (PILIH MENU, BOOKING MEJA, BAYAR)
// ==========================================
Route::middleware(['auth', 'verified', 'role:pelanggan'])->group(function () {
    Route::get('/menu', [PelangganController::class, 'index'])->name('pelanggan.menu');
    Route::post('/checkout', [PelangganController::class, 'checkout'])->name('pelanggan.checkout');
});


// ==========================================
// 6. ROLE: KARYAWAN / KASIR (KONFIRMASI PESANAN)
// ==========================================
Route::middleware(['auth', 'verified', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');
    Route::patch('/orders/{order}/status', [KasirController::class, 'updateStatus'])->name('orders.status');
});


// ==========================================
// 7. ROLE: ADMIN (CRUD MENU, MEJA, KEUANGAN & EXPORT EXCEL)
// ==========================================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/export-excel', [AdminController::class, 'exportExcel'])->name('export');

    // CRUD Menu Makanan
    Route::post('/menus', [AdminController::class, 'storeMenu'])->name('menus.store');
    Route::put('/menus/{menu}', [AdminController::class, 'updateMenu'])->name('menus.update');
    Route::delete('/menus/{menu}', [AdminController::class, 'destroyMenu'])->name('menus.destroy');

    // CRUD Meja
    Route::post('/tables', [AdminController::class, 'storeTable'])->name('tables.store');
    Route::put('/tables/{table}', [AdminController::class, 'updateTable'])->name('tables.update');
    Route::delete('/tables/{table}', [AdminController::class, 'destroyTable'])->name('tables.destroy');
});