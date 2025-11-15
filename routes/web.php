<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\UserCreate;
use App\Livewire\Admin\UserEdit;
use App\Livewire\Admin\StudioManagement;
use App\Livewire\Admin\StudioCreate;
use App\Livewire\Admin\StudioEdit;
// use App\Livewire\Admin\SutradaraManagement;
use Illuminate\Support\Facades\Route;

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
});

// Admin & Kasir
Route::middleware(['auth', 'role:admin,kasir'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Studio Routes (accessible by both admin & kasir)
    Route::get('/studio', StudioManagement::class)->name('studio.index');
    Route::get('/studio/create', StudioCreate::class)->name('studio.create');
    Route::get('/studio/{id}/edit', StudioEdit::class)->name('studio.edit');
});

// Admin Only Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Users Routes
    Route::get('/users', UserManagement::class)->name('users.index');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::get('/users/{id}/edit', UserEdit::class)->name('users.edit');
});

use App\Http\Controllers\SutradaraManagement;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/sutradara', [SutradaraManagement::class, 'index'])
            ->name('sutradara.index');
        Route::get('/sutradara/create', [SutradaraManagement::class, 'create'])->name('sutradara.create');
        Route::post('/sutradara', [SutradaraManagement::class, 'store'])->name('sutradara.store');
        Route::get('/sutradara/{id}/edit', [SutradaraManagement::class, 'edit'])->name('sutradara.edit');
        Route::put('/sutradara/{id}', [SutradaraManagement::class, 'update'])->name('sutradara.update');
        Route::delete('/sutradara/{id}', [SutradaraManagement::class, 'destroy'])->name('sutradara.destroy');
    });


require __DIR__ . '/auth.php';
