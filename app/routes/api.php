<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SteganographyApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/encode', [SteganographyApiController::class, 'encode']);
    Route::post('/decode', [SteganographyApiController::class, 'decode']);
});
