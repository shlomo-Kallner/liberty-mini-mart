<?php

namespace App\Http\Controllers;

use App\Product,
    App\Categorie,
    App\Section,
    App\Page,
    App\User,
    App\Utilities\Functions\Functions,
    Illuminate\Http\Request,
    Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use App\ProductReview;

class ProductController extends MainController
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // PARTIAL!! Requires further Implementation!
        return self::getView($request, 'cms.forms.new.product', 'Create a New Product');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $sect = Section::getNamed($request->section);
        if (Functions::testVar($sect)) {
            $cat = $sect->getCategory($request->category);
            if (Functions::testVar($cat)) {
                if (Functions::testVar(
                    $product = Product::createNew(
                        $request->product, $request->url, $request->price,
                        $request->sale??0.0, $cat->id, $request->sticker??'',
                        $request->image, $request->description, $request->title,
                        $request->article, $request->payload??[]
                    )
                ))
                $product = $cat->getProduct($request->product);
                // 'store/section/{section}/category/{category}/product/{product}'...
                if (Functions::testVar($product)) {
                    if ($request->ajax()) {
                        //$user = User::getIdFromUserArray();
                        
                    } 
                }
            }
        } 
        abort(404);
    }

    public function postReview(Request $request) 
    {
        //$request->session()->put('lastRequest', $request->all());
        //return redirect('php');
        $sect = Section::getNamed($request->section);
        if (Functions::testVar($sect)) {
            $cat = $sect->getCategory($request->category);
            if (Functions::testVar($cat)) {
                $product = $cat->getProduct($request->product);
                // 'store/section/{section}/category/{category}/product/{product}'...
                if (Functions::testVar($product)) {
                    if ($request->ajax()) {
                        $user = User::getIdFromUserArray();
                        if (ProductReview::createNew(
                            $user, $product->id, $request->rating,
                            $request->content, false
                        )
                        ) {
                            return ProductReview::getContentArrays(
                                ProductReview::getFrom(
                                    ['user' => $user]
                                )
                            );
                        }
                    } 
                }
            }
        } 
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //dd($request->section, $request->category, $request->product);
        $sect = Section::getNamed($request->section);
        if (Functions::testVar($sect)) {
            $cat = $sect->getCategory($request->category);
            if (Functions::testVar($cat)) {
                $product = $cat->getProduct($request->product);
                // 'store/section/{section}/category/{category}/product/{product}'...
                if (Functions::testVar($product)) {
                    if ($request->ajax()) {
                        return new ProductResource($product);
                        // JsonResponse::create($product->toContentArray());
                    } else {
                        $breadcrumbs = Page::getBreadcrumbs( 
                            Page::genBreadcrumb($product->title, $product->getFullUrl('store')),
                            [
                                Page::genBreadcrumb('Store', 'store'),
                                Page::genBreadcrumb($sect->title, $sect->getFullUrl('store')),
                                Page::genBreadcrumb($cat->title, $cat->getFullUrl('store')),
                            ]
                        );
                        //dd($product);
                        $content_data = [
                            'product' => $product->toFull('store'),
                            'bestsellers' => Product::getBestsellers(),
                        
                        ];
                        return parent::getView(
                            $request, 'content.product', 
                            'Product: ' . $request->product, 
                            $content_data, false, $breadcrumbs
                        );
                    }
                }
            }
        } 
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function showDelete(Request $request)
    {
        // display 'ARE YOU SURE' PAGE...
    }

    public function test(Request $request)
    {
        return static::getView($request, 'content.product', 'TEST-PRODUCT', [], true);
    }

    public function testPost(Request $request)
    {
        //return json_encode(__METHOD__);
        //dd($request);
        return response()->json(
            [
                //'data' => serialize($request->json("parameters")),
                'method' => __METHOD__,
                'data' => serialize($request->post()),
            ]
        );
    }
}
