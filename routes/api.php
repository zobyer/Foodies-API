<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FoodsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('registration',[UserController::class,'registration']);
Route::post('login',[UserController::class,'index']);


Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's
    Route::get('getfood/{id}',[FoodsController::class,'getFoodbyId']);
    Route::post('store',[FoodsController::class,'storeFood']);
    Route::get('getallfood',[FoodsController::class,'getAllFoods']);
    Route::get('getfamousfood',[FoodsController::class,'getFamousFoods']);
});
