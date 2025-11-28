<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\SutradaraManagement;
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
use App\Livewire\Kasir\RedeemTiket;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Livewire\Admin\Laporan\Index as LaporanIndex;


Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.webhook');

require __DIR__ . '/auth.php';

// Dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

Route::get('/dashboard', function () {
    if (isRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (isRole('kasir')) {
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
});

// Pemesanan user
Route::middleware(['auth', 'role:pelanggan'])->prefix('pemesanan')->name('pemesanan.')->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');

    Route::get('/{film}', [BookingController::class, 'show'])->name('show');
    Route::get('/{film}/schedule/{jadwalTayang}/seat', [BookingController::class, 'seatSelection'])->name('seats');
    Route::get('/{film}/schedule/{jadwalTayang}/payment', [BookingController::class, 'payment'])->name('payment');
    Route::post('/{film}/schedule/{jadwalTayang}/payment', [BookingController::class, 'payment'])->name('payment.post');

    Route::post('/{film}/schedule/{jadwalTayang}/store', [BookingController::class, 'store'])->name('store');

    Route::get('/success/{pemesanan}', [BookingController::class, 'success'])->name('success');
    Route::get('/ticket/{pemesanan}', [BookingController::class, 'ticket'])->name('ticket');

    Route::post('/cancel/{pemesanan}', [BookingController::class, 'cancel'])
        ->name('cancel');
});

// Admin & Kasir
Route::middleware(['auth', 'role:admin,kasir'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



    // Studio
    Route::prefix('studio')->name('studio.')->group(function () {
        Route::get('/', StudioManagement::class)->name('index');
        Route::get('/create', StudioCreate::class)->name('create')->middleware('role:admin');
        Route::get('/{id}/edit', StudioEdit::class)->name('edit')->middleware('role:admin');
    });

    // Film
    Route::prefix('film')->name('film.')->group(function () {
        Route::get('/', FilmManagement::class)->name('index');
        Route::get('/create', FilmCreate::class)->name('create')->middleware('role:admin');
        Route::get('/{id}/edit', FilmEdit::class)->name('edit')->middleware('role:admin');
    });

    // Genre
    Route::get('/genre', GenreManagement::class)->name('genre.index');

    // Harga tiket
    Route::prefix('harga-tiket')->name('harga-tiket.')->group(function () {
        Route::get('/', HargaTiketManagement::class)->name('index');
        Route::get('/create', HargaTiketCreate::class)->name('create')->middleware('role:admin');
        Route::get('/edit/{id}', HargaTiketEdit::class)->name('edit')->middleware('role:admin');
    });

    // Jadwal tayang
    Route::prefix('jadwal-tayang')->name('jadwal-tayang.')->group(function () {
        Route::get('/', JadwalTayangManagement::class)->name('index');
        Route::get('/create', JadwalTayangCreate::class)->name('create')->middleware('role:admin');
        Route::get('/edit/{id}', JadwalTayangEdit::class)->name('edit')->middleware('role:admin');
    });
});

// Admin only
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', UserManagement::class)->name('index');
        Route::get('/create', UserCreate::class)->name('create');
        Route::get('/{id}/edit', UserEdit::class)->name('edit');
    });

    Route::prefix('sutradara')->name('sutradara.')->group(function () {
        Route::get('/create', [SutradaraManagement::class, 'create'])->name('create');
        Route::post('/', [SutradaraManagement::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SutradaraManagement::class, 'edit'])->name('edit');
        Route::patch('/{id}', [SutradaraManagement::class, 'update'])->name('update');
        Route::delete('/{id}', [SutradaraManagement::class, 'destroy'])->name('destroy');
    });

    Route::get('/pemesanan-admin', PemesananAdmin::class)->name('pemesanan.index');
});

// Kasir only
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('admin.')->group(function () {
    Route::get('/pemesanan-kasir', PemesananKasir::class)->name('kasir.pemesanan.index');

    Route::get('/redeem', RedeemTiket::class)->name('kasir.redeem.index');
});


Route::middleware(['auth', 'role:admin,kasir'])->prefix('admin')->name('admin.')->group(function () {

    Route::prefix('sutradara')->name('sutradara.')->group(function () {
        Route::get('/', [SutradaraManagement::class, 'index'])->name('index');
    });
});



Route::get('/admin/laporan', LaporanIndex::class)->name('admin.laporan.index');

Route::get('/admin/laporan/export-penjualan', [LaporanController::class, 'exportPenjualan'])
    ->name('admin.laporan.export-penjualan');

Route::get('/admin/laporan/export-transaksi', [LaporanController::class, 'exportTransaksi'])
    ->name('admin.laporan.export-transaksi');
