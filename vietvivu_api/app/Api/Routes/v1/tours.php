<?php

use App\Api\Controllers\TourController;
use Illuminate\Support\Facades\Route;

Route::get('/tours', [TourController::class, 'index']);
Route::get('/tours/{id}', [TourController::class, 'show']);
Route::post('/tours', [TourController::class, 'store']);

Route::middleware(['role:1'])->group(function () {
    
});
