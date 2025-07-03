<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/login',    [AuthController::class, 'login_page']);
Route::get('/register',    [AuthController::class, 'register_page']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// 👇 Rute yang butuh token (terlindungi sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
});

