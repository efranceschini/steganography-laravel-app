<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SteganographyApiController;
use Illuminate\Support\Facades\Route;

// Normally the middleware would be 'app' for stateless API, but the encode api needs the current user so it's stateful
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/encode', [SteganographyApiController::class, 'encode'])->name('api.encode');
    Route::post('/decode', [SteganographyApiController::class, 'decode'])->name('api.decode');
});
