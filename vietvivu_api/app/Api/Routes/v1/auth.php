<?php

use Illuminate\Support\Facades\Route;
use App\Api\Controllers\AuthController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);
Route::post('/auth/refresh',  [AuthController::class, 'refreshToken']);

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/me',       [AuthController::class, 'me']);
    Route::post('/auth/logout',  [AuthController::class, 'logout']);
});
