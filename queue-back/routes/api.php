<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/health', function () {
    return response()->json(['ok' => true]);
});

Route::get('/items', [ItemController::class, 'index']);
Route::post('/items', [ItemController::class, 'store']);

/* Review */
Route::post('/items/{id}/review', [ItemController::class, 'review']);
