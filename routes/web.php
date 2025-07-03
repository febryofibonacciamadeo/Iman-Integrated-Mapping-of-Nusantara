<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\NazhirController;
use App\Http\Controllers\WakafController;
use App\Http\Controllers\ZakatSedekahController;

Route::get('/login',    [AuthController::class, 'login_page'])->name('login');
Route::get('/register',    [AuthController::class, 'register_page']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });

    Route::prefix('donatur')->controller(DonaturController::class)->group(function() {
        Route::get('/', 'show')->name('donatur.page');
        Route::get('/index', 'index')->name('donatur.get');
        Route::post('/update-or-create', 'updateOrCreate')->name('donatur.updateOrCreate');
        Route::post('/destroy', 'destroy')->name('donatur.delete');
    });

    Route::prefix('nazhir')->controller(NazhirController::class)->group(function() {
        Route::get('/', 'show')->name('nazhir.page');
        Route::get('/index', 'index')->name('nazhir.get');
        Route::post('/update-or-create', 'updateOrCreate')->name('nazhir.updateOrCreate');
        Route::post('/destroy', 'destroy')->name('nazhir.delete');
    });
});

