<?php

use App\Http\Controllers\CeritaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\QuoteController;
use App\Models\Cerita;
use App\Models\Kategori;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => 'auth'], function () {

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('/kategori', KategoriController::class);
    Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('show.kategori');
    // Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');

    Route::get('/quotes',  [QuoteController::class, 'index'])->name('quotes.index');
    Route::get('/quotes/{id}', [QuoteController::class, 'show'])->name('show.quotes');
    Route::delete('/quotes/{id}', [QuoteController::class, 'destroy']);
    Route::get('/quotes/view/manual', [QuoteController::class, 'manual_quots'])->name('quotes.manual');
    Route::get('/quotes/view/create/manual', [QuoteController::class, 'manual_create'])->name('quotes.manualCreate');
    Route::post('/quotes/sotre/manual', [QuoteController::class, 'store_manual'])->name('quotes.manualStore');
    Route::get('/quotes/edit/manual/{id}', [QuoteController::class, 'manual_edit'])->name('quotes.manualEdit');
    Route::post('/quotes/update/manual/{id}', [QuoteController::class, 'manual_update'])->name('quotes.manualUpdate');

    Route::post('/quotes/sotre/auto', [QuoteController::class, 'store_auto'])->name('quotes.autoStore');
    Route::get('/quotes/view/create/auto', [QuoteController::class, 'auto_create'])->name('quotes.autoCreate');
    Route::get('/quotes/view/auto', [QuoteController::class, 'auto_quots'])->name('quotes.auto');
    
    
   
    
   
    Route::resource('/cerita', CeritaController::class);
    Route::get('/cerita/date/{date}', [CeritaController::class, 'handleDate']);
    Route::get('/cerita/create/{date}', [CeritaController::class, 'create'])->name('cerita.create');
    Route::get('/cerita/view/create/{date}', [CeritaController::class, 'create_view'])->name('cerita.create_view');
    Route::get('/cerita/{cerita}/date/{date}', [CeritaController::class, 'show_data'])->name('cerita.show_data');
    Route::get('/cerita/view/date-expired', [CeritaController::class, 'show_data_expired_date'])->name('cerita.date_expired');
    Route::get('/cerita/view/date-belum-mulai', [CeritaController::class, 'show_data_belum_date'])->name('cerita.date_belum');
    Route::get('/cerita/edit/{id}/{time?}', [CeritaController::class, 'edit'])->name('cerita.edit');
    Route::get('/cerita/create-null/{id}/{time?}', [CeritaController::class, 'create_null_data'])->name('cerita.create_null');
});
