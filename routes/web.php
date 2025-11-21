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

// Dashboard Routes
Route::get('/', function () {
    return app(FilmController::class)->index();
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard Routes
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin' || $user->role === 'kasir') {
            return redirect()->route('admin.dashboard');
        }

        return app(FilmController::class)->index();
    })->name('dashboard');

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::post('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('deletePhoto');
    });

    // Film Booking Routes
    Route::prefix('pemesanan')->name('pemesanan.')->group(function () {
        // FIX: Letakkan rute STATIC di atas rute DYNAMIC
        // My Bookings (STATIC - harus di atas)
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');

// Pemesanan Routes (User)
Route::middleware(['auth'])->prefix('pemesanan')->name('pemesanan.')->group(function () {
    Route::get('/{film}', [BookingController::class, 'show'])->name('show');
    Route::get('/{film}/schedule/{jadwalTayang}/seat', [BookingController::class, 'seatSelection'])->name('seats');
    Route::get('/{film}/schedule/{jadwalTayang}/payment', [BookingController::class, 'payment'])->name('payment');
    Route::post('/{film}/schedule/{jadwalTayang}/store', [BookingController::class, 'store'])->name('store');
    Route::get('/success/{pemesanan}', [BookingController::class, 'success'])->name('success');
    Route::get('/ticket/{pemesanan}', [BookingController::class, 'ticket'])->name('ticket');
});

// Payment Routes
Route::middleware(['auth'])->prefix('payment')->name('payment.')->group(function () {
    Route::get('/payment/{pemesanan}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/{pemesanan}/process', [PaymentController::class, 'process'])->name('process');
    Route::get('/{pemesanan}/success', [PaymentController::class, 'success'])->name('success');
    Route::post('/{pemesanan}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
});

// Admin & Kasir Routes (Shared)
Route::middleware(['auth', 'role:admin,kasir'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::prefix('studio')->name('studio.')->group(function () {
        Route::get('/', StudioManagement::class)->name('index');
        Route::get('/create', StudioCreate::class)->name('create');
        Route::get('/{id}/edit', StudioEdit::class)->name('edit');
    });

    // Film Management (Admin & Kasir)
    Route::prefix('film')->name('film.')->group(function () {
        Route::get('/', FilmManagement::class)->name('index');
        Route::get('/create', FilmCreate::class)->name('create');
        Route::get('/{id}/edit', FilmEdit::class)->name('edit');
    });

    // Genre Management (Admin & Kasir)
    Route::get('/genre', GenreManagement::class)->name('genre.index');

    // Harga Tiket Management (Admin & Kasir)
    Route::prefix('harga-tiket')->name('harga-tiket.')->group(function () {
        Route::get('/', HargaTiketManagement::class)->name('index');
        Route::get('/create', HargaTiketCreate::class)->name('create');
        Route::get('/edit/{id}', HargaTiketEdit::class)->name('edit');
    });

    Route::prefix('jadwal-tayang')->name('jadwal-tayang.')->group(function () {
        Route::get('/', JadwalTayangManagement::class)->name('index');
        Route::get('/create', JadwalTayangCreate::class)->name('create');
        Route::get('/edit/{id}', JadwalTayangEdit::class)->name('edit');
    });
});

// Admin Only Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', UserManagement::class)->name('index');
        Route::get('/create', UserCreate::class)->name('create');
        Route::get('/{id}/edit', UserEdit::class)->name('edit');
    });

    // Sutradara Management (Admin Only)
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

// Kasir Routes
Route::middleware(['auth', 'role:kasir'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pemesanan-kasir', PemesananKasir::class)->name('kasir.pemesanan.index');
});

require __DIR__ . '/auth.php';