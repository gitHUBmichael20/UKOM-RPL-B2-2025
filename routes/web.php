<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\SutradaraManagement;
use App\Http\Controllers\PaymentController;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\UserEdit;
use App\Livewire\Admin\StudioManagement;
use App\Livewire\Admin\StudioCreate;
use App\Livewire\Admin\StudioEdit;
use App\Livewire\Admin\FilmManagement;
use App\Livewire\Admin\GenreManagement;
use App\Livewire\Admin\FilmCreate;
use App\Livewire\Admin\FilmEdit;
use App\Livewire\Admin\HargaTiketCreate;
use App\Livewire\Admin\HargaTiketEdit;
use App\Livewire\Admin\HargaTiketManagement;
use App\Livewire\Admin\JadwalTayangManagement;
use App\Livewire\Admin\JadwalTayangCreate;
use App\Livewire\Admin\JadwalTayangEdit;
use App\Livewire\Admin\PemesananAdmin;
use App\Livewire\Kasir\PemesananKasir;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

// Dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

Route::get('/dashboard', function () {
    if (isRole('admin', 'kasir')) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::post('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('deletePhoto');
    });

    // Pemesanan user
    Route::prefix('pemesanan')->name('pemesanan.')->group(function () {
        // static route
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');

        // dynamic route
        Route::get('/{film}', [BookingController::class, 'show'])->name('show');
        Route::get('/{film}/schedule/{jadwalTayang}/seat', [BookingController::class, 'seatSelection'])->name('seats');
        Route::get('/{film}/schedule/{jadwalTayang}/payment', [BookingController::class, 'payment'])->name('payment');
        Route::post('/{film}/schedule/{jadwalTayang}/store', [BookingController::class, 'store'])->name('store');
        Route::get('/success/{pemesanan}', [BookingController::class, 'success'])->name('success');
        Route::get('/ticket/{pemesanan}', [BookingController::class, 'ticket'])->name('ticket');
    });

    // Payment
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/payment/{pemesanan}', [PaymentController::class, 'show'])->name('show');
        Route::post('/{pemesanan}/process', [PaymentController::class, 'process'])->name('process');
        Route::get('/{pemesanan}/success', [PaymentController::class, 'success'])->name('success');
        Route::post('/{pemesanan}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    });
});

// Admin & Kasir
Route::middleware(['auth', 'role:admin,kasir'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Studio
    Route::prefix('studio')->name('studio.')->group(function () {
        Route::get('/', StudioManagement::class)->name('index');
        Route::get('/create', StudioCreate::class)->name('create');
        Route::get('/{id}/edit', StudioEdit::class)->name('edit');
    });

    // Film
    Route::prefix('film')->name('film.')->group(function () {
        Route::get('/', FilmManagement::class)->name('index');
        Route::get('/create', FilmCreate::class)->name('create');
        Route::get('/{id}/edit', FilmEdit::class)->name('edit');
    });

    // Genre
    Route::get('/genre', GenreManagement::class)->name('genre.index');

    // Harga tiket
    Route::prefix('harga-tiket')->name('harga-tiket.')->group(function () {
        Route::get('/', HargaTiketManagement::class)->name('index');
        Route::get('/create', HargaTiketCreate::class)->name('create');
        Route::get('/edit/{id}', HargaTiketEdit::class)->name('edit');
    });

    // Jadwal tayang
    Route::prefix('jadwal-tayang')->name('jadwal-tayang.')->group(function () {
        Route::get('/', JadwalTayangManagement::class)->name('index');
        Route::get('/create', JadwalTayangCreate::class)->name('create');
        Route::get('/edit/{id}', JadwalTayangEdit::class)->name('edit');
    });
});

// Admin only
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', UserManagement::class)->name('index');
        Route::get('/create', UserCreate::class)->name('create');
        Route::get('/{id}/edit', UserEdit::class)->name('edit');
    });

    // Sutradara
    Route::prefix('sutradara')->name('sutradara.')->group(function () {
        Route::get('/', [SutradaraManagement::class, 'index'])->name('index');
        Route::get('/create', [SutradaraManagement::class, 'create'])->name('create');
        Route::post('/', [SutradaraManagement::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SutradaraManagement::class, 'edit'])->name('edit');
        Route::put('/{id}', [SutradaraManagement::class, 'update'])->name('update');
        Route::delete('/{id}', [SutradaraManagement::class, 'destroy'])->name('destroy');
    });

    Route::get('/pemesanan-admin', PemesananAdmin::class)->name('pemesanan.index');
});

// Kasir only
Route::middleware(['auth', 'role:kasir'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pemesanan-kasir', PemesananKasir::class)->name('kasir.pemesanan.index');
});
