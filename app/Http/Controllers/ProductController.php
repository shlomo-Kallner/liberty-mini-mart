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
    App\Http\Resources\ProductResource,
    App\ProductReview,
    Illuminate\Http\Request,
    Illuminate\Http\Response,
    Illuminate\Http\Resources\Json\Resource,
    Illuminate\Http\JsonResponse,
    Illuminate\Support\Facades\Validator,
    Illuminate\Support\Str,
    Intervention\Image\Facades\Image as ImageTool,
    Intervention\Image\Size, 
    Intervention\Image\Image as ImageFile,
    Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductController extends MainController
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
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
            $useGetAll = true;
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
            } elseif ($useGetAll) {
                $products = [];
                $products['items'] = Product::getAllWithTransform(
                    $productsData['Transform'], $productsData['Dir'], 
                    $productsData['WithTrashed'], $productsData['BaseUrl'], 
                    $productsData['UseTitle'], $productsData['FullUrl'], 
                    1, $productsData['Default'], $productsData['UseBaseMaker']
                );
            } else {
                $tmp = Product::getOrderedBy(
                    $productsData['Dir'], $productsData['WithTrashed']
                )->paginate($productsData['NumShown']);
                $products = Product::makeBasePaginatedItemsArray(
                    Product::getFor(
                        $tmp->getCollection(), $productsData['BaseUrl'], 
                        $productsData['Transform'], $productsData['UseTitle'], 
                        1, $productsData['WithTrashed'], $productsData['FullUrl'], 
                        $productsData['Default'], $productsData['UseBaseMaker'], 
                        $productsData['Dir']
                    ), 
                    $tmp
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
                        Product::genUrlFragment($productsData['BaseUrl'], $productsData['FullUrl'])
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
        UserSession::updateRegenerate(
            $request, intval(User::getIdFromUserArray(false))
        );
        abort(404);
    }

    /**
     * Display a "Name Listing" of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
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
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
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
        $BaseUrl = Functions::isAdminPath($request->path()) ? 'admin/store' : 'store';
        $content = [
            'lists' => [
                'sections' => $slist
            ],
            'hasName' => 'true',
            'hasTitle' => 'true',
            'hasUrl' => 'true',
            'hasDescription' => 'true',
            'hasArticle' => 'true',
            'hasSticker' => 'true',
            'hasImage' => 'true',
            'hasParent' => 'true',
            'parentName' => 'Category',
            'parentId' => 'category',
        ];
        if (Functions::testVar($request->section) && Functions::testVar($request->category)) {
            $sect = Section::getNamed($request->section);
            if (Functions::testVar($sect)) {
                $clist = Categorie::getNameListingOf($sect->categories);
                $cat = $sect->getCategory($request->category);
                if (Functions::testVar($cat)) {
                    $content['parentList'] = $clist;
                    $content['hasSelectedParent'] = 'true';
                    $content['selectedParent'] = $cat->toNameListing();
                    $content['hasSelectedSection'] = 'true';
                    $content['selectedSection'] = $sect->toNameListing();
                    $BaseUrl = $cat->getFullUrl($BaseUrl, false);
                }
            }
        } 
        $content['thisURL'] = Product::genUrlFragment($BaseUrl, $tmpData['FullUrl']);
        $bcLinks = [];
        $bcLinks[] = self::getHomeBreadcumb();
        if (Functions::isAdminPath($request->path())) {
            $bcLinks[] = CmsController::getAdminBreadcrumb();
        }
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb(
                'Product Creation Form', 
                Product::genUrlFragment($BaseUrl, $tmpData['FullUrl'])
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(), self::creationValidationRules(), 
            self::creationValidationMessages()
        );
        if ($validator->passes()) {    
            //
            $sect = Section::getNamed($request->section);
            if (Functions::testVar($sect)) {
                $cat = $sect->getCategory($request->category);
                if (Functions::testVar($cat)) {
                    
                    //$request->file('key', 'default');
                    //$request->hasFile('key');
                    if ($request->hasFile('image')) {
                        
                        $image_id = Image::storeAndCreateNewImage(
                            $request->file('image'), 'products', 
                            $request->title, $request->description, 
                            false, 0
                        );
                        /* 
                            //$tmpPath = 'tmp/products';
                            $filename = explode('.', $file->getClientOriginalName())[0] 
                                . '_'. Functions::getDateTimeStr('_', '-', '-');
                            $ext = $file->getClientOriginalExtension();
                            $fullFilename = $filename . '.' . $ext;
                            // storing original file..
                            //$file->storeAs($tmpPath, $fullFilename);
                            $pubPath = public_path('images' . DIRECTORY_SEPARATOR . 'products');
                            $dbPath = 'images/products';
                            $img = ImageTool::make($file)->resize(300, 200);  //($path . '/' . $fullFilename);
                            $fullFilenameWithPath = $pubPath . DIRECTORY_SEPARATOR . $fullFilename;
                            
                                // if (file_exists($fullFilenameWithPath)) {
                                //     Log::info("file ${$fullFilenameWithPath} already exists!");
                                // } 
                                // $dirPerms = 0x4000 | 0644;
                                // dd($dirPerms, 0x4000, 0644, chmod($pubPath, $dirPerms));
                                // if (fileperms($pubPath) === 0) {
                                // ///if (chmod($pubPath, 0644))
                                // } 
                        
                            $img->save($fullFilenameWithPath);
                            $image_id = Functions::getVar(
                                Image::createNew(
                                    $fullFilename, $dbPath, 
                                    $request->title, $request->description,
                                    false
                                ), 0
                            ); 
                        */
                    } else {
                        $image_id = 0;
                    }
                    // Log::info("image_id: " . $image_id);
                    $article_id = Article::createNew(
                        $request->article, $request->title, 
                        null, $request->subheading??'', false
                    );
                    // Log::info("article_id: " . $article_id);
                    $product = Product::createNew(
                        $request->name, $request->url, $request->price,
                        $request->sale??0.0, $cat, $request->sticker??'',
                        $image_id, $request->description, $request->title,
                        $article_id, $request->payload??[], 1, true
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
                            self::addMsg('Product ' . $product->name . ' , ' . $product->title . ' Creation Successfully!');
                            UserSession::updateRegenerate(
                                $request, intval(User::getIdFromUserArray(false))
                            );
                            //$request->session()->regenerate();
                            return redirect('admin');
                        }
                    }
                    Log::info("Uhhh, if we got here then PRODUCT CREATION FAILED!!!");
                }
            } 
        }
        // UserSession::updateRegenerate(
        //     $request, intval(User::getIdFromUserArray(false))
        // );
        $path = $request->path();
        $path = Str::contains($path, 'create') ? $path : $path . '/create';
        // return redirect($path)
        //     ->withErrors($validator)
        //     ->withInput($request->except('image'));
        return UserSession::updateRedirect(
            $request, $path, $validator, 
            $request->except('image')
        );
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
        abort(404); /// this is an AJAX route!
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
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if (Functions::testVar($request->section) && Functions::testVar($request->category)) {
            $section = Section::getNamed(
                $request->section, true, null, false
            );
            //Log::info('we DO have Section ' . $request->section . ' ...');
            if (Functions::testVar($section)) {
                $category = $section->getCategory($request->category, true);
                //Log::info('we DO have Category ' . $request->category . ' ...');
                if (Functions::testVar($category) && Functions::testVar($request->product)) {
                    $product = $category->getProduct($request->product, true);
                    if (Functions::testVar($product)) {
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
                        $content = [
                            'lists' => [
                                'sections' => Section::getNameListing(
                                    false, $tmpData['Dir'], $tmpData['UseBaseMaker']
                                )
                            ],
                            'hasName' => 'true',
                            'hasTitle' => 'true',
                            'hasUrl' => 'true',
                            'hasDescription' => 'true',
                            'hasArticle' => 'true',
                            'hasImage' => 'true',
                            'hasSticker' => 'true',
                            'hasParent' => 'true',
                            'parentName' => 'Category',
                            'parentId' => 'newcategory',
                            'parentList' => Categorie::getNameListingOf($section->categories),
                            'hasSelectedParent' => 'true',
                            'selectedParent' => $category->toNameListing(),
                            'hasSelectedSection' => 'true',
                            'selectedSection' => $section->toNameListing(),
                            'name' => $product->name,
                            'title' => $product->title,
                            'url' => $product->url,
                            'description' => $product->description,
                            'article' => $product->article->article,
                            'sticker'=> $product->sticker??'',
                            'price' => $product->price??'',
                            'sale' => $product->sale??'',
                            'subHeading' => $product->article->subheading,
                            'hasSubHeading' => Functions::testVar($product->article->subheading) ? 'true'  : '',
                            'image' => $product->getImageArray(),
                            'thisURL' => $product->getFullUrl($tmpData['BaseUrl'], $tmpData['FullUrl']),
                        ]; 
                        $BaseUrl = Functions::isAdminPath($request->path()) ? 'admin/store' : 'store';
                        $bcLinks = [];
                        $bcLinks[] = self::getHomeBreadcumb();
                        $bcLinks[] = Page::genBreadcrumb(
                            'Our Products', 
                            Product::genUrlFragment($BaseUrl, !$request->ajax())
                        );
                        if (Functions::isAdminPath($request->path())) {
                            $bcLinks[] = CmsController::getAdminBreadcrumb();
                        }
                        $breadcrumbs = Page::getBreadcrumbs(
                            Page::genBreadcrumb(
                                'Product Modification Form', 
                                Page::genUrlFragment($BaseUrl, !$request->ajax())
                            ),
                            $bcLinks
                        );
                        return self::getView(
                            $request, 'cms.forms.edit.product', 
                            'Modify Existing Product: ' . $product->title,
                            $content, false, $breadcrumbs, null, 
                            Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
                            : null
                        );  
                    }
                }
            }
        }
        Log::info('we are abotring! at' . __METHOD__);
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $section = Section::getNamed($request->section);
        $sect = Section::getNamed($request->newsection);
        if (Functions::testVar($sect) && Functions::testVar($section)) {
            $category = $section->getCategory($request->category);
            $cat = $sect->getCategory($request->newcategory);
            if (Functions::testVar($cat) && Functions::testVar($category)) {
                $product = $category->getProduct($request->product);
                if (Functions::testVar($product)) {
                    
                    $validator = Validator::make(
                        $request->all(), self::modificationValidationRules(
                            $product->url, $product->name
                        ), 
                        self::modificationValidationMessages()
                    );
                    if ($validator->passes()) {
                        //$request->file('key', 'default');
                        //$request->hasFile('key');
                        if ($request->hasFile('image')) {
                            
                            $image_id = Image::storeAndCreateNewImage(
                                $request->file('image'), 'products', 
                                $request->title, $request->description, 
                                false, $product->image_id
                            );
                        } else {
                            $image_id = $product->image_id;
                        }
                        // Log::info("image_id: " . $image_id);
                        $article_id = Article::createNew(
                            $request->article, $request->title, 
                            null, $request->subheading??'', false
                        );
                        // Log::info("article_id: " . $article_id);
                        $saved = $product->updateWith(
                            $request->name, $request->url, $request->price, 
                            $request->sale??0.0, $cat, $request->sticker??'',
                            $image_id, $request->description,
                            $request->title, $article_id, $request->payload??[],
                            1, true
                        );
                        // $product = $cat->getProduct($request->product);
                        // 'store/section/{section}/category/{category}/product/{product}'...
                        if (Functions::testVar($saved) && $product === $saved) {
                            if ($request->ajax()) {
                                //$user = User::getIdFromUserArray();
                                // $products = $cat->getProducts(false);
                                // $pages = $products->paginate(12);
                                // $pages->withPath();
                            } else {
                                self::addMsg('Product ' . $product->name . ' , ' . $product->title . ' Modified Successfully!');
                                UserSession::updateRegenerate(
                                    $request, intval(User::getIdFromUserArray(false))
                                );
                                //$request->session()->regenerate();
                                return redirect('admin/store/product');
                            }
                        }
                        self::addMsg(
                            'Attempt to Modify Product ' . $product->name . ' , ' . $product->title 
                            . ' collided with another product on new Name, URL, Section, or Category!'
                            . ' Please Try again.'
                        );
                        //Log::info("Uhhh, if we got here then PRODUCT MODIFICATION FAILED!!!");
                    }
                }
            }
        }
        // UserSession::updateRegenerate(
        //     $request, intval(User::getIdFromUserArray(false))
        // );
        $path = $request->path();
        $path = Str::contains($path, 'edit') ? $path : $path . '/edit';
        // return redirect($path)
        //     ->withErrors($validator)
        //     ->withInput($request->except('image'));
        return UserSession::updateRedirect(
            $request, $path, $validator, 
            $request->except('image')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (Functions::testVar($request->section) && Functions::testVar($request->category)) {
            $section = Section::getNamed(
                $request->section, true, null, false
            );
            //Log::info('we DO have Section ' . $request->section . ' ...');
            if (Functions::testVar($section)) {
                $category = $section->getCategory($request->category, true);
                //Log::info('we DO have Category ' . $request->category . ' ...');
                if (Functions::testVar($category) && Functions::testVar($request->product)) {
                    $product = $category->getProduct($request->product, true);
                    if (Functions::testVar($product)) {
                        $product->delete();
                        $errors = [];
                        if ($product->trashed()) {
                            self::addMsg('Product ' . $product->name . ' , ' . $product->title . ' Deleted Successfully!');
                        } else {
                            $errors = [
                                'error' => 'Product ' . $product->name . ' , ' . $product->title . ' Deletion failed!'
                            ];
                        }
                        return UserSession::updateRedirect(
                            $request, 'admin/store/product', $errors
                        );
                    }
                }
            }
        }
        Log::info('we are abotring! at' . __METHOD__);
        UserSession::updateAndAbort($request, 404);
    }

    public function showDelete(Request $request)
    {
        // display 'ARE YOU SURE' PAGE...
        if (Functions::testVar($request->section) && Functions::testVar($request->category)) {
            $section = Section::getNamed(
                $request->section, true, null, false
            );
            //Log::info('we DO have Section ' . $request->section . ' ...');
            if (Functions::testVar($section)) {
                $category = $section->getCategory($request->category, true);
                //Log::info('we DO have Category ' . $request->category . ' ...');
                if (Functions::testVar($category) && Functions::testVar($request->product)) {
                    $product = $category->getProduct($request->product, true);
                    if (Functions::testVar($product)) {
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
                        $content = [
                            'lists' => [
                                'sections' => Section::getNameListing(
                                    false, $tmpData['Dir'], $tmpData['UseBaseMaker']
                                )
                            ],
                            'hasName' => 'true',
                            'hasTitle' => 'true',
                            'hasUrl' => 'true',
                            'hasDescription' => 'true',
                            'hasArticle' => 'true',
                            'hasImage' => 'true',
                            'hasParent' => 'true',
                            'parentName' => 'Category',
                            //'parentId' => 'newcategory',
                            //'parentList' => Categorie::getNameListingOf($section->categories),
                            //'hasSelectedParent' => 'true',
                            'selectedParent' => $category->toUrlListing(
                                $tmpData['BaseUrl'], $tmpData['FullUrl'], $tmpData['UseBaseMaker']
                            ),
                            //'hasSelectedSection' => 'true',
                            'selectedSection' => $section->toUrlListing(
                                $tmpData['BaseUrl'], $tmpData['FullUrl'], $tmpData['UseBaseMaker']
                            ),
                            'name' => $product->name,
                            'title' => $product->title,
                            'url' => $product->url,
                            'description' => $product->description,
                            'article' => $product->article->article,
                            'price' => $product->price,
                            'sale' => $product->sale,
                            'subHeading' => $product->article->subheading,
                            'hasSubHeading' => Functions::testVar($product->article->subheading) ? 'true'  : '',
                            'image' => $product->getImageArray(),
                            'thisURL' => $product->getFullUrl($tmpData['BaseUrl'], $tmpData['FullUrl']),
                        ]; 
                        $BaseUrl = Functions::isAdminPath($request->path()) ? 'admin/store' : 'store';
                        $bcLinks = [];
                        $bcLinks[] = self::getHomeBreadcumb();
                        $bcLinks[] = Page::genBreadcrumb(
                            'Our Products', 
                            Product::genUrlFragment($BaseUrl, !$request->ajax())
                        );
                        if (Functions::isAdminPath($request->path())) {
                            $bcLinks[] = CmsController::getAdminBreadcrumb();
                        }
                        $breadcrumbs = Page::getBreadcrumbs(
                            Page::genBreadcrumb(
                                'Product Deletion Form', 
                                Page::genUrlFragment($BaseUrl, !$request->ajax())
                            ),
                            $bcLinks
                        );
                        return self::getView(
                            $request, 'cms.forms.delete.product', 
                            'Are You Sure That You Want To Delete Product: ' . $product->title . ' ?',
                            $content, false, $breadcrumbs, null, 
                            Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
                            : null
                        );  
                    }
                }
            }
        }
        Log::info('we are aborting! at' . __METHOD__);
        UserSession::updateAndAbort($request, 404);
    }

    //// Validation Rules and messages

    /**
     * Get the validation rules that apply to the creation request.
     *
     * @return array
     */
    static public function creationValidationRules()
    {
        return [
            //
            'section' => 'required|max:255|string|min:3',
            'category' => 'required|max:255|string|min:3',
            'image' => 'required|file|image',
            'article' => 'required|max:255000|string|min:3',
            'subheading' => 'string|nullable|max:255',
            'title' => 'required|max:255|string|min:3',
            'name' => 'required|max:255|string|min:3',
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required', 'max:255', 'string', 'min:3',
                'regex:'. Functions::getURLRegexStr(),
            ],
            'price' => 'required|numeric',
            'sale' => 'numeric|nullable',
            'sticker' => [
                'string', 'nullable', 'regex:/new|sale/'
            ],
        ];
    }

    /**
     * Get the validation rules that apply to the modification request.
     *
     * @return array
     */
    static public function modificationValidationRules($url, $name)
    {
        return [
            //
            'newsection' => 'required|max:255|string|min:3',
            'newcategory' => 'required|max:255|string|min:3',
            'image' => 'nullable|file|image',
            'article' => 'required|max:255000|string|min:3',
            'subheading' => 'string|nullable|max:255',
            'title' => 'required|max:255|string|min:3',
            'name' => [
                'required', 'max:255', 'string', 'min:3',
                Rule::unique('products', 'name')->ignore($name, 'name')
            ],
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required', 'max:255', 'string', 'min:3',
                'regex:'. Functions::getURLRegexStr(),
                Rule::unique('products', 'url')->ignore($url, 'url')
            ],
            'price' => 'required|numeric',
            'sale' => 'numeric|nullable',
            'sticker' => [
                'string', 'nullable', 'regex:/new|sale/'
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    static public function creationValidationMessages()
    {
        return [
            'title.required' => 'A title is required',
            'category.required'  => 'A category is required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    static public function modificationValidationMessages()
    {
        return [
            'title.required' => 'A title is required',
            'category.required'  => 'A category is required',
        ];
    }


    /// testing

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
