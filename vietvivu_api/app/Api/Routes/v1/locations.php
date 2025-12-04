<?php

use App\Api\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/{id}', [LocationController::class, 'show']);

Route::middleware(['role:1,2'])->group(function () {
    Route::post('/locations', [LocationController::class, 'store']);
    Route::put('/locations/{id}', [LocationController::class, 'update']);
    Route::delete('/locations/{id}', [LocationController::class, 'destroy']);
});
