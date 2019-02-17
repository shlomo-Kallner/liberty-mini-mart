<?php

namespace App\Http\Controllers;

use App\Categorie,
    App\Utilities\Functions\Functions,
    App\Product,
    App\Section,
    App\Page,
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
        $sect = Section::getNamed($request->section);
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
            $bcLinks[] = Page::genBreadcrumb(
                $sect->title, 
                $sect->getFullUrl(
                    $catData['BaseUrl'], $catData['FullUrl']
                )
            );
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
                $content, false, $breadcumbs, null,
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
        return self::getView($request, 'cms.forms.new.category', 'Create a New Category');
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //dd($categorie);
        //dd($request->section, $request->category);
        //$categorie
        $sect = Section::getNamed($request->section);
        //dd($sect->id);
        //dd($sect);
        if (Functions::testVar($sect)) {
            /* 
                $cat = Categorie::where(
                    [
                        ['section_id', $sect->id],
                        ['url', $request->category]
                    ]
                )->first(); 
            */
            $cat = $sect->getCategory($request->category);
            // 'store/section/{section}/category/{category}/product/{product}'...
            //$sect_url = 'store/section/'. $sect->url;
            //$cat_url = $sect_url . '/category/' . $request->category;
            $breadcrumbs = Page::getBreadcrumbs( 
                Page::genBreadcrumb($cat->title, $cat->getFullUrl('store')),
                [
                    Page::genBreadcrumb('Store', 'store'),
                    Page::genBreadcrumb($sect->title, $sect->getFullUrl('store'))
                ]
            );
            //dd($cat);
            // getting the products of the category..
            $content = [
                'items' => Product::getProductsFor(
                    $cat->products, 'store', Product::TO_MINI_TRANSFORM,
                    true, 1, false
                ),
                'bestsellers' => Product::getBestsellers(3, 'store', true, 1),
            
            ];
            //self::$data['products'] = Product::getProductsForCategory($cat->id, 'mini', $cat_url);
            //self::$data['products'] = $prods;
            //dd($products);
            return parent::getView(
                $request, 'content.items_list', $cat->title, 
                $content, false, $breadcrumbs
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

    public function test(Request $request)
    {
        return static::getView($request, 'content.category', 'DEMO-CATEGORY', [], true);
    }

}
