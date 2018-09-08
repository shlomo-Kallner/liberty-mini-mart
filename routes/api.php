<?php

//use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('store')->group(
    function () {
        Route::get('/', 'ShopController@getStore');
        // 'store/section/{section}/category/{category}/product/{product}'...
        Route::get('section/{section}/category/{category}/product/{product}', 'CartController@addToCart');
        Route::post('section/{section}/category/{category}/product/{product}/addtocart', 'CartController@addToCart');
    }
);
