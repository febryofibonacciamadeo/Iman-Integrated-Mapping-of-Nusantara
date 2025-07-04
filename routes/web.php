<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\NazhirController;
use App\Http\Controllers\PenerimaManfaatController;
use App\Http\Controllers\WakafController;
use App\Http\Controllers\ZakatSedekahController;
use App\Models\Wakaf;
use App\Models\ZakatSedekah;

Route::get('/login',    [AuthController::class, 'login_page'])->name('login');
Route::get('/register',    [AuthController::class, 'register_page']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        $wakafs = Wakaf::all();
        $zakatSedekahs = ZakatSedekah::all();
        return view('dashboard', compact('wakafs', 'zakatSedekahs'));
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

    Route::prefix('wakaf')->controller(WakafController::class)->group(function() {
        Route::get('/', 'show')->name('wakaf.page');
        Route::get('/index', 'index')->name('wakaf.get');
        Route::post('/update-or-create', 'updateOrCreate')->name('wakaf.updateOrCreate');
        Route::post('/destroy', 'destroy')->name('wakaf.delete');
    });

    Route::prefix('zakat')->controller(ZakatSedekahController::class)->group(function() {
        Route::get('/', 'show')->name('zakat.page');
        Route::get('/index', 'index')->name('zakat.get');
        Route::post('/update-or-create', 'updateOrCreate')->name('zakat.updateOrCreate');
        Route::post('/destroy', 'destroy')->name('zakat.delete');
    });

    Route::prefix('penerima-mafaat')->controller(PenerimaManfaatController::class)->group(function() {
        Route::get('/', 'show')->name('penerima_manfaat.page');
        Route::get('/index', 'index')->name('penerima_manfaat.get');
        Route::post('/update-or-create', 'updateOrCreate')->name('penerima_manfaat.updateOrCreate');
        Route::post('/destroy', 'destroy')->name('penerima_manfaat.delete');
    });

    Route::prefix('penyaluran-wakaf')->controller(PenerimaManfaatController::class)->group(function() {
        Route::get('/', 'show')->name('penyaluran.page');
        Route::get('/index', 'index')->name('penyaluran.get');
        Route::post('/update-or-create', 'updateOrCreate')->name('penyaluran.updateOrCreate');
        Route::post('/destroy', 'destroy')->name('penyaluran.delete');
    });
    
    Route::prefix('wilayah-prioritas')->controller(PenerimaManfaatController::class)->group(function() {
        Route::get('/', 'show')->name('wp.page');
        Route::get('/index', 'index')->name('wp.get');
        Route::post('/update-or-create', 'updateOrCreate')->name('wp.updateOrCreate');
        Route::post('/destroy', 'destroy')->name('wp.delete');
    });

});

