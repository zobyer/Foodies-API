<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FoodsController;
use App\Http\Controllers\OrdersController;


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
    
    Route::prefix('foods/')->group(function () {
        Route::get('byid/{id}',[FoodsController::class,'getFoodbyId']);
        Route::post('store',[FoodsController::class,'storeFood']);
        Route::get('all',[FoodsController::class,'getAllFoods']);
        Route::get('famous',[FoodsController::class,'getFamousFoods']);
        Route::get('search/city',[FoodsController::class,'searchByCity']);
        Route::get('search/food',[FoodsController::class,'searchByFood']);
    });


    Route::prefix('cart/')->group(function(){
        Route::post('add',[OrdersController::class,'addToCart']);
        Route::get('count/{id}',[OrdersController::class,'countCart']);
        Route::post('delete',[OrdersController::class,'removefromcart']);
    });

    Route::prefix('orders/')->group(function(){
        Route::get('all/{id}',[OrdersController::class,'getAllOrders']);
        Route::post('place',[OrdersController::class,'placeOrders']);
        Route::post('address',[OrdersController::class,'addAddress']);
        Route::get('address/{id}',[OrdersController::class,'getAddress']);
        Route::get('last/address/{id}',[OrdersController::class,'getLastAddress']);
        Route::get('address/id/{id}/{address_id}',[OrdersController::class,'getAddressById']);
    });

    Route::get('my-orders/{id}',[OrdersController::class,'getPlacedOrders']);
    
});
