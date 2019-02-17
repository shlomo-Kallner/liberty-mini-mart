<?php

namespace App\Http\Controllers;

use App\Section,
    App\Page,
    App\Product,
    App\Categorie,
    App\Utilities\Functions\Functions;
use Illuminate\Http\Request;

class SectionController extends MainController
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
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin; // false;
        $sectData = [
            'PageNum' => 0,
            'NumShown' => 6,
            'PagingFor' => 'sectionPanel',
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
                ? Section::TO_MINI_TRANSFORM
                : Section::TO_TABLE_ARRAY_TRANSFORM 
        ];
        $content = [];
        if ($usePagination) {
            $pv = Section::getPagingVars(
                $request, $sectData['PagingFor'], $sectData['NumShown'], $sectData['Dir']
            );
            if (Functions::testVar($pv)) {
                $sectData['PageNum'] = $pv['pageNum'];
                $sectData['ViewNum'] = $pv['viewNum'];
                if (Functions::hasPropKeyIn($pv, 'limit')) {
                    $sectData['NumShown'] = $pv['limit'];
                }
            } 
            $sections = Section::getAllWithPagination(
                $sectData['Transform'], $sectData['PageNum'], 
                $sectData['NumShown'], $sectData['PagingFor'], 
                $sectData['Dir'], $sectData['WithTrashed'], 
                $sectData['BaseUrl'], $sectData['ListUrl'], 
                $sectData['ViewNum'], $sectData['FullUrl'], 
                $sectData['UseTitle'], $sectData['Version'], 
                $sectData['Default'], $sectData['UseBaseMaker']
            );
            if ($sectData['UseGetSelf']) {
                $children = Functions::countHas($sections) 
                    ? $sections['items'] : null;
                $paginator = Functions::countHas($sections) 
                    ? $sections['pagination'] : null;
                $content = Section::getSelf(
                    $sectData['BaseUrl'], $sectData['WithTrashed'],
                    $sectData['FullUrl'], $children, 
                    $paginator, $sectData['PagingFor']
                );
            } else {
                $content = $sections;
            }
        } else {
            $content['items'] = Section::getAllWithTransform(
                $sectData['Transform'], $sectData['Dir'], 
                $sectData['WithTrashed'], $sectData['BaseUrl'], 
                $sectData['UseTitle'], $sectData['FullUrl'], 
                $sectData['Version'], $sectData['Default'], 
                $sectData['UseBaseMaker']
            );
        }
        if ($request->ajax()) {
            return $content;
        } else {
            // get a listing of all sections... 
            $title = 'All Our Sections';
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if (!$is_admin) {
                $content['bestsellers'] = Product::getBestsellers();
                $viewName = 'content.items_list';
                $content['component'] = 'lib.themewagon.product_mini';
                $bcLinks[] = ShopController::getStoreBreadcrumbs($request);
            } else {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
                $viewName = 'cms.items_table';
            }
            $breadcumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb(
                    $title,
                    Section::genUrlFragment($sectData['BaseUrl'], $sectData['FullUrl'])
                ),
                $bcLinks
            );
            return self::getView(
                $request, $viewName, $title, 
                $content, false, $breadcumbs, null,
                $is_admin 
                ? CmsController::getAdminSidebar() : null
            );
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
        $is_admin = Functions::isAdminPath($request->path());
        $usePagination = !$is_admin; // false;
        $sectData = [
            'PageNum' => 0,
            'NumShown' => 6,
            'PagingFor' => 'sectionPanel',
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
                ? Section::TO_MINI_TRANSFORM
                : Section::TO_TABLE_ARRAY_TRANSFORM 
        ];
        $content = [];
        return self::getView($request, 'cms.forms.new.section', 'Create a New Store Section');
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
        return Section::getNameListing();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (isset($request->section) && !empty($request->section)) {
            
            //dd($request->section, 'in show()');
            $section = Section::getNamed($request->section);
            //$section_items = Categorie::getCategoriesOfSection($section->id);
            //$section_items = $section->getCategories(false);

            /*
                getAllWithTransform(
                    $transform = null, string $dir = 'asc', 
                    bool $withTrashed = true, string $baseUrl = 'store', 
                    bool $useTitle = true, bool $fullUrl = false, 
                    int $version = 1, $default = [], 
                    bool $useBaseMaker = true
                )
                getAllWithPagination(
                    $transform, int $pageNum, int $numShown = 4, 
                    string $pagingFor = '', string $dir = 'asc', 
                    bool $withTrashed = true, string $baseUrl = 'store', 
                    string $listUrl = '', int $viewNumber = 0, 
                    bool $fullUrl = false, bool $useTitle = true, 
                    int $version = 1, $default = [], bool $useBaseMaker = true
                )
                getFor(
                    $args, string $baseUrl = 'store', $transform = null, 
                    bool $useTitle = true, int $version = 1, 
                    bool $withTrashed = true, bool $fullUrl = false, 
                    $default = [], bool $useBaseMaker = true,
                    string $dir = 'asc'
                )
                getForWithPagination(
                    $args, $transform, int $pageNum,
                    int $numShown = 4, string $pagingFor = '', 
                    string $listUrl = '', string $baseUrl = 'store', 
                    string $dir = 'asc', int $viewNumber = 0, 
                    bool $withTrashed = true, bool $useTitle = true, 
                    bool $fullUrl = false, int $version = 1, $default = [], 
                    bool $useBaseMaker = true
                )
                toContentArrayWithPagination(
                    string $baseUrl = 'store', int $version = 1, 
                    bool $useTitle = true, bool $withTrashed = true,
                    bool $fullUrl = false, int $pageNum = 0, 
                    int $numItemsPerPage = 4, string $pagingFor = '', 
                    int $viewNumber = 0, string $listUrl = '#', 
                    bool $useBaseMaker = true
                )
            */
            $tmpData = [
                'PageNum' => 0,
                'NumShown' => 12,
                'PagingFor' => 'section-content',
                'Dir' => 'asc',
                'WithTrashed' => Functions::isAdminPath($request->path()),
                'BaseUrl' => $request->ajax() ? 'api/store' : 'store',
                'ViewNum' => 0,
                'UseBaseMaker' => $request->ajax(),
                'Default' => [],
                'UseTitle' => true,
                'FullUrl' => !$request->ajax(),
                'ListUrl' => $request->path(),
                'UsePagination' => false,
                'Transform' => Categorie::TO_MINI_TRANSFORM,
                'Version' => 1,
            ];    
            if ($tmpData['UsePagination']) {
                $pagingFor = 'section-content';
                $pa = Section::getPagingVars(
                    $request, $tmpData['PagingFor'], $tmpData['NumShown'],
                    $tmpData['Dir']
                );
                if (Functions::testVar($pa)) {
                    $tmpData['ViewNum'] = $pa['viewNum'];
                    $tmpData['PageNum'] = $pa['pageNum'];
                    $tmpData['NumShown'] = $pa['limit'];
                    $tmpData['Dir'] = $pa['order'];
                }
                $section_data = Categorie::getForWithPagination(
                    $section->categories, $tmpData['Transform'], 
                    $tmpData['PageNum'], $tmpData['NumShown'], 
                    $tmpData['PagingFor'], $tmpData['ListUrl'], 
                    $tmpData['BaseUrl'], $tmpData['Dir'], 
                    $tmpData['ViewNum'], $tmpData['WithTrashed'], 
                    $tmpData['UseTitle'], $tmpData['FullUrl'], 
                    $tmpData['Version'], $tmpData['Default'],
                    $tmpData['UseBaseMaker']
                );
                $section_data['bestsellers'] = Product::getBestsellers();
            } else {
                $section_data = [
                    'items' => Categorie::getFor(
                        $section->categories, $tmpData['BaseUrl'], 
                        $tmpData['Transform'], $tmpData['UseTitle'], 
                        $tmpData['Version'], $tmpData['WithTrashed'], 
                        $tmpData['FullUrl'], $tmpData['Default'],
                        $tmpData['UseBaseMaker'], $tmpData['Dir']
                    ),
                    'bestsellers' => Product::getBestsellers(),
                ];
            }
            $breadcumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb($section->name, $section->getFullUrl('store')),
                [
                    Page::genBreadcrumb('Store', 'store'),
                    //Page::genBreadcrumb('All Our Sections', 'store/section'),
                ]
            );
            return self::getView(
                $request, 'content.items_list', $section->title, $section_data, 
                false, $breadcumbs
            );
        } else {
            return $this->index($request);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }

    public function showDelete(Request $request)
    {
        // display 'ARE YOU SURE' PAGE...
    }

    public function test(Request $request)
    {
        //return static::getView($request, 'content.section', 'TEST-SECTION', [], true);
        return static::getView($request, 'content.items_list', 'TEST-SECTION', [], true);
    }
}
