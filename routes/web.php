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

Route::get('/', 'PageController@home');

use App\User,
    App\Utilities\Functions\Functions,
    App\PageGroup,
    App\PageGrouping,
    App\Page;
use Illuminate\Http\Request;
Route::get(
    'test/php', function (Request $request) {
        $dump = true;
        //$tmp = User::getUserArray($request);
        //$tmp = $request->session()->all();
        //$num = 44;
        //$n = Functions::int2url_encode($num);
        //$k = Functions::url2int_decode($n);
        //$j = pack('V', $num);
        //$l = bin2hex($j);

        $tmp = [
            //User::getUserArray($request),
            $request->session()->all(),
            //'page_groups' => PageGrouping::getGroups(),
            //'users' => App\User::getUsers(1, true),
            //'num' => [ $n, $k, $j, $l ],
            //'in_array' => in_array(null, [2, 'a', null, 2.4]),
        ];
        //$tmp = new \DatabaseSeeder;
        //dd($tmp);
        //$tmp->run();
        //PageGroup::where('group_id', 2)
        //->max('order');
        //->get(); //all(); //getAllPages();
        if ($dump) {
            dd($tmp);
        } else {
            return $tmp;
        }
    }
);
/* *
 *  Route::get(
 *      'php', function () {
 *          phpinfo();
 *          return '';
 *      }
 *  );
    Route::get(
        'template', function () {
            return view('master_themewagon');
        }
    );
 *
 */

/*  */


Route::middleware('userguard')->group(
    function () {
        //Route::resource('user', 'UserController');
        Route::get('signout', 'UserController@signout');

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
        Route::get('checkout', 'ShopController@checkout');
        //Route::post('checkout', )

        Route::prefix('user')->group(
            function () {
                Route::resource(
                    'search', 'SearchResultController', [
                        'parameters'=> [
                            'search' => 'search',
                        ],
                        'except' => [
                            'create', 'destroy', 'edit', 'update'
                        ]
                    ]
                );
                
                Route::get('{user?}', 'UserController@show');
                //Route::get('{user}', 'PageController@index1');

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

        // 'section/' goes to 'index()' which returns 'all-sections' of the store..
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
        // 'category/' goes to 'index()' which returns 'all-categories' of the section..
        Route::get('category/create', 'CategorieController@create');
        Route::post('category/create', 'CategorieController@store');
        
        Route::resource(
            'section/{section}/category', 'CategorieController', [
                'parameters'=> [
                    'categorie' => 'category',
                ],
                'except' => [
                    'show'
                ]
            ]
        );
        // 'product/' goes to 'index()' which returns 'all-products' of the category..
        Route::get('product/create', 'ProductController@create');
        Route::post('product/create', 'ProductController@store');
        
        Route::resource(
            'section/{section}/category/{category}/product', 'ProductController', [
                'parameters'=> [
                    'categorie' => 'category',
                ],
                'except' => [
                    'show'
                ]
            ]
        );
        Route::get(
            'section/{section}/category/{category}/product/{product}/delete', 
            'ProductController@showDelete'
        );
        
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
        Route::resource(
            'page', 'PageController', [
                'parameters'=> [
                    'page' => 'page',
                ],
                'except' => [
                    'show'
                ]
            ]
        );
        Route::get('page/{page}/delete', 'PageController@showDelete');

        
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

        // MEMBERSHIP PLANS ARE A WISHLIST ITEM!!!
        // 'plan/' goes to 'index()' which returns 'all-pages' of the site..
        /* 
            Route::resource(
                'plan', 'PlanController', [
                    'parameters'=> [
                        'plan' => 'plan',
                    ],
                    'except' => [
                        'show', 'index'
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

        Route::get('signin/{page?}', 'UserController@signinRedirect');
        Route::post('signin/{page?}', 'UserController@signin');

    }
);

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

/// MEMBERSHIP PLANS ARE A WISHLIST ITEM!!
/* 
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

Route::get('page/{page}', 'PageController@show');


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
