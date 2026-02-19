<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SteganographyApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/encode', [SteganographyApiController::class, 'encode']);
    Route::post('/decode', [SteganographyApiController::class, 'decode']);
});
