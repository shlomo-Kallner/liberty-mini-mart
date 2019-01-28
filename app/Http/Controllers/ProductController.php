<?php

namespace App\Http\Controllers;

use App\Product,
    App\Article,
    App\Categorie,
    App\Section,
    App\Page,
    App\Image,
    App\User,
    App\UserSession,
    App\Utilities\Functions\Functions,
    Illuminate\Http\Request,
    Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use App\ProductReview;
use Intervention\Image\Facades\Image as ImageTool;
use Intervention\Image\Size, 
    Intervention\Image\Image as ImageFile;
use App\Http\Requests\ProductRequest;

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
        if (Functions::testVar($request->section) && Functions::testVar($request->category)) {
            
            $sect = Section::getNamed($request->section);
            if (Functions::testVar($sect)) {
                $cat = $sect->getCategory($request->category);
                if (Functions::testVar($cat)) {
                    UserSession::updateRegenerate(
                        $request, intval(User::getIdFromUserArray(false))
                    );
                    //$request->session()->regenerate();
                    return redirect(
                        $cat->getFullUrl(
                            $request->ajax() 
                                ? 'api/store' 
                                : 'store'
                            )
                    );
                }
            }
        } else {
            $productsData = [
                'PageNum' => 0,
                'NumShown' => 12,
                'PagingFor' => 'productsPanel',
                'Dir' => 'asc',
                'WithTrashed' => Functions::isAdminPath($request->path()),
                'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin/store' : 'store',
                'ViewNum' => 0,
                'UseBaseMaker' => $request->ajax(),
                'Default' => [],
                'UseTitle' => true,
                'FullUrl' => !$request->ajax(),
                'ListUrl' => $request->path(),
                'UseGetSelf' => false,
                'Transform' => Product::TO_TABLE_ARRAY_TRANSFORM
            ];
            $pv = Product::getPagingVars(
                $request, $productsData['PagingFor'], $productsData['NumShown'],
                $productsData['Dir']
            );
            if (Functions::testVar($pv)) {
                $productsData['PageNum'] = $pv['pageNum'];
                $productsData['ViewNum'] = $pv['viewNum'];
                if (Functions::hasPropKeyIn($pv, 'limit')) {
                    $productsData['NumShown'] = $pv['limit'];
                }
                if (Functions::hasPropKeyIn($pv, 'order')) {
                    $productsData['Dir'] = $pv['order'];
                }
            } 
            $useSelfPaging = false;
            if ($useSelfPaging) {
                $products = Product::getAllWithPagination(
                    $productsData['Transform'], $productsData['PageNum'], 
                    $productsData['NumShown'], $productsData['PagingFor'], 
                    $productsData['Dir'], $productsData['WithTrashed'], 
                    $productsData['BaseUrl'], $productsData['ListUrl'], 
                    $productsData['ViewNum'], $productsData['FullUrl'], 
                    $productsData['UseTitle'], 1, $productsData['Default'], 
                    $productsData['UseBaseMaker']
                );
            } else {
                $products = [];
                $products['items'] = Product::getAllWithTransform(
                    $productsData['Transform'], $productsData['Dir'], 
                    $productsData['WithTrashed'], $productsData['BaseUrl'], 
                    $productsData['UseTitle'], $productsData['FullUrl'], 
                    1, $productsData['Default'], $productsData['UseBaseMaker']
                );
            }
            if ($request->ajax()) {
                return $products;
            } else {
                $title = 'Our Products';
                $products['type'] = 'Products';
                $bcLinks = [];
                $bcLinks[] = self::getHomeBreadcumb();
                if (Functions::isAdminPath($request->path())) {
                    $bcLinks[] = CmsController::getAdminBreadcrumb();
                }
                $breadcrumbs = Page::getBreadcrumbs(
                    Page::genBreadcrumb(
                        $title, 
                        Page::genUrlFragment($productsData['BaseUrl'], $productsData['FullUrl'])
                    ),
                    $bcLinks
                );
                return self::getView(
                    $request, 'cms.items_table', $title, $products, false, $breadcrumbs, null,
                    Functions::isAdminPath($request->path()) 
                    ? CmsController::getAdminSidebar() : null
                );
            }
        }
        abort(404);
    }

    public function list(Request $request)
    {
        $sect = Section::getNamed($request->section);
        if (Functions::testVar($sect)) {
            $cat = $sect->getCategory($request->category);
            if (Functions::testVar($cat)) {
                return Product::getNameListingOf($cat->products);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // PARTIAL!! Requires further Implementation!
        $tmpData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'productsPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => Product::TO_TABLE_ARRAY_TRANSFORM
        ];
        $slist = Section::getNameListing(false, $tmpData['Dir'], $tmpData['UseBaseMaker']);
        $content = [
            'lists' => [
                'sections' => $slist
            ],
            'hasName' => 'true',
            'hasTitle' => 'true',
            'hasUrl' => 'true',
            'hasArticle' => 'true',
            'hasImage' => 'true',
        ];
        if ($request->has('section') && $request->has('category')) {
            $sect = Section::getNamed($request->section);
            if (Functions::testVar($sect)) {
                $clist = Categorie::getNameListingOf($sect->categories);
                $cat = $sect->getCategory($request->category);
                if (Functions::testVar($cat)) {
                    $content['hasParent'] = 'true';
                    $content['parentList'] = $clist;
                    $content['hasSelectedParent'] = 'true';
                    $content['selectedParent'] = $cat->toNameListing();
                    $content['hasSelectedSection'] = 'true';
                    $content['selectedSection'] = $sect->toNameListing();
                }
            }
        } 
        $BaseUrl = Functions::isAdminPath($request->path()) ? 'admin/store' : 'store';
        $bcLinks = [];
        $bcLinks[] = self::getHomeBreadcumb();
        if (Functions::isAdminPath($request->path())) {
            $bcLinks[] = CmsController::getAdminBreadcrumb();
        }
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb(
                'Product Creation Form', 
                Page::genUrlFragment($BaseUrl, !$request->ajax())
            ),
            $bcLinks
        );
        return self::getView(
            $request, 'cms.forms.new.product', 'Create a New Product',
            $content, false, $breadcrumbs, null, 
            Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
            : null
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //
        $sect = Section::getNamed($request->section);
        if (Functions::testVar($sect)) {
            $cat = $sect->getCategory($request->category);
            if (Functions::testVar($cat)) {
                
                //$request->file('key', 'default');
                //$request->hasFile('key');
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $tmpPath = 'tmp/products';
                    $filename = $file->getClientOriginalName() 
                        . '_'. Functions::getDateTimeStr('_', '-', '-');
                    $ext = $file->getClientOriginalExtension();
                    $fullFilename = $filename . '.' . $ext;
                    // storing original file..
                    //$file->storeAs($tmpPath, $fullFilename);
                    $pubPath = 'public/images/products/';
                    $img = ImageTool::make($file)->resize(300, 200);  //($path . '/' . $fullFilename);
                    $img->save($pubPath . $fullFilename);
                    $image_id = Functions::getVar(
                        Image::createNew(
                            $fullFilename, $pubPath, 
                            $request->title, $request->description
                        ), 0
                    );
                } else {
                    $image_id = 0;
                }
                $article_id = Article::createNew(
                    $request->article, $request->title, 
                    null, $request->subheading??'', true, false
                );
                $product = Product::createNew(
                    $request->name, $request->url, $request->price,
                    $request->sale??0.0, $cat->id, $request->sticker??'',
                    $image_id, $request->description, $request->title,
                    $article_id, $request->payload??[], true
                );
                // $product = $cat->getProduct($request->product);
                // 'store/section/{section}/category/{category}/product/{product}'...
                if (Functions::testVar($product)) {
                    if ($request->ajax()) {
                        //$user = User::getIdFromUserArray();
                        $products = $cat->getProducts(false);
                        $pages = $products->paginate(12);
                        $pages->withPath();
                    } else {
                        UserSession::updateRegenerate(
                            $request, intval(User::getIdFromUserArray(false))
                        );
                        //$request->session()->regenerate();
                        return redirect('admin');
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
    public function edit(Request $request)
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
    public function update(ProductRequest $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
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
