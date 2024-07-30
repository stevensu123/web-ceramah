<?php

use App\Http\Controllers\CeritaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
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
Route::group(['middleware' => 'auth'], function(){

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('/kategori',KategoriController::class);
    Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('show.kategori');
    // Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
   
    Route::resource('/cerita',CeritaController::class);
    Route::get('/cerita/date/{date}', [CeritaController::class, 'handleDate']);
    Route::get('/cerita/view/create', [CeritaController::class, 'create_view'])->name('cerita.create_view');
    Route::get('/cerita/data/{cerita}', [CeritaController::class, 'show_data'])->name('cerita.show_data');
    Route::get('/cerita/view/date-expired', [CeritaController::class, 'show_data_expired_date'])->name('cerita.date_expired');
    Route::get('/cerita/view/date-belum-mulai', [CeritaController::class, 'show_data_belum_date'])->name('cerita.date_belum');
    Route::get('/cerita/edit/{id}', [CeritaController::class, 'edit'])->name('cerita.edit');


});

