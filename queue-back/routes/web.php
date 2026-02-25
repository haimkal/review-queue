<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    return response()->json(['ok' => true]);
});

Route::post('/items', [ItemController::class, 'store']);
