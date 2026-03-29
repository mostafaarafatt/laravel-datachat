<?php

use Illuminate\Support\Facades\Route;
use Mostafaarafat\DataChat\Http\Controllers\Api\ChatController;
use Mostafaarafat\DataChat\Http\Middleware\AuthenticateApiKey;
use Mostafaarafat\DataChat\Http\Middleware\ValidateOrigin;
use Mostafaarafat\DataChat\Http\Middleware\HandleCors;

/*
|--------------------------------------------------------------------------
| DataChat API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api/datachat')
    ->middleware([HandleCors::class, AuthenticateApiKey::class, ValidateOrigin::class])
    ->group(function () {

        // Get widget configuration
        Route::get('/config', [ChatController::class, 'config']);

        // Send a message
        Route::post('/message', [ChatController::class, 'message']);

        // Get conversation messages
        Route::get('/conversation/{id}', [ChatController::class, 'conversation']);

        // Poll for new messages
        Route::get('/conversation/{id}/poll', [ChatController::class, 'poll']);
    });