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

use App\User,
    App\Utilities\Functions\Functions,
    App\PageGroup,
    App\PageGrouping,
    App\Page;
use Illuminate\Http\Request;

Route::get('/', 'PageController@home');
Route::get('/home', 'PageController@home');

Route::prefix('test')->group(
    function () {
        //Route::get('cms', 'CmsController@index');
        Route::get('{method?}', 'TestController@index');
    }
);

Route::middleware('userguard')->group(
    function () {
        //Route::resource('user', 'UserController');
        Route::get('signout', 'UserController@signout');
        Route::get('logout', 'UserController@signout');

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
                Route::post('section/{section}/category/{category}/product/{product?}', 'ProductController@postReveiw');
                //
                // a category 'all' should return all products in the catalog/store.. nope.

                //Route::get('{page}', 'PageController@test4');
                //Route::get('{page}/{page}', 'PageController@test2');
            }
        );

        Route::get('cart/{cart?}', 'CartController@show');
        //Route::get('wishlist/{wishlist?}', 'WishlistController@show');
        Route::get('checkout', 'OrderController@create');
        Route::post('checkout', 'OrderController@store');

        Route::prefix('user')->group(
            function () {
                Route::get('{user}/search', 'SearchResultController@index');
                Route::get('{user}/search/{search}', 'SearchResultController@show');
                
                Route::get('{user?}', 'UserController@show');
                //Route::get('{user}', 'PageController@index1');
                Route::get('{user}/orders', 'OrderController@index');
                Route::get('{user}/orders/{order}', 'OrderController@show');
                
            }
        );

                
        //Route::get('user', 'UserController');
        //Route::post('user', 'UserController');
        //Route::resource('user', 'UserController');
        //Route::get('user', 'UserController');
        
    }
);

Route::middleware('adminguard')->prefix('admin')->group( 
    function () {
        //Route::get('/', 'ShopController@test');
        Route::get('/', 'CmsController@index');

        Route::resource('article', 'ArticleController');
        Route::get('article/{article}/delete', 'ArticleController@showDelete');

        Route::prefix('store')->group( 
            function () {
                // 'section/' goes to 'index()' which returns 'all-sections' of the store..
                Route::resource(
                    'section', 'SectionController', [
                        'parameters'=> [
                            'section' => 'section'
                        ]
                    ]
                );
                // 'category/' goes to 'index()' which returns 'all-categories' of the section..
                Route::get('category/create', 'CategorieController@create');
                Route::post('category/create', 'CategorieController@store');
                
                Route::resource(
                    'section/{section}/category', 'CategorieController', [
                        'parameters'=> [
                            'categorie' => 'category',
                        ]
                    ]
                );
                // 'product/' goes to 'index()' which returns 'all-products' of the category..
                Route::get('product/create', 'ProductController@create');
                Route::post('product/create', 'ProductController@store');
                Route::get('product', 'ProductController@index');
                Route::post('product', 'ProductController@store');
                
                Route::resource('section/{section}/category/{category}/product', 'ProductController');
                Route::get(
                    'section/{section}/category/{category}/product/{product}/delete', 
                    'ProductController@showDelete'
                );
            }
        );
        
        // 'user/' goes to 'index()' which returns 'all-users' of the site..
        Route::resource(
            'user', 'UserController', [
                'parameters'=> [
                    'user' => 'user',
                ]
            ]
        );
        Route::get('user/{user}/delete', 'UserController@showDelete');
        
        Route::resource(
            '{user}/orders', 'OrderController', 
            [
                'parameters' => ['order' => 'order']
            ]
        );

        // 'page/' goes to 'index()' which returns 'all-pages' of the site..
        Route::resource(
            'page', 'PageController', [
                'parameters'=> [
                    'page' => 'page',
                ]
            ]
        );
        Route::get('page/{page}/delete', 'PageController@showDelete');

        // 'page/' goes to 'index()' which returns 'all-pages' of the site..
        Route::resource(
            'menus', 'PageGroupingController', [
                'parameters'=> [
                    'menu' => 'menu',
                ]
            ]
        );
        Route::get('menus/{menu}/delete', 'PageGroupingController@showDelete');

        
        // MEMBERSHIP PLANS AND SEARCHES ARE WISHLIST ITEMS!!!
        /* 
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

            // 'plan/' goes to 'index()' which returns 'all-pages' of the site..
            Route::resource(
                'plan', 'PlanController', [
                    'parameters'=> [
                        'plan' => 'plan',
                    ]
                ]
            );
            Route::get('plan/{plan}/delete', 'PlanController@showDelete'); 
        */
    }
);


Route::middleware('signedguard')->group(
    function () {
        
        Route::get('signup', 'UserController@signup');
        Route::post('signup', 'UserController@register');
        
        Route::get('register', 'UserController@signup');
        Route::post('register', 'UserController@register');

        Route::get('signin/{page?}', 'UserController@signinRedirect');
        Route::post('signin/{page?}', 'UserController@signin');

        Route::get('login/{page?}', 'UserController@signinRedirect');
        Route::post('login/{page?}', 'UserController@signin');

    }
);



/* /// MEMBERSHIP PLANS AND SEARCHES ARE WISHLIST ITEMS!!
    Route::resource(
        'search', 'SearchResultController', [
            'parameters'=> [
                'search' => 'search',
            ],
            'only' => [
                'index', 'show', 'store'
            ]
        ]
    );
    Route::resource(
        'plan', 'PlanController', [
            'parameters'=> [
                'plan' => 'plan',
            ],
            'only' => [
                'index', 'show'
            ]
        ]
    ); 
*/

Route::get('page', 'PageController@index');
Route::get('page/{page}', 'PageController@show');

/* 

    Route::prefix('store')->group(
        function () {

            Route::resource('cart', 'CartController');

            Route::resource('catalog', 'CatalogController');

            Route::resource('catalog/{catalog}/category', 'CategorieController');

            Route::resource(
                'catalog/{catalog}/category/{category}/product', 
                'ProductController'
            );

            // Advanced stuff...
            Route::resource(
                'catalog/{catalog}/department', 'DepartmentController'
            );
            Route::resource(
                'catalog/{catalog}/department/{department}/section', 
                'SectionController'
            );
            Route::resource(
                'catalog/{catalog}/department/{department}/section/{section}/product', 
                'ProductController'
            );
        }
    );


    Route::prefix('admin')->group(function() {
    Route::get(
        '/', function () {
                return view('welcome');
            }
        );

    Route::resource('user', 'UserController');

    Route::resource('catalog', 'CatalogController');

    Route::resource('catalog/{catalog}/category', 'CategorieController');

    Route::resource(
        'catalog/{catalog}/category/{category}/product', 
        'ProductController'
        );

    // Advanced stuff...
    Route::resource('catalog/{catalog}/department', 'DepartmentController');
    Route::resource(
        'catalog/{catalog}/department/{department}/section', 
        'SectionController'
        );
    Route::resource(
        'catalog/{catalog}/department/{department}/section/{section}/product', 
        'ProductController'
        );
    });

*/