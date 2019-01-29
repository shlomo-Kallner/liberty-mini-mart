<?php

use Illuminate\Http\Request;

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

Route::prefix('test')->group(
    function () {
        Route::get('{method?}', 'TestController@index');
    }
);

Route::prefix('store')->group(
    function () {
        //Route::get('/', 'ShopController@getStore');
        // 'store/section/{section}/category/{category}/product/{product}'...
        Route::get('section/list', 'SectionController@list');
        Route::post('section/list', 'SectionController@list');
        Route::get('section/{section}', 'SectionController@show');
        Route::get('section/{section}/category/list', 'CategorieController@list');
        Route::post('section/{section}/category/list', 'CategorieController@list');
        Route::get('section/{section}/category/{category}', 'CategorieController@show');
        Route::get('section/{section}/category/{category}/product/list', 'ProductController@list');
        Route::post('section/{section}/category/{category}/product/list', 'ProductController@list');
        Route::get('section/{section}/category/{category}/product/{product}', 'ProductController@show');

        // adding/removing/deleting from the cart API..
        Route::get('section/{section}/category/{category}/product/{product}/addtocart', 'CartController@addToCart');
        Route::post('section/{section}/category/{category}/product/{product}/addtocart', 'CartController@addToCart');
        Route::post('section/{section}/category/{category}/product/{product}/delfromcart', 'CartController@delFromCart');
        Route::post('section/{section}/category/{category}/product/{product}/remfromcart', 'CartController@remFromCart');
    }
);

Route::middleware('adminguard')->prefix('admin')->group(
    function () {
        Route::resource('articles', 'ArticleController');
        Route::get('article/{article}/delete', 'ArticleController@showDelete');

        // 'user/' goes to 'index()' which returns 'all-users' of the site..
        Route::resource(
            'user', 'UserController', [
                'parameters'=> [
                    'user' => 'user',
                ],
                'except' => [
                    'show'
                ]
            ]
        );
        Route::get('user/{user}/delete', 'UserController@showDelete');

        // 'page/' goes to 'index()' which returns 'all-pages' of the site..
        Route::resource('page', 'PageController');
        Route::get('page/{page}/delete', 'PageController@showDelete');

        Route::resource('menus', 'PageGroupingController');
        Route::get('menus/{menu}/delete', 'PageGroupingController@showDelete');
        
        Route::resource(
            'search', 'SearchResultController', [
                'parameters'=> [
                    'search' => 'search',
                ],
                'except' => [
                    'create', 'edit', 'update'
                ]
            ]
        );
        Route::get('search/{search}/delete', 'SearchResultController@showDelete');
        
        Route::prefix('store')->group(
            function () {
                
                // 'section/' goes to 'index()' which returns 'all-sections' of the store..
                Route::get('section/list', 'SectionController@list');
                Route::post('section/list', 'SectionController@list');
                Route::resource(
                    'section', 'SectionController', [
                        'parameters'=> [
                            'section' => 'section',
                        ]
                    ]
                );
                // 'category/' goes to 'index()' which returns 'all-categories' of the section..
                Route::get('category/create', 'CategorieController@create');
                Route::post('category/create', 'CategorieController@store');
                Route::get('section/{section}/category/list', 'CategorieController@list');
                Route::post('section/{section}/category/list', 'CategorieController@list');
                Route::resource(
                    'section/{section}/category', 'CategorieController', [
                        'parameters'=> [
                            'section' => 'section',
                            'categorie' => 'category',
                        ]
                    ]
                );
                // 'product/' goes to 'index()' which returns 'all-products' of the category..
                Route::get('product/create', 'ProductController@create');
                Route::post('product/create', 'ProductController@store');
                Route::get('section/{section}/category/{category}/product/list', 'ProductController@list');
                Route::post('section/{section}/category/{category}/product/list', 'ProductController@list');
                Route::resource(
                    'section/{section}/category/{category}/product', 'ProductController', [
                        'parameters'=> [
                            'section' => 'section',
                            'categorie' => 'category',
                            'product' => 'product',
                        ]
                    ]
                );
                Route::get(
                    'section/{section}/category/{category}/product/{product}/delete', 
                    'ProductController@showDelete'
                );
                

                

            }
        );
    }
);
