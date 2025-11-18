<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\UserEdit;
use App\Livewire\Admin\StudioManagement;
use App\Livewire\Admin\StudioCreate;
use App\Livewire\Admin\StudioEdit;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\FilmManagement;
use App\Livewire\Admin\GenreManagement;
use App\Http\Controllers\SutradaraManagement;
use App\Livewire\Admin\FilmCreate;
use App\Livewire\Admin\FilmEdit;
use App\Livewire\Admin\HargaTiketCreate;
use App\Livewire\Admin\HargaTiketEdit;
use App\Livewire\Admin\HargaTiketManagement;

Route::get('/', function () {
    $user = auth()->user();

    if ($user->role === 'admin' || $user->role === 'kasir') {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin' || $user->role === 'kasir') {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.deletePhoto');

    Route::get('/admin/harga-tiket', HargaTiketManagement::class)
        ->name('admin.harga-tiket.index');  
});

// Admin & Kasir Routes
Route::middleware(['auth', 'role:admin,kasir'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Studio Routes
    Route::get('/studio', StudioManagement::class)->name('studio.index');
    Route::get('/studio/create', StudioCreate::class)->name('studio.create');
    Route::get('/studio/{id}/edit', StudioEdit::class)->name('studio.edit');

    // Film Routes
    Route::get('/film', FilmManagement::class)->name('film.index');
    Route::get('/film/create', FilmCreate::class)->name('film.create');
    Route::get('/film/{id}/edit', FilmEdit::class)->name('film.edit');

    // Genre Routes
    Route::get('/genre', GenreManagement::class)->name('genre.index');
});

// Admin Only Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Users Routes
    Route::get('/users', UserManagement::class)->name('users.index');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::get('/users/{id}/edit', UserEdit::class)->name('users.edit');

    // Sutradara Routes
    Route::get('/sutradara', [SutradaraManagement::class, 'index'])->name('sutradara.index');
    Route::get('/sutradara/create', [SutradaraManagement::class, 'create'])->name('sutradara.create');
    Route::post('/sutradara', [SutradaraManagement::class, 'store'])->name('sutradara.store');
    Route::get('/sutradara/{id}/edit', [SutradaraManagement::class, 'edit'])->name('sutradara.edit');
    Route::put('/sutradara/{id}', [SutradaraManagement::class, 'update'])->name('sutradara.update');
    Route::delete('/sutradara/{id}', [SutradaraManagement::class, 'destroy'])->name('sutradara.destroy');

    // Harga Tiket Routes
    Route::get('/create', HargaTiketCreate::class)->name('harga-tiket.create');
    Route::get('/edit/{id}', HargaTiketEdit::class)->name('harga-tiket.edit');
});

require __DIR__ . '/auth.php';
