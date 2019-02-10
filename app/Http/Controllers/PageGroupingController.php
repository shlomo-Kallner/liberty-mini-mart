<?php

namespace App\Http\Controllers;

use App\PageGrouping,
    App\PageGroup,
    App\Page,
    App\UserSession,
    Illuminate\Http\Request,
    App\Utilities\Functions\Functions,
    App\Rules\FieldIsUniqueRule,
    App\Rules\FieldIsUniqueOrEqualRule,
    Illuminate\Support\Facades\Log,
    Illuminate\Support\Facades\Validator,
    Illuminate\Support\Str;

class PageGroupingController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagesData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'menusPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'UseGetAllWithPagination' => false,
            'Transform' => PageGroup::TO_TABLE_ARRAY_TRANSFORM
        ];
        $usePagination = false;
        if ($usePagination) {
            $pv = PageGroup::getPagingVars(
                $request, $pagesData['PagingFor'], $pagesData['NumShown'],
                $pagesData['Dir']
            );
            if (Functions::testVar($pv)) {
                $pagesData['PageNum'] = $pv['pageNum'];
                $pagesData['ViewNum'] = $pv['viewNum'];
                if (Functions::hasPropKeyIn($pv, 'limit')) {
                    $pagesData['NumShown'] = $pv['limit'];
                }
            } 
            if ($pagesData['UseGetAllWithPagination']) {
                $pages = PageGroup::getAllWithPagination(
                    $pagesData['Transform'], $pagesData['PageNum'], 
                    $pagesData['NumShown'], $pagesData['PagingFor'], 
                    $pagesData['Dir'], $pagesData['WithTrashed'], 
                    $pagesData['BaseUrl'], $pagesData['ListUrl'], 
                    $pagesData['ViewNum'], $pagesData['FullUrl'], 
                    $pagesData['UseTitle'], 1, $pagesData['Default'], 
                    $pagesData['UseBaseMaker']
                );
                if ($pagesData['UseGetSelf']) {
                    $children = Functions::countHas($pages) 
                        ? $pages['items'] : null;
                    $paginator = Functions::countHas($pages) 
                        ? $pages['pagination'] : null;
                    $pages_index = PageGroup::getSelf(
                        $pagesData['BaseUrl'], $pagesData['WithTrashed'],
                        $pagesData['FullUrl'], $children, 
                        $paginator, $pagesData['PagingFor']
                    );
                }
            }
        } else {
            $pages = [];
            $pages['items'] = PageGroup::getAllWithTransform(
                $pagesData['Transform'], $pagesData['Dir'], 
                $pagesData['WithTrashed'], $pagesData['BaseUrl'], 
                $pagesData['UseTitle'], $pagesData['FullUrl'], 
                1, $pagesData['Default'], $pagesData['UseBaseMaker']
            );
        }
        //dd($pages);
        if ($request->ajax()) {
            return $pages;
        } else {
            $title = 'Our Navigation Menus';
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if (Functions::isAdminPath($request->path())) {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
            }
            $breadcrumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb(
                    $title, 
                    PageGroup::genUrlFragment($pagesData['BaseUrl'], $pagesData['FullUrl'])
                ),
                $bcLinks
            );
            return self::getView(
                $request, 'cms.items_table', $title, $pages, false, $breadcrumbs, null,
                Functions::isAdminPath($request->path()) 
                ? CmsController::getAdminSidebar() : null
            );
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Display a "Name Listing" of the resource.
     *
     * @param  \Illuminate\Http\Request $request - the request object...
     * 
     * @return \Illuminate\Http\Response
     */
    public function orderingList(Request $request)
    {
        $is_admin = true; //Functions::isAdminPath($request->path());
        $pageGroup = PageGroup::getNamed(
            $request->menu, $is_admin, null, false
        );
        $res = [];
        if (Functions::testVar($pageGroup)) {
            $group = PageGrouping::getGroup($pageGroup, 'asc', $is_admin);
            if (Functions::testVar($group)) {
                foreach ($group as $key => $page) {
                    $res[] = $page->order;
                }
            }
        }
        Log::info('json response', ['request' => $request, 'res_array' => $res]);
        return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $tmpData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'menusPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => PageGroup::TO_TABLE_ARRAY_TRANSFORM
        ];
        $groups = PageGroup::getOrdered(
            $tmpData['Dir'], $tmpData['WithTrashed'], 'name'
        );
        if (Functions::testVar($groups)) {
            $orderMin = $groups->min('order');
            $orderMax = $groups->max('order');
        } else {
            $orderMin = 1;
            $orderMax = 1;
        }
        $content = [
            'hasName' => 'true',
            'thisURL' => PageGroup::genUrlFragment($tmpData['BaseUrl'], $tmpData['FullUrl']),
            'hasOrder' => 'true',
            'orderMin' => $orderMin,
            'orderMax' => $orderMax,
            'order' => '',
            'cancelUrl' => 'admin/menus',
        ];
        $bcLinks = [];
        $bcLinks[] = self::getHomeBreadcumb();
        if (Functions::isAdminPath($request->path())) {
            $bcLinks[] = CmsController::getAdminBreadcrumb();
        }
        $bcLinks[] = Page::genBreadcrumb(
            'Our Navigation Menus', 
            PageGroup::genUrlFragment($tmpData['BaseUrl'], $tmpData['FullUrl'])
        );
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb(
                'Navigation Menu Creation Form', 
                PageGroup::genUrlFragment($tmpData['BaseUrl'], $tmpData['FullUrl'])
            ),
            $bcLinks
        );
        return self::getView(
            $request, 'cms.forms.new.menu', 'Create a New Navigation Menu',
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
        //dd((new Page)->getTable());
        $validator = Validator::make(
            $request->all(), self::creationValidationRules(), 
            self::creationValidationMessages()
        );
        $path = $request->path();
        $passed = $validator->passes();
        $is_admin = Functions::isAdminPath($request->path());
        if ($passed) {
            $pageGroup = PageGroup::createNew(
                $request->name, intval($request->order??-1), true
            );
            if (Functions::testVar($pageGroup)) {
                self::addMsg('Navigation Menu ' . $pageGroup->name . ' Creation Successfully!');
                $path = 'admin/menus';
            } else {
                self::addMsg("Uhhh, if we got here then Navigation Menu CREATION FAILED!!!");
                self::addMsg("You probably chose an in use name...");
                //dd($page);
                $passed = null;
            }
        }
        $path = $passed || Str::contains($path, 'create') ? $path : $path . '/create';
        return UserSession::updateRedirect(
            $request, $path, $validator, 
            !$passed ? $request->all()
            : []
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PageGrouping  $pageGrouping
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $menu = PageGroup::getNamed($request->menu, $is_admin);
        $pagesData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'pagesPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UsePageGroupings' => false,
            'UseGetSelf' => false,
            'Transform' => Page::TO_TABLE_ARRAY_TRANSFORM
        ];
        if (Functions::testVar($menu)) {
            $pages = [];
            $pages['items'] = Page::getFor(
                $menu->pages, $pagesData['BaseUrl'], 
                $pagesData['Transform'], $pagesData['UseTitle'], 
                1, $pagesData['WithTrashed'], 
                $pagesData['FullUrl'], $pagesData['Default'], 
                $pagesData['UseBaseMaker'], $pagesData['Dir']
            );
            //dd($pages);
            if ($request->ajax()) {
                return $pages;
            } else {
                $title = 'Menu ' . $menu->name . '\'s Content Pages';
                $bcLinks = [];
                $bcLinks[] = self::getHomeBreadcumb();
                if (Functions::isAdminPath($request->path())) {
                    $bcLinks[] = CmsController::getAdminBreadcrumb();
                }
                $bcLinks[] = Page::genBreadcrumb(
                    'Our Navigation Menus', 
                    PageGroup::genUrlFragment($pagesData['BaseUrl'], $pagesData['FullUrl'])
                );
                $breadcrumbs = Page::getBreadcrumbs(
                    Page::genBreadcrumb(
                        $title, 
                        PageGroup::genUrlFragment($pagesData['BaseUrl'], $pagesData['FullUrl'])
                    ),
                    $bcLinks
                );
                return self::getView(
                    $request, 'cms.items_table', $title, $pages, false, $breadcrumbs, null,
                    Functions::isAdminPath($request->path()) 
                    ? CmsController::getAdminSidebar() : null
                );
            }
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PageGrouping  $pageGrouping
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $menu = PageGroup::getNamed($request->menu, $is_admin);
        $pagesData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'pagesPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UsePageGroupings' => false,
            'UseGetSelf' => false,
            'Transform' => PageGroup::TO_TABLE_ARRAY_TRANSFORM
        ];
        if (Functions::testVar($menu)) {
            $groups = PageGroup::getOrdered(
                $pagesData['Dir'], $pagesData['WithTrashed'], 'name'
            );
            if (Functions::testVar($groups)) {
                $orderMin = $groups->min('order');
                $orderMax = $groups->max('order');
            } else {
                $orderMin = 1;
                $orderMax = 1;
            }
            $content = [
                'hasName' => 'true',
                'name' => $menu->name,
                'thisURL' => $menu->getFullUrl($pagesData['BaseUrl'], $pagesData['FullUrl']),
                'hasOrder' => 'true',
                'orderMin' => $orderMin,
                'orderMax' => $orderMax,
                'order' => $menu->order,
                'HttpVerb' => 'PATCH',
                'cancelUrl' => 'admin/menus',
            ];
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if (Functions::isAdminPath($request->path())) {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
            }
            $bcLinks[] = Page::genBreadcrumb(
                'Our Navigation Menus', 
                PageGroup::genUrlFragment($pagesData['BaseUrl'], $pagesData['FullUrl'])
            );
            $breadcrumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb(
                    'Navigation Menu Modification Form', 
                    PageGroup::genUrlFragment($pagesData['BaseUrl'], $pagesData['FullUrl'])
                ),
                $bcLinks
            );
            return self::getView(
                $request, 'cms.forms.edit.menu', 'Modify Existing Navigation Menu',
                $content, false, $breadcrumbs, null, 
                Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
                : null
            );
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PageGrouping  $pageGrouping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $menu = PageGroup::getNamed($request->menu, $is_admin);
        $pagesData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'pagesPanel',
            'Dir' => 'asc',
            'WithTrashed' => $is_admin,
            'BaseUrl' => $is_admin ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UsePageGroupings' => false,
            'UseGetSelf' => false,
            'Transform' => PageGroup::TO_TABLE_ARRAY_TRANSFORM
        ];
        if (Functions::testVar($menu)) {
            $validator = Validator::make(
                $request->all(), 
                self::modificationValidationRules($menu->name, false), 
                self::modificationValidationMessages()
            );
            $path = $request->path();
            $passed = $validator->passes();
            if ($passed) {
                $pageGroup = $menu->updateWith(
                    $request->name, intval($request->order??-1), true
                ); 
            }
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PageGrouping  $pageGrouping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    public function showDelete(Request $request)
    {
        //
    }

    //// Validation Rules and messages

    /**
     * Get the validation rules that apply to the creation request.
     *
     * @return array
     */
    static public function creationValidationRules(bool $withTrashed = false)
    {
        return [
            //
            'name' => [
                'required', 'max:255', 'string', 'min:3',
                new FieldIsUniqueRule((new Page)->getTable(), 'name', $withTrashed),
            ],
            'order' => [
                'sometimes' , 'required', 'numeric', 
            ],
        ];
    }

    /**
     * Get the validation rules that apply to the modification request.
     *
     * @return array
     */
    static public function modificationValidationRules($name, bool $withTrashed = false)
    {
        return [
            'name' =>  [
                'required', 'max:255', 'string', 'min:3',
                // (string $table, string $field, $value, bool $withTrashed = false)
                new FieldIsUniqueOrEqualRule((new Page)->getTable(), 'name', $name, $withTrashed),
            ],
            'order' => [
                'sometimes' , 'required', 'numeric', 
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
        return [];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    static public function modificationValidationMessages()
    {
        return [];
    }
}
