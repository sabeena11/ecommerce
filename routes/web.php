<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('frontend.index');
// });

Route::get('/',[App\Http\Controllers\FrontendController::class, 'index']);
Route::group(['prefix' => 'admin'], function() {


    Route::group(['middleware' => 'admin.guest'], function() {
        Route::get('/login',[App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/authenticate',[App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('admin.authenticate');
        Route::get('/',[App\Http\Controllers\FrontendController::class, 'index']);

    });
    

    Route::group(['middleware' => 'admin.auth'], function() {
        Route::get('/dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/logout',[App\Http\Controllers\Admin\DashboardController::class, 'logout'])->name('admin.logout');


        Route::resource('/upload-image', \App\Http\Controllers\Admin\UploadImageController::class);


    });
    
});