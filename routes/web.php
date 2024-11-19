<?php

use App\Models\Cerita;
use App\Models\Kategori;
use UniSharp\LaravelFilemanager\Lfm;
use App\Http\Middleware\SetLfmFolder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CeritaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HeroimageController;
use App\Http\Controllers\FilemanagerController;
use App\Http\Controllers\NotificationController;

/*~
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
Route::group(['middleware' => 'auth',], function () {

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('/kategori', KategoriController::class);
    // Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('show.kategori');
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

    Route::resource('/roles', RoleController::class);

    Route::resource('/users', UsersController::class);
    Route::get('/pending/users', [UsersController::class, 'pendingView'])->name('users.pending-view');
    Route::get('fetch-users', [UsersController::class, 'fetch'])->name('fetch.users');
    Route::put('/update-status/{id}', [UsersController::class, 'updateStatus'])->name('update-status');
    // web.php
    Route::get('/user/delete/log', [UsersController::class, 'deletedUsers'])->name('users.deleteLog');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead']);
    Route::get('/clear-deleted-users-log', [UsersController::class, 'clearDeletedUsersLog'])->name('clear.deleted.users.log');

    Route::get('/videos/back', [VideoController::class, 'backToIndexWithModal'])->name('videos.back');
    Route::resource('videos', VideoController::class);
    // Route untuk form tambah video dari file
    Route::get('/videos/create/file', [VideoController::class, 'createFromFile'])->name('videos.create.file');
    // Route untuk form tambah video dari link YouTube
    Route::get('/videos/create/youtube', [VideoController::class, 'createFromYouTube'])->name('videos.create.youtube');
    Route::get('/videos/edit_from_file/{id}', [VideoController::class, 'editFromFile'])->name('videos.edit.from.file');
    Route::get('/videos/edit_from_youtube/{id}', [VideoController::class, 'editFromYoutube'])->name('videos.edit.from.youtube');
    
    Route::resource('/heroimage', HeroimageController::class);
    // Route::get('filemanager', [FileManagerController::class, 'index']);
    // Rute dengan middleware auth
// Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['auth']], function () {
//     // Rute untuk membuka file manager dengan parameter folder
//     Route::get('/folder/{folder}', function ($folder) {
//         return redirect()->to('/laravel-filemanager?folder=' . $folder);
//     })->name('filemanager.folder');

//     // Rute untuk file manager itu sendiri
//     \UniSharp\LaravelFilemanager\Lfm::routes();
// });




 
 
    

    Route::resource('/cerita', CeritaController::class);
    Route::get('/cerita/date/{date}', [CeritaController::class, 'handleDate']);
    Route::get('/cerita/create/{date}', [CeritaController::class, 'create'])->name('cerita.create')->middleware('prevent.manual.url.access');
    Route::get('/cerita/view/create/{date}', [CeritaController::class, 'create_view'])->name('cerita.create_view');
    Route::get('/cerita/{cerita}/date/{date}', [CeritaController::class, 'show_data'])->name('cerita.show_data');
    Route::get('/cerita/view/date-expired', [CeritaController::class, 'show_data_expired_date'])->name('cerita.date_expired');
    Route::get('/cerita/view/date-belum-mulai', [CeritaController::class, 'show_data_belum_date'])->name('cerita.date_belum');
    Route::get('/cerita/edit/{id}/{time?}', [CeritaController::class, 'edit'])->name('cerita.edit');
    Route::get('/cerita/create-null/{id}/{time?}', [CeritaController::class, 'create_null_data'])->name('cerita.create_null');
});
