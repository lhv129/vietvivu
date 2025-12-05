<?php

use App\Api\Controllers\ClientMenuController;
use Illuminate\Support\Facades\Route;



Route::get('/client-menus', [ClientMenuController::class, 'index']);
Route::get('/client-menus/{id}', [ClientMenuController::class, 'show']);

Route::middleware(['role:1'])->group(function () {
    Route::post('/client-menus', [ClientMenuController::class, 'store']);
    Route::put('/client-menus/{id}', [ClientMenuController::class, 'update']);
    Route::delete('/client-menus/{id}', [ClientMenuController::class, 'destroy']);
});
