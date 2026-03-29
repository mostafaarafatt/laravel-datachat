<?php

use Illuminate\Support\Facades\Route;
use Mostafaarafat\DataChat\Http\Controllers\Dashboard\DashboardController;

/*
|--------------------------------------------------------------------------
| DataChat Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('datachat')
    ->middleware(['web', 'auth'])
    ->name('datachat.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        // Create widget
        Route::get('/create', [DashboardController::class, 'create'])->name('create');
        Route::post('/create', [DashboardController::class, 'store'])->name('store');

        // View widget
        Route::get('/{id}', [DashboardController::class, 'show'])->name('show');

        // Edit widget
        Route::get('/{id}/edit', [DashboardController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DashboardController::class, 'update'])->name('update');

        // Delete widget
        Route::delete('/{id}', [DashboardController::class, 'destroy'])->name('destroy');

        // Regenerate API key
        Route::post('/{id}/regenerate-key', [DashboardController::class, 'regenerateKey'])->name('regenerate-key');
    });