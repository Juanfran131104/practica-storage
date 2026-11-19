<?php

use App\Http\Controllers\DocController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('photos.index'));

Route::prefix('photos')->name('photos.')->group(function () {
    Route::get('/', [PhotoController::class, 'index'])->name('index');
    Route::post('/', [PhotoController::class, 'store'])->name('store');
    Route::put('/{photo}', [PhotoController::class, 'update'])->name('update');
    Route::delete('/{photo}', [PhotoController::class, 'destroy'])->name('destroy');
});

Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', [DocController::class, 'index'])->name('index');
    Route::post('/', [DocController::class, 'store'])->name('store');
    Route::put('/{doc}', [DocController::class, 'update'])->name('update');
    Route::delete('/{doc}', [DocController::class, 'destroy'])->name('destroy');
});