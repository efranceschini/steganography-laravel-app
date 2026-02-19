<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SteganographyApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('home');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'showDashboard'])->name('dashboard');
        Route::resource('/images', ImageController::class)->only(['create', 'store', 'destroy'])->whereNumber('image');
        Route::get('images', fn () => abort(404));
        Route::get('images/{image}', fn () => abort(404));
    });

});
/*
Route::prefix('api')->middleware('api')->group(function () {
    Route::get('/encode', [SteganographyApiController::class, 'encode']);
    Route::post('/decode', [SteganographyApiController::class, 'decode']);
});
*/
