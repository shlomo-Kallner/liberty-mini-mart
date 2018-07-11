<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', 'PageController@test3');

/**
 * Route::get(
 *    'php', function () {
 *      phpinfo();
 *      return '';
 *  }
 *);
 *
 */

/* Route::get(
    'template', function () {
        return view('master_themewagon');
    }
); */

//Route::resource('user', 'UserController');
Route::prefix('user')->group(
    function () {
        Route::get('/', 'PageController@index1');
        Route::get('{page}', 'PageController@index1');
    }
);

Route::prefix('store')->group( 
    function () {
        //Route::get('/', 'ShopController@test');
        Route::get('/', 'ShopController@index');
        //Route::get('all', 'ShopController@products');

        // some pre-database-filling testing routes..
        //Route::get('section/test', 'SectionController@test');
        //Route::get('section/test/category/test', 'CategorieController@test');
        //Route::get('section/test/category/test/product/test', 'ProductController@test');
        //Route::post('section/test/category/test/product/test', 'ProductController@testPost');

        Route::get('section/{section?}', 'SectionController@show');
        //
        Route::get('section/{section}/category/{category?}', 'CategorieController@show');
        //
        Route::get('section/{section}/category/{category}/product/{product?}', 'ProductController@show');
        //
        // a category 'all' should return all products in the catalog/store..

        Route::get('{page}', 'PageController@test4');
        //Route::get('{page}/{page}', 'PageController@test2');
    }
);

Route::get('cart', 'CartController@show');
Route::get('wishlist', 'WishlistController@show');
Route::get('checkout', 'ShopController@checkout');

Route::prefix('admin')->group( 
    function () {
        //Route::get('/', 'ShopController@test');
        Route::get('/', 'CmsController@index');

        Route::resource(
            'section', 'SectionController', [
                'parameters'=> [
                    'section' => 'section'
                ],
                'except' => [
                    'show'
                ]
            ]
        );
        // 'section/' goes to 'index()' which returns 'all-sections' of the store..
        Route::resource(
            'section/{section}/category', 'CategorieController', [
                'parameters'=> [
                    'categorie' => 'category',
                    'section' => 'section'
                ],
                'except' => [
                    'show'
                ]
            ]
        );
        // 'category/' goes to 'index()' which returns 'all-categories' of the store..
        Route::resource(
            'section/{section}/category/{category}/product', 'ProductController', [
                'parameters'=> [
                    'categorie' => 'category',
                    'section' => 'section',
                    'product' => 'product',
                ],
                'except' => [
                    'show'
                ]
            ]
        );
        // 'product/' goes to 'index()' which returns 'all-products' of the category..
        
    }
);
//Route::get('user', 'UserController');
//Route::post('user', 'UserController');
//Route::resource('user', 'UserController');
//Route::get('user', 'UserController');


Route::get('signup', 'UserController@signup');
Route::post('signup', 'UserController@register');

Route::get('signin/{page?}', 'UserController@signinRedirect');
Route::post('signin/{page?}', 'UserController@signin');

Route::get('signout', 'UserController@signout');

Route::get('{page}', 'PageController@show');


//
//
//Route::prefix('store')->group(function() {
//
//    Route::resource('cart', 'CartController');
//
//    Route::resource('catalog', 'CatalogController');
//
//    Route::resource('catalog/{catalog}/category', 'CategorieController');
//
//    Route::resource('catalog/{catalog}/category/{category}/product', 'ProductController');
//
//    // Advanced stuff...
//    //    Route::resource('catalog/{catalog}/department', 'DepartmentController');
//    //    Route::resource('catalog/{catalog}/department/{department}/section', 'SectionController');
//    //    Route::resource('catalog/{catalog}/department/{department}/section/{section}/product', 'ProductController');
//});
//
//
//Route::prefix('admin')->group(function() {
//    Route::get('/', function () {
//        return view('welcome');
//    });
//
//    Route::resource('user', 'UserController');
//
//    Route::resource('catalog', 'CatalogController');
//
//    Route::resource('catalog/{catalog}/category', 'CategorieController');
//
//    Route::resource('catalog/{catalog}/category/{category}/product', 'ProductController');
//
//    // Advanced stuff...
//    //    Route::resource('catalog/{catalog}/department', 'DepartmentController');
//    //    Route::resource('catalog/{catalog}/department/{department}/section', 'SectionController');
//    //    Route::resource('catalog/{catalog}/department/{department}/section/{section}/product', 'ProductController');
//});
//
//Route::resource('{page}', 'PagesController');
