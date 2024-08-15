<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([ 'middleware' => 'api'], function ($router) {


    Route::post('register', [APIController::class,'register']);
    Route::post('login', [ APIController::class,'login']);
    Route::get('profile', [APIController::class,'profile']);
    Route::post('refresh', [APIController::class,'refresh']);
    Route::post('logout', [APIController::class,'logout']);
    Route::get('checktoken', [APIController::class,'checkToken']);
    Route::post('/update-profile', [APIController::class,'uploadImageProfile']);


});
