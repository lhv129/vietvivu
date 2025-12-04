<?php

use App\Api\Controllers\StartLocationController;
use Illuminate\Support\Facades\Route;

Route::get('/start-locations', [StartLocationController::class, 'index']);
Route::get('/start-locations/{id}', [StartLocationController::class, 'show']);

Route::middleware(['role:1,2'])->group(function () {
    Route::post('/start-locations', [StartLocationController::class, 'store']);
    Route::put('/start-locations/{id}', [StartLocationController::class, 'update']);
    Route::delete('/start-locations/{id}', [StartLocationController::class, 'destroy']);
});
