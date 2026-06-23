<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'check.active', 'check.must.change.password'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/notifikasi-kadaluarsa', [DashboardController::class, 'notifikasiKadaluarsa'])->name('dashboard.notifikasi-kadaluarsa')->middleware('can:kelola bidang');

    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/data', [DocumentController::class, 'data'])->name('data');
        Route::get('/trashed', [DocumentController::class, 'trashed'])->name('trashed');
        Route::get('/status/{status}', [DocumentController::class, 'byStatus'])->name('status');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::get('/{document}/preview', [DocumentController::class, 'preview'])->name('preview');

        Route::middleware('can:upload dokumen')->group(function () {
            Route::get('/create', [DocumentController::class, 'create'])->name('create');
            Route::get('/create/baru', [DocumentController::class, 'createBaru'])->name('create.baru');
            Route::get('/create/mou', [DocumentController::class, 'createMou'])->name('create.mou');
            Route::get('/create/update', [DocumentController::class, 'createUpdate'])->name('create.update');
            Route::post('/', [DocumentController::class, 'store'])->name('store');
        });

        Route::middleware('can:edit dokumen')->group(function () {
            Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
            Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
        });

        Route::middleware('can:hapus dokumen')->group(function () {
            Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
            Route::delete('/{id}/force-delete', [DocumentController::class, 'forceDelete'])->name('force-delete');
        });

        Route::middleware('can:restore dokumen')->group(function () {
            Route::post('/{id}/restore', [DocumentController::class, 'restore'])->name('restore');
        });

        Route::get('/{document}', [DocumentController::class, 'show'])->name('show')->middleware('can:lihat dokumen');
    });

    Route::resource('bidang', BidangController::class)->middleware('can:kelola bidang');
    Route::resource('kategori', KategoriController::class)->middleware('can:kelola kategori');

    Route::prefix('users')->name('users.')->middleware('can:kelola user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('logs')->name('logs.')->middleware('can:lihat log')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/data', [ActivityLogController::class, 'data'])->name('data');
    });

    Route::resource('roles', RoleController::class)->middleware('can:kelola role');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::put('password', [ProfileController::class, 'password'])->name('password');
    });
});

require __DIR__.'/auth.php';
