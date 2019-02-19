<?php

namespace App\Http\Controllers;

use App\Categorie,
    App\Utilities\Functions\Functions,
    Illuminate\Support\Facades\Log, 
    Illuminate\Validation\Rule,
    Illuminate\Support\Facades\Validator,
    Illuminate\Support\Str,
    App\Product,
    App\Section,
    App\Page,
    App\Image,
    App\Article,
    App\UserSession,
    Illuminate\Http\Request;

class CategorieController extends MainController
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     * Basicly you get here if either you did not enter 
     * the category's url fragment in the url or ..
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin; // false;
        $catData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'categoriesPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => $usePagination 
                ? Categorie::TO_MINI_TRANSFORM
                : Categorie::TO_TABLE_ARRAY_TRANSFORM, 
        ];
        $sect = Section::getNamed($request->section??'', $catData['WithTrashed']);
        if (Functions::testVar($sect)) {
            return UserSession::updateRedirect(
                $request, $sect->getFullUrl($catData['BaseUrl'], $catData['FullUrl'])
            );
        } else {
            $content = [];
            if ($usePagination) {
                $pv = Categorie::getPagingVars(
                    $request, $catData['PagingFor'], $catData['NumShown'],
                    $catData['Dir']
                );
                if (Functions::testVar($pv)) {
                    $catData['PageNum'] = $pv['pageNum'];
                    $catData['ViewNum'] = $pv['viewNum'];
                    if (Functions::hasPropKeyIn($pv, 'limit')) {
                        $catData['NumShown'] = $pv['limit'];
                    }
                } 
                $cats = Categorie::getAllWithPagination(
                    $catData['Transform'], $catData['PageNum'], 
                    $catData['NumShown'], $catData['PagingFor'], 
                    $catData['Dir'], $catData['WithTrashed'], $catData['BaseUrl'], 
                    $catData['ListUrl'], $catData['ViewNum'], 
                    $catData['FullUrl'], $catData['UseTitle'], 
                    $catData['Version'], $catData['Default'], 
                    $catData['UseBaseMaker']
                );
                if ($catData['UseGetSelf']) {
                    $children = Functions::countHas($cats) 
                        ? $cats['items'] : null;
                    $paginator = Functions::countHas($cats) 
                        ? $cats['pagination'] : null;
                    $content = Page::getSelf(
                        $catData['BaseUrl'], $catData['WithTrashed'],
                        $catData['FullUrl'], $children, 
                        $paginator, $catData['PagingFor']
                    );
                } else {
                    $content = $cats;
                    // $content['items'] = $cats['items'];
                    // $content['pagination'] = $cats['pagination'];
                }
                
            } else {
                $content['items'] = Categorie::getAllWithTransform(
                    $catData['Transform'], $catData['Dir'], 
                    $catData['WithTrashed'], $catData['BaseUrl'], 
                    $catData['UseTitle'], $catData['FullUrl'], 
                    $catData['Version'], $catData['Default'], 
                    $catData['UseBaseMaker']
                );
            }
            $title = 'All Our Categories';
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if ($is_admin) {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
            } else {
                $bcLinks[] = ShopController::getStoreBreadcrumbs($request);
                $content['bestsellers'] = Product::getBestsellers();
            }
            // $bcLinks[] = Page::genBreadcrumb(
            //     $sect->title, 
            //     $sect->getFullUrl(
            //         $catData['BaseUrl'], $catData['FullUrl']
            //     )
            // );
            $breadcrumbs = Page::getBreadcrumbs( 
                Page::genBreadcrumb($title, $request->path()),
                $bcLinks
            );
            if ($is_admin) {
                $viewName = 'cms.items_table';
            } elseif ($usePagination) {
                $viewName = 'content.items_list';
                $content['component'] = 'lib.themewagon.product_mini';
            } else {
                $viewName = 'content.items_list';
                $content['component'] = 'lib.themewagon.product_mini';
            }
            return self::getView(
                $request, $viewName, $title, 
                $content, false, $breadcrumbs, null,
                $is_admin 
                ? CmsController::getAdminSidebar() : null
            );
        }
    }

    /**
     * Display a "Name Listing" of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $sect = Section::getNamed($request->section);
        if (Functions::testVar($sect)) {
            return Categorie::getNameListingOf($sect->categories);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin; // false;
        $catData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'categoriesPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => $usePagination 
                ? Categorie::TO_MINI_TRANSFORM
                : Categorie::TO_TABLE_ARRAY_TRANSFORM, 
        ];
        $slist = Section::getNameListing(
            $catData['WithTrashed'], $catData['Dir'], $catData['UseBaseMaker']
        );
        $content = [
            'parentList' => $slist,
            'hasName' => 'true',
            'hasTitle' => 'true',
            'hasUrl' => 'true',
            'hasDescription' => 'true',
            'hasArticle' => 'true',
            'hasSticker' => 'true',
            'hasImage' => 'true',
            'hasParent' => 'true',
            'parentName' => 'Section',
            'parentId' => 'secturl',
        ];
        $sect = Section::getNamed($request->section??'', $catData['WithTrashed']);
        if (Functions::testVar($sect)) {
            $content['hasSelectedParent'] = 'true';
            $content['selectedParent'] = $sect->toNameListing();
            $catData['BaseUrl'] = $sect->getFullUrl($catData['BaseUrl'], false);
        } 
        $content['thisURL'] = Categorie::genUrlFragment($catData['BaseUrl'], $catData['FullUrl']);
        $bcLinks = [];
        $bcLinks[] = self::getHomeBreadcumb();
        if (Functions::isAdminPath($request->path())) {
            $bcLinks[] = CmsController::getAdminBreadcrumb();
        }
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb(
                'Category Creation Form', 
                Categorie::genUrlFragment($catData['BaseUrl'], $catData['FullUrl'])
            ),
            $bcLinks
        );
        return self::getView(
            $request, 'cms.forms.new.category', 'Create a New Category',
            $content, false, $breadcrumbs, null, 
            Functions::isAdminPath($request->path()) 
            ? CmsController::getAdminSidebar()
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
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin; // false;
        $catData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'categoriesPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => $usePagination 
                ? Categorie::TO_MINI_TRANSFORM
                : Categorie::TO_TABLE_ARRAY_TRANSFORM, 
        ];
        $validator = Validator::make(
            $request->all(), self::creationValidationRules(), 
            self::creationValidationMessages()
        );
        $path = $request->path();
        $passes = $validator->passes();
        if ($passes) {

            if ($request->hasFile('image')) {
                        
                $image_id = Image::storeAndCreateNewImage(
                    $request->file('image'), 'categories', 
                    $request->title, $request->description, 
                    false, 0
                );
            } else {
                $image_id = 0;
            }
            // Log::info("image_id: " . $image_id);
            $article_id = Article::createNew(
                $request->article, $request->title, 
                null, $request->subheading??'', false
            );
            // Log::info("article_id: " . $article_id);
            $section_id = Section::getIdFrom($request->secturl, false, 0);
            $category = Categorie::createNew(
                $request->name, $request->url, $request->description, 
                $request->title, $article_id, $section_id,
                $image_id, $request->sticker, true
            );
            if (Functions::testVar($category)) {
                self::addMsg('Category ' . $category->name . ' , ' . $category->title . ' Creation Successfull!');
                $path = 'admin/store/category';
            } else {
                Log::info("Uhhh, if we got here then Category CREATION FAILED!!!");
                $passes = null;
            }
        }
        $path = $passes || Str::contains($path, 'create') ? $path : $path . '/create';
        return UserSession::updateRedirect(
            $request, $path, $validator, 
            $request->except('image')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin;
        $tmpData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'categoriesPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UsePagination' => $usePagination,
            'Transform' => $usePagination 
                ? Product::TO_MINI_TRANSFORM
                : Product::TO_TABLE_ARRAY_TRANSFORM,
        ];   
        $tmpData['BaseUrl'] = $request->ajax() ? 'api/' . $tmpData['BaseUrl'] : $tmpData['BaseUrl'];
        $sect = Section::getNamed($request->section);
        if (Functions::testVar($sect)) {
            $cat = $sect->getCategory($request->category, $tmpData['WithTrashed']);
            // 'store/section/{section}/category/{category}/product/{product}'...
            //$sect_url = 'store/section/'. $sect->url;
            //$cat_url = $sect_url . '/category/' . $request->category;
           //dd($cat);
            // getting the products of the category..
            $content = [
                'items' => Product::getFor(
                    $cat->products, $tmpData['BaseUrl'], 
                    $tmpData['Transform'], $tmpData['UseTitle'], 
                    $tmpData['Version'], $tmpData['WithTrashed'],
                    $tmpData['FullUrl'], $tmpData['Default'], 
                    $tmpData['UseBaseMaker'], $tmpData['Dir']
                ),
            ];
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if ($is_admin) {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
                $viewName = 'cms.items_table';
            } else {
                $bcLinks[] = ShopController::getStoreBreadcrumbs($request);
                $content['bestsellers'] = Product::getBestsellers(
                    3, $tmpData['BaseUrl'], $tmpData['UseTitle'], 
                    $tmpData['FullUrl'], $tmpData['Version'], 
                    $tmpData['WithTrashed'], $tmpData['Default'], 
                    $tmpData['UseBaseMaker'], $tmpData['Dir']
                );
                $viewName = 'content.items_list';
                $content['component'] = 'lib.themewagon.product_mini';
            }
            $bcLinks[] = Page::genBreadcrumb(
                $sect->title, 
                $sect->getFullUrl($tmpData['BaseUrl'], $tmpData['FullUrl'])
            );
            //self::$data['products'] = Product::getProductsForCategory($cat->id, 'mini', $cat_url);
            //self::$data['products'] = $prods;
            //dd($products);
            $breadcrumbs = Page::getBreadcrumbs( 
                Page::genBreadcrumb(
                    $cat->title, $cat->getFullUrl($tmpData['BaseUrl'], $tmpData['FullUrl'])
                ),
                $bcLinks
            );
            return parent::getView(
                $request, $viewName, $cat->title, 
                $content, false, $breadcrumbs, null, 
                Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
                : null
            );
        } 
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Categorie  $categorie
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
     * @param  \App\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categorie  $categorie
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
            'secturl' => 'required|max:255|string|min:3|exists:sections,url',
            'image' => 'required|file|image',
            'article' => 'required|max:255000|string|min:3',
            'subheading' => 'string|nullable|max:255',
            'title' => 'required|max:255|string|min:3',
            'name' => 'required|max:255|string|min:3|unique:categories,name',
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required', 'max:255', 'string', 'min:3',
                'regex:'. Functions::getURLRegexStr(),
                'unique:categories,url'
            ],
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
            'secturl' => 'required|max:255|string|min:3|exists:sections,url',
            'image' => 'nullable|file|image',
            'article' => 'required|max:255000|string|min:3',
            'subheading' => 'string|nullable|max:255',
            'title' => 'required|max:255|string|min:3',
            'name' => [
                'required', 'max:255', 'string', 'min:3',
                Rule::unique('categories', 'name')->ignore($name, 'name')
            ],
            'description' => 'required|max:255|string|min:3',
            'url' => [
                'required', 'max:255', 'string', 'min:3',
                'regex:'. Functions::getURLRegexStr(),
                Rule::unique('categories', 'url')->ignore($url, 'url')
            ],
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
        ];
    }


    /// testing!!!

    public function test(Request $request)
    {
        return static::getView($request, 'content.category', 'DEMO-CATEGORY', [], true);
    }

}
